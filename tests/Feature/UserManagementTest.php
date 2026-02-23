<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Admin bisa melihat daftar user.
     */
    public function test_admin_can_view_users_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    /**
     * Test Guru (Non-Admin) TIDAK bisa melihat daftar user.
     */
    public function test_guru_cannot_view_users_list()
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('users.index'));

        $response->assertStatus(403); // Middleware can:is-admin
    }

    /**
     * Test Admin bisa membuat user baru.
     */
    public function test_admin_can_create_new_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $data = [
            'name' => 'New Guru',
            'email' => 'newguru@example.com',
            'password' => 'password123',
            'role' => 'guru',
            'laboratorium' => 'Fisika',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email' => 'newguru@example.com',
            'role' => 'guru',
            'laboratorium' => 'Fisika',
        ]);
    }

    /**
     * Test Validasi: Email harus unik & Role Guru wajib Laboratorium.
     */
    public function test_create_user_validation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['email' => 'existing@example.com']);

        $data = [
            'name' => 'Duplicate Email User',
            'email' => 'existing@example.com', // Duplicate
            'password' => 'password123',
            'role' => 'guru',
            'laboratorium' => null, // Wajib jika role guru
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $data);

        $response->assertSessionHasErrors(['email', 'laboratorium']);
    }

    /**
     * Test Admin bisa edit user.
     */
    public function test_admin_can_update_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['name' => 'Old Name', 'role' => 'guru']);

        $updateData = [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => 'guru',
            'laboratorium' => 'Biologi',
        ];

        $response = $this->actingAs($admin)->patch(route('users.update', $user), $updateData);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name', 'laboratorium' => 'Biologi']);
    }

    /**
     * Test Admin bisa hapus user lain.
     */
    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $userToDelete));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /**
     * Test Admin TIDAK bisa hapus diri sendiri.
     */
    public function test_admin_cannot_delete_self()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertSessionHasErrors(['message']); // Kunci error 'message' di controller
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
