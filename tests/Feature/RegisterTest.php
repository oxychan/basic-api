<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    private const REGISTER_URL = 'api/register';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_requires_name_email_and_password()
    {
        $payload = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];

        $this->postJson(RegisterTest::REGISTER_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]);
    }

    public function test_name_must_be_a_string()
    {
        $payload = [
            'name' => 24,
            'email' => 'testing@mail.com',
            'password' => 'password',
        ];

        $this->postJson(RegisterTest::REGISTER_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                'name' => ['The name must be a string.'],
            ]);
    }

    public function test_password_confirmation_must_be_the_same()
    {
        $payload = [
            'name' => 'taufik',
            'email' => 'testing@mail.com',
            'password' => 'password',
            'password_confirmation' => 'wrongconfirmation',
        ];

        $this->postJson(RegisterTest::REGISTER_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                'password' => ['The password confirmation does not match.'],
            ]);
    }

    public function test_email_must_be_unique()
    {
        User::factory()->create([
            'email' => 'sameEmail@mail.com',
        ]);

        $payload = [
            'name' => 'taufik',
            'email' => 'sameEmail@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->postJson(RegisterTest::REGISTER_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                'email' => ['The email has already been taken.'],
            ]);
    }

    public function test_register_successfully()
    {
        $user = User::factory()->create();
        $payload = [
            'name' => $user['name'],
            'email' => 'unique@mail.com',
            'password' => $user['password'],
            'password_confirmation' => $user['password'],
        ];

        $this->postJson(RegisterTest::REGISTER_URL, $payload)
            ->assertStatus(201);
    }
}
