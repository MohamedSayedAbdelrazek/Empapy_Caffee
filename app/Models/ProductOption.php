<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOption extends Model
{
    use HasFactory;

    // Option types
    const TYPE_WEIGHT = 'weight';
    const TYPE_ROAST = 'roast';
    const TYPE_ADDITIVE = 'additive';

    protected $fillable = [
        'product_id',
        'type',
        'name',
        'name_ar',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns this option
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all values for this option
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class)->orderBy('sort_order');
    }

    /**
     * Get the default value for this option
     */
    public function getDefaultValueAttribute()
    {
        return $this->values()->where('is_default', true)->first()
            ?? $this->values()->first();
    }

    /**
     * Get localized type name
     */
    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_WEIGHT => 'الوزن',
            self::TYPE_ROAST => 'التحميص',
            self::TYPE_ADDITIVE => 'الإضافات',
            default => $this->type,
        };
    }

    /**
     * Scope for weight options
     */
    public function scopeWeights($query)
    {
        return $query->where('type', self::TYPE_WEIGHT);
    }

    /**
     * Scope for roast options
     */
    public function scopeRoasts($query)
    {
        return $query->where('type', self::TYPE_ROAST);
    }

    /**
     * Scope for additive options
     */
    public function scopeAdditives($query)
    {
        return $query->where('type', self::TYPE_ADDITIVE);
    }
}
