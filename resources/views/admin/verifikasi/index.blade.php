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
        <!-- Papan Hijau (DESIGN.md 7.11: Persetujuan/Verifikasi) -->
        <x-papan warna="hijau" class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-putih">Verifikasi Pengelola Wisata</h2>
                    <p class="text-putih/90 text-sm mt-1">
                        Pemeriksaan identitas dan pemberian izin akses ke ruang kerja pengelola desa wisata.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 bg-putih text-aspal font-bold text-xs rounded-kontrol">
                        {{ $pendingPengelola->total() }} Menunggu tinjauan
                    </span>
                </div>
            </div>
        </x-papan>

        <!-- SEKSI 1: Antrean Verifikasi Pending -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between bg-beton/30">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-kuning inline-block"></span>
                    <h3 class="font-bold font-papan text-aspal text-lg">Permohonan akun baru (Pending)</h3>
                </div>
                <span class="text-xs text-abu font-semibold">{{ $pendingPengelola->total() }} akun</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Calon Pengelola</th>
                            <th class="py-3 px-4">Kontak (HP/WA)</th>
                            <th class="py-3 px-4">Waktu Daftar</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Keputusan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-sm text-aspal">
                        @forelse($pendingPengelola as $index => $pengelola)
                            <tr class="h-[64px] hover:bg-beton/40">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $pendingPengelola->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-aspal text-[15px]">{{ $pengelola->name }}</div>
                                    <div class="text-xs text-abu font-mono">{{ $pengelola->email }}</div>
                                </td>
                                <td class="py-3 px-4 font-mono text-xs">
                                    @if($pengelola->phone)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $pengelola->phone)) }}" target="_blank" class="text-aspal hover:underline inline-flex items-center gap-1 font-semibold">
                                            <span>{{ $pengelola->phone }}</span>
                                            <span class="text-[10px] text-abu">↗</span>
                                        </a>
                                    @else
                                        <span class="text-abu">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs text-abu">
                                    {{ $pengelola->created_at ? $pengelola->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <x-tag-status :status="$pengelola->status" />
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
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
                                                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold text-xs transition-colors cursor-pointer flex items-center gap-1.5 shadow-xs"
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
                                            class="px-3.5 py-1.5 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 rounded-lg font-semibold text-xs transition-colors cursor-pointer flex items-center gap-1.5 shadow-xs"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span>Tolak</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center">
                                    <div class="text-abu font-semibold">Semua permohonan pendaftaran telah ditinjau.</div>
                                    <div class="text-xs text-abu/70 mt-1">Tidak ada akun pengelola yang menunggu persetujuan saat ini.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pendingPengelola->hasPages())
                <div class="p-4 border-t border-beton">
                    {{ $pendingPengelola->links() }}
                </div>
            @endif
        </div>

        <!-- SEKSI 2: Riwayat Verifikasi Terakhir -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between">
                <h3 class="font-bold font-papan text-aspal text-lg">Riwayat verifikasi terakhir (10 data)</h3>
                <span class="text-xs text-abu">Status disetujui / ditolak</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Nama Pengelola</th>
                            <th class="py-3 px-4">Kontak</th>
                            <th class="py-3 px-4 text-center">Keputusan</th>
                            <th class="py-3 px-4">Alasan Penolakan / Catatan</th>
                            <th class="py-3 px-4 text-right">Waktu Ditinjau</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-sm text-aspal">
                        @forelse($riwayatVerifikasi as $index => $rw)
                            <tr class="h-[56px] hover:bg-beton/40">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-aspal">{{ $rw->name }}</div>
                                    <div class="text-xs text-abu font-mono">{{ $rw->email }}</div>
                                </td>
                                <td class="py-3 px-4 font-mono text-xs text-abu">
                                    {{ $rw->phone ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <x-tag-status :status="$rw->status" />
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    @if($rw->status === 'ditolak')
                                        <span class="text-merah font-semibold">{{ $rw->rejection_reason ?? 'Tidak memenuhi kualifikasi.' }}</span>
                                    @else
                                        <span class="text-hijau font-semibold">Disetujui sebagai pengelola aktif</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right text-xs text-abu font-mono whitespace-nowrap">
                                    {{ $rw->updated_at ? $rw->updated_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-abu">
                                    Belum ada riwayat verifikasi yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL: Tolak Verifikasi dengan Alasan -->
        <div
            x-show="rejectModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-aspal/60"
            style="display: none;"
            x-transition.opacity
        >
            <div
                @click.away="rejectModal = false"
                class="bg-putih border-2 border-aspal rounded-papan max-w-md w-full p-6 space-y-4"
            >
                <div class="flex items-center justify-between border-b border-beton pb-3">
                    <h3 class="font-bold font-papan text-xl text-merah">Tolak Pendaftaran Pengelola</h3>
                    <button type="button" @click="rejectModal = false" class="text-abu hover:text-aspal font-bold text-lg cursor-pointer">&times;</button>
                </div>

                <p class="text-sm text-aspal">
                    Anda akan menolak permohonan akun untuk <strong x-text="activeUser.name"></strong> (<span class="font-mono text-xs" x-text="activeUser.email"></span>).
                </p>

                <form
                    method="POST"
                    :action="'/admin/verifikasi/' + activeUser.id + '/tolak'"
                    class="space-y-4"
                >
                    @csrf

                    <div>
                        <x-input-label for="rejection_reason" value="Alasan penolakan (opsional)" />
                        <textarea
                            id="rejection_reason"
                            name="rejection_reason"
                            rows="3"
                            placeholder="Contoh: Bukan warga yang didelegasikan oleh pokdarwis Kampung Gedung Batin."
                            class="p-3 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-beton">
                        <button
                            type="button"
                            @click="rejectModal = false"
                            class="h-[44px] px-4 bg-putih border-2 border-aspal text-aspal font-bold text-sm rounded-kontrol hover:bg-beton cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="h-[44px] px-4 bg-merah text-putih font-bold text-sm rounded-kontrol hover:opacity-95 cursor-pointer"
                        >
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
