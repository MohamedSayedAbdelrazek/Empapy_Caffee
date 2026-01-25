<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'type',
        'value',
        'trigger',
        'is_first_order_only',
        'min_order_amount',
        'max_points_per_order',
        'is_active',
        'starts_at',
        'ends_at',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_first_order_only' => 'boolean',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    /**
     * Scope for active rules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    /**
     * Scope for specific trigger
     */
    public function scopeForTrigger($query, string $trigger)
    {
        return $query->where('trigger', $trigger);
    }

    /**
     * Calculate points based on rule type
     */
    public function calculatePoints(float $amount): int
    {
        return match ($this->type) {
            'fixed' => (int) $this->value,
            'per_currency' => (int) floor($amount * $this->value),
            'percentage' => (int) floor($amount * ($this->value / 100)),
            default => 0,
        };
    }

    /**
     * Check if rule applies to given amount
     */
    public function appliesTo(float $amount): bool
    {
        if ($this->min_order_amount && $amount < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Get type label in Arabic
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'fixed' => 'نقاط ثابتة',
            'per_currency' => 'لكل 1 ج.م',
            'percentage' => 'نسبة مئوية',
            default => $this->type,
        };
    }

    /**
     * Get trigger label in Arabic
     */
    public function getTriggerLabelAttribute(): string
    {
        return match ($this->trigger) {
            'order_complete' => 'إتمام الطلب',
            'signup' => 'التسجيل',
            'review' => 'كتابة تقييم',
            'referral_made' => 'إحالة ناجحة (للمُحيل)',
            'referral_signup' => 'التسجيل بإحالة (للمُحال)',
            'birthday' => 'عيد الميلاد',
            default => $this->trigger,
        };
    }

    /**
     * Get value display
     */
    public function getValueDisplayAttribute(): string
    {
        return match ($this->type) {
            'fixed' => "{$this->value} نقطة",
            'per_currency' => "{$this->value} نقطة لكل 1 ج.م",
            'percentage' => "{$this->value}% من قيمة الطلب",
            default => (string) $this->value,
        };
    }

    /**
     * Check if rule is currently valid
     */
    public function getIsValidAttribute(): bool
    {
        if (!$this->is_active) return false;

        if ($this->starts_at && $this->starts_at->isFuture()) return false;

        if ($this->ends_at && $this->ends_at->isPast()) return false;

        return true;
    }

    /**
     * Default rules for seeding
     */
    public static function getDefaultRules(): array
    {
        return [
            [
                'slug' => 'order_points',
                'name' => 'نقاط الطلب',
                'description' => 'اكسب نقاط عن كل جنيه تنفقه',
                'type' => 'per_currency',
                'value' => 1,
                'trigger' => 'order_complete',
                'is_active' => true,
            ],
            [
                'slug' => 'signup_bonus',
                'name' => 'مكافأة التسجيل',
                'description' => 'نقاط ترحيبية للأعضاء الجدد',
                'type' => 'fixed',
                'value' => 50,
                'trigger' => 'signup',
                'is_active' => true,
            ],
            [
                'slug' => 'first_order_bonus',
                'name' => 'مكافأة أول طلب',
                'description' => 'نقاط إضافية لأول طلب',
                'type' => 'fixed',
                'value' => 100,
                'trigger' => 'order_complete',
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'slug' => 'review_points',
                'name' => 'نقاط التقييم',
                'description' => 'نقاط مقابل كتابة تقييم للمنتج',
                'type' => 'fixed',
                'value' => 10,
                'trigger' => 'review',
                'is_active' => true,
            ],
            [
                'slug' => 'referral_bonus',
                'name' => 'مكافأة الإحالة',
                'description' => 'نقاط عند إحالة صديق بنجاح',
                'type' => 'fixed',
                'value' => 200,
                'trigger' => 'referral_made',
                'is_active' => true,
            ],
            [
                'slug' => 'referred_bonus',
                'name' => 'مكافأة المُحال',
                'description' => 'نقاط للتسجيل عن طريق إحالة',
                'type' => 'fixed',
                'value' => 50,
                'trigger' => 'referral_signup',
                'is_active' => true,
            ],
        ];
    }
}
