@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="space-y-8" id="printableArea">
    
    <!-- Filter Laporan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Filter Laporan</h2>
            <p class="text-sm text-gray-500 mt-1">Pilih jenis laporan yang ingin Anda lihat.</p>
        </div>
        <div class="w-full md:w-64">
            <select id="report-filter" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition font-semibold text-gray-700 bg-gray-50 cursor-pointer">
                <option value="bahan-baku">Laporan Stok Bahan Baku</option>
                <option value="stok-masuk">Laporan Stok Masuk</option>
                <option value="penjualan">Laporan Penjualan</option>
            </select>
        </div>
    </div>

    <!-- Laporan Stok Bahan Baku -->
    <div id="report-bahan-baku" class="report-section bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-pink-50/30 flex justify-between items-center flex-wrap gap-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 text-pink-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan Stok Bahan Baku
            </h3>
            <div class="flex gap-2">
                <a href="{{ route('laporan.download', ['jenis' => 'bahan_baku', 'format' => 'print']) }}" target="_blank" class="text-sm bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Lihat Laporan
                </a>
                <a href="{{ route('laporan.download', ['jenis' => 'bahan_baku', 'format' => 'excel']) }}" class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Excel
                </a>
                <a href="{{ route('laporan.download', ['jenis' => 'bahan_baku', 'format' => 'word']) }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Word
                </a>
            </div>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="px-8 py-4 font-semibold">Nama Bahan Baku</th>
                        <th class="px-8 py-4 font-semibold text-center">Stok Tersedia</th>
                        <th class="px-8 py-4 font-semibold text-center">Safety Stock (Target)</th>
                        <th class="px-8 py-4 font-semibold text-center">Reorder Point</th>
                        <th class="px-8 py-4 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @foreach($bahanBakus as $b)
                    @php
                        $lastEoq = App\Models\HasilEoq::where('bahan_baku_id', $b->id)->latest()->first();
                        $isSafe = true;
                        if($lastEoq && $b->stok <= $lastEoq->reorder_point) $isSafe = false;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-8 py-4 font-bold text-gray-900">{{ $b->nama_bahan }}</td>
                        <td class="px-8 py-4 text-center font-semibold {{ !$isSafe ? 'text-red-500' : 'text-green-600' }}">{{ $b->stok }} {{ $b->satuan }}</td>
                        <td class="px-8 py-4 text-center">{{ $lastEoq ? $lastEoq->safety_stock . ' ' . $b->satuan : '-' }}</td>
                        <td class="px-8 py-4 text-center">{{ $lastEoq ? $lastEoq->reorder_point . ' ' . $b->satuan : '-' }}</td>
                        <td class="px-8 py-4 text-center">
                            @if($isSafe)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Aman</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Kritis / ROP</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Laporan Stok Masuk -->
    <div id="report-stok-masuk" class="report-section hidden bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-blue-50/30 flex justify-between items-center flex-wrap gap-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Laporan Stok Masuk
            </h3>
            <div class="flex gap-2">
                <a href="{{ route('laporan.download', ['jenis' => 'stok_masuk', 'format' => 'print']) }}" target="_blank" class="text-sm bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Lihat Laporan
                </a>
                <a href="{{ route('laporan.download', ['jenis' => 'stok_masuk', 'format' => 'excel']) }}" class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Excel
                </a>
                <a href="{{ route('laporan.download', ['jenis' => 'stok_masuk', 'format' => 'word']) }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Word
                </a>
            </div>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="px-8 py-4 font-semibold">Tanggal</th>
                        <th class="px-8 py-4 font-semibold">Bahan Baku</th>
                        <th class="px-8 py-4 font-semibold">Pemasok</th>
                        <th class="px-8 py-4 font-semibold text-center">Jumlah Masuk</th>
                        <th class="px-8 py-4 font-semibold text-right">Total Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($stokMasuks as $sm)
                    <tr class="hover:bg-gray-50">
                        <td class="px-8 py-4">{{ $sm->tanggal }}</td>
                        <td class="px-8 py-4 font-bold text-gray-900">{{ $sm->bahanBaku->nama_bahan ?? '-' }}</td>
                        <td class="px-8 py-4">{{ $sm->pemasok->nama_pemasok ?? '-' }}</td>
                        <td class="px-8 py-4 text-center font-semibold text-blue-600">{{ $sm->jumlah }} {{ $sm->bahanBaku->satuan ?? '' }}</td>
                        <td class="px-8 py-4 text-right">Rp {{ number_format($sm->total_biaya, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-8 text-center text-gray-500">Belum ada data stok masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Laporan Penjualan -->
    <div id="report-penjualan" class="report-section hidden bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-green-50/30 flex justify-between items-center flex-wrap gap-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Laporan Penjualan
            </h3>
            <div class="flex gap-2">
                <a href="{{ route('laporan.download', ['jenis' => 'penjualan', 'format' => 'print']) }}" target="_blank" class="text-sm bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Lihat Laporan
                </a>
                <a href="{{ route('laporan.download', ['jenis' => 'penjualan', 'format' => 'excel']) }}" class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Excel
                </a>
                <a href="{{ route('laporan.download', ['jenis' => 'penjualan', 'format' => 'word']) }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Word
                </a>
            </div>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="px-8 py-4 font-semibold">No Penjualan</th>
                        <th class="px-8 py-4 font-semibold">Tanggal</th>
                        <th class="px-8 py-4 font-semibold">Rincian Pembelian</th>
                        <th class="px-8 py-4 font-semibold text-right">Total Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($penjualans as $trx)
                    <tr class="hover:bg-gray-50">
                        <td class="px-8 py-4 font-bold text-gray-900">{{ $trx->no_penjualan }}</td>
                        <td class="px-8 py-4">{{ $trx->tanggal }}</td>
                        <td class="px-8 py-4">
                            <ul class="list-disc pl-4 text-sm text-gray-600">
                                @foreach($trx->detailPenjualans as $detail)
                                    <li>{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }} (x{{ $detail->qty }}) = Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-8 py-4 text-right font-bold text-green-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-8 text-center text-gray-500">Belum ada transaksi penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Hanya sembunyikan semua saat awal load, kecuali bahan-baku
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.report-section').forEach(function(section) {
            section.classList.add('hidden');
        });
        document.getElementById('report-bahan-baku').classList.remove('hidden');
    });

    document.getElementById('report-filter').addEventListener('change', function() {
        // Sembunyikan semua section
        document.querySelectorAll('.report-section').forEach(function(section) {
            section.classList.add('hidden');
        });
        
        // Tampilkan section yang dipilih
        const selected = this.value;
        if(selected === 'bahan-baku') {
            document.getElementById('report-bahan-baku').classList.remove('hidden');
        } else if(selected === 'stok-masuk') {
            document.getElementById('report-stok-masuk').classList.remove('hidden');
        } else if(selected === 'penjualan') {
            document.getElementById('report-penjualan').classList.remove('hidden');
        }
    });
</script>
@endsection
