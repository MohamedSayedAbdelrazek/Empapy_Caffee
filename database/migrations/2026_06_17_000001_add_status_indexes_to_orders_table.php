<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The admin order list filters by `status` / `payment_status` and the
     * kanban board runs one `WHERE status = ? ORDER BY created_at DESC` query
     * per column. Composite indexes on (status, created_at) and
     * (payment_status, created_at) let MySQL satisfy both the filter and the
     * sort without a filesort. (DATA-01)
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'orders_status_created_at_index');
            $table->index(['payment_status', 'created_at'], 'orders_payment_status_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_created_at_index');
            $table->dropIndex('orders_payment_status_created_at_index');
        });
    }
};
