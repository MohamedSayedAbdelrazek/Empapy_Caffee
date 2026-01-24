<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            abort(403, 'غير مصرح - يجب تسجيل الدخول');
        }

        // Check if user has the required permission
        if (!auth()->user()->hasPermission($permission)) {
            // If AJAX request, return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لهذا الإجراء'
                ], 403);
            }

            // Otherwise abort with 403
            abort(403, 'ليس لديك صلاحية لهذا الإجراء');
        }

        return $next($request);
    }
}
