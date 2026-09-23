<x-app-layout>
    <x-slot:title>Verifikasi Pengelola Wisata</x-slot:title>
    <x-slot:header>Verifikasi Pendaftaran Pengelola</x-slot:header>

    <div class="space-y-6" x-data="{
        rejectModal: false,
        activeUser: { id: null, name: '', email: '' },
        openReject(u) {
            this.activeUser = { ...u };
            this.rejectModal = true;
        }
    }">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Verifikasi & Persetujuan Akun</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Verifikasi Pengelola Wisata
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Pemeriksaan identitas dan pemberian izin akses ruang kerja pengelola desa wisata untuk menjaga keamanan data dan kenyamanan Kampung Gedung Batin.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl {{ $pendingPengelola->total() > 0 ? 'bg-amber-500/20 text-amber-300 border border-amber-400/30' : 'bg-white/10 text-white border border-white/20' }} text-xs font-semibold">
                    @if($pendingPengelola->total() > 0)
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    @endif
                    <span>{{ $pendingPengelola->total() }} Menunggu Tinjauan</span>
                </span>
            </div>
        </div>

        <!-- 2. SEKSI 1: Antrean Verifikasi Pending -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <div>
                        <h3 class="font-bold tracking-tight text-slate-900 text-base">Permohonan Akun Baru (Pending)</h3>
                        <p class="text-xs text-slate-500">Antrean pendaftaran calon pengelola yang memerlukan tinjauan administrator</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                    {{ $pendingPengelola->total() }} Antrean
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Calon Pengelola</th>
                            <th class="py-3.5 px-4">Kontak (HP/WA)</th>
                            <th class="py-3.5 px-4">Waktu Daftar</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Keputusan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse($pendingPengelola as $index => $pengelola)
                            <tr class="h-[64px] hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400 text-xs">
                                    {{ $pendingPengelola->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs bg-amber-100 text-amber-900 border border-amber-200 shadow-2xs">
                                            {{ strtoupper(substr($pengelola->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ $pengelola->name }}</div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $pengelola->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs">
                                    @if($pengelola->phone)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $pengelola->phone)) }}" target="_blank" class="text-emerald-700 hover:text-emerald-800 font-semibold hover:underline inline-flex items-center gap-1">
                                            <span>{{ $pengelola->phone }}</span>
                                            <span class="text-[11px]">↗</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-500">
                                    {{ $pengelola->created_at ? $pengelola->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <x-tag-status :status="$pengelola->status" />
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Setujui -->
                                        <form
                                            method="POST"
                                            action="{{ route('admin.verifikasi.approve', $pengelola) }}"
                                            onsubmit="return confirm('Setujui akun pengelola untuk \'{{ $pengelola->name }}\'? Pengguna akan dapat login ke sistem.');"
                                            class="inline"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-xs transition-all cursor-pointer inline-flex items-center gap-1.5 shadow-xs"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Setujui</span>
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <button
                                            type="button"
                                            @click="openReject({{ json_encode([
                                                'id' => $pengelola->id,
                                                'name' => $pengelola->name,
                                                'email' => $pengelola->email
                                            ]) }})"
                                            class="px-3.5 py-1.5 bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 hover:border-rose-300 rounded-xl font-semibold text-xs transition-all cursor-pointer inline-flex items-center gap-1.5 shadow-2xs"
                                        >
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span>Tolak</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 space-y-2">
                                    <svg class="w-8 h-8 mx-auto text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-slate-700">Semua permohonan pendaftaran telah ditinjau</p>
                                    <p class="text-xs text-slate-400">Tidak ada akun pengelola yang menunggu persetujuan saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pendingPengelola->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                    {{ $pendingPengelola->links() }}
                </div>
            @endif
        </div>

        <!-- 3. SEKSI 2: Riwayat Verifikasi Terakhir -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="font-bold tracking-tight text-slate-900 text-base">Riwayat Verifikasi Terakhir</h3>
                    <p class="text-xs text-slate-500">Catatan 10 keputusan persetujuan dan penolakan akun pengelola terkini</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $riwayatVerifikasi->count() }} Tercatat
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Nama Pengelola</th>
                            <th class="py-3.5 px-4">Kontak</th>
                            <th class="py-3.5 px-4 text-center">Keputusan</th>
                            <th class="py-3.5 px-4">Catatan / Alasan Penolakan</th>
                            <th class="py-3.5 px-4 text-right">Waktu Ditinjau</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse($riwayatVerifikasi as $index => $rw)
                            <tr class="h-[60px] hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400 text-xs">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $rw->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $rw->email }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs text-slate-500">
                                    {{ $rw->phone ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <x-tag-status :status="$rw->status" />
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    @if($rw->status === 'ditolak')
                                        <span class="text-rose-700 font-semibold bg-rose-50 border border-rose-200/80 px-2.5 py-1 rounded-lg inline-block">
                                            {{ $rw->rejection_reason ?? 'Tidak memenuhi kualifikasi.' }}
                                        </span>
                                    @else
                                        <span class="text-emerald-700 font-semibold bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-lg inline-block">
                                            Disetujui sebagai pengelola aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right text-xs text-slate-500 font-mono whitespace-nowrap">
                                    {{ $rw->updated_at ? $rw->updated_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada riwayat verifikasi yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. MODAL: Tolak Verifikasi dengan Alasan -->
        <div
            x-show="rejectModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            style="display: none;"
            x-transition.opacity
        >
            <div
                @click.away="rejectModal = false"
                class="bg-white border border-slate-200/90 rounded-2xl max-w-md w-full p-6 sm:p-7 space-y-4 shadow-2xl"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold tracking-tight text-xl text-rose-700">Tolak Pendaftaran Pengelola</h3>
                    <button
                        type="button"
                        @click="rejectModal = false"
                        class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 inline-flex items-center justify-center font-bold text-lg cursor-pointer transition-colors"
                    >
                        &times;
                    </button>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed">
                    Anda akan menolak permohonan akun untuk <strong class="text-slate-900" x-text="activeUser.name"></strong> (<span class="font-mono text-xs text-slate-500" x-text="activeUser.email"></span>).
                </p>

                <form
                    method="POST"
                    :action="'/admin/verifikasi/' + activeUser.id + '/tolak'"
                    class="space-y-4"
                >
                    @csrf

                    <div>
                        <label for="rejection_reason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Alasan Penolakan (Opsional)
                        </label>
                        <textarea
                            id="rejection_reason"
                            name="rejection_reason"
                            rows="3"
                            placeholder="Contoh: Bukan warga yang didelegasikan oleh pokdarwis Kampung Gedung Batin."
                            class="p-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 transition-all placeholder-slate-400"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="rejectModal = false"
                            class="h-11 px-5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition-all cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="h-11 px-5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-rose-600/20 transition-all cursor-pointer"
                        >
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
