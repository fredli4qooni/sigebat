<!-- MODAL 1: Tambah Pengguna Baru -->
<div
    x-show="createModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    style="display: none;"
    x-transition.opacity
>
    <div
        @click.away="createModal = false"
        class="bg-white border border-slate-200/90 rounded-2xl max-w-lg w-full p-6 sm:p-7 space-y-5 max-h-[90vh] overflow-y-auto shadow-2xl"
    >
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="font-bold tracking-tight text-xl text-slate-900">Tambah Pengguna Baru</h3>
            <button
                type="button"
                @click="createModal = false"
                class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 inline-flex items-center justify-center font-bold text-lg cursor-pointer transition-colors"
            >
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="new_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Lengkap
                </label>
                <input
                    id="new_name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    placeholder="Contoh: Budi Santoso"
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <label for="new_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Alamat Email
                </label>
                <input
                    id="new_email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="Contoh: budi@gedungbatin.desa.id"
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <label for="new_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nomor HP / WhatsApp
                </label>
                <input
                    id="new_phone"
                    name="phone"
                    type="text"
                    value="{{ old('phone') }}"
                    required
                    placeholder="Contoh: 081234567890"
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="new_role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Peran Sistem
                    </label>
                    <select
                        id="new_role"
                        name="role"
                        required
                        class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full cursor-pointer"
                    >
                        <option value="pengelola" selected>Pengelola wisata</option>
                        <option value="admin">Administrator</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1" />
                </div>

                <div>
                    <label for="new_status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status Akun
                    </label>
                    <select
                        id="new_status"
                        name="status"
                        required
                        class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full cursor-pointer"
                    >
                        <option value="aktif" selected>Aktif</option>
                        <option value="pending">Pending</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            <div>
                <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kata Sandi
                </label>
                <input
                    id="new_password"
                    name="password"
                    type="password"
                    required
                    placeholder="Minimal 8 karakter"
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button
                    type="button"
                    @click="createModal = false"
                    class="h-11 px-5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition-all cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-emerald-600/20 transition-all cursor-pointer"
                >
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: Edit Data Pengguna -->
<div
    x-show="editModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    style="display: none;"
    x-transition.opacity
>
    <div
        @click.away="editModal = false"
        class="bg-white border border-slate-200/90 rounded-2xl max-w-lg w-full p-6 sm:p-7 space-y-5 max-h-[90vh] overflow-y-auto shadow-2xl"
    >
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="font-bold tracking-tight text-xl text-slate-900">
                Edit Pengguna: <span x-text="activeUser.name"></span>
            </h3>
            <button
                type="button"
                @click="editModal = false"
                class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 inline-flex items-center justify-center font-bold text-lg cursor-pointer transition-colors"
            >
                &times;
            </button>
        </div>

        <form
            method="POST"
            :action="'/admin/pengguna/' + activeUser.id"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <template x-if="activeUser.id === currentUserId">
                <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 font-semibold flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Perhatian: Anda sedang mengubah akun Anda sendiri. Peran Admin dan Status Aktif terkunci untuk mencegah lockout sistem.</span>
                </div>
            </template>

            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Lengkap
                </label>
                <input
                    id="edit_name"
                    name="name"
                    type="text"
                    x-model="activeUser.name"
                    required
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
            </div>

            <div>
                <label for="edit_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Alamat Email
                </label>
                <input
                    id="edit_email"
                    name="email"
                    type="email"
                    x-model="activeUser.email"
                    required
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
            </div>

            <div>
                <label for="edit_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nomor HP / WhatsApp
                </label>
                <input
                    id="edit_phone"
                    name="phone"
                    type="text"
                    x-model="activeUser.phone"
                    required
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit_role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Peran Sistem
                    </label>
                    <select
                        id="edit_role"
                        name="role"
                        x-model="activeUser.role"
                        :disabled="activeUser.id === currentUserId"
                        required
                        class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed"
                    >
                        <option value="admin">Administrator</option>
                        <option value="pengelola">Pengelola wisata</option>
                    </select>
                    <template x-if="activeUser.id === currentUserId">
                        <input type="hidden" name="role" value="admin">
                    </template>
                </div>

                <div>
                    <label for="edit_status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status Akun
                    </label>
                    <select
                        id="edit_status"
                        name="status"
                        x-model="activeUser.status"
                        :disabled="activeUser.id === currentUserId"
                        required
                        class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed"
                    >
                        <option value="aktif">Aktif</option>
                        <option value="pending">Pending</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <template x-if="activeUser.id === currentUserId">
                        <input type="hidden" name="status" value="aktif">
                    </template>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button
                    type="button"
                    @click="editModal = false"
                    class="h-11 px-5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition-all cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-emerald-600/20 transition-all cursor-pointer"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 3: Reset Kata Sandi Pengguna -->
<div
    x-show="resetModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
    style="display: none;"
    x-transition.opacity
>
    <div
        @click.away="resetModal = false"
        class="bg-white border border-slate-200/90 rounded-2xl max-w-md w-full p-6 sm:p-7 space-y-5 shadow-2xl"
    >
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="font-bold tracking-tight text-xl text-slate-900">Reset Kata Sandi</h3>
            <button
                type="button"
                @click="resetModal = false"
                class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 inline-flex items-center justify-center font-bold text-lg cursor-pointer transition-colors"
            >
                &times;
            </button>
        </div>

        <p class="text-sm text-slate-600 leading-relaxed">
            Atur ulang kata sandi untuk akun <strong class="text-slate-900" x-text="activeUser.name"></strong> (<span class="font-mono text-xs text-slate-500" x-text="activeUser.email"></span>).
        </p>

        <form
            method="POST"
            :action="'/admin/pengguna/' + activeUser.id + '/reset-password'"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <div>
                <label for="reset_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kata Sandi Baru
                </label>
                <input
                    id="reset_password"
                    name="password"
                    type="password"
                    required
                    placeholder="Minimal 8 karakter"
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
            </div>

            <div>
                <label for="reset_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Ulangi Kata Sandi Baru
                </label>
                <input
                    id="reset_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    placeholder="Ketik ulang kata sandi baru"
                    class="h-11 px-3.5 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all w-full"
                >
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button
                    type="button"
                    @click="resetModal = false"
                    class="h-11 px-5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition-all cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-emerald-600/20 transition-all cursor-pointer"
                >
                    Reset Sandi
                </button>
            </div>
        </form>
    </div>
</div>
