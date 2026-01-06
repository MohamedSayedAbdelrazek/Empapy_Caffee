<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * تحويل قاعدة البيانات للعربية فقط
     */
    public function up(): void
    {
        // 1. جدول categories - إزالة الحقول الإنجليزية
        Schema::table('categories', function (Blueprint $table) {
            // نسخ البيانات العربية للحقول الرئيسية أولاً
            DB::statement('UPDATE categories SET name = name_ar WHERE name_ar IS NOT NULL AND name_ar != ""');
            DB::statement('UPDATE categories SET description = description_ar WHERE description_ar IS NOT NULL');

            // حذف الأعمدة العربية بعد النسخ
            $table->dropColumn(['name_ar', 'description_ar']);
        });

        // 2. جدول products - إزالة الحقول الإنجليزية
        Schema::table('products', function (Blueprint $table) {
            // نسخ البيانات العربية للحقول الرئيسية أولاً
            DB::statement('UPDATE products SET name = name_ar WHERE name_ar IS NOT NULL AND name_ar != ""');
            DB::statement('UPDATE products SET description = description_ar WHERE description_ar IS NOT NULL');
            DB::statement('UPDATE products SET origin = origin_ar WHERE origin_ar IS NOT NULL');

            // حذف الأعمدة العربية بعد النسخ
            $table->dropColumn(['name_ar', 'description_ar', 'origin_ar']);
        });

        // 3. جدول order_items - إزالة الحقول الإنجليزية
        Schema::table('order_items', function (Blueprint $table) {
            // نسخ البيانات العربية للحقول الرئيسية أولاً
            DB::statement('UPDATE order_items SET product_name = product_name_ar WHERE product_name_ar IS NOT NULL AND product_name_ar != ""');

            // حذف العمود العربي بعد النسخ
            $table->dropColumn('product_name_ar');
        });

        // 4. جدول product_option_values - إزالة الحقول الإنجليزية
        Schema::table('product_option_values', function (Blueprint $table) {
            // نسخ البيانات العربية للحقول الرئيسية أولاً
            DB::statement('UPDATE product_option_values SET value = value_ar WHERE value_ar IS NOT NULL AND value_ar != ""');

            // حذف العمود العربي بعد النسخ
            $table->dropColumn('value_ar');
        });

        // 5. جدول order_item_options - إزالة الحقول الإنجليزية
        Schema::table('order_item_options', function (Blueprint $table) {
            // نسخ البيانات العربية للحقول الرئيسية أولاً
            DB::statement('UPDATE order_item_options SET option_name = option_name_ar WHERE option_name_ar IS NOT NULL AND option_name_ar != ""');
            DB::statement('UPDATE order_item_options SET value_name = value_name_ar WHERE value_name_ar IS NOT NULL AND value_name_ar != ""');

            // حذف الأعمدة العربية بعد النسخ
            $table->dropColumn(['option_name_ar', 'value_name_ar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة إضافة الأعمدة العربية
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->text('description_ar')->nullable()->after('description');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->text('description_ar')->nullable()->after('description');
            $table->string('origin_ar')->nullable()->after('origin');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name_ar')->nullable()->after('product_name');
        });

        Schema::table('product_option_values', function (Blueprint $table) {
            $table->string('value_ar')->nullable()->after('value');
        });

        Schema::table('order_item_options', function (Blueprint $table) {
            $table->string('option_name_ar')->nullable()->after('option_name');
            $table->string('value_name_ar')->nullable()->after('value_name');
        });
    }
};
