<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->date('pre_order_available_on')->nullable()->after('is_pre_order');
            $table->text('pre_order_note')->nullable()->after('pre_order_available_on');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_pre_order')->default(false)->after('price');
            $table->date('pre_order_available_on')->nullable()->after('is_pre_order');
            $table->text('pre_order_note')->nullable()->after('pre_order_available_on');
            $table->string('pre_order_status')->default('normal')->after('pre_order_note');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'is_pre_order',
                'pre_order_available_on',
                'pre_order_note',
                'pre_order_status',
            ]);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'pre_order_available_on',
                'pre_order_note',
            ]);
        });
    }
};
