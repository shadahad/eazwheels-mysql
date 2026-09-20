<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminKeyIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredKey = config('app.admin_api_key', env('ADMIN_API_KEY'));
        
        // Accept via X-Admin-Key header, query param, or Authorization Bearer
        $providedKey = $request->header('X-Admin-Key') 
            ?? $request->bearerToken() 
            ?? $request->input('admin_key');

        if (!$configuredKey || !$providedKey || !hash_equals((string) $configuredKey, (string) $providedKey)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Invalid administrative credentials.'
                ], 401);
            }
            return response()->view('errors.401-admin', [], 401);
        }

        return $next($request);
    }
}