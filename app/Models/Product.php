<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'image',
        'gallery',
        'weight',
        'roast_level',
        'origin',
        'is_featured',
        'is_active',
        'has_weight_options',
        'has_roast_options',
        'has_additive_options',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'has_weight_options' => 'boolean',
        'has_roast_options' => 'boolean',
        'has_additive_options' => 'boolean',
    ];

    /**
     * Get the category this product belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all orders containing this product
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price', 'total')
            ->withTimestamps();
    }

    /**
     * Get the current price (sale price if available, otherwise regular price)
     */
    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if product is on sale
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Calculate discount percentage
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->is_on_sale) {
            return 0;
        }
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get all options for this product
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class)->orderBy('sort_order');
    }

    /**
     * Get weight option for this product
     */
    public function weightOption(): HasMany
    {
        return $this->hasMany(ProductOption::class)->where('type', ProductOption::TYPE_WEIGHT);
    }

    /**
     * Get roast option for this product
     */
    public function roastOption(): HasMany
    {
        return $this->hasMany(ProductOption::class)->where('type', ProductOption::TYPE_ROAST);
    }

    /**
     * Get additive option for this product
     */
    public function additiveOption(): HasMany
    {
        return $this->hasMany(ProductOption::class)->where('type', ProductOption::TYPE_ADDITIVE);
    }

    /**
     * Check if product has any options
     */
    public function getHasOptionsAttribute(): bool
    {
        return $this->has_weight_options || $this->has_roast_options || $this->has_additive_options;
    }

    /**
     * Get weight values for this product
     */
    public function getWeightValuesAttribute()
    {
        if (!$this->has_weight_options) {
            return collect();
        }

        return $this->weightOption()->with('values')->get()->flatMap->values;
    }

    /**
     * Get roast values for this product
     */
    public function getRoastValuesAttribute()
    {
        if (!$this->has_roast_options) {
            return collect();
        }

        return $this->roastOption()->with('values')->get()->flatMap->values;
    }

    /**
     * Get additive values for this product
     */
    public function getAdditiveValuesAttribute()
    {
        if (!$this->has_additive_options) {
            return collect();
        }

        return $this->additiveOption()->with('values')->get()->flatMap->values;
    }

    /**
     * Get the starting price (minimum weight price + minimum option modifiers)
     * This is used for "يبدأ من" display
     * 
     * NEW LOGIC:
     * - Weight options: price_modifier IS the full price for that weight
     * - Roast/Additive: price_modifier is ADDED to the weight price
     */
    public function getStartingPriceAttribute(): float
    {
        // If no weight options, use product base price
        if (!$this->has_weight_options) {
            $basePrice = $this->current_price;
        } else {
            // Weight options: get the minimum/default weight PRICE (not modifier)
            $weightValues = $this->weight_values;
            if ($weightValues->isNotEmpty()) {
                $default = $weightValues->where('is_default', true)->first();
                // The price_modifier for weight IS the full price
                $basePrice = $default ? $default->price_modifier : $weightValues->min('price_modifier');
            } else {
                $basePrice = $this->current_price;
            }
        }

        $additionalModifiers = 0;

        // Roast options: these are MODIFIERS on top of weight price
        if ($this->has_roast_options) {
            $roastValues = $this->roast_values;
            if ($roastValues->isNotEmpty()) {
                $default = $roastValues->where('is_default', true)->first();
                $additionalModifiers += $default ? $default->price_modifier : $roastValues->min('price_modifier');
            }
        }

        // Additive options: these are MODIFIERS on top of weight price
        if ($this->has_additive_options) {
            $additiveValues = $this->additive_values;
            if ($additiveValues->isNotEmpty()) {
                $default = $additiveValues->where('is_default', true)->first();
                $additionalModifiers += $default ? $default->price_modifier : $additiveValues->min('price_modifier');
            }
        }

        return $basePrice + $additionalModifiers;
    }

    /**
     * Get the minimum possible price (lowest weight + min modifiers)
     */
    public function getMinPriceAttribute(): float
    {
        // Base: lowest weight price or product price
        if ($this->has_weight_options) {
            $weightValues = $this->weight_values;
            $basePrice = $weightValues->isNotEmpty() ? $weightValues->min('price_modifier') : $this->current_price;
        } else {
            $basePrice = $this->current_price;
        }

        $minModifiers = 0;

        // Add minimum roast modifier (could be 0 or negative)
        if ($this->has_roast_options) {
            $roastValues = $this->roast_values;
            if ($roastValues->isNotEmpty()) {
                $minModifiers += $roastValues->min('price_modifier');
            }
        }

        // Add minimum additive modifier
        if ($this->has_additive_options) {
            $additiveValues = $this->additive_values;
            if ($additiveValues->isNotEmpty()) {
                $minModifiers += $additiveValues->min('price_modifier');
            }
        }

        return $basePrice + $minModifiers;
    }

    /**
     * Get the maximum possible price (highest weight + max modifiers)
     */
    public function getMaxPriceAttribute(): float
    {
        // Base: highest weight price or product price
        if ($this->has_weight_options) {
            $weightValues = $this->weight_values;
            $basePrice = $weightValues->isNotEmpty() ? $weightValues->max('price_modifier') : $this->current_price;
        } else {
            $basePrice = $this->current_price;
        }

        $maxModifiers = 0;

        // Add maximum roast modifier
        if ($this->has_roast_options) {
            $roastValues = $this->roast_values;
            if ($roastValues->isNotEmpty()) {
                $maxModifiers += $roastValues->max('price_modifier');
            }
        }

        // Add maximum additive modifier
        if ($this->has_additive_options) {
            $additiveValues = $this->additive_values;
            if ($additiveValues->isNotEmpty()) {
                $maxModifiers += $additiveValues->max('price_modifier');
            }
        }

        return $basePrice + $maxModifiers;
    }

    /**
     * Calculate price with selected options
     * 
     * NEW LOGIC:
     * - If weight option selected: use its price_modifier AS the base price
     * - Roast/Additive modifiers are ADDED to this
     */
    public function calculatePriceWithOptions(array $selectedOptionValueIds): float
    {
        if (empty($selectedOptionValueIds)) {
            return $this->current_price;
        }

        $values = ProductOptionValue::with('option')->whereIn('id', $selectedOptionValueIds)->get();

        $finalPrice = $this->current_price;
        $additionalModifiers = 0;

        foreach ($values as $value) {
            if ($value->option->type === ProductOption::TYPE_WEIGHT) {
                // Weight: the price_modifier IS the full price
                $finalPrice = $value->price_modifier;
            } else {
                // Roast/Additive: add the modifier
                $additionalModifiers += $value->price_modifier;
            }
        }

        return $finalPrice + $additionalModifiers;
    }
}
