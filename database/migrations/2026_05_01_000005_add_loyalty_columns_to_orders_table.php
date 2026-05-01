<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal_before_points', 10, 2)->default(0)->after('total_amount');
            $table->unsignedInteger('points_redeemed')->default(0)->after('subtotal_before_points');
            $table->decimal('points_discount_amount', 10, 2)->default(0)->after('points_redeemed');
            $table->decimal('points_discount_percent', 5, 2)->default(0)->after('points_discount_amount');
            $table->string('points_owner_type')->nullable()->after('points_discount_percent');
            $table->foreignId('points_merchant_id')->nullable()->constrained('users')->nullOnDelete()->after('points_owner_type');
            $table->unsignedInteger('global_points_earned')->default(0)->after('points_merchant_id');
            $table->unsignedInteger('merchant_points_earned')->default(0)->after('global_points_earned');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('points_merchant_id');
            $table->dropColumn([
                'subtotal_before_points',
                'points_redeemed',
                'points_discount_amount',
                'points_discount_percent',
                'points_owner_type',
                'global_points_earned',
                'merchant_points_earned',
            ]);
        });
    }
};
