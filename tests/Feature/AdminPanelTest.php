<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\EventBudaya;
use App\Models\Fasilitas;
use App\Models\JenisFasilitas;
use App\Models\KategoriEvent;
use App\Models\KategoriWisata;
use App\Models\ObjekWisata;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create([
            'email' => 'admin@gedungbatin.desa.id',
        ]);
    }

    public function test_admin_dashboard_can_be_accessed_by_admin(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('Panel pemantauan sistem');
        $response->assertSee('Ringkasan data desa');
    }

    public function test_admin_dashboard_is_forbidden_for_pengelola(): void
    {
        $pengelola = User::factory()->pengelola()->create();
        $response = $this->actingAs($pengelola)->get(route('admin.dashboard'));
        $response->assertForbidden();
    }

    public function test_master_wisata_crud_and_deletion_restriction(): void
    {
        // 1. View
        $response = $this->actingAs($this->admin)->get(route('admin.master.wisata'));
        $response->assertOk();
        $response->assertSee('Kategori Objek Wisata');

        // 2. Store
        $storeResponse = $this->actingAs($this->admin)->post(route('admin.master.wisata.store'), [
            'nama' => 'Wisata Sejarah Unik',
            'deskripsi' => 'Situs cagar budaya bersejarah',
        ]);
        $storeResponse->assertRedirect(route('admin.master.wisata'));
        $this->assertDatabaseHas('kategori_wisata', [
            'nama' => 'Wisata Sejarah Unik',
            'slug' => 'wisata-sejarah-unik',
        ]);

        $kategori = KategoriWisata::where('slug', 'wisata-sejarah-unik')->first();
        $this->assertNotNull($kategori);

        // 3. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.master.wisata.update', $kategori), [
            'nama' => 'Wisata Pusaka Budaya',
            'deskripsi' => 'Warisan rumah panggung tua',
        ]);
        $updateResponse->assertRedirect(route('admin.master.wisata'));
        $this->assertDatabaseHas('kategori_wisata', [
            'id' => $kategori->id,
            'nama' => 'Wisata Pusaka Budaya',
        ]);

        // 4. Deletion restriction when child exists
        ObjekWisata::factory()->create([
            'kategori_wisata_id' => $kategori->id,
            'created_by' => $this->admin->id,
        ]);

        $deleteAttempt = $this->actingAs($this->admin)->delete(route('admin.master.wisata.destroy', $kategori));
        $deleteAttempt->assertSessionHas('error');
        $this->assertDatabaseHas('kategori_wisata', ['id' => $kategori->id]);

        // 5. Successful deletion when no child records
        $emptyKategori = KategoriWisata::factory()->create(['nama' => 'Kategori Kosong']);
        $deleteSuccess = $this->actingAs($this->admin)->delete(route('admin.master.wisata.destroy', $emptyKategori));
        $deleteSuccess->assertRedirect(route('admin.master.wisata'));
        $this->assertDatabaseMissing('kategori_wisata', ['id' => $emptyKategori->id]);
    }

    public function test_master_event_crud_and_deletion_restriction(): void
    {
        // 1. View & Store
        $response = $this->actingAs($this->admin)->post(route('admin.master.event.store'), [
            'nama' => 'Ritual Adat Lampung',
            'deskripsi' => 'Upacara pemanggilan leluhur',
        ]);
        $response->assertRedirect(route('admin.master.event'));
        $this->assertDatabaseHas('kategori_event', ['nama' => 'Ritual Adat Lampung']);

        $kategori = KategoriEvent::where('nama', 'Ritual Adat Lampung')->first();

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.master.event.update', $kategori), [
            'nama' => 'Festival Musik Tradisi',
            'deskripsi' => 'Kulintang & Cetik',
        ]);
        $updateResponse->assertRedirect(route('admin.master.event'));
        $this->assertDatabaseHas('kategori_event', ['id' => $kategori->id, 'nama' => 'Festival Musik Tradisi']);

        // 3. Deletion restriction when referenced
        EventBudaya::factory()->create([
            'kategori_event_id' => $kategori->id,
            'created_by' => $this->admin->id,
        ]);

        $deleteAttempt = $this->actingAs($this->admin)->delete(route('admin.master.event.destroy', $kategori));
        $deleteAttempt->assertSessionHas('error');
        $this->assertDatabaseHas('kategori_event', ['id' => $kategori->id]);

        // 4. Successful delete when clean
        $emptyKategori = KategoriEvent::factory()->create(['nama' => 'Event Kosong']);
        $deleteSuccess = $this->actingAs($this->admin)->delete(route('admin.master.event.destroy', $emptyKategori));
        $deleteSuccess->assertRedirect(route('admin.master.event'));
        $this->assertDatabaseMissing('kategori_event', ['id' => $emptyKategori->id]);
    }

    public function test_master_fasilitas_crud_and_deletion_restriction(): void
    {
        // 1. View & Store
        $response = $this->actingAs($this->admin)->post(route('admin.master.fasilitas.store'), [
            'nama' => 'Pos Keamanan Adat',
            'icon' => 'shield',
        ]);
        $response->assertRedirect(route('admin.master.fasilitas'));
        $this->assertDatabaseHas('jenis_fasilitas', ['nama' => 'Pos Keamanan Adat']);

        $jenis = JenisFasilitas::where('nama', 'Pos Keamanan Adat')->first();

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.master.fasilitas.update', $jenis), [
            'nama' => 'Pos Ronda Desa',
            'icon' => 'home',
        ]);
        $updateResponse->assertRedirect(route('admin.master.fasilitas'));
        $this->assertDatabaseHas('jenis_fasilitas', ['id' => $jenis->id, 'nama' => 'Pos Ronda Desa']);

        // 3. Restriction
        Fasilitas::factory()->create([
            'jenis_fasilitas_id' => $jenis->id,
            'created_by' => $this->admin->id,
        ]);

        $deleteAttempt = $this->actingAs($this->admin)->delete(route('admin.master.fasilitas.destroy', $jenis));
        $deleteAttempt->assertSessionHas('error');
        $this->assertDatabaseHas('jenis_fasilitas', ['id' => $jenis->id]);

        // 4. Successful delete
        $emptyJenis = JenisFasilitas::factory()->create(['nama' => 'Fasilitas Kosong']);
        $deleteSuccess = $this->actingAs($this->admin)->delete(route('admin.master.fasilitas.destroy', $emptyJenis));
        $deleteSuccess->assertRedirect(route('admin.master.fasilitas'));
        $this->assertDatabaseMissing('jenis_fasilitas', ['id' => $emptyJenis->id]);
    }

    public function test_admin_can_view_users_list_and_search(): void
    {
        $user1 = User::factory()->create(['name' => 'Wawan Pengelola', 'email' => 'wawan@test.com', 'role' => 'pengelola']);
        $user2 = User::factory()->create(['name' => 'Rini Kasir', 'email' => 'rini@test.com', 'role' => 'pengelola']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.index', ['q' => 'Wawan']));
        $response->assertOk();
        $response->assertSee('Wawan Pengelola');
        $response->assertDontSee('Rini Kasir');
    }

    public function test_admin_can_create_new_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Pengelola Baru',
            'email' => 'pengelola.baru@gedungbatin.desa.id',
            'phone' => '082211334455',
            'role' => 'pengelola',
            'status' => 'aktif',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'pengelola.baru@gedungbatin.desa.id',
            'role' => 'pengelola',
            'status' => 'aktif',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'CREATE_USER',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_update_existing_user(): void
    {
        $targetUser = User::factory()->pengelola()->create(['name' => 'Nama Lama']);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $targetUser), [
            'name' => 'Nama Baru',
            'email' => $targetUser->email,
            'phone' => '081299887766',
            'role' => 'pengelola',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Nama Baru',
            'phone' => '081299887766',
        ]);
    }

    public function test_admin_cannot_demote_or_deactivate_self(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->admin), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'phone' => $this->admin->phone,
            'role' => 'pengelola',
            'status' => 'nonaktif',
        ]);

        $response->assertSessionHas('error');
        $this->admin->refresh();
        $this->assertEquals('admin', $this->admin->role);
        $this->assertEquals('aktif', $this->admin->status);
    }

    public function test_admin_cannot_delete_self_or_last_admin(): void
    {
        // 1. Cannot delete self
        $deleteSelfResponse = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));
        $deleteSelfResponse->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        // 2. Cannot delete the last admin
        $anotherAdmin = User::factory()->admin()->create();
        // Now there are 2 admins. Delete another admin should succeed.
        $deleteAnother = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $anotherAdmin));
        $deleteAnother->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $anotherAdmin->id]);

        // Now attempt to delete if only 1 admin remains (tested by creating single admin)
        $this->assertDatabaseCount('users', 1);
        $deleteOnlyAdmin = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));
        $deleteOnlyAdmin->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_reset_user_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.users.reset-password', $user), [
            'password' => 'NewSecretPassword123!',
            'password_confirmation' => 'NewSecretPassword123!',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $user->refresh();
        $this->assertTrue(Hash::check('NewSecretPassword123!', $user->password));

        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'RESET_PASSWORD_BY_ADMIN',
            'user_id' => $this->admin->id,
            'entitas_id' => $user->id,
        ]);
    }

    public function test_admin_can_approve_pending_pengelola(): void
    {
        $pendingUser = User::factory()->pending()->create(['name' => 'Calon Pengelola']);

        $response = $this->actingAs($this->admin)->post(route('admin.verifikasi.approve', $pendingUser));
        $response->assertRedirect(route('admin.verifikasi.index'));

        $pendingUser->refresh();
        $this->assertEquals('aktif', $pendingUser->status);
        $this->assertNotNull($pendingUser->email_verified_at);

        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'VERIFY_APPROVE',
            'user_id' => $this->admin->id,
            'entitas_id' => $pendingUser->id,
        ]);
    }

    public function test_admin_can_reject_pending_pengelola_with_reason(): void
    {
        $pendingUser = User::factory()->pending()->create(['name' => 'Pelamar Tidak Sesuai']);

        $response = $this->actingAs($this->admin)->post(route('admin.verifikasi.reject', $pendingUser), [
            'rejection_reason' => 'Identitas tidak valid dan bukan warga desa.',
        ]);
        $response->assertRedirect(route('admin.verifikasi.index'));

        $pendingUser->refresh();
        $this->assertEquals('ditolak', $pendingUser->status);
        $this->assertEquals('Identitas tidak valid dan bukan warga desa.', $pendingUser->rejection_reason);

        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'VERIFY_REJECT',
            'user_id' => $this->admin->id,
            'entitas_id' => $pendingUser->id,
        ]);
    }

    public function test_admin_can_view_and_filter_activity_logs(): void
    {
        ActivityLog::log(
            aksi: 'CUSTOM_TEST_ACTION',
            keterangan: ['test_key' => 'test_value'],
            userId: $this->admin->id
        );

        $response = $this->actingAs($this->admin)->get(route('admin.log', [
            'user_id' => $this->admin->id,
            'aksi' => 'CUSTOM_TEST_ACTION',
        ]));

        $response->assertOk();
        $response->assertSee('CUSTOM_TEST_ACTION');
        $response->assertSee('test_key');
        $response->assertSee('test_value');
    }
}
