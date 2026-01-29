<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdditiveWeightPrice extends Model
{
    use HasFactory;

    protected $table = 'additive_weight_prices';

    protected $fillable = [
        'additive_option_value_id',
        'weight_option_value_id',
        'price_modifier',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
    ];

    /**
     * Get the additive option value (e.g., "بالهيل")
     */
    public function additiveValue(): BelongsTo
    {
        return $this->belongsTo(ProductOptionValue::class, 'additive_option_value_id');
    }

    /**
     * Get the weight option value (e.g., "125 جم")
     */
    public function weightValue(): BelongsTo
    {
        return $this->belongsTo(ProductOptionValue::class, 'weight_option_value_id');
    }

    /**
     * Get formatted price modifier string
     */
    public function getFormattedPriceModifierAttribute(): string
    {
        if ($this->price_modifier == 0) {
            return '';
        }

        $sign = $this->price_modifier > 0 ? '+' : '';
        return $sign . number_format($this->price_modifier) . ' ج.م';
    }

    /**
     * Find price for a specific additive + weight combination
     */
    public static function getPriceFor(int $additiveValueId, int $weightValueId): ?float
    {
        $entry = static::where('additive_option_value_id', $additiveValueId)
            ->where('weight_option_value_id', $weightValueId)
            ->first();

        return $entry?->price_modifier;
    }
}
