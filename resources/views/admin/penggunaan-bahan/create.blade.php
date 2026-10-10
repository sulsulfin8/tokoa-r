@extends('layouts.app')
@section('title', 'Catat Penggunaan Bahan')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Catat Penggunaan Bahan (Manual)</h3>
            <p class="text-xs text-gray-500 mt-1">Gunakan form ini untuk mencatat bahan terbuang/kadaluarsa.</p>
        </div>
        <a href="{{ route('penggunaan-bahan.index') }}" class="p-2 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shadow-sm" title="Kembali"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
    </div>
    <form action="{{ route('penggunaan-bahan.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Bahan Baku</label>
            <select name="bahan_baku_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
                @foreach($bahanBakus as $b) <option value="{{ $b->id }}">{{ $b->nama_bahan }} (Sisa: {{ $b->stok }} {{ $b->satuan }})</option> @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pemakaian</label>
            <input type="number" name="jumlah" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
            <input type="text" name="keterangan" placeholder="Contoh: Tumpah / Kadaluarsa" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition">
        </div>
        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg shadow-sm transition duration-300 mt-4">Simpan & Kurangi Stok</button>
    </form>
</div>
@endsection
