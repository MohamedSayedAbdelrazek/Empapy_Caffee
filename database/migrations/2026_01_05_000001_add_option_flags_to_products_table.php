<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Flags to enable/disable options per product
            $table->boolean('has_weight_options')->default(false)->after('is_active');
            $table->boolean('has_roast_options')->default(false)->after('has_weight_options');
            $table->boolean('has_additive_options')->default(false)->after('has_roast_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_weight_options', 'has_roast_options', 'has_additive_options']);
        });
    }
};
