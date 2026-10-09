<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak. Tindakan ini hanya dapat dilakukan oleh Administrator.'], 403);
            }
            return redirect()->route('inventaris.index')->with('error', 'Akses ditolak. Fitur ini hanya untuk peran Administrator.');
        }

        return $next($request);
    }
}
