<?php

namespace Tests\Unit\Repositories;

use App\Models\Task;
use App\Repositories\Eloquent\EloquentTaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentTaskRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new EloquentTaskRepository;
    }

    public function test_it_creates_task()
    {
        $data = Task::factory()->make()->toArray();

        /** @var \App\Models\Task $task */
        $task = $this->repo->create($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', ['title' => $data['title']]);
    }

    public function test_it_updates_task()
    {
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create();

        $this->repo->update($task, ['status' => 'done']);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'done']);
    }

    public function test_it_deletes_task()
    {
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create();

        $this->repo->delete($task);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
