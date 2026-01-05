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
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Option type: weight, roast, additive
            $table->enum('type', ['weight', 'roast', 'additive']);
            
            // Option group name (e.g., "الوزن", "التحميص", "الإضافات")
            $table->string('name')->nullable();
            $table->string('name_ar')->nullable();
            
            // Sort order for display
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['product_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
