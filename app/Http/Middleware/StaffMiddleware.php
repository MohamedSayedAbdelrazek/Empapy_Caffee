<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     * Allows access for admin and cashier roles.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isStaff()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'غير مصرح لك بالوصول'], 403);
            }

            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول كموظف للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
