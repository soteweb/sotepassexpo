<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (! auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                abort(403, 'Acceso no autorizado.');
            }

            return redirect()->route('dashboard')->with('error', 'Acceso denegado: Esta sección es exclusiva para administradores de Soteweb.');
        }

        return $next($request);
    }
}
