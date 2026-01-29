<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'total',
        'is_reward_item',
        'reward_note',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'is_reward_item' => 'boolean',
    ];

    /**
     * Get the order this item belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product (may be null if deleted)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get selected options for this order item
     */
    public function selectedOptions(): HasMany
    {
        return $this->hasMany(OrderItemOption::class);
    }

    /**
     * Get total price modifier from all selected options
     */
    public function getOptionsPriceModifierAttribute(): float
    {
        return $this->selectedOptions()->sum('price_modifier');
    }

    /**
     * Get formatted options text for display
     */
    public function getOptionsDisplayTextAttribute(): string
    {
        return $this->selectedOptions
            ->map(fn($opt) => $opt->display_text)
            ->implode(' | ');
    }
}
