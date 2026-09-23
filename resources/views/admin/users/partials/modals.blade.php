<!-- MODAL 1: Tambah Pengguna Baru -->
<div
    x-show="createModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-aspal/60"
    style="display: none;"
    x-transition.opacity
>
    <div
        @click.away="createModal = false"
        class="bg-putih border-2 border-aspal rounded-papan max-w-lg w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto"
    >
        <div class="flex items-center justify-between border-b border-beton pb-3">
            <h3 class="font-bold font-papan text-xl text-aspal">Tambah Pengguna Baru</h3>
            <button type="button" @click="createModal = false" class="text-abu hover:text-aspal font-bold text-lg cursor-pointer">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="new_name" value="Nama lengkap" />
                <x-text-input id="new_name" name="name" type="text" :value="old('name')" required placeholder="Contoh: Budi Santoso" />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="new_email" value="Alamat email" />
                <x-text-input id="new_email" name="email" type="email" :value="old('email')" required placeholder="Contoh: budi@gedungbatin.desa.id" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="new_phone" value="Nomor HP / WhatsApp" />
                <x-text-input id="new_phone" name="phone" type="text" :value="old('phone')" required placeholder="Contoh: 081234567890" />
                <x-input-error :messages="$errors->get('phone')" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="new_role" value="Peran sistem" />
                    <select
                        id="new_role"
                        name="role"
                        required
                        class="h-[48px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                        <option value="pengelola" selected>Pengelola wisata</option>
                        <option value="admin">Administrator</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" />
                </div>

                <div>
                    <x-input-label for="new_status" value="Status akun" />
                    <select
                        id="new_status"
                        name="status"
                        required
                        class="h-[48px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                    >
                        <option value="aktif" selected>Aktif</option>
                        <option value="pending">Pending</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" />
                </div>
            </div>

            <div>
                <x-input-label for="new_password" value="Kata sandi" />
                <x-text-input id="new_password" name="password" type="password" required placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-beton">
                <button
                    type="button"
                    @click="createModal = false"
                    class="h-[44px] px-4 bg-putih border-2 border-aspal text-aspal font-bold text-sm rounded-kontrol hover:bg-beton cursor-pointer"
                >
                    Batal
                </button>
                <x-primary-button>
                    Simpan Pengguna
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: Edit Data Pengguna -->
<div
    x-show="editModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-aspal/60"
    style="display: none;"
    x-transition.opacity
>
    <div
        @click.away="editModal = false"
        class="bg-putih border-2 border-aspal rounded-papan max-w-lg w-full p-6 space-y-4 max-h-[90vh] overflow-y-auto"
    >
        <div class="flex items-center justify-between border-b border-beton pb-3">
            <h3 class="font-bold font-papan text-xl text-aspal">
                Edit Pengguna: <span x-text="activeUser.name"></span>
            </h3>
            <button type="button" @click="editModal = false" class="text-abu hover:text-aspal font-bold text-lg cursor-pointer">&times;</button>
        </div>

        <form
            method="POST"
            :action="'/admin/pengguna/' + activeUser.id"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <template x-if="activeUser.id === currentUserId">
                <div class="p-3 bg-kuning/20 border-2 border-kuning rounded-kontrol text-xs text-aspal font-semibold">
                    Perhatian: Anda sedang mengubah akun Anda sendiri. Peran Admin dan Status Aktif terkunci untuk mencegah lockout sistem.
                </div>
            </template>

            <div>
                <x-input-label for="edit_name" value="Nama lengkap" />
                <input
                    id="edit_name"
                    name="name"
                    type="text"
                    x-model="activeUser.name"
                    required
                    class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-base text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                >
            </div>

            <div>
                <x-input-label for="edit_email" value="Alamat email" />
                <input
                    id="edit_email"
                    name="email"
                    type="email"
                    x-model="activeUser.email"
                    required
                    class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-base text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                >
            </div>

            <div>
                <x-input-label for="edit_phone" value="Nomor HP / WhatsApp" />
                <input
                    id="edit_phone"
                    name="phone"
                    type="text"
                    x-model="activeUser.phone"
                    required
                    class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-base text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                >
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="edit_role" value="Peran sistem" />
                    <select
                        id="edit_role"
                        name="role"
                        x-model="activeUser.role"
                        :disabled="activeUser.id === currentUserId"
                        required
                        class="h-[48px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal disabled:bg-beton disabled:cursor-not-allowed"
                    >
                        <option value="admin">Administrator</option>
                        <option value="pengelola">Pengelola wisata</option>
                    </select>
                    <template x-if="activeUser.id === currentUserId">
                        <input type="hidden" name="role" value="admin">
                    </template>
                </div>

                <div>
                    <x-input-label for="edit_status" value="Status akun" />
                    <select
                        id="edit_status"
                        name="status"
                        x-model="activeUser.status"
                        :disabled="activeUser.id === currentUserId"
                        required
                        class="h-[48px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal disabled:bg-beton disabled:cursor-not-allowed"
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

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-beton">
                <button
                    type="button"
                    @click="editModal = false"
                    class="h-[44px] px-4 bg-putih border-2 border-aspal text-aspal font-bold text-sm rounded-kontrol hover:bg-beton cursor-pointer"
                >
                    Batal
                </button>
                <x-primary-button>
                    Simpan Perubahan
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 3: Reset Kata Sandi Pengguna -->
<div
    x-show="resetModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-aspal/60"
    style="display: none;"
    x-transition.opacity
>
    <div
        @click.away="resetModal = false"
        class="bg-putih border-2 border-aspal rounded-papan max-w-md w-full p-6 space-y-4"
    >
        <div class="flex items-center justify-between border-b border-beton pb-3">
            <h3 class="font-bold font-papan text-xl text-aspal">Reset Kata Sandi</h3>
            <button type="button" @click="resetModal = false" class="text-abu hover:text-aspal font-bold text-lg cursor-pointer">&times;</button>
        </div>

        <p class="text-sm text-abu">
            Atur ulang kata sandi untuk akun <strong class="text-aspal" x-text="activeUser.name"></strong> (<span class="font-mono text-xs" x-text="activeUser.email"></span>).
        </p>

        <form
            method="POST"
            :action="'/admin/pengguna/' + activeUser.id + '/reset-password'"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="reset_password" value="Kata sandi baru" />
                <x-text-input id="reset_password" name="password" type="password" required placeholder="Minimal 8 karakter" />
            </div>

            <div>
                <x-input-label for="reset_password_confirmation" value="Ulangi kata sandi baru" />
                <x-text-input id="reset_password_confirmation" name="password_confirmation" type="password" required placeholder="Ketik ulang kata sandi baru" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-beton">
                <button
                    type="button"
                    @click="resetModal = false"
                    class="h-[44px] px-4 bg-putih border-2 border-aspal text-aspal font-bold text-sm rounded-kontrol hover:bg-beton cursor-pointer"
                >
                    Batal
                </button>
                <x-primary-button>
                    Reset Sandi
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
