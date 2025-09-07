<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Contracts\TaskRepository;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponse;

    public function __construct(private TaskRepository $tasks, private TaskService $service) {}

    public function index(Request $request, int $projectId)
    {
        $filters = $request->only(['status', 'due_from', 'due_to', 'search']);
        $perPage = (int) ($request->get('per_page', 15));

        return $this->success($this->service->paginate($projectId, $filters, $perPage));
    }

    public function store(TaskRequest $request, int $projectId)
    {
        $task = $this->service->create(['project_id' => $projectId] + $request->validated());

        return $this->success($task, 201);
    }

    public function show(int $projectId, int $taskId)
    {
        $task = $this->tasks->findInProject($projectId, $taskId);
        if (! $task) {
            return $this->error('Not found', 404);
        }

        return $this->success($task);
    }

    public function update(TaskRequest $request, int $projectId, int $taskId)
    {
        $task = $this->tasks->findInProject($projectId, $taskId);
        if (! $task) {
            return $this->error('Not found', 404);
        }
        $task = $this->service->update($task, $request->validated());

        return $this->success($task);
    }

    public function destroy(int $projectId, int $taskId)
    {
        $task = $this->tasks->findInProject($projectId, $taskId);
        if (! $task) {
            return $this->error('Not found', 404);
        }
        $this->service->delete($task);

        return $this->success(['deleted' => true]);
    }
}
