<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->onDelete('cascade');
            $table->foreignId('pemasok_id')->nullable()->constrained('pemasoks')->onDelete('set null');
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->integer('total_biaya');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_masuks');
    }
};
