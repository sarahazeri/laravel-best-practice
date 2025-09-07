<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepository;

class EloquentCommentRepository implements CommentRepository
{
    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);

        return $comment;
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
