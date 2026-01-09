<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Allows access for admin role only.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'غير مصرح لك بالوصول'], 403);
            }

            // If cashier, redirect to orders (their allowed area)
            if (auth()->check() && auth()->user()->isCashier()) {
                return redirect()->route('admin.orders.index')
                    ->with('error', 'ليس لديك صلاحية الوصول إلى هذه الصفحة');
            }

            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول كمدير للوصول');
        }

        return $next($request);
    }
}
