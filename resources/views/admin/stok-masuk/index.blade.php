@extends('layouts.app')
@section('title', 'Riwayat Stok Masuk')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Riwayat Pembelian & Stok Masuk</h2>
        <a href="{{ route('stok-masuk.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Data
        </a>
    </div>
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-pink-50 text-pink-800 text-sm tracking-wide border-b border-pink-100">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                    <th class="px-6 py-4 font-semibold">Bahan Baku</th>
                    <th class="px-6 py-4 font-semibold">Pemasok</th>
                    <th class="px-6 py-4 font-semibold text-center">Jumlah Stok</th>
                    <th class="px-6 py-4 font-semibold">Total Biaya</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($stokMasuks as $index => $s)
                <tr class="hover:bg-gray-50 transition last:border-b-0">
                    <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">{{ $s->tanggal }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $s->bahanBaku->nama_bahan }}</td>
                    <td class="px-6 py-4">{{ $s->pemasok->nama_pemasok }}</td>
                    <td class="px-6 py-4 text-center font-bold text-green-600">+{{ $s->jumlah }} {{ $s->bahanBaku->satuan ?? '' }}</td>
                    <td class="px-6 py-4 font-semibold text-pink-600">Rp {{ number_format($s->total_biaya, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('stok-masuk.edit', $s->id) }}" class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <form action="{{ route('stok-masuk.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini? Stok bahan baku akan dikembalikan/berkurang.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-full bg-[#fce8e6] text-[#d93025] hover:bg-red-200 flex items-center justify-center transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat stok masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
