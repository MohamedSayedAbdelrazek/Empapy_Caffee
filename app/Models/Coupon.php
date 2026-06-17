<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name_ar',
        'description_ar',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'per_user_limit',
        'usage_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    /**
     * Accessor for name (alias for name_ar)
     */
    public function getNameAttribute()
    {
        return $this->name_ar;
    }

    /**
     * Accessor for description (alias for description_ar)
     */
    public function getDescriptionAttribute()
    {
        return $this->description_ar;
    }

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'per_user_limit' => 'integer',
        'usage_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Users who have used this coupon (with per-user usage_count on the pivot).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('usage_count')
            ->withTimestamps();
    }

    /**
     * How many times a given user has already used this coupon.
     */
    public function usageCountForUser(int $userId): int
    {
        return (int) (CouponUser::where('coupon_id', $this->id)
            ->where('user_id', $userId)
            ->value('usage_count') ?? 0);
    }

    /**
     * Check if coupon is valid. When $userId is provided, the per-user usage
     * limit is also enforced.
     */
    public function isValid(?int $userId = null): bool
    {
        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check date range
        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        // Check global usage limit
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        // Check per-user usage limit (only when we know who is redeeming)
        if ($userId !== null && $this->per_user_limit !== null
            && $this->usageCountForUser($userId) >= $this->per_user_limit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount for an order total
     */
    public function calculateDiscount(float $orderTotal): float
    {
        // Check minimum order amount
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = ($orderTotal * $this->value) / 100;
        } else {
            $discount = $this->value;
        }

        // Apply max discount cap
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        // Don't exceed order total
        return min($discount, $orderTotal);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Scope for valid coupons
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereColumn('usage_count', '<', 'usage_limit');
            });
    }
}
