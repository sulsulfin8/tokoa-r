@extends('layouts.app')
@section('title', 'Edit Bahan Baku')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 w-full max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
        <h3 class="text-xl font-bold text-gray-800">Edit Data Bahan Baku</h3>
        <a href="{{ route('bahan-baku.index') }}" class="p-2 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shadow-sm" title="Kembali"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
    </div>

    <!-- Panduan Standar Satuan Roti & Kue -->
    <div class="bg-gradient-to-r from-pink-50 to-purple-50/50 border border-pink-200 rounded-2xl p-4 text-xs text-pink-900 mb-6 space-y-1.5 shadow-sm">
        <div class="font-bold text-pink-800 flex items-center gap-1.5 text-sm">
            <svg class="w-4 h-4 text-pink-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Panduan Satuan Pembuatan Kue & Roti (A&R Bakery):</span>
        </div>
        <ul class="list-disc pl-5 text-gray-700 space-y-1">
            <li><strong>Bahan Berat / Timbang (Terigu, Gula, Margarin):</strong> Satuan Utama = <span class="font-semibold text-pink-700">kg</span>, Satuan Resep = <span class="font-semibold text-pink-700">gram</span> (Nilai Konversi = <strong>1000</strong>).</li>
            <li><strong>Telur:</strong> Satuan Utama = <span class="font-semibold text-pink-700">rak</span>, Satuan Resep = <span class="font-semibold text-pink-700">butir</span> (Nilai Konversi = <strong>30</strong>).</li>
            <li><strong>Bahan Cair / Pewarna:</strong> Satuan Utama = <span class="font-semibold text-pink-700">botol</span>, Satuan Resep = <span class="font-semibold text-pink-700">mili</span> (Nilai Konversi = misal <strong>30</strong> atau <strong>100</strong>).</li>
        </ul>
    </div>

    <form action="{{ route('bahan-baku.update', $bahanBaku->id) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan Baku</label>
            <input type="text" name="nama_bahan" value="{{ $bahanBaku->nama_bahan }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-gray-50 focus:bg-white text-sm" required>
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan Utama / Stok (Contoh: kg, rak, botol)</label>
                <input type="text" name="satuan" value="{{ $bahanBaku->satuan }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-gray-50 focus:bg-white text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Satuan Utama (Rp)</label>
                <input type="text" name="harga" value="{{ number_format($bahanBaku->harga, 0, ',', '.') }}" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value) this.value = parseInt(this.value, 10).toLocaleString('id-ID');" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-gray-50 focus:bg-white text-sm" required>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan Resep (Contoh: gram, butir, mili)</label>
                <input type="text" name="satuan_kecil" value="{{ $bahanBaku->satuan_kecil }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-gray-50 focus:bg-white text-sm" placeholder="Contoh: gram, butir, mili">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Konversi (1 Satuan = X Satuan Resep)</label>
                <input type="number" name="nilai_konversi" value="{{ $bahanBaku->nilai_konversi ?? 1000 }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-gray-50 focus:bg-white text-sm" placeholder="Contoh: 1000 (1 kg = 1000 gram), 30 (1 rak = 30 butir)">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Tersedia</label>
                <input type="number" step="any" name="stok" value="{{ $bahanBaku->stok }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-gray-50 focus:bg-white text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lead Time (Hari)</label>
                <input type="number" name="lead_time" value="{{ $bahanBaku->lead_time }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-gray-50 focus:bg-white text-sm" required>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-5 bg-pink-50 p-4 rounded-xl border border-pink-100">
            <div>
                <label class="block text-sm font-medium text-pink-800 mb-1">Pemakaian Max / Hari</label>
                <input type="number" step="any" name="pemakaian_max" value="{{ $bahanBaku->pemakaian_max }}" class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:border-pink-500 outline-none transition bg-white text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-pink-800 mb-1">Pemakaian Rata-rata / Hari</label>
                <input type="number" step="any" name="pemakaian_rata" value="{{ $bahanBaku->pemakaian_rata }}" class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:border-pink-500 outline-none transition bg-white text-sm" required>
            </div>
            <div class="col-span-2 text-xs text-pink-600">
                *Data pemakaian ini dalam satuan utama (kg/rak/botol per hari), digunakan untuk menghitung Safety Stock dan Reorder Point secara akurat pada fitur EOQ.
            </div>
        </div>
        
        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-pink-500/30 transition duration-300 mt-4">
            Perbarui Data Bahan Baku
        </button>
    </form>
</div>
@endsection
