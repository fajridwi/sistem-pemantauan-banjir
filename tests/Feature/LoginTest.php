<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'warga@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'warga@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect();
    }

    #[Test]
    public function login_fails_with_invalid_password()
    {
        User::factory()->create([
            'email' => 'warga@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'warga@example.com',
            'password' => 'salahpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function login_fails_with_unregistered_email()
    {
        $response = $this->post(route('login'), [
            'email' => 'tidakada@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function logged_in_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
    }
}
