<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_option_id',
        'value',
        'value_ar',
        'price_modifier',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the option group this value belongs to
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    /**
     * Get formatted price modifier string (e.g., "+15 ج.م" or "-5 ج.م")
     * For roast/additive modifiers
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
     * Get formatted price string (e.g., "150 ج.م")
     * For weight options where price_modifier IS the full price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price_modifier) . ' ج.م';
    }

    /**
     * Get display label with price
     * Shows differently for weight (full price) vs modifiers
     */
    public function getDisplayLabelAttribute(): string
    {
        $label = $this->value_ar;

        // Check if this is a weight option (show full price) or modifier
        if ($this->option && $this->option->type === ProductOption::TYPE_WEIGHT) {
            // Weight: show full price
            $label .= ' - ' . $this->formatted_price;
        } elseif ($this->price_modifier != 0) {
            // Roast/Additive: show modifier
            $label .= ' (' . $this->formatted_price_modifier . ')';
        }

        return $label;
    }

    /**
     * Check if this value adds to the price
     */
    public function getAddsToPriceAttribute(): bool
    {
        return $this->price_modifier > 0;
    }

    /**
     * Scope for default values
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
