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
        Schema::create('loyalty_rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('name_ar', 150);
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('points_required');
            $table->enum('reward_type', ['discount_fixed', 'discount_percent', 'free_shipping', 'free_product']);
            $table->decimal('reward_value', 10, 2)->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('image')->nullable();
            $table->string('icon', 10)->default('🎁');
            $table->integer('stock')->nullable();
            $table->integer('times_redeemed')->default(0);
            $table->integer('max_per_user')->nullable();
            $table->string('tier_required')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();
            $table->timestamps();

            $table->index('points_required');
            $table->index('is_active');
            $table->index('reward_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_rewards');
    }
};
