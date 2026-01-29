<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates a pivot table for weight-additive pricing matrix.
     * Allows different additive prices based on selected weight.
     */
    public function up(): void
    {
        Schema::create('additive_weight_prices', function (Blueprint $table) {
            $table->id();

            // Reference to the additive option value (e.g., "بالهيل")
            $table->foreignId('additive_option_value_id')
                ->constrained('product_option_values')
                ->onDelete('cascade');

            // Reference to the weight option value (e.g., "125 جم")
            $table->foreignId('weight_option_value_id')
                ->constrained('product_option_values')
                ->onDelete('cascade');

            // The price modifier for this specific combination
            $table->decimal('price_modifier', 10, 2)->default(0);

            $table->timestamps();

            // Ensure unique combination
            $table->unique(
                ['additive_option_value_id', 'weight_option_value_id'],
                'additive_weight_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additive_weight_prices');
    }
};
