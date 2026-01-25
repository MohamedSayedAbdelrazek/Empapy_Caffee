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
        // Remove name_ar and description_ar from point_rules
        if (Schema::hasColumn('point_rules', 'name_ar')) {
            Schema::table('point_rules', function (Blueprint $table) {
                $table->dropColumn(['name_ar', 'description_ar']);
            });
        }

        // Remove name_ar and description_ar from loyalty_rewards
        if (Schema::hasColumn('loyalty_rewards', 'name_ar')) {
            Schema::table('loyalty_rewards', function (Blueprint $table) {
                $table->dropColumn(['name_ar', 'description_ar']);
            });
        }

        // Remove name_ar and description_ar from loyalty_tiers (if exists)
        if (Schema::hasTable('loyalty_tiers') && Schema::hasColumn('loyalty_tiers', 'name_ar')) {
            Schema::table('loyalty_tiers', function (Blueprint $table) {
                $table->dropColumn(['name_ar', 'description_ar']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the columns if needed
        Schema::table('point_rules', function (Blueprint $table) {
            $table->string('name_ar')->after('name')->nullable();
            $table->text('description_ar')->after('description')->nullable();
        });

        Schema::table('loyalty_rewards', function (Blueprint $table) {
            $table->string('name_ar')->after('name')->nullable();
            $table->text('description_ar')->after('description')->nullable();
        });

        if (Schema::hasTable('loyalty_tiers')) {
            Schema::table('loyalty_tiers', function (Blueprint $table) {
                $table->string('name_ar')->after('name')->nullable();
                $table->text('description_ar')->after('description')->nullable();
            });
        }
    }
};
