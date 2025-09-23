<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role->name !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
