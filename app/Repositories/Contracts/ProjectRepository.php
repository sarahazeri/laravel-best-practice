<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepository
{
    public function create(array $data): Project;

    public function update(Project $project, array $data): Project;

    public function delete(Project $project): void;

    public function findForUser(int $userId, int $id): ?Project;

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;
}
