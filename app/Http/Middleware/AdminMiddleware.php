<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté ET si son rôle est 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // L'utilisateur est admin, on continue
        }

        // Si l'utilisateur n'est pas connecté ou n'est pas admin, on le redirige
        return redirect('/')->with('error', 'Accès non autorisé.');
        // Ou si vous préférez une erreur 403 : abort(403, 'Accès non autorisé.');
    }
}
