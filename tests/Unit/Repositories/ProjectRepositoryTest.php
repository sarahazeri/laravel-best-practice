<?php

namespace Tests\Unit\Repositories;

use App\Models\Project;
use App\Repositories\Eloquent\EloquentProjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\TestCase;

class ProjectRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentProjectRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new EloquentProjectRepository;
    }

    public function test_it_creates_project()
    {
        $data = Project::factory()->make()->toArray();

        /** @var \App\Models\Project $project */
        $project = $this->repo->create($data);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertDatabaseHas('projects', ['name' => $data['name']]);
    }

    public function test_it_updates_project()
    {
        /** @var \App\Models\Project $project */
        $project = Project::factory()->create();

        $this->repo->update($project, ['name' => 'Updated Title']);

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Updated Title']);
    }

    public function test_it_deletes_project()
    {

        /** @var \App\Models\Project $project */
        $project = Project::factory()->create();

        $this->repo->delete($project);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
