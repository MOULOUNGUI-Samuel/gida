<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SupportEntrepriseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur est de type support (type = 2)
        if (auth()->user()->type !== 2) {
            return redirect()->route('home')->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}