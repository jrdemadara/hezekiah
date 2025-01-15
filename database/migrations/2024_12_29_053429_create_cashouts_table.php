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
        Schema::create('cashouts', function (Blueprint $table) {
            $table->id();
            $table->decimal('gross_amount', total: 10, places: 2);
            $table->decimal('transaction_fee', total: 10, places: 2);
            $table->decimal('net_amount', total: 10, places: 2);
            $table->decimal('referral_deduction', total: 10, places: 2);
            $table->decimal('order_deduction', total: 10, places: 2);
            $table->enum('status', ['pending', 'approved', 'done', 'denied']);
            $table->boolean('pooled')->default(false);
            $table->enum('cashout_type', ['otc', 'ewallet']);
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashouts');
    }
};
