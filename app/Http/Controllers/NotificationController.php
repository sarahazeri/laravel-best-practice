<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;

    public function unseen(Request $request)
    {
        $list = $request->user()->unreadNotifications()->latest()->paginate(20);

        return $this->success($list);
    }

    public function markRead(Request $request, string $id)
    {
        $n = $request->user()->notifications()->where('id', $id)->first();
        if (! $n) {
            return $this->error('Not found', 404);
        }
        $n->markAsRead();

        return $this->success(['read' => true]);
    }
}
