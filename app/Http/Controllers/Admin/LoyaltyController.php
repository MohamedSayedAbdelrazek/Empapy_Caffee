<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyReward;
use App\Models\PointRule;
use App\Models\PointTransaction;
use App\Models\Referral;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoyaltyController extends Controller
{
    protected LoyaltyService $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Loyalty Dashboard
     */
    public function index()
    {
        $stats = $this->loyaltyService->getStatistics();

        // Recent transactions
        $recentTransactions = PointTransaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Top earners this month
        $topEarners = LoyaltyPoint::with('user')
            ->orderByDesc('total_earned')
            ->take(5)
            ->get();

        // Recent referrals
        $recentReferrals = Referral::with(['referrer', 'referred'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.loyalty.dashboard', compact(
            'stats',
            'recentTransactions',
            'topEarners',
            'recentReferrals'
        ));
    }

    // ========================================
    // POINT RULES MANAGEMENT
    // ========================================

    /**
     * List all point rules
     */
    public function rules()
    {
        $rules = PointRule::orderBy('trigger')->orderByDesc('priority')->get();
        return view('admin.loyalty.rules.index', compact('rules'));
    }

    /**
     * Show create rule form
     */
    public function createRule()
    {
        return view('admin.loyalty.rules.create');
    }

    /**
     * Store new rule
     */
    public function storeRule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,per_currency,percentage',
            'value' => 'required|numeric|min:0',
            'trigger' => 'required|string|max:50',
            'is_first_order_only' => 'boolean',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_points_per_order' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'priority' => 'integer|min:0',
        ]);

        // Auto-generate slug
        $validated['slug'] = Str::slug($request->trigger . '_' . now()->timestamp);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_first_order_only'] = $request->boolean('is_first_order_only');

        PointRule::create($validated);

        return redirect()->route('admin.loyalty.rules')
            ->with('success', 'تم إنشاء قاعدة النقاط بنجاح');
    }

    /**
     * Show edit rule form
     */
    public function editRule(PointRule $rule)
    {
        return view('admin.loyalty.rules.edit', compact('rule'));
    }

    /**
     * Update rule
     */
    public function updateRule(Request $request, PointRule $rule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,per_currency,percentage',
            'value' => 'required|numeric|min:0',
            'trigger' => 'required|string|max:50',
            'is_first_order_only' => 'boolean',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_points_per_order' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'priority' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_first_order_only'] = $request->boolean('is_first_order_only');

        $rule->update($validated);

        return redirect()->route('admin.loyalty.rules')
            ->with('success', 'تم تحديث قاعدة النقاط بنجاح');
    }

    /**
     * Delete rule
     */
    public function destroyRule(PointRule $rule)
    {
        $rule->delete();

        return redirect()->route('admin.loyalty.rules')
            ->with('success', 'تم حذف قاعدة النقاط');
    }

    // ========================================
    // REWARDS MANAGEMENT
    // ========================================

    /**
     * List all rewards
     */
    public function rewards()
    {
        $rewards = LoyaltyReward::with('product')
            ->orderBy('sort_order')
            ->get();

        return view('admin.loyalty.rewards.index', compact('rewards'));
    }

    /**
     * Show create reward form
     */
    public function createReward()
    {
        $products = \App\Models\Product::active()->get();

        return view('admin.loyalty.rewards.create', compact('products'));
    }

    /**
     * Store new reward
     */
    public function storeReward(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'points_required' => 'required|integer|min:1',
            'reward_type' => 'required|in:discount_fixed,discount_percent,free_shipping,free_product',
            'reward_value' => 'nullable|numeric|min:0',
            'product_id' => 'nullable|exists:products,id',
            'image' => 'nullable|image|max:2048',
            'icon' => 'string|max:10',
            'stock' => 'nullable|integer|min:0',
            'max_per_user' => 'nullable|integer|min:1',
            'tier_required' => 'nullable|exists:loyalty_tiers,slug',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rewards', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        LoyaltyReward::create($validated);

        return redirect()->route('admin.loyalty.rewards')
            ->with('success', 'تم إنشاء المكافأة بنجاح');
    }

    /**
     * Show edit reward form
     */
    public function editReward(LoyaltyReward $reward)
    {
        $products = \App\Models\Product::active()->get();

        return view('admin.loyalty.rewards.edit', compact('reward', 'products'));
    }

    /**
     * Update reward
     */
    public function updateReward(Request $request, LoyaltyReward $reward)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'points_required' => 'required|integer|min:1',
            'reward_type' => 'required|in:discount_fixed,discount_percent,free_shipping,free_product',
            'reward_value' => 'nullable|numeric|min:0',
            'product_id' => 'nullable|exists:products,id',
            'image' => 'nullable|image|max:2048',
            'icon' => 'string|max:10',
            'stock' => 'nullable|integer|min:0',
            'max_per_user' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rewards', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $reward->update($validated);

        return redirect()->route('admin.loyalty.rewards')
            ->with('success', 'تم تحديث المكافأة بنجاح');
    }

    /**
     * Delete reward
     */
    public function destroyReward(LoyaltyReward $reward)
    {
        $reward->delete();

        return redirect()->route('admin.loyalty.rewards')
            ->with('success', 'تم حذف المكافأة');
    }

    // ========================================
    // USER POINTS MANAGEMENT
    // ========================================

    /**
     * List users with their points
     */
    public function users(Request $request)
    {
        $query = LoyaltyPoint::with('user', 'tier');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by tier
        // Sort
        $sortBy = $request->get('sort', 'available_points');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $users = $query->paginate(20);

        return view('admin.loyalty.users.index', compact('users'));
    }

    /**
     * Show user points detail
     */
    public function showUser(\App\Models\User $user)
    {
        $loyalty = $user->loyaltyPoints;
        $transactions = $user->pointTransactions()
            ->latest()
            ->paginate(20);

        $redemptions = $user->rewardRedemptions()
            ->with('reward')
            ->latest()
            ->take(10)
            ->get();

        $referrals = $user->referralsMade()
            ->with('referred')
            ->latest()
            ->get();

        return view('admin.loyalty.users.show', compact(
            'user',
            'loyalty',
            'transactions',
            'redemptions',
            'referrals'
        ));
    }

    /**
     * Adjust user points
     */
    public function adjustPoints(Request $request, \App\Models\User $user)
    {
        $validated = $request->validate([
            'points' => 'required|integer|not_in:0',
            'reason' => 'required|string|max:255',
        ]);
        $transaction = $this->loyaltyService->adjustPoints(
            $user,
            $validated['points'],
            $validated['reason'],
            $validated['reason'],
            auth()->user()
        );

        if ($transaction) {
            return back()->with('success', 'تم تعديل النقاط بنجاح');
        }

        return back()->with('error', 'فشل تعديل النقاط');
    }

    // ========================================
    // TRANSACTIONS HISTORY
    // ========================================

    /**
     * List all transactions
     */
    public function transactions(Request $request)
    {
        $query = PointTransaction::with('user');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->paginate(50);

        return view('admin.loyalty.transactions', compact('transactions'));
    }
}
