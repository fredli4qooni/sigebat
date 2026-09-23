<x-app-layout>
    <x-slot:title>Log Aktivitas Sistem</x-slot:title>
    <x-slot:header>Rekam Jejak Aktivitas (Audit Log)</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Audit & Integritas Sistem</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Audit Log Aktivitas Sistem
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Perekaman otomatis setiap aksi autentikasi, pembuatan data, verifikasi akun, dan penghapusan data untuk menjaga integritas dan transparansi sistem.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-semibold">
                    {{ $logs->total() }} Total Catatan
                </span>
            </div>
        </div>

        <!-- 2. Bar Filter Log Modern -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm">
            <form method="GET" action="{{ route('admin.log') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <!-- Filter Pengguna -->
                <div>
                    <label for="user_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Pengguna (Pelaku)
                    </label>
                    <select
                        id="user_id"
                        name="user_id"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-xs sm:text-sm font-semibold text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
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
                    <label for="aksi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Jenis Aksi
                    </label>
                    <input
                        id="aksi"
                        type="text"
                        name="aksi"
                        value="{{ request('aksi') }}"
                        placeholder="Contoh: LOGIN, CREATE, DELETE..."
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                    >
                </div>

                <!-- Dari Tanggal -->
                <div>
                    <label for="start_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Dari Tanggal
                    </label>
                    <input
                        id="start_date"
                        type="date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label for="end_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Sampai Tanggal
                    </label>
                    <input
                        id="end_date"
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                    >
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="h-11 flex-1 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs transition-all cursor-pointer inline-flex items-center justify-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>Filter Log</span>
                    </button>
                    @if(request()->hasAny(['user_id', 'aksi', 'start_date', 'end_date']))
                        <a
                            href="{{ route('admin.log') }}"
                            class="h-11 px-3.5 flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs border border-slate-200 rounded-xl transition-all no-underline whitespace-nowrap"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 3. Tabel Log Aktivitas -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="font-bold tracking-tight text-slate-900 text-base">Catatan Riwayat Aktivitas</h3>
                    <p class="text-xs text-slate-500">Daftar rekaman kronologis peristiwa dan interaksi pengguna sistem</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 font-mono">
                    Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4 w-40">Waktu (WIB)</th>
                            <th class="py-3.5 px-4">Pengguna (Pelaku)</th>
                            <th class="py-3.5 px-4">Aksi</th>
                            <th class="py-3.5 px-4">Entitas Terkait</th>
                            <th class="py-3.5 px-4">Keterangan / Parameter</th>
                            <th class="py-3.5 px-4 font-mono text-xs">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-800">
                        @forelse($logs as $index => $log)
                            <tr class="h-[60px] hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-500 whitespace-nowrap">
                                    <div class="font-semibold text-slate-900">{{ $log->created_at ? $log->created_at->translatedFormat('d M Y') : '-' }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $log->created_at ? $log->created_at->format('H:i:s') : '-' }} WIB</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($log->user)
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shadow-2xs {{ $log->user->isAdmin() ? 'bg-slate-900 text-white' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                                {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 text-xs">{{ $log->user->name }}</div>
                                                <div class="text-[11px] text-slate-500 font-mono">{{ $log->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">Sistem / Tamu</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $aksiUpper = strtoupper($log->aksi);
                                        $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                                        if (str_contains($aksiUpper, 'LOGIN') || str_contains($aksiUpper, 'APPROVE')) {
                                            $badgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                        } elseif (str_contains($aksiUpper, 'LOGOUT')) {
                                            $badgeClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                        } elseif (str_contains($aksiUpper, 'CREATE')) {
                                            $badgeClass = 'bg-sky-50 text-sky-800 border-sky-200';
                                        } elseif (str_contains($aksiUpper, 'UPDATE') || str_contains($aksiUpper, 'RESET')) {
                                            $badgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
                                        } elseif (str_contains($aksiUpper, 'DELETE') || str_contains($aksiUpper, 'REJECT') || str_contains($aksiUpper, 'FAILED')) {
                                            $badgeClass = 'bg-rose-50 text-rose-800 border-rose-200';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-mono font-bold border {{ $badgeClass }}">
                                        {{ $log->aksi }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs">
                                    @if($log->entitas_tipe)
                                        <span class="font-semibold text-slate-900">{{ class_basename($log->entitas_tipe) }}</span>
                                        @if($log->entitas_id)
                                            <span class="text-slate-400">#{{ $log->entitas_id }}</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 max-w-xs">
                                    @if(is_array($log->keterangan) && count($log->keterangan) > 0)
                                        <div class="space-y-1">
                                            @foreach($log->keterangan as $key => $val)
                                                <div class="text-[11px] leading-tight">
                                                    <span class="font-semibold text-slate-700 font-mono">{{ $key }}:</span>
                                                    <span class="text-slate-600 font-mono">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif(is_string($log->keterangan))
                                        <span class="text-slate-600 font-mono text-[11px]">{{ $log->keterangan }}</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-500 whitespace-nowrap">
                                    {{ $log->ip_address ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 space-y-2">
                                    <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-slate-500">Tidak ada catatan log aktivitas yang cocok dengan kriteria filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
