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
        Schema::table('coupons', function (Blueprint $table) {
            // Max times a single customer may use this coupon. Null = no per-user cap.
            $table->unsignedInteger('per_user_limit')->nullable()->after('usage_limit');
        });

        // Tracks how many times each user has used each coupon (per-user limit enforcement).
        Schema::create('coupon_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamps();

            $table->unique(['coupon_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_user');

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('per_user_limit');
        });
    }
};
