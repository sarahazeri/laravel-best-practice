<?php

namespace App\Providers;

use App\Repositories\Contracts\CommentRepository;
use App\Repositories\Contracts\ProjectRepository;
use App\Repositories\Contracts\TaskRepository;
use App\Repositories\Eloquent\EloquentCommentRepository;
use App\Repositories\Eloquent\EloquentProjectRepository;
use App\Repositories\Eloquent\EloquentTaskRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProjectRepository::class, EloquentProjectRepository::class);
        $this->app->bind(TaskRepository::class, EloquentTaskRepository::class);
        $this->app->bind(CommentRepository::class, EloquentCommentRepository::class);
    }
}
