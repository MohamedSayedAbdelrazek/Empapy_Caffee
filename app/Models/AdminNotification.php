<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'icon_color',
        'action_url',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for recent notifications (last 24 hours)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Create a new order notification
     */
    public static function createOrderNotification($order)
    {
        return self::create([
            'type' => 'new_order',
            'title' => 'طلب جديد! 💰',
            'message' => "طلب جديد #{$order->order_number} من {$order->customer_name} بقيمة " . number_format($order->total) . " ج.م",
            'icon' => 'bi-cart-check-fill',
            'icon_color' => 'success',
            'action_url' => route('admin.orders.show', $order->id),
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'total' => $order->total,
            ],
        ]);
    }

    /**
     * Create a low stock notification
     */
    public static function createLowStockNotification($product)
    {
        return self::create([
            'type' => 'low_stock',
            'title' => 'تنبيه مخزون منخفض! ⚠️',
            'message' => "المنتج \"{$product->name_ar}\" متبقي منه {$product->stock} فقط",
            'icon' => 'bi-exclamation-triangle-fill',
            'icon_color' => 'warning',
            'action_url' => route('admin.products.edit', $product->id),
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name_ar,
                'stock' => $product->stock,
            ],
        ]);
    }

    /**
     * Create a new customer notification
     */
    public static function createNewCustomerNotification($user)
    {
        return self::create([
            'type' => 'new_customer',
            'title' => 'عميل جديد! 👤',
            'message' => "انضم عميل جديد: {$user->name}",
            'icon' => 'bi-person-plus-fill',
            'icon_color' => 'info',
            'action_url' => route('admin.users.show', $user->id),
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
            ],
        ]);
    }

    /**
     * Create a new review notification
     */
    public static function createReviewNotification($review)
    {
        $stars = str_repeat('⭐', $review->rating);
        return self::create([
            'type' => 'new_review',
            'title' => 'تقييم جديد! ' . $stars,
            'message' => "قام {$review->user->name} بتقييم \"{$review->product->name_ar}\" بـ {$review->rating} نجوم",
            'icon' => 'bi-star-fill',
            'icon_color' => 'warning',
            'action_url' => route('admin.products.show', $review->product_id),
            'data' => [
                'review_id' => $review->id,
                'product_id' => $review->product_id,
                'rating' => $review->rating,
            ],
        ]);
    }

    /**
     * Get Arabic type label
     */
    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'new_order' => 'طلب جديد',
            'low_stock' => 'مخزون منخفض',
            'new_customer' => 'عميل جديد',
            'new_review' => 'تقييم جديد',
            default => 'إشعار',
        };
    }

    /**
     * Get time ago in Arabic
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
