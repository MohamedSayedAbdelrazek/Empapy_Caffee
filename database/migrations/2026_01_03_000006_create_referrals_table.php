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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
            $table->string('referral_code', 20);
            $table->enum('status', ['pending', 'completed', 'rewarded'])->default('pending');
            $table->integer('referrer_points_awarded')->default(0);
            $table->integer('referred_points_awarded')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rewarded_at')->nullable();
            $table->timestamps();

            $table->unique(['referrer_id', 'referred_id']);
            $table->index('referral_code');
            $table->index('status');
        });

        // Also create a table to track reward redemptions
        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reward_id')->constrained('loyalty_rewards')->cascadeOnDelete();
            $table->integer('points_spent');
            $table->enum('status', ['pending', 'applied', 'expired', 'cancelled'])->default('pending');
            $table->string('redemption_code', 20)->unique();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('redemption_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_redemptions');
        Schema::dropIfExists('referrals');
    }
};
