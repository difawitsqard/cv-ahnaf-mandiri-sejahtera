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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name'); // Nama item
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade'); // Foreign key ke tabel outlets
            $table->integer('price'); // Harga per unit bahan
            $table->integer('quantity'); // Jumlah item yang tersedia
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // Foreign key ke tabel units
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
