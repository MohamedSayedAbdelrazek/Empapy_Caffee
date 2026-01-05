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
        Schema::create('order_item_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');

            // Reference to the original option value (nullable in case option is deleted)
            $table->foreignId('product_option_value_id')->nullable()->constrained()->onDelete('set null');

            // Snapshot of option data at time of order (for historical records)
            $table->string('option_type'); // weight, roast, additive
            $table->string('option_name'); // e.g., "الوزن"
            $table->string('option_name_ar')->nullable();
            $table->string('value_name'); // e.g., "250 جم"
            $table->string('value_name_ar')->nullable();
            $table->decimal('price_modifier', 10, 2)->default(0);

            $table->timestamps();

            // Index for faster queries
            $table->index('order_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_options');
    }
};
