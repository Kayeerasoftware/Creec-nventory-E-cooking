<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect('/login');
        }

        $role = $user->role;

        // Admin has all permissions
        if ($role === 'admin') {
            return $next($request);
        }

        // Check specific permissions
        $permissions = [
            'technician' => ['update_technician'],
            'trainer' => ['update_trainer', 'update_technician_partial'],
        ];

        if (isset($permissions[$role]) && in_array($permission, $permissions[$role])) {
            return $next($request);
        }

        return response()->json(['error' => 'Forbidden - Insufficient permissions'], 403);
    }
}
