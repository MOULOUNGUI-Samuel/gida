<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si l'utilisateur est sur la page d'accueil, le rediriger selon son type
            if ($request->is('/')) {
                if ($user->type == 0) { // Admin
                    return redirect()->route('dashboard');
                } else { // Employé
                    return redirect()->route('dashboardEmployer');
                }
            }
        }

        return $next($request);
    }
}
