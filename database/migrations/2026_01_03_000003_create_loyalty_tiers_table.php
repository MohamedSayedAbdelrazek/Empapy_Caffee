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
        Schema::create('loyalty_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 100);
            $table->string('name_ar', 100);
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('min_points')->default(0);
            $table->integer('max_points')->nullable();
            $table->integer('discount_percent')->default(0);
            $table->boolean('free_shipping')->default(false);
            $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            $table->decimal('points_multiplier', 3, 2)->default(1.00);
            $table->string('icon', 10)->default('🥉');
            $table->string('color', 7)->default('#CD7F32');
            $table->string('badge_image')->nullable();
            $table->json('perks')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('sort_order');
            $table->index('min_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_tiers');
    }
};
