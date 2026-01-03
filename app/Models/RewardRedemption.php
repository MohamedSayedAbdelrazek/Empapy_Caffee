<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RewardRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_id',
        'points_spent',
        'status',
        'redemption_code',
        'order_id',
        'expires_at',
        'applied_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'applied_at' => 'datetime',
    ];

    /**
     * The user who redeemed
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The reward that was redeemed
     */
    public function reward(): BelongsTo
    {
        return $this->belongsTo(LoyaltyReward::class, 'reward_id');
    }

    /**
     * The order where reward was applied
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope for pending redemptions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for valid/usable redemptions
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Generate unique redemption code
     */
    public static function generateCode(): string
    {
        do {
            $code = 'RWD-' . strtoupper(Str::random(8));
        } while (self::where('redemption_code', $code)->exists());

        return $code;
    }

    /**
     * Apply to order
     */
    public function applyToOrder(Order $order): void
    {
        $this->order_id = $order->id;
        $this->status = 'applied';
        $this->applied_at = now();
        $this->save();
    }

    /**
     * Cancel redemption
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->save();

        // Refund points to user
        $user = $this->user;
        if ($user && $user->loyaltyPoints) {
            $user->loyaltyPoints->addPoints(
                $this->points_spent,
                'refund',
                'Refund for cancelled reward',
                'استرداد لمكافأة ملغاة',
                $this
            );
        }
    }

    /**
     * Check if expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if can be used
     */
    public function getIsUsableAttribute(): bool
    {
        return $this->status === 'pending' && !$this->is_expired;
    }

    /**
     * Get status label in Arabic
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قابل للاستخدام',
            'applied' => 'مستخدم',
            'expired' => 'منتهي',
            'cancelled' => 'ملغي',
            default => $this->status,
        };
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'success',
            'applied' => 'info',
            'expired' => 'warning',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}
