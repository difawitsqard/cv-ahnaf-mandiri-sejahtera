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
        Schema::create('order_items', function (Blueprint $table) {
            $table->string('order_id'); // Sesuaikan tipe data dengan orders.id
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // Foreign key
            $table->foreignId('menu_id')->constrained('menus')->onDelete('restrict'); // Foreign key ke tabel menus
            $table->integer('quantity'); // Jumlah menu yang dipesan
            $table->bigInteger('price'); // Harga menu
            $table->bigInteger('subtotal'); // Total harga pesanan
            $table->text('note')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
