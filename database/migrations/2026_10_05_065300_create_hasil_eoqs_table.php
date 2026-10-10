<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_eoqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->onDelete('cascade');
            $table->string('periode');
            $table->integer('kebutuhan_tahunan_d');
            $table->integer('biaya_pesan_s');
            $table->integer('biaya_simpan_h');
            $table->integer('eoq');
            $table->integer('safety_stock');
            $table->integer('reorder_point');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_eoqs');
    }
};
