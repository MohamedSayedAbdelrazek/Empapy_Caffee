<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyReward;
use App\Models\LoyaltyTier;
use App\Models\RewardRedemption;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    protected LoyaltyService $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * My Points Dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $loyalty = $user->loyaltyPoints;

        // Auto-create loyalty record if not exists
        if (!$loyalty) {
            $loyalty = $user->loyaltyPoints()->create([
                'referral_code' => \App\Models\LoyaltyPoint::generateReferralCode($user),
                'current_tier' => 'bronze',
            ]);
        }

        // Get current tier info
        $currentTier = LoyaltyTier::where('slug', $loyalty->current_tier)->first();
        $nextTier = $currentTier?->getNextTier();

        // Get all tiers for progress visualization
        $allTiers = LoyaltyTier::active()->ordered()->get();

        // Recent transactions
        $transactions = $user->pointTransactions()
            ->latest()
            ->take(10)
            ->get();

        // Pending redemptions
        $pendingRedemptions = $user->getPendingRedemptions();

        // Featured rewards
        $featuredRewards = LoyaltyReward::active()
            ->featured()
            ->inStock()
            ->orderBy('points_required')
            ->take(3)
            ->get();

        // Stats
        $stats = [
            'total_earned' => $loyalty->total_earned,
            'total_redeemed' => $loyalty->total_redeemed,
            'referrals' => $user->successful_referrals_count,
        ];

        // Get point rules for dynamic display
        $pointRules = [
            'order' => \App\Models\PointRule::active()->forTrigger('order_complete')->first(),
            'review' => \App\Models\PointRule::active()->forTrigger('review')->first(),
            'referral' => \App\Models\PointRule::active()->forTrigger('referral_made')->first(),
        ];

        return view('loyalty.index', compact(
            'loyalty',
            'currentTier',
            'nextTier',
            'allTiers',
            'transactions',
            'pendingRedemptions',
            'featuredRewards',
            'stats',
            'pointRules'
        ));
    }

    /**
     * Rewards Catalog
     */
    public function rewards(Request $request)
    {
        $user = auth()->user();
        $userPoints = $user->points;

        $query = LoyaltyReward::active()->inStock();

        // Filter by affordability
        if ($request->boolean('affordable')) {
            $query->affordable($userPoints);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('reward_type', $request->type);
        }

        // Sort
        $sort = $request->get('sort', 'points_asc');
        switch ($sort) {
            case 'points_asc':
                $query->orderBy('points_required');
                break;
            case 'points_desc':
                $query->orderByDesc('points_required');
                break;
            case 'popular':
                $query->orderByDesc('times_redeemed');
                break;
        }

        $rewards = $query->get();

        // Mark which rewards user can afford
        $rewards->each(function ($reward) use ($user) {
            $reward->user_can_redeem = $reward->canBeRedeemedBy($user);
        });

        $tiers = LoyaltyTier::active()->ordered()->get();

        return view('loyalty.rewards', compact('rewards', 'userPoints', 'tiers'));
    }

    /**
     * Redeem a reward
     */
    public function redeem(LoyaltyReward $reward)
    {
        $user = auth()->user();
        $result = $this->loyaltyService->redeemReward($user, $reward);

        if (request()->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->route('loyalty.index')
                ->with('success', $result['message'])
                ->with('redemption_code', $result['redemption']->redemption_code);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * My Redemptions
     */
    public function redemptions()
    {
        $user = auth()->user();

        $redemptions = $user->rewardRedemptions()
            ->with('reward', 'order')
            ->latest()
            ->paginate(20);

        return view('loyalty.redemptions', compact('redemptions'));
    }

    /**
     * Referral Page
     */
    public function referral()
    {
        $user = auth()->user();
        $loyalty = $user->loyaltyPoints;

        $referralLink = $user->referral_link;
        $referralCode = $user->referral_code;

        // Get referral statistics
        $totalReferrals = $user->referralsMade()->count();
        $completedReferrals = $user->referralsMade()->completed()->count();
        $pendingReferrals = $user->referralsMade()->pending()->count();
        $totalPointsEarned = $user->referralsMade()->sum('referrer_points_awarded');

        // Get referral history
        $referrals = $user->referralsMade()
            ->with('referred')
            ->latest()
            ->take(10)
            ->get();

        // Get referral rules
        $referrerRule = \App\Models\PointRule::active()
            ->forTrigger('referral_made')
            ->first();
        $referredRule = \App\Models\PointRule::active()
            ->forTrigger('referral_signup')
            ->first();

        return view('loyalty.referral', compact(
            'referralLink',
            'referralCode',
            'totalReferrals',
            'completedReferrals',
            'pendingReferrals',
            'totalPointsEarned',
            'referrals',
            'referrerRule',
            'referredRule'
        ));
    }

    /**
     * Transaction History
     */
    public function history(Request $request)
    {
        $user = auth()->user();

        $query = $user->pointTransactions()->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(20);

        return view('loyalty.history', compact('transactions'));
    }
}
