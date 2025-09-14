<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepository;

class CommentController extends Controller
{
    use ApiResponse;

    public function __construct(private CommentRepository $comments) {}

    public function index(int $projectId, int $taskId)
    {
        return $this->success(Comment::with(['task'])->where('task_id', $taskId)->latest()->paginate(15));
    }

    public function store(CommentRequest $request, int $projectId, int $taskId)
    {
        $comment = $this->comments->create([
            'task_id' => $taskId,
            'user_id' => $request->user()->id,
            'body' => $request->validated('body'),
        ]);

        return $this->success($comment, 201);
    }

    public function update(CommentRequest $request, int $projectId, int $taskId, Comment $comment)
    {
        $this->authorize('update', $comment);

        return $this->success($this->comments->update($comment, $request->validated()));
    }

    public function destroy(int $projectId, int $taskId, Comment $comment)
    {

        $this->authorize('delete', $comment);
        $this->comments->delete($comment);

        return $this->success(['deleted' => true]);
    }
}
