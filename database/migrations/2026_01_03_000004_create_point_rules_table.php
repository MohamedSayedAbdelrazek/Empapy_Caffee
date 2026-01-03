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
        Schema::create('point_rules', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 100);
            $table->string('name_ar', 100);
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->enum('type', ['fixed', 'per_currency', 'percentage']);
            $table->decimal('value', 10, 2);
            $table->string('trigger', 50);
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->integer('max_points_per_order')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->index('trigger');
            $table->index('is_active');
            $table->index(['starts_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_rules');
    }
};
