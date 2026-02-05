<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add 'flavor' to the enum type in product_options (if not exists)
        $currentTypes = DB::select("SHOW COLUMNS FROM product_options WHERE Field = 'type'")[0]->Type ?? '';
        if (strpos($currentTypes, 'flavor') === false) {
            DB::statement("ALTER TABLE product_options MODIFY COLUMN type ENUM('weight', 'roast', 'additive', 'flavor') NOT NULL");
        }

        // 2. Add has_flavor_options column to products table (if not exists)
        if (!Schema::hasColumn('products', 'has_flavor_options')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('has_flavor_options')->default(false)->after('has_additive_options');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum
        DB::statement("ALTER TABLE product_options MODIFY COLUMN type ENUM('weight', 'roast', 'additive') NOT NULL");

        // Remove column
        if (Schema::hasColumn('products', 'has_flavor_options')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('has_flavor_options');
            });
        }
    }
};
