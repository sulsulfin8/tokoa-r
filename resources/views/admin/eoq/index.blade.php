@extends('layouts.app')
@section('title', 'Perhitungan EOQ & ROP')
@section('header', 'Perhitungan EOQ, Safety Stock, & ROP')

@section('content')
<div class="mb-6 bg-pink-50 border-l-4 border-pink-500 p-4 rounded-r-xl shadow-sm">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-pink-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-bold text-pink-800">Informasi</h3>
            <div class="mt-1 text-sm text-pink-700 leading-relaxed">
                <p>Halaman perhitungan EOQ digunakan untuk mengetahui berapa jumlah bahan baku yang sebaiknya dipesan agar lebih hemat dan tidak sering kehabisan stok. Di halaman ini akan ditampilkan hasil perhitungan seperti jumlah pembelian yang disarankan, berapa kali harus memesan, dan perkiraan total biaya persediaan.</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Form Hitung EOQ -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <span class="bg-pink-100 text-pink-600 p-2 rounded-lg mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </span>
            Kalkulator EOQ
        </h3>
        <form action="{{ route('eoq.hitung') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Bahan Baku</label>
                <select name="bahan_baku_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition px-4 py-2 border bg-gray-50" required>
                    <option value="">-- Pilih --</option>
                    @foreach($bahanBakus as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bahan }} (Satuan: {{ $b->satuan }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Periode (Contoh: Tahun 2026)</label>
                <input type="text" name="periode" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition px-4 py-2 border bg-gray-50" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kebutuhan Tahunan (D) - Satuan Utama (kg/rak/botol)</label>
                <input type="number" name="kebutuhan_tahunan_d" placeholder="Total kebutuhan dalam 1 tahun" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition px-4 py-2 border bg-gray-50" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Pesan (S) per pesanan (Rp)</label>
                <input type="number" name="biaya_pesan_s" placeholder="Biaya setiap kali memesan" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition px-4 py-2 border bg-gray-50" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Simpan (H) per satuan utama / tahun (Rp)</label>
                <input type="number" name="biaya_simpan_h" placeholder="Biaya simpan per kg/rak/botol per tahun" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 transition px-4 py-2 border bg-gray-50" required>
            </div>
            
            <button type="submit" class="w-full mt-4 bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-pink-500/30 transition duration-300">
                Hitung & Simpan Analisis
            </button>
        </form>
    </div>

    <!-- Hasil Perhitungan (Sesuai Gambar) -->
    <div class="bg-[#f8fafc] rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col justify-start">
        <h3 class="text-lg font-bold text-gray-800 mb-6 border-b border-gray-200 pb-4">Hasil Perhitungan</h3>
        
        @if($hasilEoq->count() > 0)
            @php 
                $latest = $hasilEoq->first();
                $frek = $latest->eoq > 0 ? ceil($latest->kebutuhan_tahunan_d / $latest->eoq) : 0;
            @endphp
            <div class="mb-6">
                <p class="text-gray-800 font-medium text-lg mb-2">EOQ</p>
                <h4 class="text-4xl font-bold text-gray-900 pb-4 border-b border-gray-200">{{ $latest->eoq }} {{ $latest->bahanBaku->satuan ?? '' }}</h4>
            </div>
            
            <div class="mb-8">
                <p class="text-gray-800 font-medium text-lg mb-2">Frekuensi Pemesanan</p>
                <h4 class="text-3xl font-bold text-gray-900 pb-6 border-b border-gray-200">{{ $frek }} kali/tahun</h4>
            </div>
            
            <p class="text-gray-700 text-[15px] leading-relaxed">
                Jumlah pemesanan optimal<br>
                untuk meminimalkan total biaya<br>
                persediaan.
            </p>
        @else
            <div class="flex flex-col items-center justify-center h-full text-gray-400">
                <p>Belum ada hasil perhitungan.</p>
            </div>
        @endif
    </div>
</div>

<!-- Riwayat Perhitungan -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-800">Riwayat Hasil Perhitungan</h3>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-pink-50 text-pink-800 text-sm tracking-wide border-b border-pink-100">
                        <th class="p-4 font-semibold">Bahan Baku</th>
                        <th class="p-4 font-semibold">Periode</th>
                        <th class="p-4 font-semibold text-center">Jml Pembelian (EOQ)</th>
                        <th class="p-4 font-semibold text-center">Frekuensi Pesan</th>
                        <th class="p-4 font-semibold text-center">Total Biaya</th>
                        <th class="p-4 font-semibold text-center">Safety Stock</th>
                        <th class="p-4 font-semibold text-center">ROP</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($hasilEoq as $hasil)
                    @php
                        $frekuensi_pesan = $hasil->eoq > 0 ? ceil($hasil->kebutuhan_tahunan_d / $hasil->eoq) : 0;
                        $biaya_pesan = $hasil->eoq > 0 ? (($hasil->kebutuhan_tahunan_d / $hasil->eoq) * $hasil->biaya_pesan_s) : 0;
                        $biaya_simpan = $hasil->eoq > 0 ? (($hasil->eoq / 2) * $hasil->biaya_simpan_h) : 0;
                        $harga_bahan = $hasil->bahanBaku->harga ?? 0;
                        $biaya_pembelian = $hasil->kebutuhan_tahunan_d * $harga_bahan;
                        
                        $total_biaya = $biaya_pembelian + $biaya_pesan + $biaya_simpan;
                    @endphp
                    <tr class="hover:bg-pink-50/30 transition">
                        <td class="p-4 font-medium">
                            {{ $hasil->bahanBaku->nama_bahan }}
                            <div class="text-xs text-gray-500 font-normal mt-1">Harga: Rp {{ number_format($hasil->bahanBaku->harga ?? 0, 0, ',', '.') }} / {{ $hasil->bahanBaku->satuan ?? '' }}</div>
                        </td>
                        <td class="p-4">{{ $hasil->periode }}</td>
                        <td class="p-4 text-center font-bold text-blue-600 bg-blue-50/50">{{ $hasil->eoq }} {{ $hasil->bahanBaku->satuan ?? '' }}</td>
                        <td class="p-4 text-center font-semibold">
                            <span title="Dihitung dari: (Kebutuhan Tahunan ÷ EOQ)" class="cursor-help border-b border-dashed border-gray-400 pb-0.5">{{ $frekuensi_pesan }} kali</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="font-semibold text-gray-800 whitespace-nowrap">Rp {{ number_format($total_biaya, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-gray-500 mt-1" title="Total Biaya = (Kebutuhan x Harga) + Biaya Pesan + Biaya Simpan">
                                (Biaya Bahan + Pesan + Simpan)
                            </div>
                        </td>
                        <td class="p-4 text-center font-bold text-green-600 bg-green-50/50">{{ $hasil->safety_stock }} {{ $hasil->bahanBaku->satuan ?? '' }}</td>
                        <td class="p-4 text-center font-bold text-red-600 bg-red-50/50">{{ $hasil->reorder_point }} {{ $hasil->bahanBaku->satuan ?? '' }}</td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('eoq.edit', $hasil->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('eoq.destroy', $hasil->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data perhitungan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500">
                            Belum ada riwayat perhitungan EOQ.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>
@endsection
