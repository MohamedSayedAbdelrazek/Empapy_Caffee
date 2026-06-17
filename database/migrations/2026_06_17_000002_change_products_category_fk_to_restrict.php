<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DATA-01: products.category_id was ON DELETE CASCADE, which would hard-delete
     * a category's products (and, transitively, their order_items) — bypassing
     * the products' soft-delete. Switch to ON DELETE RESTRICT so the database
     * itself refuses to delete a category that still has products. The leftover
     * index on category_id is reused, so no index churn.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });
    }
};
