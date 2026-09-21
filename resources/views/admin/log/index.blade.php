<x-app-layout>
    <x-slot:title>Log Aktivitas Sistem</x-slot:title>
    <x-slot:header>Rekam Jejak Aktivitas (Audit Log)</x-slot:header>

    <div class="space-y-6">
        <!-- Papan Aspal (DESIGN.md 7.11: Log & Pengaturan Berwarna Aspal Gelap) -->
        <x-papan warna="aspal" class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-putih">Audit Log Aktivitas Sistem</h2>
                    <p class="text-putih/80 text-sm mt-1">
                        Perekaman otomatis setiap aksi autentikasi, pembuatan data, verifikasi akun, dan penghapusan data untuk integritas sistem.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 bg-putih/10 border border-putih/20 text-putih font-mono text-xs rounded-kontrol">
                        {{ $logs->total() }} Total rekaman
                    </span>
                </div>
            </div>
        </x-papan>

        <!-- Bar Filter Log -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-4 sm:p-5">
            <form method="GET" action="{{ route('admin.log') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <!-- Filter Pengguna -->
                <div>
                    <label class="block text-xs font-bold text-aspal mb-1">Pengguna</label>
                    <select
                        name="user_id"
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-xs font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                        <option value="">Semua pengguna</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Aksi -->
                <div>
                    <label class="block text-xs font-bold text-aspal mb-1">Jenis Aksi</label>
                    <input
                        type="text"
                        name="aksi"
                        value="{{ request('aksi') }}"
                        placeholder="Contoh: LOGIN, CREATE, DELETE..."
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-xs text-aspal placeholder-abu focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                </div>

                <!-- Dari Tanggal -->
                <div>
                    <label class="block text-xs font-bold text-aspal mb-1">Dari Tanggal</label>
                    <input
                        type="date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-xs text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label class="block text-xs font-bold text-aspal mb-1">Sampai Tanggal</label>
                    <input
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-xs text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="h-[44px] flex-1 bg-aspal text-putih font-bold text-xs rounded-kontrol hover:bg-black cursor-pointer">
                        Filter
                    </button>
                    @if(request()->hasAny(['user_id', 'aksi', 'start_date', 'end_date']))
                        <a href="{{ route('admin.log') }}" class="h-[44px] px-3 flex items-center justify-center bg-beton text-aspal font-semibold text-xs border border-abu rounded-kontrol hover:bg-abu/20 no-underline whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Log Aktivitas -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between">
                <h3 class="font-bold font-papan text-aspal text-lg">Catatan riwayat aktivitas</h3>
                <span class="text-xs text-abu font-mono">Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4 w-40">Waktu (WIB)</th>
                            <th class="py-3 px-4">Pengguna (Pelaku)</th>
                            <th class="py-3 px-4">Aksi</th>
                            <th class="py-3 px-4">Entitas Terkait</th>
                            <th class="py-3 px-4">Keterangan / Parameter</th>
                            <th class="py-3 px-4 font-mono text-xs">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-xs text-aspal">
                        @forelse($logs as $index => $log)
                            <tr class="h-[56px] hover:bg-beton/40">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4 font-mono text-abu whitespace-nowrap">
                                    <div class="font-semibold text-aspal">{{ $log->created_at ? $log->created_at->translatedFormat('d M Y') : '-' }}</div>
                                    <div>{{ $log->created_at ? $log->created_at->format('H:i:s') : '-' }} WIB</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($log->user)
                                        <div class="font-bold text-aspal">{{ $log->user->name }}</div>
                                        <div class="text-[11px] text-abu font-mono">{{ $log->user->email }}</div>
                                        <div>
                                            <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded text-[10px] font-bold {{ $log->user->isAdmin() ? 'bg-aspal text-putih' : 'bg-biru text-putih' }}">
                                                {{ $log->user->isAdmin() ? 'Admin' : 'Pengelola' }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-abu italic">Sistem / Tamu</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $aksiUpper = strtoupper($log->aksi);
                                        $warnaTag = 'bg-beton text-aspal border border-abu';
                                        if (str_contains($aksiUpper, 'LOGIN')) {
                                            $warnaTag = 'bg-hijau text-putih';
                                        } elseif (str_contains($aksiUpper, 'LOGOUT')) {
                                            $warnaTag = 'bg-abu text-putih';
                                        } elseif (str_contains($aksiUpper, 'CREATE')) {
                                            $warnaTag = 'bg-biru text-putih';
                                        } elseif (str_contains($aksiUpper, 'UPDATE') || str_contains($aksiUpper, 'RESET')) {
                                            $warnaTag = 'bg-kuning text-aspal';
                                        } elseif (str_contains($aksiUpper, 'DELETE') || str_contains($aksiUpper, 'REJECT') || str_contains($aksiUpper, 'FAILED')) {
                                            $warnaTag = 'bg-merah text-putih';
                                        } elseif (str_contains($aksiUpper, 'APPROVE')) {
                                            $warnaTag = 'bg-hijau text-putih';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-tag text-[11px] font-mono font-bold {{ $warnaTag }}">
                                        {{ $log->aksi }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-mono text-xs">
                                    @if($log->entitas_tipe)
                                        <span class="font-semibold text-aspal">{{ class_basename($log->entitas_tipe) }}</span>
                                        @if($log->entitas_id)
                                            <span class="text-abu">#{{ $log->entitas_id }}</span>
                                        @endif
                                    @else
                                        <span class="text-abu">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 max-w-xs">
                                    @if(is_array($log->keterangan) && count($log->keterangan) > 0)
                                        <div class="space-y-1">
                                            @foreach($log->keterangan as $key => $val)
                                                <div class="text-[11px]">
                                                    <span class="font-semibold text-aspal font-mono">{{ $key }}:</span>
                                                    <span class="text-abu font-mono">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(is_string($log->keterangan))
                                        <span class="text-abu">{{ $log->keterangan }}</span>
                                    @else
                                        <span class="text-abu">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-mono text-abu whitespace-nowrap">
                                    {{ $log->ip_address ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-abu">
                                    Tidak ada catatan log aktivitas yang cocok dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-4 border-t border-beton">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
