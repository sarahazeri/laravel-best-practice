<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['todo', 'in-progress', 'done']),
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'assigned_to' => $this->faker->optional()->randomElement([User::factory()]),
        ];
    }
}
