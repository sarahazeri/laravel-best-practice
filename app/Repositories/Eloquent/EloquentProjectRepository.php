<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentProjectRepository implements ProjectRepository
{
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(Project $project, array $data): Project
    {
        $project->update($data);

        return $project;
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    public function findForUser(int $userId, int $id): ?Project
    {
        return Project::where('user_id', $userId)->find($id);
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Project::where('user_id', $userId)->latest()->paginate($perPage);
    }
}
