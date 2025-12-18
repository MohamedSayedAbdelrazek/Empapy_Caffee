<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount('orders')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['orders' => function ($query) {
            $query->latest()->take(10);
        }]);

        $totalSpent = $user->orders()
            ->where('status', 'delivered')
            ->sum('total');

        return view('admin.users.show', compact('user', 'totalSpent'));
    }
}
