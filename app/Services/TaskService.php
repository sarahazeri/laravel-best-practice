<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepository;
use App\Services\Filters\DueDateFromFilter;
use App\Services\Filters\DueDateToFilter;
use App\Services\Filters\SearchFilter;
use App\Services\Filters\StatusFilter;
use App\Services\Filters\TaskFilterStrategy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    public function __construct(private TaskRepository $tasks) {}

    public function paginate(int $projectId, array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $cacheKey = $this->cacheKey($projectId, $filters, $perPage, request('page', 1));

        return Cache::tags(['tasks', "project:$projectId"])
            ->remember($cacheKey, 300, fn () => $this->tasks->paginateWithFilters($projectId, $filters, $perPage));
    }

    public function create(array $data): Task
    {
        $task = $this->tasks->create($data);
        Cache::tags(['tasks', "project:{$task->project_id}"])->flush();

        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $task = $this->tasks->update($task, $data);
        Cache::tags(['tasks', "project:{$task->project_id}"])->flush();

        return $task;
    }

    public function delete(Task $task): void
    {
        $projectId = $task->project_id;
        $this->tasks->delete($task);
        Cache::tags(['tasks', "project:$projectId"])->flush();
    }

    private function cacheKey(int $projectId, array $filters, int $perPage, int $page): string
    {
        return "tasks:p:$projectId:f:".md5(json_encode($filters)).":pp:$perPage:pg:$page";
    }

    /** @return TaskFilterStrategy[] */
    public static function strategies(): array
    {
        return [
            'status' => new StatusFilter,
            'due_from' => new DueDateFromFilter,
            'due_to' => new DueDateToFilter,
            'search' => new SearchFilter,
        ];
    }

    public static function applyStrategies(Builder $q, array $filters): Builder
    {
        foreach (self::strategies() as $key => $strategy) {
            $q = $strategy->apply($q, $filters[$key] ?? null);
        }

        return $q;
    }
}
