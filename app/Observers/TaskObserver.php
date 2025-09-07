<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskUpdatedNotification;

class TaskObserver
{
    public function created(Task $task): void
    {
        if ($task->assigned_to && $task->assignee) {
            $task->assignee->notify(new TaskUpdatedNotification($task, 'assigned'));
        }
    }

    public function updated(Task $task): void
    {
        if ($task->wasChanged(['status', 'title', 'description', 'due_date', 'assigned_to'])) {
            if ($task->assigned_to && $task->assignee) {
                $task->assignee->notify(new TaskUpdatedNotification($task, 'updated'));
            }
        }
    }
}
