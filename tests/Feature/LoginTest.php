<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private const LOGIN_URI = 'api/login';
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // email dan password tidak boleh kosong
    public function test_requires_email_and_password()
    {
        $this->postJson(self::LOGIN_URI, [
            'email' => '',
            'password' => '',
        ])
            ->assertStatus(422)
            ->assertJsonFragment([
                "email" => ["The email field is required."], 
                "password" => ["The password field is required."], 
            ]);
    }

    // email harus sesuai dengan tipe email
    public function test_email_must_match_the_type()
    {
        $this->postJson(self::LOGIN_URI, [
            'email' => 'wrongemailtype',
            'password' => bcrypt('password'),
        ])
            ->assertStatus(422)
            ->assertJsonFragment([
                "email" => ['The email must be a valid email address.'],
            ]);
    }

    // login berhasil
    public function test_login_successfully()
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user['email'],
            'password' => 'password',
        ];

        $this->postJson(self::LOGIN_URI, $payload)
            ->assertStatus(200);
    }
}
