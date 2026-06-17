<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Per-user coupon usage record (pivot for the coupon_user table).
 * Used to enforce a coupon's per_user_limit.
 */
class CouponUser extends Model
{
    protected $table = 'coupon_user';

    protected $fillable = [
        'coupon_id',
        'user_id',
        'usage_count',
    ];

    protected $casts = [
        'usage_count' => 'integer',
    ];
}
