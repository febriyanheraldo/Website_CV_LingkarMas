<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\Role::class,
        ]);

        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Semua request /api selalu dijawab JSON
        $exceptions->shouldRenderJsonWhen(fn (Request $request, Throwable $e) => $request->is('api/*') || $request->expectsJson());

        // 422: message = pesan error pertama (contoh: "Stok tidak cukup")
        $exceptions->render(function (ValidationException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'message' => collect($e->errors())->flatten()->first() ?? 'Data tidak valid',
                'errors'  => $e->errors(),
            ], 422);
        });

        // 401: belum login / token tidak valid
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'message' => 'Sesi berakhir, silakan login kembali',
                'errors'  => (object) [],
            ], 401);
        });

        // 401 (HttpException), 403, 404, 405, 429, dan lainnya
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $status = $e->getStatusCode();
            $default = [
                403 => 'Anda tidak memiliki akses',
                404 => 'Data tidak ditemukan',
                405 => 'Metode tidak diizinkan',
                429 => 'Terlalu banyak percobaan, coba lagi beberapa saat lagi',
            ][$status] ?? 'Terjadi kesalahan';

            $pesan = $e->getMessage();
            $pakaiDefault = in_array($status, [404, 405, 429], true)
                || $pesan === ''
                || $pesan === 'This action is unauthorized.';

            return response()->json([
                'message' => $pakaiDefault ? $default : $pesan,
                'errors'  => (object) [],
            ], $status, $e->getHeaders());
        });
    })->create();
