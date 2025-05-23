<?php

namespace Tests\Feature;

use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    // use RefreshDatabase;

    // public function test_login_form_can_be_rendered()
    // {
    //     $response = $this->get(route('login'));

    //     $response->assertStatus(200);
    //     $response->assertSee('Halaman Login');
    // }

    public function test_admin_can_login()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
            'role' => 'admin',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
            'remember' => true,
        ]);

        $this->assertTrue(Auth::check());
        $this->assertEquals($admin->id, Auth::id());
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'password',
            'remember' => true,
        ]);

        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $response->assertRedirect(route('user.dashboard'));
    }

    // public function test_user_cannot_login_with_invalid_credentials()
    // {
    //     $response = $this->post(route('login'), [
    //         'email' => 'wrong@example.com',
    //         'password' => 'wrongpassword',
    //     ]);

    //     $this->assertFalse(Auth::check());
    //     $response->assertRedirect('/');
    //     $response->assertSessionHasErrors(['name' => 'The provided credentials do not match our records.']);
    // }

    public function test_user_cannot_login_if_account_is_not_active()
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password'),
            'status' => 'non active',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'inactive@example.com',
            'password' => 'password',
        ]);

        $this->assertFalse(Auth::check());
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['name' => 'Akun Anda tidak dapat digunakan.']);
    }

    public function test_user_can_logout()
    {
        // Simulasi login
        $user = User::factory()->create([
            'email' => 'usera@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
            'role' => 'user',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $this->assertFalse(Auth::check());
        $response->assertRedirect('/');
    }

    public function test_admin_can_logout()
    {
        // Simulasi login
        $admin = User::factory()->create([
            'email' => 'admina@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('logout'));

        $this->assertFalse(Auth::check());
        $response->assertRedirect('/');
    }

    public function test_admin_cannot_login_if_account_is_not_active()
    {
        $admin = User::factory()->create([
            'email' => 'inactive_admin@example.com',
            'password' => bcrypt('password'),
            'status' => 'non active',
            'role' => 'admin',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'inactive_admin@example.com',
            'password' => 'password',
        ]);

        $this->assertFalse(Auth::check());
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['name' => 'Akun Anda tidak dapat digunakan.']);
    }
}