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
        <!-- Papan Biru (DESIGN.md 7.11: Fasilitas/Pengguna/Akun) -->
        <x-papan warna="biru" class="p-6">
            <h2 class="text-2xl font-bold font-papan text-putih">Kelola Pengguna Sistem</h2>
            <p class="text-putih/90 text-sm mt-1">
                Daftar akun Administrator dan Pengelola Desa Wisata Kampung Gedung Batin.
            </p>
        </x-papan>

        <!-- Filter & Bar Tambah Pengguna -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-4 sm:p-5">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                    <!-- Search Input -->
                    <div class="w-full sm:w-64">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari nama, email, no HP..."
                            class="h-[44px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal placeholder-abu focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                    </div>

                    <!-- Role Filter -->
                    <div class="w-36">
                        <select
                            name="role"
                            class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
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
                            class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            <option value="">Semua status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="h-[44px] px-4 bg-aspal text-putih font-bold text-sm rounded-kontrol hover:bg-black cursor-pointer">
                            Filter
                        </button>

                        @if(request()->hasAny(['q', 'role', 'status']))
                            <a href="{{ route('admin.users.index') }}" class="h-[44px] px-3 flex items-center bg-beton text-aspal font-semibold text-xs border border-abu rounded-kontrol hover:bg-abu/20 no-underline">
                                Reset filter
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Tombol Tambah Pengguna Baru -->
                <div class="flex-shrink-0">
                    <button
                        type="button"
                        @click="createModal = true"
                        class="h-[44px] px-5 bg-hijau text-putih border-2 border-aspal rounded-kontrol font-bold text-sm hover:opacity-95 cursor-pointer flex items-center gap-2"
                    >
                        <span class="text-lg leading-none">+</span>
                        <span>Tambah Pengguna</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Pengguna -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div class="p-4 border-b border-beton flex items-center justify-between">
                <h3 class="font-bold font-papan text-aspal text-lg">Daftar akun pengguna</h3>
                <span class="text-xs text-abu">{{ $users->total() }} akun terdaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aspal text-putih text-sm font-semibold">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Pengguna</th>
                            <th class="py-3 px-4">Kontak (HP)</th>
                            <th class="py-3 px-4 text-center">Peran</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4">Terdaftar</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-beton text-sm text-aspal">
                        @forelse($users as $index => $u)
                            <tr class="h-[64px] hover:bg-beton/40">
                                <td class="py-3 px-4 text-center font-bold text-abu">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-kontrol flex items-center justify-center font-bold text-xs {{ $u->isAdmin() ? 'bg-aspal text-putih' : 'bg-biru text-putih' }}">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-aspal flex items-center gap-1.5">
                                                <span>{{ $u->name }}</span>
                                                @if($u->id === auth()->id())
                                                    <span class="text-[11px] font-bold px-1.5 py-0.5 rounded bg-kuning text-aspal">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-abu font-mono">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-mono text-xs">
                                    @if($u->phone)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $u->phone)) }}" target="_blank" class="text-aspal hover:underline flex items-center gap-1">
                                            <span>{{ $u->phone }}</span>
                                            <span class="text-[10px] text-abu">↗</span>
                                        </a>
                                    @else
                                        <span class="text-abu">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($u->isAdmin())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-tag bg-aspal text-putih text-xs font-bold">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-tag bg-biru text-putih text-xs font-bold">
                                            Pengelola
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <x-tag-status :status="$u->status" />
                                </td>
                                <td class="py-3 px-4 text-xs text-abu">
                                    {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
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
                                            class="px-2.5 py-1.5 bg-putih border-2 border-aspal rounded-kontrol text-xs font-bold text-aspal hover:bg-beton cursor-pointer"
                                            title="Ubah data pengguna"
                                        >
                                            Edit
                                        </button>

                                        <!-- Tombol Reset Sandi -->
                                        <button
                                            type="button"
                                            @click="openReset({{ json_encode([
                                                'id' => $u->id,
                                                'name' => $u->name,
                                                'email' => $u->email
                                            ]) }})"
                                            class="px-2.5 py-1.5 bg-putih border border-abu rounded-kontrol text-xs font-semibold text-aspal hover:bg-beton cursor-pointer"
                                            title="Reset kata sandi pengguna"
                                        >
                                            Sandi
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
                                                    class="px-2.5 py-1.5 bg-putih border-2 border-merah rounded-kontrol text-xs font-bold text-merah hover:bg-merah hover:text-putih cursor-pointer"
                                                    title="Hapus akun pengguna"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-abu">
                                    Tidak ada data pengguna yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-beton">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        @include('admin.users.partials.modals')
    </div>
</x-app-layout>
