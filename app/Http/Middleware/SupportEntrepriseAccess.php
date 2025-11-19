<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SupportEntrepriseAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || (string)auth()->user()->type !== '2') {
            return redirect()->route('login')
                ->with('error', 'Accès réservé aux entreprises support.');
        }

        return $next($request);
    }
}