<x-app-layout>
    <x-slot:title>Kelola Pengguna Sistem</x-slot:title>
    <x-slot:header>Manajemen Pengguna</x-slot:header>

    <div class="space-y-6" x-data="{
        createModal: false,
        editModal: false,
        resetModal: false,
        activeUser: { id: null, name: '', email: '', phone: '', role: 'pengelola', status: 'aktif' },
        currentUserId: {{ auth()->id() }},
        openEdit(u) {
            this.activeUser = { ...u };
            this.editModal = true;
        },
        openReset(u) {
            this.activeUser = { ...u };
            this.resetModal = true;
        }
    }">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Akses & Hak Pengguna</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Kelola Pengguna Sistem
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Manajemen akun Administrator dan Pengelola Desa Wisata Kampung Gedung Batin, kendali peran, status akun, dan reset sandi.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-semibold">
                    {{ $users->total() }} Akun Terdaftar
                </span>
            </div>
        </div>

        <!-- 2. Filter & Bar Tambah Pengguna -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                    <!-- Search Input -->
                    <div class="w-full sm:w-72 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari nama, email, no HP..."
                            class="h-11 pl-10 pr-4 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                        >
                    </div>

                    <!-- Role Filter -->
                    <div class="w-36">
                        <select
                            name="role"
                            class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full cursor-pointer"
                        >
                            <option value="">Semua peran</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pengelola" {{ request('role') === 'pengelola' ? 'selected' : '' }}>Pengelola</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-36">
                        <select
                            name="status"
                            class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full cursor-pointer"
                        >
                            <option value="">Semua status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            class="h-11 px-4 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs transition-all cursor-pointer inline-flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter</span>
                        </button>

                        @if(request()->hasAny(['q', 'role', 'status']))
                            <a
                                href="{{ route('admin.users.index') }}"
                                class="h-11 px-3.5 flex items-center bg-slate-100 text-slate-600 font-semibold text-xs border border-slate-200 rounded-xl hover:bg-slate-200 transition-all no-underline"
                            >
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Tombol Tambah Pengguna Baru -->
                <div class="flex-shrink-0">
                    <button
                        type="button"
                        @click="createModal = true"
                        class="h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-xs sm:text-sm shadow-xs hover:shadow-emerald-600/20 transition-all cursor-pointer inline-flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Pengguna</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 3. Tabel Akun Pengguna -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="font-bold tracking-tight text-slate-900 text-base">Daftar Akun Pengguna</h3>
                    <p class="text-xs text-slate-500">Kelola kredensial dan hak akses administrator serta pengelola</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $users->total() }} Akun Terdaftar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Pengguna</th>
                            <th class="py-3.5 px-4">Kontak (HP)</th>
                            <th class="py-3.5 px-4 text-center">Peran</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4">Terdaftar</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse($users as $index => $u)
                            <tr class="h-[64px] hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400 text-xs">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shadow-2xs {{ $u->isAdmin() ? 'bg-slate-900 text-white' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                                <span>{{ $u->name }}</span>
                                                @if($u->id === auth()->id())
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-200">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs">
                                    @if($u->phone)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $u->phone)) }}" target="_blank" class="text-emerald-700 hover:text-emerald-800 font-semibold hover:underline inline-flex items-center gap-1">
                                            <span>{{ $u->phone }}</span>
                                            <span class="text-[11px]">↗</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($u->isAdmin())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-900 text-white text-xs font-bold">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold">
                                            Pengelola
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <x-tag-status :status="$u->status" />
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-500">
                                    {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Tombol Edit -->
                                        <button
                                            type="button"
                                            @click="openEdit({{ json_encode([
                                                'id' => $u->id,
                                                'name' => $u->name,
                                                'email' => $u->email,
                                                'phone' => $u->phone,
                                                'role' => $u->role,
                                                'status' => $u->status
                                            ]) }})"
                                            class="px-3 py-1.5 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-700 transition-all cursor-pointer inline-flex items-center gap-1.5 shadow-2xs"
                                            title="Ubah data pengguna"
                                        >
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Edit</span>
                                        </button>

                                        <!-- Tombol Reset Sandi -->
                                        <button
                                            type="button"
                                            @click="openReset({{ json_encode([
                                                'id' => $u->id,
                                                'name' => $u->name,
                                                'email' => $u->email
                                            ]) }})"
                                            class="px-3 py-1.5 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-700 transition-all cursor-pointer inline-flex items-center gap-1.5 shadow-2xs"
                                            title="Reset kata sandi pengguna"
                                        >
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                            </svg>
                                            <span>Sandi</span>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        @if($u->id !== auth()->id())
                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.destroy', $u) }}"
                                                onsubmit="return confirm('Hapus akun \'{{ $u->name }}\'? Tindakan ini tidak dapat dibatalkan.');"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5 bg-rose-50/70 border border-rose-200 hover:bg-rose-100 hover:border-rose-300 rounded-xl text-xs font-semibold text-rose-700 transition-all cursor-pointer inline-flex items-center gap-1.5 shadow-2xs"
                                                    title="Hapus akun pengguna"
                                                >
                                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 space-y-2">
                                    <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-slate-500">Tidak ada data pengguna yang sesuai dengan filter pencarian.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        @include('admin.users.partials.modals')
    </div>
</x-app-layout>
