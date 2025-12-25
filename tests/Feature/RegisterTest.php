<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $response = $this->post(route('register'), [
            'name' => 'Andi Pratama',
            'email' => 'andi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'masyarakat'
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'andi@example.com',
            'role' => 'masyarakat'
        ]);
    }

    /** @test */
    public function registration_fails_when_required_fields_are_missing()
    {
        $response = $this->post(route('register'), []);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
            'role'
        ]);
    }

    /** @test */
    public function registration_fails_when_email_is_not_unique()
    {
        User::factory()->create([
            'email' => 'andi@example.com'
        ]);

        $response = $this->post(route('register'), [
            'name' => 'User Baru',
            'email' => 'andi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'masyarakat'
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function registration_fails_when_role_is_invalid()
    {
        $response = $this->post(route('register'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin'
        ]);

        $response->assertSessionHasErrors('role');
    }
}
