<?php

namespace App\Http\Responses;

trait ApiResponse
{
    public function success(mixed $data = null, int $status = 200)
    {
        return response()->json(['status' => 'success', 'data' => $data], $status);
    }

    public function error(string $message, int $status = 400, array $errors = [])
    {
        return response()->json(['status' => 'error', 'message' => $message, 'errors' => $errors], $status);
    }
}
