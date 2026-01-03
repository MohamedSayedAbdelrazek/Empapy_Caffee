<?php

namespace App\Services;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyReward;
use App\Models\LoyaltyTier;
use App\Models\Order;
use App\Models\PointRule;
use App\Models\PointTransaction;
use App\Models\Referral;
use App\Models\RewardRedemption;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    // ========================================
    // POINT EARNING
    // ========================================

    /**
     * Award points to user based on order
     */
    public function processOrderPoints(Order $order): ?PointTransaction
    {
        $user = $order->user;
        if (!$user || !$user->loyaltyPoints) {
            return null;
        }

        $loyalty = $user->loyaltyPoints;

        // Get active order rules
        $rules = PointRule::active()
            ->forTrigger('order_complete')
            ->orderByDesc('priority')
            ->get();

        $totalPoints = 0;
        $descriptions = [];

        foreach ($rules as $rule) {
            if (!$rule->appliesTo($order->total)) {
                continue;
            }

            // Check if first order and this is first-order rule
            if ($rule->slug === 'first_order_bonus' && $user->orders()->where('status', 'delivered')->count() > 1) {
                continue;
            }

            $points = $rule->calculatePoints($order->total);

            // Apply max points cap if set
            if ($rule->max_points_per_order && $points > $rule->max_points_per_order) {
                $points = $rule->max_points_per_order;
            }

            // Apply tier multiplier
            $tier = LoyaltyTier::getTierForPoints($loyalty->tier_points);
            if ($tier && $tier->points_multiplier > 1) {
                $points = (int) round($points * $tier->points_multiplier);
            }

            $totalPoints += $points;
            $descriptions[] = $rule->name_ar;
        }

        if ($totalPoints <= 0) {
            return null;
        }

        // Add points
        $transaction = $loyalty->addPoints(
            $totalPoints,
            'order',
            "Points earned from order #{$order->id}",
            'نقاط مكتسبة من الطلب #' . $order->id,
            $order
        );

        // Check for tier upgrade
        $this->checkTierUpgrade($user);

        // Process referral if applicable
        $this->processReferralCompletion($user);

        return $transaction;
    }

    /**
     * Award signup bonus points
     */
    public function processSignupBonus(User $user): ?PointTransaction
    {
        $loyalty = $user->loyaltyPoints;
        if (!$loyalty) {
            return null;
        }

        $rule = PointRule::active()
            ->forTrigger('signup')
            ->first();

        if (!$rule) {
            return null;
        }

        $points = (int) $rule->value;

        return $loyalty->addPoints(
            $points,
            'signup',
            'Welcome bonus for signing up',
            'نقاط ترحيبية للتسجيل'
        );
    }

    /**
     * Award review points
     */
    public function processReviewPoints(User $user, $review): ?PointTransaction
    {
        $loyalty = $user->loyaltyPoints;
        if (!$loyalty) {
            return null;
        }

        $rule = PointRule::active()
            ->forTrigger('review')
            ->first();

        if (!$rule) {
            return null;
        }

        $points = (int) $rule->value;

        return $loyalty->addPoints(
            $points,
            'review',
            'Points for writing a product review',
            'نقاط لكتابة تقييم منتج',
            $review
        );
    }

    /**
     * Award birthday bonus
     */
    public function processBirthdayBonus(User $user): ?PointTransaction
    {
        $loyalty = $user->loyaltyPoints;
        if (!$loyalty || !$loyalty->birthday) {
            return null;
        }

        // Check if birthday is today
        if ($loyalty->birthday->format('m-d') !== now()->format('m-d')) {
            return null;
        }

        // Check if already claimed this year
        if ($loyalty->birthday_bonus_claimed) {
            return null;
        }

        $rule = PointRule::active()
            ->forTrigger('birthday')
            ->first();

        if (!$rule) {
            return null;
        }

        $points = (int) $rule->value;

        $loyalty->birthday_bonus_claimed = true;
        $loyalty->save();

        return $loyalty->addPoints(
            $points,
            'birthday',
            'Happy Birthday bonus!',
            'مكافأة عيد ميلاد سعيد! 🎂'
        );
    }

    // ========================================
    // POINT REDEMPTION
    // ========================================

    /**
     * Redeem a reward
     */
    public function redeemReward(User $user, LoyaltyReward $reward): array
    {
        $canRedeem = $reward->canBeRedeemedBy($user);

        if (!$canRedeem['can']) {
            return ['success' => false, 'message' => $canRedeem['reason']];
        }

        try {
            DB::beginTransaction();

            $loyalty = $user->loyaltyPoints;

            // Deduct points
            $loyalty->deductPoints(
                $reward->points_required,
                'reward',
                "Redeemed: {$reward->name}",
                "استبدال: {$reward->name_ar}",
                $reward
            );

            // Update reward stats
            $reward->increment('times_redeemed');
            if ($reward->stock !== null) {
                $reward->decrement('stock');
            }

            // Create redemption record
            $redemption = RewardRedemption::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'points_spent' => $reward->points_required,
                'redemption_code' => RewardRedemption::generateCode(),
                'expires_at' => now()->addDays(30),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'تم استبدال المكافأة بنجاح!',
                'redemption' => $redemption,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reward redemption failed: ' . $e->getMessage());

            return ['success' => false, 'message' => 'حدث خطأ، يرجى المحاولة لاحقاً'];
        }
    }

    /**
     * Apply redemption to order
     */
    public function applyRedemptionToOrder(RewardRedemption $redemption, Order $order): array
    {
        if (!$redemption->is_usable) {
            return ['success' => false, 'message' => 'هذه المكافأة غير صالحة للاستخدام'];
        }

        $reward = $redemption->reward;
        $discount = 0;
        $freeShipping = false;

        switch ($reward->reward_type) {
            case 'discount_fixed':
                $discount = min($reward->reward_value, $order->subtotal);
                break;
            case 'discount_percent':
                $discount = $order->subtotal * ($reward->reward_value / 100);
                break;
            case 'free_shipping':
                $freeShipping = true;
                break;
            case 'free_product':
                // Handle separately - add product to order
                break;
        }

        $redemption->applyToOrder($order);

        return [
            'success' => true,
            'discount' => $discount,
            'free_shipping' => $freeShipping,
        ];
    }

    // ========================================
    // TIER MANAGEMENT
    // ========================================

    /**
     * Check and update user tier
     */
    public function checkTierUpgrade(User $user): ?array
    {
        $loyalty = $user->loyaltyPoints;
        if (!$loyalty) {
            return null;
        }

        $oldTier = $loyalty->current_tier;
        $upgraded = $loyalty->updateTier();

        if ($upgraded) {
            $newTier = LoyaltyTier::where('slug', $loyalty->current_tier)->first();

            return [
                'upgraded' => true,
                'old_tier' => $oldTier,
                'new_tier' => $loyalty->current_tier,
                'tier' => $newTier,
            ];
        }

        return ['upgraded' => false];
    }

    // ========================================
    // REFERRAL SYSTEM
    // ========================================

    /**
     * Process referral signup
     */
    public function processReferralSignup(User $newUser, string $referralCode): ?Referral
    {
        // Find referrer by code
        $referrerLoyalty = LoyaltyPoint::where('referral_code', $referralCode)->first();

        if (!$referrerLoyalty) {
            return null;
        }

        // Can't refer yourself
        if ($referrerLoyalty->user_id === $newUser->id) {
            return null;
        }

        // Check if already referred
        if (Referral::where('referred_id', $newUser->id)->exists()) {
            return null;
        }

        // Create referral record
        $referral = Referral::create([
            'referrer_id' => $referrerLoyalty->user_id,
            'referred_id' => $newUser->id,
            'referral_code' => $referralCode,
            'status' => 'pending',
        ]);

        // Award referred user bonus
        $rule = PointRule::active()
            ->forTrigger('referral_signup')
            ->first();

        if ($rule && $newUser->loyaltyPoints) {
            $newUser->loyaltyPoints->addPoints(
                (int) $rule->value,
                'referral',
                'Bonus for signing up with referral',
                'مكافأة التسجيل بإحالة صديق',
                $referral
            );

            $referral->referred_points_awarded = (int) $rule->value;
            $referral->save();
        }

        return $referral;
    }

    /**
     * Process referral completion (when referred user makes first order)
     */
    public function processReferralCompletion(User $user): void
    {
        // Check if user was referred and referral is pending
        $referral = Referral::where('referred_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$referral) {
            return;
        }

        // Check if this is their first completed order
        if ($user->orders()->where('status', 'delivered')->count() !== 1) {
            return;
        }

        $referral->markCompleted();

        // Award referrer bonus
        $rule = PointRule::active()
            ->forTrigger('referral_made')
            ->first();

        if ($rule) {
            $referrer = $referral->referrer;
            if ($referrer && $referrer->loyaltyPoints) {
                $referrer->loyaltyPoints->addPoints(
                    (int) $rule->value,
                    'referral',
                    "Referral bonus - {$user->name} made first order",
                    "مكافأة إحالة - {$user->name} أكمل أول طلب",
                    $referral
                );

                $referral->markRewarded((int) $rule->value, $referral->referred_points_awarded);
            }
        }
    }

    // ========================================
    // ADMIN FUNCTIONS
    // ========================================

    /**
     * Manually adjust user points
     */
    public function adjustPoints(User $user, int $points, string $reason, string $reasonAr, ?User $admin = null): ?PointTransaction
    {
        $loyalty = $user->loyaltyPoints;
        if (!$loyalty) {
            return null;
        }

        if ($points > 0) {
            return $loyalty->addPoints($points, 'admin', $reason, $reasonAr, null, $admin?->id);
        } else {
            // For deductions, use absolute value
            return $loyalty->deductPoints(abs($points), 'admin', $reason, $reasonAr);
        }
    }

    // ========================================
    // STATISTICS
    // ========================================

    /**
     * Get loyalty statistics for admin dashboard
     */
    public function getStatistics(): array
    {
        $totalPoints = LoyaltyPoint::sum('total_earned');
        $totalRedeemed = LoyaltyPoint::sum('total_redeemed');
        $activeUsers = LoyaltyPoint::where('available_points', '>', 0)->count();

        $tierDistribution = LoyaltyPoint::select('current_tier', DB::raw('count(*) as count'))
            ->groupBy('current_tier')
            ->get()
            ->pluck('count', 'current_tier')
            ->toArray();

        $monthlyEarned = PointTransaction::where('type', 'earned')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('points');

        $monthlyRedeemed = PointTransaction::where('type', 'redeemed')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum(DB::raw('ABS(points)'));

        return [
            'total_points_issued' => $totalPoints,
            'total_points_redeemed' => $totalRedeemed,
            'points_in_circulation' => $totalPoints - $totalRedeemed,
            'active_users' => $activeUsers,
            'tier_distribution' => $tierDistribution,
            'monthly_earned' => $monthlyEarned,
            'monthly_redeemed' => $monthlyRedeemed,
        ];
    }
}
