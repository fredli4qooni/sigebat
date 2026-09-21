<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventBudaya;
use App\Models\KategoriEvent;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class KalenderController extends Controller
{
    /**
     * Tampilan Kalender Bulanan Interaktif & Daftar Agenda Budaya
     */
    public function index(Request $request): View
    {
        $now = Carbon::now('Asia/Jakarta');

        $bulan = (int) $request->input('bulan', $now->month);
        $tahun = (int) $request->input('tahun', $now->year);

        // Validasi batasan bulan & tahun
        if ($bulan < 1 || $bulan > 12) {
            $bulan = $now->month;
        }
        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = $now->year;
        }

        $currentMonth = Carbon::createFromDate($tahun, $bulan, 1, 'Asia/Jakarta')->startOfMonth();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Navigasi bulan sebelum dan sesudah
        $prevMonthDate = $currentMonth->copy()->subMonth();
        $prevBulan = $prevMonthDate->month;
        $prevTahun = $prevMonthDate->year;

        $nextMonthDate = $currentMonth->copy()->addMonth();
        $nextBulan = $nextMonthDate->month;
        $nextTahun = $nextMonthDate->year;

        // Query agenda aktif yang beririsan dengan bulan ini
        $query = EventBudaya::with('kategori')
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', $endOfMonth->format('Y-m-d'))
            ->where('tanggal_selesai', '>=', $startOfMonth->format('Y-m-d'));

        if ($kategoriSlug = $request->input('kategori')) {
            $query->whereHas('kategori', function ($q) use ($kategoriSlug): void {
                $q->where('slug', $kategoriSlug);
            });
        }

        $allMonthEvents = $query->orderBy('tanggal_mulai')
            ->orderBy('jam_mulai')
            ->get();

        // Bangun matriks 7 kolom (Senin - Minggu)
        $gridStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $todayStr = $now->format('Y-m-d');
        $calendarDays = [];
        $cursor = $gridStart->copy();

        while ($cursor->lte($gridEnd)) {
            $dateStr = $cursor->format('Y-m-d');

            // Event yang berlangsung pada tanggal ini
            $eventsOnDay = $allMonthEvents->filter(function ($ev) use ($dateStr): bool {
                $start = $ev->tanggal_mulai ? $ev->tanggal_mulai->format('Y-m-d') : '';
                $end = $ev->tanggal_selesai ? $ev->tanggal_selesai->format('Y-m-d') : '';

                return $start <= $dateStr && $end >= $dateStr;
            })->values();

            $calendarDays[] = [
                'date' => $dateStr,
                'day' => (int) $cursor->format('j'),
                'is_current_month' => $cursor->month === $currentMonth->month,
                'is_today' => $dateStr === $todayStr,
                'events' => $eventsOnDay,
            ];

            $cursor->addDay();
        }

        $kategoriList = KategoriEvent::has('eventBudaya')->orderBy('nama')->get();

        return view('kalender.index', [
            'currentMonth' => $currentMonth,
            'namaBulan' => $currentMonth->translatedFormat('F'),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'prevBulan' => $prevBulan,
            'prevTahun' => $prevTahun,
            'nextBulan' => $nextBulan,
            'nextTahun' => $nextTahun,
            'calendarDays' => $calendarDays,
            'allMonthEvents' => $allMonthEvents,
            'kategoriList' => $kategoriList,
            'selectedKategori' => $request->input('kategori'),
            'selectedDate' => $request->input('tanggal'),
        ]);
    }

    /**
     * Tampilan Detail Event Budaya
     */
    public function show(string $slug): View
    {
        $event = EventBudaya::with(['kategori', 'creator'])
            ->where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        $eventLain = EventBudaya::with('kategori')
            ->where('status', 'aktif')
            ->where('id', '!=', $event->id)
            ->where('tanggal_selesai', '>=', now('Asia/Jakarta')->format('Y-m-d'))
            ->orderBy('tanggal_mulai')
            ->take(2)
            ->get();

        if ($eventLain->count() < 2) {
            $more = EventBudaya::with('kategori')
                ->where('status', 'aktif')
                ->where('id', '!=', $event->id)
                ->whereNotIn('id', $eventLain->pluck('id'))
                ->latest('id')
                ->take(2 - $eventLain->count())
                ->get();

            $eventLain = $eventLain->merge($more);
        }

        // Generate Google Calendar Link
        $startDate = $event->tanggal_mulai ? $event->tanggal_mulai->format('Y-m-d') : now()->format('Y-m-d');
        $startTime = $event->jam_mulai ? substr($event->jam_mulai, 0, 5).':00' : '08:00:00';
        $endDate = $event->tanggal_selesai ? $event->tanggal_selesai->format('Y-m-d') : $startDate;
        $endTime = $event->jam_selesai ? substr($event->jam_selesai, 0, 5).':00' : '17:00:00';

        $startUtc = Carbon::parse("{$startDate} {$startTime}", 'Asia/Jakarta')->setTimezone('UTC')->format('Ymd\THis\Z');
        $endUtc = Carbon::parse("{$endDate} {$endTime}", 'Asia/Jakarta')->setTimezone('UTC')->format('Ymd\THis\Z');

        $gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
            .'&text='.urlencode($event->judul)
            .'&dates='.$startUtc.'/'.$endUtc
            .'&details='.urlencode(strip_tags($event->deskripsi ?: ''))
            .'&location='.urlencode($event->lokasi ?: 'Kampung Gedung Batin, Way Kanan');

        return view('kalender.show', compact('event', 'eventLain', 'gcalUrl'));
    }

    /**
     * Unduh File Kalender Digital iCalendar (.ics)
     */
    public function downloadIcs(string $slug): Response
    {
        $event = EventBudaya::where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        $startDate = $event->tanggal_mulai ? $event->tanggal_mulai->format('Y-m-d') : now()->format('Y-m-d');
        $startTime = $event->jam_mulai ? substr($event->jam_mulai, 0, 5).':00' : '08:00:00';
        $endDate = $event->tanggal_selesai ? $event->tanggal_selesai->format('Y-m-d') : $startDate;
        $endTime = $event->jam_selesai ? substr($event->jam_selesai, 0, 5).':00' : '17:00:00';

        $startUtc = Carbon::parse("{$startDate} {$startTime}", 'Asia/Jakarta')->setTimezone('UTC')->format('Ymd\THis\Z');
        $endUtc = Carbon::parse("{$endDate} {$endTime}", 'Asia/Jakarta')->setTimezone('UTC')->format('Ymd\THis\Z');
        $nowUtc = Carbon::now('UTC')->format('Ymd\THis\Z');

        $summary = str_replace(["\r", "\n"], ' ', $event->judul);
        $description = str_replace(["\r", "\n"], ' ', strip_tags($event->deskripsi ?: ''));
        $location = str_replace(["\r", "\n"], ' ', $event->lokasi ?: 'Kampung Gedung Batin, Way Kanan');
        $url = route('event.show', $event->slug);

        $ics = "BEGIN:VCALENDAR\r\n"
            ."VERSION:2.0\r\n"
            ."PRODID:-//SIGEBAT//Desa Wisata Gedung Batin//ID\r\n"
            ."CALSCALE:GREGORIAN\r\n"
            ."METHOD:PUBLISH\r\n"
            ."BEGIN:VEVENT\r\n"
            ."UID:sigebat-event-{$event->id}@gedungbatin.desa.id\r\n"
            ."DTSTAMP:{$nowUtc}\r\n"
            ."DTSTART:{$startUtc}\r\n"
            ."DTEND:{$endUtc}\r\n"
            ."SUMMARY:{$summary}\r\n"
            ."DESCRIPTION:{$description}\r\n"
            ."LOCATION:{$location}\r\n"
            ."URL:{$url}\r\n"
            ."STATUS:CONFIRMED\r\n"
            ."END:VEVENT\r\n"
            ."END:VCALENDAR\r\n";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$event->slug.'.ics"',
        ]);
    }

    /**
     * Redirect alias /kalender/{slug} ke /event/{slug}
     */
    public function redirectSlug(string $slug): RedirectResponse
    {
        return redirect()->route('event.show', $slug);
    }
}
