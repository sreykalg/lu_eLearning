<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@lifeun.edu.kh',
            'password' => 'Str0ng!Pass',
            'password_confirmation' => 'Str0ng!Pass',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'test@lifeun.edu.kh')->first();
        $this->assertNotNull($user);
        $this->assertSame('student', $user->role);
    }

    public function test_registration_always_assigns_student_even_if_role_is_sent(): void
    {
        $response = $this->post('/register', [
            'name' => 'Malicious User',
            'email' => 'malicious@lifeun.edu.kh',
            'password' => 'Str0ng!Pass',
            'password_confirmation' => 'Str0ng!Pass',
            'role' => 'instructor',
        ]);

        $this->assertAuthenticated();
        $user = User::where('email', 'malicious@lifeun.edu.kh')->first();
        $this->assertSame('student', $user->role);
    }
}
