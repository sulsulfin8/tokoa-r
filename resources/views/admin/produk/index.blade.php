@extends('layouts.app')
@section('title', 'Data Produk')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Produk</h2>
        @if(auth()->check() && auth()->user()->role == 'admin')
        <a href="{{ route('produk.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Data
        </a>
        @endif
    </div>
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-pink-50 text-pink-800 text-sm tracking-wide border-b border-pink-100">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold">Nama Produk</th>
                    <th class="px-6 py-4 font-semibold text-center">Stok & Batch</th>
                    <th class="px-6 py-4 font-semibold">Harga Jual</th>
                    <th class="px-6 py-4 font-semibold">Resep (Untuk 5 Pcs)</th>
                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <th class="px-6 py-4 font-semibold text-center w-24">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($produks as $index => $p)
                <tr class="hover:bg-gray-50 transition last:border-b-0">
                    <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($p->foto)
                                <img src="{{ asset('storage/' . $p->foto) }}" alt="{{ $p->nama_produk }}" class="w-12 h-12 object-cover rounded-md shadow-sm">
                            @else
                                <div class="w-12 h-12 bg-pink-50 rounded-md flex items-center justify-center text-pink-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <span class="font-bold text-gray-800">{{ $p->nama_produk }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-sm font-bold text-gray-800">Stok: <span class="stok-produk">{{ $p->stok }}</span></div>
                        <div class="text-xs text-pink-600 bg-pink-50 rounded px-2 py-1 mt-1 inline-block">1 Resep = <span class="batch-size">{{ $p->batch_size ?? 1 }}</span> Pcs</div>
                    </td>
                    <td class="px-6 py-4 font-semibold text-pink-600">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <ul class="list-disc pl-4 text-xs text-gray-500">
                            @foreach($p->reseps as $resep)
                                @php
                                    $bahan = $resep->bahanBaku;
                                    $teks = '';
                                    if($bahan->satuan_kecil) {
                                        $konv = $bahan->nilai_konversi ?? 1;
                                        $bsr = floor($resep->jumlah_dibutuhkan / $konv);
                                        $kcl = $resep->jumlah_dibutuhkan % $konv;
                                        if($bsr > 0) $teks .= $bsr . ' ' . $bahan->satuan . ' ';
                                        if($kcl > 0) $teks .= $kcl . ' ' . $bahan->satuan_kecil;
                                        if($teks == '') $teks = '0 ' . $bahan->satuan;
                                    } else {
                                        $teks = $resep->jumlah_dibutuhkan . ' ' . $bahan->satuan;
                                    }
                                @endphp
                                <li class="resep-item mb-1 text-gray-600" 
                                    data-nama="{{ $bahan->nama_bahan }}" 
                                    data-kebutuhan="{{ $resep->jumlah_dibutuhkan }}" 
                                    data-konv="{{ $bahan->nilai_konversi ?? 1 }}" 
                                    data-satuan="{{ $bahan->satuan }}" 
                                    data-satuankecil="{{ $bahan->satuan_kecil }}">
                                    <span class="font-semibold text-gray-700">{{ $bahan->nama_bahan }}</span> 
                                    (<span class="resep-text">{{ trim($teks) }}</span>)
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center items-center gap-3">
                            <a href="{{ route('produk.edit', $p->id) }}" class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-full bg-[#fce8e6] text-[#d93025] hover:bg-red-200 flex items-center justify-center transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ (auth()->check() && auth()->user()->role == 'admin') ? 6 : 5 }}" class="px-6 py-8 text-center text-gray-500">Belum ada data produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
