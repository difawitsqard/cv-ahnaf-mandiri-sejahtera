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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->nullable(); // Nama pemesan
            $table->bigInteger('sub_total'); // Sub total harga pesanan
            $table->bigInteger('discount')->default(0);
            $table->bigInteger('tax')->default(0);
            $table->bigInteger('total'); // Total harga pesanan
            $table->enum('payment_method', ['cash', 'transfer_bank', 'credit_card', 'other'])->default('cash');
            $table->bigInteger('paid')->default(0); // Jumlah uang yang dibayarkan
            $table->bigInteger('change')->default(0); // Kembalian
            $table->uuid('batch_uuid')->nullable();
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending'); // Status pesanan
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // Foreign key ke tabel users
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
