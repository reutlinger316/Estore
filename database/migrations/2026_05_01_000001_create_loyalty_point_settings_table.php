<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_point_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('owner_type', ['admin', 'merchant']);
            $table->foreignId('merchant_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->decimal('amount_per_point', 10, 2)->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['owner_type', 'merchant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_point_settings');
    }
};
