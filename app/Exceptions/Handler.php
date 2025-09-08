<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    protected $dontFlash = ['password', 'password_confirmation'];

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            if ($e instanceof HttpException && $e->getStatusCode() === 404) {
                return $this->error('Not Found', 404);
            }
            if (config('app.debug')) {
                return parent::render($request, $e);
            }

            return $this->error('Server Error', 500);
        }

        return parent::render($request, $e);
    }
}
