@extends('layouts.app')
@section('title', 'Edit Pemasok')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
        <h3 class="text-xl font-bold text-gray-800">Edit Pemasok</h3>
        <a href="{{ route('pemasok.index') }}" class="p-2 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shadow-sm" title="Kembali"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
    </div>
    <form action="{{ route('pemasok.update', $pemasok->id) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemasok</label>
            <input type="text" name="nama_pemasok" value="{{ $pemasok->nama_pemasok }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kontak</label>
            <input type="text" name="no_telp" value="{{ $pemasok->no_telp }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
            <input type="text" name="alamat" value="{{ $pemasok->alamat }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition" required>
        </div>
        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg shadow-sm transition duration-300 mt-4">Simpan Perubahan</button>
    </form>
</div>
@endsection
