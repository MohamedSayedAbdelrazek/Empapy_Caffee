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
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->integer('total_earned')->default(0);
            $table->integer('total_redeemed')->default(0);
            $table->integer('available_points')->default(0);
            $table->string('current_tier')->default('bronze');
            $table->integer('tier_points')->default(0);
            $table->string('referral_code', 20)->unique();
            $table->date('birthday')->nullable();
            $table->boolean('birthday_bonus_claimed')->default(false);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            $table->index('current_tier');
            $table->index('available_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
