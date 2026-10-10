@extends('layouts.app')
@section('title', 'Data Bahan Baku')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Data Bahan Baku</h2>
        <a href="{{ route('bahan-baku.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Data
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-pink-50 text-pink-800 text-sm tracking-wide border-b border-pink-100">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold">Nama Bahan</th>
                    <th class="px-6 py-4 font-semibold text-center">Stok</th>
                    <th class="px-6 py-4 font-semibold text-center">Harga / Satuan</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @foreach($bahanBakus as $index => $b)
                <tr class="hover:bg-pink-50/30 transition border-b border-gray-100 last:border-b-0">
                    <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-gray-800 font-medium">{{ $b->nama_bahan }}</td>
                    <td class="px-6 py-4 text-center font-semibold">
                        @if($b->stok <= 0)
                            <span class="text-red-500">Habis</span>
                        @else
                            {{ $b->stok }} {{ $b->satuan }}
                            @if($b->satuan_kecil)
                                <div class="text-xs text-gray-500 font-normal">(= {{ number_format($b->stok * ($b->nilai_konversi ?? 1), 0, ',', '.') }} {{ $b->satuan_kecil }})</div>
                            @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center items-center gap-3">
                            <!-- Edit Button (Light Blue) -->
                            <a href="{{ route('bahan-baku.edit', $b->id) }}" class="w-9 h-9 rounded-full bg-[#e8f0fe] text-[#1967d2] hover:bg-blue-200 flex items-center justify-center transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <!-- Delete Button (Light Red) -->
                            <form action="{{ route('bahan-baku.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Yakin hapus bahan baku ini?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-full bg-[#fce8e6] text-[#d93025] hover:bg-red-200 flex items-center justify-center transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($bahanBakus->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data bahan baku.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination Mockup (Matching the image) -->
    <div class="mt-6 flex justify-end">
        <div class="flex items-center gap-1.5">
            <button class="w-9 h-9 flex items-center justify-center rounded-md border border-gray-200 text-gray-500 bg-white hover:bg-gray-50 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button class="w-9 h-9 flex items-center justify-center rounded-md border border-pink-600 bg-pink-600 text-white font-medium text-sm transition shadow-sm shadow-pink-500/30">1</button>
            <button class="w-9 h-9 flex items-center justify-center rounded-md border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 font-medium text-sm transition">2</button>
            <button class="w-9 h-9 flex items-center justify-center rounded-md border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 font-medium text-sm transition">3</button>
            <button class="w-9 h-9 flex items-center justify-center rounded-md border border-gray-200 text-gray-500 bg-white hover:bg-gray-50 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>
</div>
@endsection
