<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'min_points',
        'max_points',
        'discount_percent',
        'free_shipping',
        'free_shipping_threshold',
        'points_multiplier',
        'icon',
        'color',
        'badge_image',
        'perks',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'free_shipping' => 'boolean',
        'is_active' => 'boolean',
        'perks' => 'array',
        'points_multiplier' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
    ];

    /**
     * Users in this tier
     */
    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(LoyaltyPoint::class, 'current_tier', 'slug');
    }

    /**
     * Scope for active tiers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get next tier
     */
    public function getNextTier(): ?self
    {
        return self::where('min_points', '>', $this->min_points)
            ->where('is_active', true)
            ->orderBy('min_points')
            ->first();
    }

    /**
     * Get previous tier
     */
    public function getPreviousTier(): ?self
    {
        return self::where('min_points', '<', $this->min_points)
            ->where('is_active', true)
            ->orderByDesc('min_points')
            ->first();
    }

    /**
     * Get tier for given point amount
     */
    public static function getTierForPoints(int $points): ?self
    {
        return self::where('min_points', '<=', $points)
            ->where('is_active', true)
            ->orderByDesc('min_points')
            ->first();
    }

    /**
     * Get number of users in this tier
     */
    public function getUserCountAttribute(): int
    {
        return $this->loyaltyPoints()->count();
    }

    /**
     * Get all benefits as array
     */
    public function getAllBenefitsAttribute(): array
    {
        $benefits = [];

        if ($this->discount_percent > 0) {
            $benefits[] = "خصم {$this->discount_percent}% على كل طلب";
        }

        if ($this->free_shipping) {
            if ($this->free_shipping_threshold) {
                $benefits[] = "شحن مجاني للطلبات أكثر من " . number_format($this->free_shipping_threshold) . " ج.م";
            } else {
                $benefits[] = "شحن مجاني على جميع الطلبات";
            }
        }

        if ($this->points_multiplier > 1) {
            $benefits[] = "نقاط مضاعفة x{$this->points_multiplier}";
        }

        if ($this->perks) {
            $benefits = array_merge($benefits, $this->perks);
        }

        return $benefits;
    }

    /**
     * Default tiers for seeding
     */
    public static function getDefaultTiers(): array
    {
        return [
            [
                'slug' => 'bronze',
                'name' => 'Bronze',
                'name_ar' => 'برونزي',
                'description' => 'Welcome tier for new members',
                'description_ar' => 'مستوى الترحيب للأعضاء الجدد',
                'min_points' => 0,
                'max_points' => 999,
                'discount_percent' => 0,
                'free_shipping' => false,
                'points_multiplier' => 1.00,
                'icon' => '🥉',
                'color' => '#CD7F32',
                'sort_order' => 1,
            ],
            [
                'slug' => 'silver',
                'name' => 'Silver',
                'name_ar' => 'فضي',
                'description' => 'Members with consistent purchases',
                'description_ar' => 'للأعضاء ذوي المشتريات المستمرة',
                'min_points' => 1000,
                'max_points' => 4999,
                'discount_percent' => 5,
                'free_shipping' => true,
                'free_shipping_threshold' => 300,
                'points_multiplier' => 1.25,
                'icon' => '🥈',
                'color' => '#C0C0C0',
                'sort_order' => 2,
                'perks' => ['أولوية في خدمة العملاء'],
            ],
            [
                'slug' => 'gold',
                'name' => 'Gold',
                'name_ar' => 'ذهبي',
                'description' => 'Valued loyal customers',
                'description_ar' => 'للعملاء المميزين',
                'min_points' => 5000,
                'max_points' => 14999,
                'discount_percent' => 10,
                'free_shipping' => true,
                'points_multiplier' => 1.50,
                'icon' => '🥇',
                'color' => '#FFD700',
                'sort_order' => 3,
                'perks' => ['عينات مجانية مع كل طلب', 'أولوية في خدمة العملاء'],
            ],
            [
                'slug' => 'platinum',
                'name' => 'Platinum',
                'name_ar' => 'بلاتيني',
                'description' => 'Our most valued VIP customers',
                'description_ar' => 'لعملائنا الأكثر قيمة',
                'min_points' => 15000,
                'max_points' => null,
                'discount_percent' => 15,
                'free_shipping' => true,
                'points_multiplier' => 2.00,
                'icon' => '💎',
                'color' => '#E5E4E2',
                'sort_order' => 4,
                'perks' => [
                    'شحن سريع مجاني (24 ساعة)',
                    'عينات حصرية',
                    'وصول مبكر للمنتجات الجديدة',
                    'هدايا في المناسبات',
                    'خط دعم VIP مباشر',
                ],
            ],
        ];
    }
}
