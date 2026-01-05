<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'product_option_value_id',
        'option_type',
        'option_name',
        'option_name_ar',
        'value_name',
        'value_name_ar',
        'price_modifier',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
    ];

    /**
     * Get the order item this option belongs to
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Get the original option value (may be null if deleted)
     */
    public function optionValue(): BelongsTo
    {
        return $this->belongsTo(ProductOptionValue::class, 'product_option_value_id');
    }

    /**
     * Get formatted price modifier
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
     * Get display text for this option
     */
    public function getDisplayTextAttribute(): string
    {
        $text = $this->option_name_ar . ': ' . $this->value_name_ar;

        if ($this->price_modifier != 0) {
            $text .= ' (' . $this->formatted_price_modifier . ')';
        }

        return $text;
    }

    /**
     * Create from a ProductOptionValue
     */
    public static function createFromOptionValue(ProductOptionValue $value, int $orderItemId): self
    {
        return self::create([
            'order_item_id' => $orderItemId,
            'product_option_value_id' => $value->id,
            'option_type' => $value->option->type,
            'option_name' => $value->option->name ?? $value->option->type_name,
            'option_name_ar' => $value->option->name_ar ?? $value->option->type_name,
            'value_name' => $value->value,
            'value_name_ar' => $value->value_ar,
            'price_modifier' => $value->price_modifier,
        ]);
    }
}
