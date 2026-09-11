<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $flatRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $sub) {
                $flatRoles[] = trim($sub);
            }
        }

        if (!$request->user() || !in_array($request->user()->role, $flatRoles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
