<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'balance_after',
        'type',
        'source',
        'reference_type',
        'reference_id',
        'description',
        'description_ar',
        'admin_id',
    ];

    /**
     * The user who made this transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The admin who made the adjustment (if any)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the referenced model (Order, Review, etc.)
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for earned transactions
     */
    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    /**
     * Scope for redeemed transactions
     */
    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeemed');
    }

    /**
     * Scope for specific source
     */
    public function scopeFromSource($query, string $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Get type label in Arabic
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'earned' => 'مكتسب',
            'redeemed' => 'مستخدم',
            'expired' => 'منتهي',
            'bonus' => 'مكافأة',
            'adjustment' => 'تعديل',
            default => $this->type,
        };
    }

    /**
     * Get source label in Arabic
     */
    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'order' => 'طلب',
            'signup' => 'تسجيل',
            'review' => 'تقييم',
            'referral' => 'إحالة',
            'birthday' => 'عيد ميلاد',
            'admin' => 'تعديل إداري',
            'reward' => 'استبدال مكافأة',
            default => $this->source,
        };
    }

    /**
     * Check if this is a positive transaction
     */
    public function getIsPositiveAttribute(): bool
    {
        return $this->points > 0;
    }

    /**
     * Get formatted points with sign
     */
    public function getFormattedPointsAttribute(): string
    {
        $sign = $this->points > 0 ? '+' : '';
        return $sign . number_format($this->points);
    }
}
