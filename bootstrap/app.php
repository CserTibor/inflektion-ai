<?php

use App\Http\Middleware\AuthenticationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(
            [
                'authentication' => AuthenticationMiddleware::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(
            function (Throwable $exception) {

                if (!request()->expectsJson()) {
                    return null;
                }

                if ($exception instanceof ValidationException) {
                    return response()->json(
                        [
                            'message' => $exception->getMessage(),
                            'trace' => $exception->getTrace(),
                        ],
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                    );
                }

                return response()->json(
                    [
                        'message' => $exception->getMessage(),
                        'trace' => $exception->getTrace(),
                    ],
                    is_int($exception->getCode()) && $exception->getCode() !== 0
                        ? $exception->getCode()
                        : Response::HTTP_INTERNAL_SERVER_ERROR,
                );
            }
        );
    })->create();

