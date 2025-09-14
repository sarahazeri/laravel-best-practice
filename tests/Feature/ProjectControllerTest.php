<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_project()
    {
        $user = User::factory()->create();
        $data = Project::factory()->make()->toArray();

        /** @var \App\Models\User $user */
        $this->actingAs($user)
            ->postJson('/api/projects', $data)
            ->assertStatus(201)
            ->assertJsonFragment(['name' => $data['name']]);
    }

    public function test_user_can_list_own_projects()
    {
        $user = User::factory()->create();
        Project::where('user_id', $user->id)->delete();
        Project::factory()->count(2)->create(['user_id' => $user->id]);

        /** @var \App\Models\User $user */
        $this->actingAs($user)
            ->getJson('/api/projects')
            ->assertStatus(200)
            ->assertJsonCount(2);
    }
}
