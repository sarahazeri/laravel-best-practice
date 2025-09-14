<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    // use RefreshDatabase;

    public function test_registers_and_logs_in(): void
    {
        User::where('email', 'john@example.com')->delete();

        $resp = $this->postJson('/api/auth/register', [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password123',
        ])->assertStatus(201)->json('data');

        $this->assertNotEmpty($resp['token']);

        $this->postJson('/api/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ])->assertOk()->assertJsonStructure(['data' => ['token', 'user']]);
    }
}
