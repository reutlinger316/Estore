<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_fronts', function (Blueprint $table) {
            $table->foreignId('store_account_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('merchant_id');

            $table->string('confirmation_status')->default('pending')->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('confirmation_status');
        });
    }

    public function down(): void
    {
        Schema::table('store_fronts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_account_id');
            $table->dropColumn(['confirmation_status', 'confirmed_at']);
        });
    }
};
