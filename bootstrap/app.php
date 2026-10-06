<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\MemberAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'AdminAuth' => AdminAuth::class,
            'MemberAuth' => MemberAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'The uploaded file is too large. Maximum allowed size is 200MB.'], 413);
            }

            return back()->withInput()->withErrors(['video_file' => 'The uploaded file exceeds the 200MB size limit. Please upload a smaller video or provide a YouTube link.']);
        });
    })->create();
