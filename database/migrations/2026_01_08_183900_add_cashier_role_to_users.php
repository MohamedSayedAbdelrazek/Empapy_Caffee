<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add 'cashier' role to the users table enum
     */
    public function up(): void
    {
        // Modify the enum to include 'cashier'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer', 'cashier') DEFAULT 'customer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum (only if no cashier users exist)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer') DEFAULT 'customer'");
    }
};
