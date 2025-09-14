<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read User|null $user
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'body'];

    /**
     * Get the task associated with the comment.
     *
     *  @property-read Task|null $task
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
