<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (auth()->check() && auth()->user()->role !== $role) {
            return redirect('/unauthorized'); // Atau gunakan response abort
        }

        return $next($request);
    }
}
