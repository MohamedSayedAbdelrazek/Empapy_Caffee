<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
    ];

    /**
     * Get the user that owns the wishlist item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product in the wishlist
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get wishlist items for current user or session
     */
    public static function getForUser()
    {
        if (auth()->check()) {
            return self::where('user_id', auth()->id());
        }
        return self::where('session_id', session()->getId());
    }

    /**
     * Check if product is in wishlist
     */
    public static function hasProduct($productId): bool
    {
        return self::getForUser()->where('product_id', $productId)->exists();
    }
}
