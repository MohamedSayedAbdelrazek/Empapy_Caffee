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
            ['name' => 'delete-orders', 'display_name_ar' => 'حذف الطلبات', 'group' => 'orders'],
            ['name' => 'export-orders', 'display_name_ar' => 'تصدير الطلبات', 'group' => 'orders'],

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

            // Reports Group
            ['name' => 'view-reports', 'display_name_ar' => 'عرض التقارير', 'group' => 'reports'],
            ['name' => 'export-reports', 'display_name_ar' => 'تصدير التقارير', 'group' => 'reports'],
            ['name' => 'view-analytics', 'display_name_ar' => 'عرض الإحصائيات', 'group' => 'reports'],

            // Settings Group
            ['name' => 'edit-settings', 'display_name_ar' => 'تعديل الإعدادات', 'group' => 'settings'],
            ['name' => 'manage-site', 'display_name_ar' => 'إدارة الموقع', 'group' => 'settings'],

            // Reviews Group
            ['name' => 'view-reviews', 'display_name_ar' => 'عرض التقييمات', 'group' => 'reviews'],
            ['name' => 'moderate-reviews', 'display_name_ar' => 'مراجعة التقييمات', 'group' => 'reviews'],
            ['name' => 'delete-reviews', 'display_name_ar' => 'حذف التقييمات', 'group' => 'reviews'],

            // Notifications Group
            ['name' => 'send-notifications', 'display_name_ar' => 'إرسال إشعارات', 'group' => 'notifications'],
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
