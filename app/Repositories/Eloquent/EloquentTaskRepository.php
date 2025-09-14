<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTaskRepository implements TaskRepository
{
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function findInProject(int $projectId, int $taskId): ?Task
    {
        return Task::with(['assignee', 'comments'])->where('project_id', $projectId)->find($taskId);
    }

    public function paginateWithFilters(int $projectId, array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $q = Task::with(['assignee', 'comments'])->where('project_id', $projectId);
        if (! empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }
        if (! empty($filters['due_from'])) {
            $q->whereDate('due_date', '>=', $filters['due_from']);
        }
        if (! empty($filters['due_to'])) {
            $q->whereDate('due_date', '<=', $filters['due_to']);
        }
        if (! empty($filters['search'])) {
            $term = $filters['search'];
            $q->where(function ($qq) use ($term) {
                $qq->where('title', 'like', "%$term%")
                    ->orWhere('description', 'like', "%$term%");
            });
        }

        return $q->latest()->paginate($perPage);
    }
}
