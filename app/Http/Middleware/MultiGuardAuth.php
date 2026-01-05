<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiGuardAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check() || Auth::guard('technician')->check() || Auth::guard('trainer')->check()) {
            return $next($request);
        }

        return redirect('/login');
    }
}
