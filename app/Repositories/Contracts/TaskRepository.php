<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepository
{
    public function create(array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): void;

    public function findInProject(int $projectId, int $taskId): ?Task;

    public function paginateWithFilters(int $projectId, array $filters, int $perPage = 15): LengthAwarePaginator;
}
