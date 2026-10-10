<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penggunaan_bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('penggunaan_bahan_bakus'); }
};
