<?php

namespace Tests\Feature;

use App\Models\EventBudaya;
use App\Models\KategoriEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicKalenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_page_can_be_rendered_for_current_month(): void
    {
        $user = User::factory()->pengelola()->create();
        $kategori = KategoriEvent::factory()->create(['nama' => 'Pentas Adat Pepadun']);

        $now = Carbon::now('Asia/Jakarta');
        $event = EventBudaya::factory()->create([
            'judul' => 'Festival Tari Cangget Way Kanan',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => $now->copy()->startOfMonth()->addDays(2)->format('Y-m-d'),
            'tanggal_selesai' => $now->copy()->startOfMonth()->addDays(3)->format('Y-m-d'),
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('kalender.index'));

        $response->assertOk();
        $response->assertSee('Kalender Event Budaya Digital');
        $response->assertSee('Festival Tari Cangget Way Kanan');
        $response->assertSee('Pentas Adat Pepadun');
    }

    public function test_calendar_page_can_navigate_to_specific_month_and_year(): void
    {
        $user = User::factory()->pengelola()->create();
        $kategori = KategoriEvent::factory()->create();

        // Buat event di Desember 2026
        $eventDesember = EventBudaya::factory()->create([
            'judul' => 'Perayaan Begawi Cakak Pepadun',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => '2026-12-10',
            'tanggal_selesai' => '2026-12-12',
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('kalender.index', ['bulan' => 12, 'tahun' => 2026]));

        $response->assertOk();
        $response->assertSee('Desember 2026');
        $response->assertSee('Perayaan Begawi Cakak Pepadun');
    }

    public function test_calendar_page_can_be_filtered_by_category(): void
    {
        $user = User::factory()->pengelola()->create();
        $kategori1 = KategoriEvent::factory()->create(['nama' => 'Upacara Adat', 'slug' => 'upacara-adat']);
        $kategori2 = KategoriEvent::factory()->create(['nama' => 'Pameran Seni', 'slug' => 'pameran-seni']);

        $now = Carbon::now('Asia/Jakarta');
        $event1 = EventBudaya::factory()->create([
            'judul' => 'Upacara Begawi Adat Marga',
            'kategori_event_id' => $kategori1->id,
            'tanggal_mulai' => $now->copy()->format('Y-m-d'),
            'tanggal_selesai' => $now->copy()->format('Y-m-d'),
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $event2 = EventBudaya::factory()->create([
            'judul' => 'Pameran Tenun Tapis Tradisional',
            'kategori_event_id' => $kategori2->id,
            'tanggal_mulai' => $now->copy()->format('Y-m-d'),
            'tanggal_selesai' => $now->copy()->format('Y-m-d'),
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('kalender.index', ['kategori' => 'upacara-adat']));

        $response->assertOk();
        $response->assertSee('Upacara Begawi Adat Marga');
        $response->assertDontSee('Pameran Tenun Tapis Tradisional');
    }

    public function test_pending_or_inactive_event_is_not_shown_in_calendar(): void
    {
        $user = User::factory()->pengelola()->create();
        $kategori = KategoriEvent::factory()->create();

        $now = Carbon::now('Asia/Jakarta');
        $pendingEvent = EventBudaya::factory()->create([
            'judul' => 'Acara Rahasia Belum Disetujui',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => $now->copy()->format('Y-m-d'),
            'tanggal_selesai' => $now->copy()->format('Y-m-d'),
            'status' => 'pending',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('kalender.index'));

        $response->assertOk();
        $response->assertDontSee('Acara Rahasia Belum Disetujui');
    }

    public function test_event_detail_page_can_be_rendered_with_gcal_link(): void
    {
        $user = User::factory()->pengelola()->create();
        $kategori = KategoriEvent::factory()->create(['nama' => 'Ritual Adat']);

        $event = EventBudaya::factory()->create([
            'judul' => 'Upacara Cuci Senjata Pusaka Way Kanan',
            'slug' => 'upacara-cuci-senjata-pusaka',
            'kategori_event_id' => $kategori->id,
            'tanggal_mulai' => '2026-10-15',
            'tanggal_selesai' => '2026-10-15',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '12:00:00',
            'lokasi' => 'Sesat Agung Gedung Batin',
            'deskripsi' => 'Pembersihan pusaka leluhur adat pepadun yang diwariskan turun-temurun.',
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('event.show', 'upacara-cuci-senjata-pusaka'));

        $response->assertOk();
        $response->assertSee('Upacara Cuci Senjata Pusaka Way Kanan');
        $response->assertSee('Sesat Agung Gedung Batin');
        $response->assertSee('Google Calendar');
        $response->assertSee('Unduh Berkas Kalender (.ICS)');
        $response->assertSee('WhatsApp');
    }

    public function test_event_detail_returns_404_for_inactive_or_missing_event(): void
    {
        $user = User::factory()->pengelola()->create();

        $inactiveEvent = EventBudaya::factory()->create([
            'slug' => 'event-rahasia-draf',
            'status' => 'pending',
            'created_by' => $user->id,
        ]);

        $responseMissing = $this->get(route('event.show', 'tidak-ada-event'));
        $responseMissing->assertNotFound();

        $responseInactive = $this->get(route('event.show', 'event-rahasia-draf'));
        $responseInactive->assertNotFound();
    }

    public function test_calendar_slug_redirects_to_event_slug(): void
    {
        $user = User::factory()->pengelola()->create();
        $event = EventBudaya::factory()->create([
            'slug' => 'pesta-panen-desa',
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $response = $this->get('/kalender/pesta-panen-desa');
        $response->assertRedirect(route('event.show', 'pesta-panen-desa'));
    }

    public function test_download_ics_returns_valid_icalendar_file(): void
    {
        $user = User::factory()->pengelola()->create();
        $event = EventBudaya::factory()->create([
            'judul' => 'Pawai Budaya Way Kanan',
            'slug' => 'pawai-budaya-way-kanan',
            'tanggal_mulai' => '2026-11-20',
            'tanggal_selesai' => '2026-11-20',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00',
            'lokasi' => 'Alun-alun Kampung Gedung Batin',
            'status' => 'aktif',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('event.ics', 'pawai-budaya-way-kanan'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
        $content = $response->getContent();
        $this->assertStringContainsString('BEGIN:VCALENDAR', $content);
        $this->assertStringContainsString('SUMMARY:Pawai Budaya Way Kanan', $content);
        $this->assertStringContainsString('END:VCALENDAR', $content);
    }
}
