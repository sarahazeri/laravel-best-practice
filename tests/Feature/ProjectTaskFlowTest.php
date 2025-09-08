<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTaskFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_project_and_task_and_checks_notifications(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('t')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $p = $this->postJson('/api/projects', [
            'name' => 'Demo',
            'description' => 'D',
        ], $headers)
            ->assertStatus(201)
            ->json('data');

        $t = $this->postJson("/api/projects/{$p['id']}/tasks", [
            'title' => 'First Task',
            'status' => 'todo',
            'assigned_to' => $user->id,
        ], $headers)
            ->assertStatus(201)
            ->json('data');

        $this->putJson("/api/projects/{$p['id']}/tasks/{$t['id']}", [
            'title' => $t['title'],
            'status' => 'in-progress',
        ], $headers)
            ->assertOk();

        $n = $this->getJson('/api/notifications/unseen', $headers)
            ->assertOk()
            ->json('data.data');

        $this->assertIsArray($n);
        $this->assertNotEmpty($n);
    }
}
