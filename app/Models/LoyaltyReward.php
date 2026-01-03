<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'points_required',
        'reward_type',
        'reward_value',
        'product_id',
        'image',
        'icon',
        'stock',
        'times_redeemed',
        'max_per_user',
        'tier_required',
        'is_active',
        'is_featured',
        'sort_order',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'available_from' => 'date',
        'available_until' => 'date',
        'reward_value' => 'decimal:2',
    ];

    /**
     * The product given as reward (if any)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Required tier to redeem (if any)
     */
    public function requiredTier(): BelongsTo
    {
        return $this->belongsTo(LoyaltyTier::class, 'tier_required', 'slug');
    }

    /**
     * Redemptions of this reward
     */
    public function redemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class, 'reward_id');
    }

    /**
     * Scope for active rewards
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('available_from')
                    ->orWhere('available_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('available_until')
                    ->orWhere('available_until', '>=', now());
            });
    }

    /**
     * Scope for featured rewards
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for available stock
     */
    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('stock')
                ->orWhere('stock', '>', 0);
        });
    }

    /**
     * Scope for rewards user can afford
     */
    public function scopeAffordable($query, int $userPoints)
    {
        return $query->where('points_required', '<=', $userPoints);
    }

    /**
     * Check if user can redeem this reward
     */
    public function canBeRedeemedBy(User $user): array
    {
        $loyalty = $user->loyaltyPoints;

        if (!$loyalty || $loyalty->available_points < $this->points_required) {
            return ['can' => false, 'reason' => 'نقاط غير كافية'];
        }

        if ($this->stock !== null && $this->stock <= 0) {
            return ['can' => false, 'reason' => 'نفدت الكمية'];
        }

        if ($this->tier_required) {
            $userTier = LoyaltyTier::where('slug', $loyalty->current_tier)->first();
            $requiredTier = LoyaltyTier::where('slug', $this->tier_required)->first();

            if (!$userTier || !$requiredTier || $userTier->sort_order < $requiredTier->sort_order) {
                return ['can' => false, 'reason' => "يتطلب مستوى {$requiredTier->name_ar}"];
            }
        }

        if ($this->max_per_user) {
            $userRedemptions = $this->redemptions()
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'applied'])
                ->count();

            if ($userRedemptions >= $this->max_per_user) {
                return ['can' => false, 'reason' => 'تم الوصول للحد الأقصى'];
            }
        }

        return ['can' => true, 'reason' => null];
    }

    /**
     * Get reward type label in Arabic
     */
    public function getRewardTypeLabelAttribute(): string
    {
        return match ($this->reward_type) {
            'discount_fixed' => 'خصم ثابت',
            'discount_percent' => 'خصم نسبة',
            'free_shipping' => 'شحن مجاني',
            'free_product' => 'منتج مجاني',
            default => $this->reward_type,
        };
    }

    /**
     * Get reward value display
     */
    public function getValueDisplayAttribute(): string
    {
        return match ($this->reward_type) {
            'discount_fixed' => number_format($this->reward_value) . ' ج.م خصم',
            'discount_percent' => $this->reward_value . '% خصم',
            'free_shipping' => 'شحن مجاني',
            'free_product' => $this->product?->name_ar ?? 'منتج مجاني',
            default => '',
        };
    }

    /**
     * Check if in stock
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->stock === null || $this->stock > 0;
    }

    /**
     * Check if available now
     */
    public function getIsAvailableAttribute(): bool
    {
        if (!$this->is_active) return false;

        if ($this->available_from && $this->available_from->isFuture()) return false;

        if ($this->available_until && $this->available_until->isPast()) return false;

        return $this->is_in_stock;
    }

    /**
     * Default rewards for seeding
     */
    public static function getDefaultRewards(): array
    {
        return [
            [
                'name' => '5 EGP Discount',
                'name_ar' => 'خصم 5 ج.م',
                'description' => 'Get 5 EGP off your next order',
                'description_ar' => 'احصل على خصم 5 ج.م على طلبك القادم',
                'points_required' => 100,
                'reward_type' => 'discount_fixed',
                'reward_value' => 5,
                'icon' => '🎫',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '30 EGP Discount',
                'name_ar' => 'خصم 30 ج.م',
                'description' => 'Get 30 EGP off your next order',
                'description_ar' => 'احصل على خصم 30 ج.م على طلبك القادم',
                'points_required' => 500,
                'reward_type' => 'discount_fixed',
                'reward_value' => 30,
                'icon' => '🎟️',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => '70 EGP Discount',
                'name_ar' => 'خصم 70 ج.م',
                'description' => 'Get 70 EGP off your next order',
                'description_ar' => 'احصل على خصم 70 ج.م على طلبك القادم',
                'points_required' => 1000,
                'reward_type' => 'discount_fixed',
                'reward_value' => 70,
                'icon' => '🏆',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Free Shipping',
                'name_ar' => 'شحن مجاني',
                'description' => 'Free shipping on your next order',
                'description_ar' => 'شحن مجاني على طلبك القادم',
                'points_required' => 300,
                'reward_type' => 'free_shipping',
                'icon' => '🚚',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => '15% Discount',
                'name_ar' => 'خصم 15%',
                'description' => 'Get 15% off your next order',
                'description_ar' => 'احصل على خصم 15% على طلبك القادم',
                'points_required' => 800,
                'reward_type' => 'discount_percent',
                'reward_value' => 15,
                'icon' => '💫',
                'is_active' => true,
                'tier_required' => 'silver',
                'sort_order' => 5,
            ],
            [
                'name' => '25% Discount',
                'name_ar' => 'خصم 25%',
                'description' => 'Get 25% off your next order - VIP only!',
                'description_ar' => 'احصل على خصم 25% على طلبك القادم - لأعضاء VIP فقط!',
                'points_required' => 1500,
                'reward_type' => 'discount_percent',
                'reward_value' => 25,
                'icon' => '👑',
                'is_active' => true,
                'is_featured' => true,
                'tier_required' => 'gold',
                'sort_order' => 6,
            ],
        ];
    }
}
