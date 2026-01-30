<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name_ar',
        'group',
        'description',
    ];

    /**
     * Users who have this permission
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }

    /**
     * Get all permissions grouped by their group
     */
    public static function grouped()
    {
        return self::orderBy('group')->orderBy('display_name_ar')->get()->groupBy('group');
    }

    /**
     * Get group display names
     */
    public static function groupLabels(): array
    {
        return [
            'products' => 'المنتجات',
            'orders' => 'الطلبات',
            'categories' => 'الفئات',
            'coupons' => 'الكوبونات',
            'users' => 'المستخدمين',
            'settings' => 'الإعدادات',
            'notifications' => 'الإشعارات',
        ];
    }
}
