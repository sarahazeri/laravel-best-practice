<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Policies\CommentPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [Comment::class => CommentPolicy::class,
        Task::class => TaskPolicy::class,
        Project::class => ProjectPolicy::class, ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
