<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_redirects_to_admin_dashboard_and_logs_activity(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/admin');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'aksi' => 'LOGIN',
        ]);
    }

    public function test_pengelola_login_redirects_to_pengelola_dashboard_and_logs_activity(): void
    {
        $pengelola = User::factory()->pengelola()->create([
            'email' => 'pengelola@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'pengelola@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($pengelola);
        $response->assertRedirect('/pengelola');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $pengelola->id,
            'aksi' => 'LOGIN',
        ]);
    }

    public function test_pending_pengelola_login_is_rejected_with_proper_message(): void
    {
        $pendingUser = User::factory()->pending()->create([
            'email' => 'pending@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'pending@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            'Akun Anda menunggu verifikasi Admin.',
            session('errors')->first('email')
        );

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $pendingUser->id,
            'aksi' => 'LOGIN_BLOCKED',
        ]);
    }

    public function test_rejected_pengelola_login_is_rejected_with_proper_message(): void
    {
        $ditolakUser = User::factory()->ditolak('Dokumen KTP tidak jelas')->create([
            'email' => 'ditolak@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'ditolak@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
        $this->assertStringContainsString('Pendaftaran akun Anda ditolak oleh Admin', session('errors')->first('email'));
    }

    public function test_nonaktif_user_login_is_rejected_with_proper_message(): void
    {
        $nonaktifUser = User::factory()->nonaktif()->create([
            'email' => 'nonaktif@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'nonaktif@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(
            'Akun Anda dinonaktifkan. Hubungi Admin.',
            session('errors')->first('email')
        );
    }

    public function test_pengelola_cannot_access_admin_routes(): void
    {
        $pengelola = User::factory()->pengelola()->create();

        $response = $this->actingAs($pengelola)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_pengelola_routes(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/pengelola');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_protected_routes(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/pengelola')->assertRedirect('/login');
    }

    public function test_pengelola_registration_creates_pending_account_and_logs_activity(): void
    {
        $response = $this->post('/daftar-pengelola', [
            'name' => 'Calon Pengelola Baru',
            'email' => 'calon@test.com',
            'phone' => '081234567899',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('users', [
            'email' => 'calon@test.com',
            'role' => 'pengelola',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'aksi' => 'REGISTER',
        ]);

        // User must not be logged in after registration
        $this->assertGuest();
    }

    public function test_user_logout_logs_activity_and_redirects_to_home(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'aksi' => 'LOGOUT',
        ]);
    }
}
