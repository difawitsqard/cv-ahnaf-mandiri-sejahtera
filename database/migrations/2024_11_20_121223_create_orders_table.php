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
            $table->bigInteger('total'); // Total harga pesanan
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending'); // Status pesanan
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('restrict'); // Foreign key ke tabel outlets
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
