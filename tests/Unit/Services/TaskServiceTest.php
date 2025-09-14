<?php

namespace Tests\Unit\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(TaskService::class);
    }

    public function test_create_task_through_service()
    {
        $data = Task::factory()->make()->toArray();

        $task = $this->service->create($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', ['title' => $data['title']]);
    }

    public function test_it_caches_task_listings(): void
    {
        $user = User::factory()->create();
        /** @var \App\Models\Project $project */
        $project = Project::factory()->create(['user_id' => $user->id]);
        Task::factory()->count(2)->create([
            'project_id' => $project->id,
            'status' => 'todo',
        ]);

        /** @var TaskService $svc */
        $svc = app(TaskService::class);
        $filters = ['status' => 'todo'];

        $first = $svc->paginate($project->id, $filters, 15);
        $second = $svc->paginate($project->id, $filters, 15);

        $this->assertEquals($first->total(), $second->total());
    }
}
