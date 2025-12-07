<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStaff
{
    public function handle(Request $request, Closure $next)
    {
        if (! in_array(Auth::user()?->role, ['admin', 'manager'])) {
            abort(403, 'Доступ запрещён');
        }

        return $next($request);
    }
}
