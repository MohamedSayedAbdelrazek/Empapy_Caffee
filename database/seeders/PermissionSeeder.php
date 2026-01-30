<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Products Group
            ['name' => 'view-products', 'display_name_ar' => 'عرض المنتجات', 'group' => 'products'],
            ['name' => 'create-products', 'display_name_ar' => 'إضافة منتجات', 'group' => 'products'],
            ['name' => 'edit-products', 'display_name_ar' => 'تعديل منتجات', 'group' => 'products'],
            ['name' => 'delete-products', 'display_name_ar' => 'حذف منتجات', 'group' => 'products'],

            // Orders Group
            ['name' => 'view-orders', 'display_name_ar' => 'عرض الطلبات', 'group' => 'orders'],
            ['name' => 'edit-orders', 'display_name_ar' => 'تعديل الطلبات', 'group' => 'orders'],

            // Categories Group
            ['name' => 'view-categories', 'display_name_ar' => 'عرض الفئات', 'group' => 'categories'],
            ['name' => 'create-categories', 'display_name_ar' => 'إضافة فئات', 'group' => 'categories'],
            ['name' => 'edit-categories', 'display_name_ar' => 'تعديل فئات', 'group' => 'categories'],
            ['name' => 'delete-categories', 'display_name_ar' => 'حذف فئات', 'group' => 'categories'],

            // Coupons Group
            ['name' => 'view-coupons', 'display_name_ar' => 'عرض الكوبونات', 'group' => 'coupons'],
            ['name' => 'create-coupons', 'display_name_ar' => 'إضافة كوبونات', 'group' => 'coupons'],
            ['name' => 'edit-coupons', 'display_name_ar' => 'تعديل كوبونات', 'group' => 'coupons'],
            ['name' => 'delete-coupons', 'display_name_ar' => 'حذف كوبونات', 'group' => 'coupons'],

            // Users Group (Staff Management)
            ['name' => 'view-users', 'display_name_ar' => 'عرض المستخدمين', 'group' => 'users'],
            ['name' => 'create-users', 'display_name_ar' => 'إضافة مستخدمين', 'group' => 'users'],
            ['name' => 'edit-users', 'display_name_ar' => 'تعديل مستخدمين', 'group' => 'users'],
            ['name' => 'delete-users', 'display_name_ar' => 'حذف مستخدمين', 'group' => 'users'],
            ['name' => 'manage-permissions', 'display_name_ar' => 'إدارة الصلاحيات', 'group' => 'users'],

            // Loyalty Group
            ['name' => 'view-loyalty', 'display_name_ar' => 'عرض نظام الولاء', 'group' => 'loyalty'],
            ['name' => 'manage-loyalty', 'display_name_ar' => 'إدارة نظام الولاء', 'group' => 'loyalty'],

            // Announcements Group
            ['name' => 'view-announcements', 'display_name_ar' => 'عرض الإعلانات', 'group' => 'announcements'],
            ['name' => 'manage-announcements', 'display_name_ar' => 'إدارة الإعلانات', 'group' => 'announcements'],

            // Contacts Group
            ['name' => 'view-contacts', 'display_name_ar' => 'عرض رسائل التواصل', 'group' => 'contacts'],
            ['name' => 'manage-contacts', 'display_name_ar' => 'إدارة رسائل التواصل', 'group' => 'contacts'],

            // Settings Group
            ['name' => 'view-analytics', 'display_name_ar' => 'عرض الإحصائيات', 'group' => 'settings'],
            ['name' => 'edit-settings', 'display_name_ar' => 'تعديل الإعدادات', 'group' => 'settings'],
            ['name' => 'manage-site', 'display_name_ar' => 'إدارة الموقع', 'group' => 'settings'],

            // Notifications Group
            ['name' => 'view-notifications', 'display_name_ar' => 'عرض الإشعارات', 'group' => 'notifications'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('✅ تم إنشاء ' . count($permissions) . ' صلاحية بنجاح!');
    }
}
