<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if (auth()->user()->role === 'admin') {
                return redirect('/admin');
            } elseif (auth()->user()->role === 'kasir') {
                return redirect('/kasir');
            }
        }

        return $next($request);
    }
}