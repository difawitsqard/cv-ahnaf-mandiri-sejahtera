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
            $table->string('name'); // Nama item
            $table->text('description')->nullable(); // Deskripsi item
            $table->bigInteger('stock'); // Jumlah item yang tersedia
            $table->bigInteger('min_stock')->default(0); // Jumlah minimum item yang harus tersedia
            $table->bigInteger('total_stock')->default(0); // Jumlah total item yang pernah ada
            $table->bigInteger('price')->default(0); // Harga item
            $table->string('image_path')->nullable(); // Path gambar item
            // $table->foreignId('category_id')->nullable()->constrained('stock_item_categories')->nullOnDelete(); // Foreign key ke tabel stock_item_categories
            $table->foreignId('category_id')->constrained('stock_item_categories')->onDelete('restrict'); // Foreign key ke tabel stock_item_categories
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade'); // Foreign key ke tabel outlets
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict'); // Foreign key ke tabel units
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
