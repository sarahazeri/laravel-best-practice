<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment_to_task()
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
        $data = Comment::factory()->make()->toArray();

        /** @var \App\Models\User $user */
        $this->actingAs($user)
            ->postJson("/api/projects/{$p['id']}/tasks/{$t['id']}/comments", $data)
            ->assertStatus(201)
            ->assertJsonFragment(['body' => $data['body']]);
    }
}
