<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_penjualan')->unique();
            $table->date('tanggal');
            $table->integer('total_harga')->default(0);
            $table->enum('status', ['pending', 'selesai', 'dibatalkan'])->default('selesai');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
