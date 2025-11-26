<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // デバッグログ（必要なら残す）
        Log::info('ADMIN_CHECK', [
            'user' => $request->user(),
            'role' => $request->user()?->role,
        ]);

        $user = $request->user();

        if (! $user || $user->role !== 'admin') {
            abort(403, 'This action is unauthorized.');
        }

        return $next($request);
    }
}

