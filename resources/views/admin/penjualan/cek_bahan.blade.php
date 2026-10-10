@extends('layouts.app')
@section('title', 'Cek Bahan Baku Pesanan')
@section('header', 'Pengecekan Ketersediaan Bahan Baku')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-800">Pesanan: {{ $penjualan->no_penjualan }}</h3>
        <a href="{{ route('penjualan.index') }}" class="text-gray-500 hover:text-gray-700 transition">Kembali</a>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <p class="text-sm text-blue-600 font-semibold mb-1">Tanggal</p>
                <p class="font-bold text-gray-800">{{ $penjualan->tanggal }}</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                <p class="text-sm text-purple-600 font-semibold mb-1">Pemesan</p>
                <p class="font-bold text-gray-800">{{ $penjualan->user->name ?? 'Admin' }}</p>
            </div>
            <div class="bg-pink-50 rounded-xl p-4 border border-pink-100">
                <p class="text-sm text-pink-600 font-semibold mb-1">Total Harga</p>
                <p class="font-bold text-gray-800">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                <p class="text-sm text-green-600 font-semibold mb-1">Status</p>
                <p class="font-bold text-gray-800 uppercase">{{ $penjualan->status }}</p>
            </div>
        </div>

        <h4 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">Kebutuhan Bahan Baku</h4>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b">
                        <th class="p-3 font-semibold">Nama Bahan</th>
                        <th class="p-3 font-semibold">Stok Tersedia</th>
                        <th class="p-3 font-semibold">Dibutuhkan</th>
                        <th class="p-3 font-semibold">Status</th>
                        <th class="p-3 font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($hasilCek as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 font-bold text-gray-800">{{ $item['nama_bahan'] }}</td>
                        <td class="p-3">{{ $item['stok_tersedia'] }}</td>
                        <td class="p-3">{{ $item['kebutuhan_teks'] }}</td>
                        <td class="p-3">
                            @if($item['status'] == 'Cukup')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">Cukup</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">Kurang</span>
                            @endif
                        </td>
                        <td class="p-3">
                            @if($item['status'] == 'Kurang')
                                <span class="text-red-600 font-semibold text-sm">Kurang {{ $item['teks_kurang'] }}</span>
                            @else
                                <span class="text-green-600 font-semibold text-sm">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data bahan baku untuk produk ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($penjualan->status == 'pending')
        <div class="mt-6 flex justify-end border-t pt-4">
            @if($semuaCukup)
            <form action="{{ route('penjualan.approve', $penjualan->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Proses Pesanan & Potong Bahan Baku
                </button>
            </form>
            @else
            <div class="text-red-500 italic text-sm font-medium">Bahan baku tidak mencukupi untuk memproses pesanan ini.</div>
            @endif
        </div>
        @endif
        
    </div>
</div>
@endsection
