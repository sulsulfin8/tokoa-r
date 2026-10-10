@extends('layouts.app')
@section('title', 'Edit Perhitungan EOQ')
@section('header', 'Edit Hasil Perhitungan EOQ')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
            <span class="bg-blue-100 text-blue-600 p-2.5 rounded-xl mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </span>
            Edit Data EOQ
        </h3>
        <a href="{{ route('eoq.index') }}" class="text-gray-500 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('eoq.update', $eoq->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Pilih Bahan Baku</label>
                <select name="bahan_baku_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-3 border bg-gray-50" required>
                    <option value="">-- Pilih Bahan Baku --</option>
                    @foreach($bahanBakus as $b)
                        <option value="{{ $b->id }}" {{ $eoq->bahan_baku_id == $b->id ? 'selected' : '' }}>{{ $b->nama_bahan }} (Satuan: {{ $b->satuan }})</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Periode</label>
                <input type="text" name="periode" value="{{ $eoq->periode }}" placeholder="Contoh: Tahun 2026" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-3 border bg-gray-50" required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Kebutuhan Tahunan (D) - Satuan Utama (kg/rak/botol)</label>
                <input type="number" name="kebutuhan_tahunan_d" value="{{ $eoq->kebutuhan_tahunan_d }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-3 border bg-gray-50" required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Biaya Pesan (S) per pesanan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" name="biaya_pesan_s" value="{{ $eoq->biaya_pesan_s }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition pl-12 pr-4 py-3 border bg-gray-50" required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Biaya Simpan (H) per satuan utama / tahun</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" name="biaya_simpan_h" value="{{ $eoq->biaya_simpan_h }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition pl-12 pr-4 py-3 border bg-gray-50" required>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Catatan: Hasil EOQ, ROP, dan Total Biaya akan dihitung ulang secara otomatis setelah data ini disimpan.
                    </p>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-blue-500/30 transition duration-300 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Perbarui & Hitung Ulang
            </button>
        </div>
    </form>
</div>
@endsection
