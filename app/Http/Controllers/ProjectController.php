<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Contracts\ProjectRepository;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponse;

    public function __construct(private ProjectRepository $projects) {}

    public function index(Request $request)
    {
        return $this->success($this->projects->paginateForUser($request->user()->id));
    }

    public function store(ProjectRequest $request)
    {
        $project = $this->projects->create(['user_id' => $request->user()->id] + $request->validated());

        return $this->success($project, 201);
    }

    public function show(Request $request, int $project)
    {
        $p = $this->projects->findForUser($request->user()->id, $project);
        if (! $p) {
            return $this->error('Not found', 404);
        }

        return $this->success($p);
    }

    public function update(ProjectRequest $request, int $project)
    {
        $this->authorize('update', $project);
        $p = $this->projects->findForUser($request->user()->id, $project);
        if (! $p) {
            return $this->error('Not found', 404);
        }

        return $this->success($this->projects->update($p, $request->validated()));
    }

    public function destroy(Request $request, int $project)
    {
        $this->authorize('delete', $project);
        $p = $this->projects->findForUser($request->user()->id, $project);
        if (! $p) {
            return $this->error('Not found', 404);
        }
        $this->projects->delete($p);

        return $this->success(['deleted' => true]);
    }
}
