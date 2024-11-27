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
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name'); // Nama outlet
            $table->string('address'); // Alamat outlet
            $table->string('phone_number'); // Nomor telepon outlet
            $table->string('image_path')->nullable(); // Path gambar outlet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
