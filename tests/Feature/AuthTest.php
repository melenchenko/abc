<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class AuthTest extends TestCase
{
    use WithFaker;

    public function test_auth_no_parameters()
    {
        $response = $this->post('/api/user/auth');

        $response->assertStatus(422);
    }

    public function test_auth_incorrect_credentials()
    {
        $email = $this->faker->unique()->safeEmail();

        $response = $this->post('/api/user/auth', [
            'email' => $email,
            'password' => '12345678',
        ]);

        $response->assertStatus(401);
    }

    public function test_auth_correct()
    {
        $email = $this->faker->unique()->safeEmail();
        $password = '12345678';

        $this->post('/api/user/register', [
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ]);

        $response = $this->post('/api/user/auth', [
            'email' => $email,
            'password' => $password,
        ]);

        User::where('email', $email)->delete();

        $response->assertStatus(200);
    }
}
