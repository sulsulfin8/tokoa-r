@extends('layouts.app')
@section('title', 'Perencanaan Produksi')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 w-full">
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Perencanaan Produksi (Sistem Pakar / DSS)</h2>
        <p class="text-sm text-gray-500 mt-1">Masukkan target produksi untuk mengecek ketersediaan bahan baku sesuai resep.</p>
    </div>

    <form action="{{ route('perencanaan.hitung') }}" method="POST" class="bg-gradient-to-r from-pink-50 to-pink-100/50 p-6 rounded-xl border border-pink-200 mb-8 shadow-inner">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
            <div>
                <label class="block text-sm font-bold text-pink-900 mb-2">Pilih Produk (Kue/Roti)</label>
                <select name="produk_id" class="w-full px-4 py-3 rounded-lg border border-pink-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-white" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-pink-900 mb-2">Rencana Produksi (Pcs)</label>
                <div class="flex gap-4">
                    <input type="number" name="jumlah_produksi" min="1" placeholder="Misal: 1000" class="flex-1 px-4 py-3 rounded-lg border border-pink-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-white" required>
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold px-6 py-3 rounded-lg shadow-md transition duration-300">Cek Bahan</button>
                </div>
            </div>
        </div>
    </form>

    @if(isset($hasil))
    <div class="mt-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            Analisis Kebutuhan untuk Target <span class="text-pink-600">{{ $jumlah }} pcs</span> {{ $produk->nama_produk }}
        </h3>
        
        @if(isset($info))
        <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mb-6">
            <h4 class="font-bold text-blue-900 mb-2">Skenario Produksi:</h4>
            <ul class="text-sm text-blue-800 list-disc pl-5 space-y-1">
                <li>Stok Produk Saat Ini: <strong>{{ $info['stok'] }} pcs</strong></li>
                @if($info['stok'] >= $jumlah)
                    <li>Target sudah terpenuhi oleh stok yang ada. Tidak perlu produksi baru.</li>
                @else
                    <li>Kekurangan yang harus diproduksi: <strong>{{ $info['butuh'] }} pcs</strong></li>
                    <li>Karena 1 resep menghasilkan <strong>{{ $info['batch_size'] }} pcs</strong>, maka sistem akan membuat <strong>{{ $info['batches'] }} kali resep (Batch)</strong>.</li>
                    <li>Total produksi riil: <strong>{{ $info['produksi_real'] }} pcs</strong> (Sisa {{ $info['produksi_real'] - $info['butuh'] }} pcs akan masuk ke stok).</li>
                @endif
            </ul>
        </div>
        @endif
        
        <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-pink-50 text-pink-800 text-sm tracking-wide border-b border-pink-100">
                        <th class="px-6 py-4 font-bold">Bahan Baku</th>
                        <th class="px-6 py-4 font-bold text-center">Stok Saat Ini</th>
                        <th class="px-6 py-4 font-bold text-center">Kebutuhan Produksi</th>
                        <th class="px-6 py-4 font-bold text-center">Selisih</th>
                        <th class="px-6 py-4 font-bold text-center">Status / Keputusan</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($hasil as $h)
                    <tr class="border-b border-gray-100 last:border-b-0 hover:bg-pink-50/30 transition">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $h['nama_bahan'] }}</td>
                        <td class="px-6 py-4 text-center">{{ $h['stok_tersedia_teks'] }}</td>
                        <td class="px-6 py-4 text-center font-semibold text-blue-600">{{ $h['kebutuhan_teks'] }}</td>
                        
                        @if($h['status'] == 'Kurang')
                            <td class="px-6 py-4 text-center font-bold text-red-500">-{{ $h['selisih_teks'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    Stok Kurang
                                </span>
                                <div class="text-[11px] text-red-500 mt-1">Harus pesan lagi</div>
                            </td>
                        @else
                            <td class="px-6 py-4 text-center font-bold text-green-500">+{{ $h['selisih_teks'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                    Stok Aman
                                </span>
                                <div class="text-[11px] text-green-500 mt-1">Bisa produksi</div>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
