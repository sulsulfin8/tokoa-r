<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->string('satuan');
            $table->integer('harga');
            $table->integer('stok')->default(0);
            $table->integer('pemakaian_max')->default(0);
            $table->integer('pemakaian_rata')->default(0);
            $table->integer('lead_time')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
