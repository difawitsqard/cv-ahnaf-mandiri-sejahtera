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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama menu
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade'); // Foreign key ke tabel outlets
            $table->bigInteger('price'); // Harga menu
            $table->foreignId('stock_item_id')->constrained('stock_items')->onDelete('cascade'); // Foreign key ke tabel stock_items
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
