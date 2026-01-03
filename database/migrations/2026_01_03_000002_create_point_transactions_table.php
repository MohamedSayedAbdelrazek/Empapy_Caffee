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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points');
            $table->integer('balance_after')->default(0);
            $table->enum('type', ['earned', 'redeemed', 'expired', 'bonus', 'adjustment']);
            $table->string('source', 50);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description');
            $table->string('description_ar');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'source']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
