@extends('layouts.app')
@section('title', 'Edit Penggunaan Bahan')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Edit Penggunaan Bahan (Manual)</h3>
            <p class="text-xs text-gray-500 mt-1">Sesuaikan tanggal, bahan baku, jumlah pemakaian, atau keterangan.</p>
        </div>
        <a href="{{ route('penggunaan-bahan.index') }}" class="p-2 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shadow-sm" title="Kembali">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
    </div>

    @if($errors->any())
    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200 text-sm">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('penggunaan-bahan.update', $penggunaan->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $penggunaan->tanggal) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Bahan Baku</label>
            <select name="bahan_baku_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
                @foreach($bahanBakus as $b)
                    <option value="{{ $b->id }}" {{ (old('bahan_baku_id', $penggunaan->bahan_baku_id) == $b->id) ? 'selected' : '' }}>
                        {{ $b->nama_bahan }} (Stok saat ini: {{ $b->stok }} {{ $b->satuan }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pemakaian</label>
            <input type="number" step="any" name="jumlah" value="{{ old('jumlah', $penggunaan->jumlah) }}" min="0.01" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
            <p class="text-xs text-gray-400 mt-1">Perubahan jumlah akan otomatis menyesuaikan stok bahan baku.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
            <input type="text" name="keterangan" value="{{ old('keterangan', $penggunaan->keterangan) }}" placeholder="Contoh: Tumpah / Kadaluarsa" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition">
        </div>
        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg shadow-sm transition duration-300 mt-4">Simpan Perubahan</button>
    </form>
</div>
@endsection
