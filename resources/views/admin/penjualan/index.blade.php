@extends('layouts.app')
@section('title', 'Data Penjualan')
@section('header', 'Riwayat Transaksi Penjualan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center flex-wrap gap-4">
        <h3 class="text-xl font-bold text-gray-800">Daftar Transaksi</h3>
        @if(auth()->check() && auth()->user()->role != 'admin')
        <a href="{{ route('penjualan.create') }}" class="bg-[#2069c9] hover:bg-[#1a55a6] text-white font-medium py-2.5 px-5 rounded-xl shadow-sm transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Transaksi Baru
        </a>
        @endif
    </div>
    <div class="p-0 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-pink-50 text-pink-800 text-sm tracking-wide border-b border-pink-100">
                    <th class="p-4 font-semibold">No Penjualan</th>
                    <th class="p-4 font-semibold">Tanggal</th>
                    <th class="p-4 font-semibold">Produk Dibeli</th>
                    <th class="p-4 font-semibold text-right">Total Harga</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <th class="p-4 font-semibold text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($penjualans as $trx)
                <tr class="hover:bg-pink-50/30 transition">
                    <td class="p-4 font-bold text-gray-900">{{ $trx->no_penjualan }}</td>
                    <td class="p-4">{{ $trx->tanggal }}</td>
                    <td class="p-4">
                        <div class="flex flex-col gap-3">
                            @foreach($trx->detailPenjualans as $detail)
                                <div class="flex items-center gap-3">
                                    @if($detail->produk && $detail->produk->foto)
                                        <img src="{{ asset('storage/' . $detail->produk->foto) }}" alt="Foto" class="w-10 h-10 rounded-md object-cover border border-gray-200 shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-md bg-pink-50 flex items-center justify-center text-pink-300 border border-pink-100 shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-800">{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                        <span class="text-xs text-gray-500">Jumlah: <span class="font-bold text-gray-700">{{ $detail->qty }}</span></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="p-4 text-right font-bold text-pink-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td class="p-4 text-center">
                        <span class="inline-block px-3 py-1 {{ $trx->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }} rounded-full text-xs font-bold uppercase tracking-wider">
                            {{ $trx->status }}
                        </span>
                    </td>
                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <td class="p-4 text-center">
                        @if($trx->status == 'pending')
                            <a href="{{ route('penjualan.cek_bahan', $trx->id) }}" class="inline-block px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wider border border-blue-100 hover:bg-blue-100 transition">Cek Bahan Baku</a>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        Belum ada transaksi penjualan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
