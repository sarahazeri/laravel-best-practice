<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('projects', ProjectController::class);

    Route::get('projects/{project}/tasks', [TaskController::class, 'index']);
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->middleware('throttle:60,1');
    Route::get('projects/{project}/tasks/{task}', [TaskController::class, 'show']);
    Route::put('projects/{project}/tasks/{task}', [TaskController::class, 'update'])->middleware('throttle:60,1');
    Route::delete('projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])->middleware('throttle:60,1');

    Route::get('projects/{project}/tasks/{task}/comments', [CommentController::class, 'index']);
    Route::post('projects/{project}/tasks/{task}/comments', [CommentController::class, 'store']);
    Route::put('projects/{project}/tasks/{task}/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('projects/{project}/tasks/{task}/comments/{comment}', [CommentController::class, 'destroy']);

});
