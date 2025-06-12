<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // For API requests (based on route or headers)
        if ($request->expectsJson() || $request->is('api/*')) {

            // Handle auth errors (wrong token, missing token)
            if ($exception instanceof AuthenticationException) {
                return response()->json(['message' => 'Invalid or missing token.'], 401);
            }

            // Handle validation errors
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $exception->errors(),
                ], 422);
            }

            // Handle all other exceptions with proper status
            $status = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;

            return response()->json([
                'message' => $exception->getMessage() ?: 'Server Error',
            ], $status);
        }

        // Non-API (web) request
        return parent::render($request, $exception);
    }
}
