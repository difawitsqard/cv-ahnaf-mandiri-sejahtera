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
        Schema::create('stock_item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kategori
            $table->boolean('is_static')->default(false); // Menandai kategori sebagai statis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_item_categories');
    }
};
