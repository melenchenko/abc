<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class RegisterTest extends TestCase
{
    use WithFaker;

    public function test_register_no_parameters()
    {
        $response = $this->post('/api/user/register');

        $response->assertStatus(422);
    }

    public function test_register_incorrect_email()
    {
        $response = $this->post('/api/user/register', [
            'email' => 'incorrect',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422);
    }

    public function test_register_short_password()
    {
        $response = $this->post('/api/user/register', [
            'email' => 'mail@example.com',
            'password' => '1',
            'password_confirmation' => '1'
        ]);

        $response->assertStatus(422);
    }

    public function test_register_wrong_confirmation()
    {
        $response = $this->post('/api/user/register', [
            'email' => 'mail@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345677'
        ]);

        $response->assertStatus(422);
    }

    public function test_register_not_unique_email()
    {
        $email = User::all()->first();

        $response = $this->post('/api/user/register', [
            'email' => $email,
            'password' => '12345678',
            'password_confirmation' => '12345679'
        ]);

        $response->assertStatus(422);
    }

    public function test_register_correct()
    {
        $email = $this->faker->unique()->safeEmail();

        //todo сделать отдельный конфиг с отдельной базой для юнит-тестов, а не работать на основной
        $response = $this->post('/api/user/register', [
            'email' => $email,
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        User::where('email', $email)->delete();

        $response->assertStatus(201);
    }


}
