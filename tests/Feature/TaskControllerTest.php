<?php

namespace tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_tasks()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        /** @var \App\Models\Project $project */
        $project = Project::factory()->create(['user_id' => $user->id]);

        Task::factory()->count(3)->create(['assigned_to' => $user->id, 'project_id' => $project->id]);

        $this->actingAs($user)
            ->getJson("/api/projects/{$project['id']}/tasks")
            ->assertStatus(200)
            ->assertJsonCount(3, 'data.data');
    }

    public function test_user_can_create_task()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        /** @var \App\Models\Project $project */
        $project = Project::factory()->create(['user_id' => $user->id]);

        $data = Task::factory()->make()->toArray();

        $this->actingAs($user)
            ->postJson("/api/projects/{$project['id']}/tasks", $data)
            ->assertStatus(201)
            ->assertJsonFragment(['title' => $data['title']]);
    }

    public function test_unauthorized_user_cannot_delete_task()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        /** @var \App\Models\Project $project */
        $project = Project::factory()->create(['user_id' => $user->id]);

        $task = Task::factory()->create(['assigned_to' => $user->id, 'project_id' => $project->id]);

        $this->deleteJson("/api/projects/{$project['id']}/tasks/{$task['id']}")
            ->assertStatus(401);
    }

    public function test_user_cannot_update_task_of_another_user()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        /** @var \App\Models\Project $project */
        $project = Project::factory()->create(['user_id' => $user->id]);

        $task = Task::factory()->create(['assigned_to' => $user->id, 'project_id' => $project->id]);

        /** @var \App\Models\User $otherUser */
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->putJson("/api/projects/{$project['id']}/tasks/{$task['id']}", ['title' => 'Hack'])
            ->assertStatus(403);
    }

    public function test_task_not_found_returns_404()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        /** @var \App\Models\Project $project */
        $project = Project::factory()->create(['user_id' => $user->id]);
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create(['assigned_to' => $user->id, 'project_id' => $project->id]);
        $deletedTaskId = $task->id;
        $task->delete();
        $this->actingAs($user)
            ->getJson("/api/projects/{$project->id}/tasks/{$deletedTaskId}")
            ->assertStatus(404);
    }

    public function test_validation_error_returns_422()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        /** @var \App\Models\Project $project */
        $project = Project::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->postJson("/api/projects/{$project['id']}/tasks", [])
            ->assertStatus(422);
    }
}
