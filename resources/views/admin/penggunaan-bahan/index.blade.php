@extends('layouts.app')
@section('title', 'Penggunaan Bahan Baku')
@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex justify-between items-center flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Riwayat Penggunaan Bahan Baku</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Pantau pemakaian bahan baku otomatis dari pesanan kue reseller maupun pencatatan manual</p>
                </div>
            </div>
        </div>
        <a href="{{ route('penggunaan-bahan.create') }}" class="bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm hover:shadow transition flex items-center text-sm gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Catat Penggunaan Manual
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-2">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Filter Bulan & Tahun -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('penggunaan-bahan.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="w-full sm:w-48">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Pilih Bulan</label>
                <select name="bulan" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-pink-300 focus:border-pink-500 outline-none transition">
                    <option value="">Semua Bulan</option>
                    @php
                        $daftarBulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($daftarBulan as $num => $nama)
                        <option value="{{ $num }}" {{ (string)$bulanDipilih === (string)$num ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-36">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Pilih Tahun</label>
                <select name="tahun" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-pink-300 focus:border-pink-500 outline-none transition">
                    @foreach($tahunList as $thn)
                        <option value="{{ $thn }}" {{ (string)$tahunDipilih === (string)$thn ? 'selected' : '' }}>
                            {{ $thn }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-xl text-sm transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Tampilkan
                </button>
                @if($bulanDipilih != '' || $tahunDipilih != date('Y'))
                <a href="{{ route('penggunaan-bahan.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-sm transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Rekapitulasi Total Penggunaan per Bahan Baku (Periode Terpilih) -->
    <div class="bg-white rounded-2xl shadow-sm border border-pink-100/80 p-6">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-gray-800">Rekap Penggunaan Bahan</h3>
                        <span class="text-xs font-bold text-pink-700 bg-pink-50 px-2.5 py-0.5 rounded-full border border-pink-200">
                            {{ $rekapBulanan->count() }} Bahan
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">
                        Periode: 
                        <span class="font-bold text-pink-600">
                            {{ $bulanDipilih ? ($daftarBulan[(int)$bulanDipilih] ?? '') : 'Semua Bulan' }} {{ $tahunDipilih }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- View Switcher: Card Grid vs Chart -->
            @if($rekapBulanan->count() > 0)
            <div class="inline-flex p-1 bg-gray-100 rounded-xl text-xs font-semibold">
                <button type="button" onclick="setViewRekap('cards')" id="btn-view-cards" class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5 bg-white text-gray-800 shadow-xs font-bold">
                    <svg class="w-3.5 h-3.5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Kartu Rekap</span>
                </button>
                <button type="button" onclick="setViewRekap('chart')" id="btn-view-chart" class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5 text-gray-500 hover:text-gray-800">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Grafik Batang</span>
                </button>
            </div>
            @endif
        </div>

        @if($rekapBulanan->count() > 0)
        <!-- VIEW 1: KARTU REKAP (CARDS) -->
        <div id="container-view-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($rekapBulanan as $rekap)
            @php
                $totSemua = $rekap['total_semua'] > 0 ? $rekap['total_semua'] : 1;
                $pctReseller = round(($rekap['total_reseller'] / $totSemua) * 100);
                $pctManual = 100 - $pctReseller;
            @endphp
            <div class="relative bg-white rounded-2xl border border-gray-100 hover:border-pink-200 p-5 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Header Card: Icon, Nama, Frekuensi -->
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider truncate" title="{{ $rekap['nama_bahan'] }}">
                                    {{ $rekap['nama_bahan'] }}
                                </h4>
                            </div>
                        </div>
                        <span class="inline-flex items-center text-[11px] font-semibold text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full border border-gray-100 shrink-0">
                            {{ $rekap['frekuensi'] }}x pakai
                        </span>
                    </div>

                    <!-- Metric Utama: Total Pemakaian -->
                    <div class="my-2">
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl font-black text-gray-900 tracking-tight">
                                {{ (floor($rekap['total_semua']) == $rekap['total_semua']) ? number_format($rekap['total_semua'], 0, ',', '.') : number_format($rekap['total_semua'], 2, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-pink-600 bg-pink-50 px-2 py-0.5 rounded-md border border-pink-100">
                                {{ $rekap['satuan'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Mini Visual Progress Bar (Reseller vs Manual) -->
                    <div class="mt-3 mb-3">
                        <div class="flex justify-between items-center text-[10px] text-gray-400 font-semibold mb-1">
                            <span>Reseller ({{ $pctReseller }}%)</span>
                            <span>Manual ({{ $pctManual }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden flex">
                            <div style="width: {{ $pctReseller }}%" class="bg-gradient-to-r from-pink-500 to-rose-500 h-full transition-all duration-500" title="Pesanan Reseller: {{ $pctReseller }}%"></div>
                            <div style="width: {{ $pctManual }}%" class="bg-amber-400 h-full transition-all duration-500" title="Manual: {{ $pctManual }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Breakdown Footer Badges -->
                <div class="pt-3 border-t border-gray-100/80 flex flex-col gap-1.5 text-xs">
                    <div class="flex items-center justify-between text-gray-600 bg-pink-50/50 px-2.5 py-1 rounded-lg">
                        <span class="inline-flex items-center gap-1.5 text-pink-700 font-semibold text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                            🛒 Reseller
                        </span>
                        <span class="font-bold text-gray-800 text-[11px]">
                            {{ (floor($rekap['total_reseller']) == $rekap['total_reseller']) ? number_format($rekap['total_reseller'], 0, ',', '.') : number_format($rekap['total_reseller'], 2, ',', '.') }} {{ $rekap['satuan'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-gray-600 bg-amber-50/50 px-2.5 py-1 rounded-lg">
                        <span class="inline-flex items-center gap-1.5 text-amber-700 font-semibold text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            ✍️ Manual
                        </span>
                        <span class="font-bold text-gray-800 text-[11px]">
                            {{ (floor($rekap['total_manual']) == $rekap['total_manual']) ? number_format($rekap['total_manual'], 0, ',', '.') : number_format($rekap['total_manual'], 2, ',', '.') }} {{ $rekap['satuan'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- VIEW 2: GRAFIK PENGGUNAAN (CHART) -->
        <div id="container-view-chart" class="hidden">
            <div class="p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Perbandingan Sumber Pemakaian Bahan Baku</p>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-pink-600">
                            <span class="w-2.5 h-2.5 rounded-sm bg-pink-500"></span> Pesanan Reseller
                        </span>
                        <span class="inline-flex items-center gap-1 font-semibold text-amber-600">
                            <span class="w-2.5 h-2.5 rounded-sm bg-amber-400"></span> Catat Manual
                        </span>
                    </div>
                </div>
                <div class="relative w-full h-72">
                    <canvas id="rekapBahanChart"></canvas>
                </div>
            </div>
        </div>
        @else
        <div class="py-10 text-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
            <div class="w-10 h-10 mx-auto mb-2 bg-pink-50 rounded-full flex items-center justify-center text-pink-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <p class="text-sm font-bold text-gray-600">Belum ada pemakaian bahan baku pada periode ini.</p>
            <p class="text-xs text-gray-400 mt-1">Pemakaian akan terdata otomatis saat ada pesanan reseller yang disetujui atau saat admin mencatat manual.</p>
        </div>
        @endif
    </div>

    <!-- Tab Filter Switcher -->
    <div class="flex items-center gap-2 border-b border-gray-200 pb-3 flex-wrap">
        <button type="button" onclick="setTab('semua')" id="tab-btn-semua" class="tab-btn px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 bg-pink-600 text-white shadow-sm">
            <span>Semua Riwayat</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-white/20 text-white">{{ count($riwayatReseller) + $penggunaanManuals->count() }}</span>
        </button>
        <button type="button" onclick="setTab('reseller')" id="tab-btn-reseller" class="tab-btn px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <span>🛍️ Pemotongan Pesanan Reseller</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-gray-200 text-gray-700">{{ count($riwayatReseller) }}</span>
        </button>
        <button type="button" onclick="setTab('manual')" id="tab-btn-manual" class="tab-btn px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <span>✍️ Penggunaan Manual</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-gray-200 text-gray-700">{{ $penggunaanManuals->count() }}</span>
        </button>
    </div>

    <!-- SECTION 1: PEMOTONGAN BAHAN BAKU DARI PESANAN RESELLER (OTOMATIS) -->
    <div id="section-reseller" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-pink-600"></span>
                    <h3 class="text-lg font-bold text-gray-800">Pemotongan Bahan Baku dari Pesanan Reseller (Otomatis)</h3>
                </div>
                <p class="text-xs text-gray-500 mt-0.5 ml-4.5">Bahan baku yang otomatis terpotong berdasarkan resep kue saat pesanan reseller diproses/disetujui</p>
            </div>
            <span class="text-xs font-bold text-pink-700 bg-pink-50 px-3 py-1.5 rounded-full border border-pink-200">
                Total: {{ count($riwayatReseller) }} Pesanan Reseller
            </span>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200/80">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/90 text-gray-600 text-xs font-bold uppercase tracking-wider border-b border-gray-200">
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">No Faktur</th>
                        <th class="px-4 py-3.5">Reseller</th>
                        <th class="px-4 py-3.5 min-w-[220px]">Kue yang Dipesan</th>
                        <th class="px-4 py-3.5 min-w-[280px]">Bahan Baku Terpotong (Resep)</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    @forelse($riwayatReseller as $index => $item)
                    <tr class="hover:bg-pink-50/20 transition">
                        <td class="px-4 py-4 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-800 text-sm">
                                {{ \Carbon\Carbon::parse($item['sale']->tanggal)->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-[11px] text-gray-400">
                                {{ \Carbon\Carbon::parse($item['sale']->tanggal)->translatedFormat('l') }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-pink-50 text-pink-700 border border-pink-200/80">
                                {{ $item['sale']->no_penjualan ?? ('#INV-'.$item['sale']->id) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-700 font-bold flex items-center justify-center text-xs shrink-0">
                                    {{ strtoupper(substr($item['sale']->user->name ?? 'R', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-gray-900 text-sm leading-tight truncate">
                                        {{ $item['sale']->user->name ?? 'Reseller' }}
                                    </div>
                                    <div class="text-[11px] text-gray-400 leading-tight">
                                        {{ $item['sale']->user->email ?? 'Reseller' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="space-y-1.5 max-w-xs">
                                @foreach($item['kue_list'] as $kue)
                                <div class="flex items-center justify-between gap-2 text-xs bg-gray-50 border border-gray-100 px-2.5 py-1 rounded-lg">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500 shrink-0"></span>
                                        <span class="font-semibold text-gray-800 truncate">{{ $kue['nama_produk'] }}</span>
                                    </div>
                                    <div class="text-gray-500 text-[11px] font-medium shrink-0 bg-white px-1.5 py-0.5 rounded border border-gray-100">
                                        {{ $kue['qty'] }} pcs &bull; {{ $kue['batches'] }} batch
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-md">
                                @forelse($item['bahan_dipakai'] as $b)
                                <div class="inline-flex items-center gap-1.5 bg-rose-50/80 hover:bg-rose-50 border border-rose-200/80 px-2.5 py-1 rounded-lg text-xs" title="{{ $b['nama_bahan'] }}">
                                    <span class="font-medium text-gray-700">{{ $b['nama_bahan'] }}:</span>
                                    <span class="font-black text-rose-600">
                                        -{{ (floor($b['jumlah']) == $b['jumlah']) ? number_format($b['jumlah'], 0, ',', '.') : number_format($b['jumlah'], 2, ',', '.') }} {{ $b['satuan'] }}
                                    </span>
                                </div>
                                @empty
                                <span class="text-xs text-gray-400 italic">Tidak ada resep terhubung</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Terpotong Otomatis
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                            <div class="w-10 h-10 mx-auto mb-2 bg-pink-50 rounded-full flex items-center justify-center text-pink-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="font-bold text-gray-500">Belum ada pesanan reseller pada periode ini.</p>
                            <p class="text-xs text-gray-400 mt-0.5">Pemotongan bahan baku akan otomatis tercatat saat pesanan reseller disetujui oleh admin.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 2: RIWAYAT PENGGUNAAN MANUAL -->
    <div id="section-manual" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Penggunaan Manual</h3>
                </div>
                <p class="text-xs text-gray-500 mt-0.5 ml-4.5">Catatan pemakaian bahan baku yang diinput langsung oleh admin</p>
            </div>
            <span class="text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200">
                Total: {{ $penggunaanManuals->count() }} Catatan Manual
            </span>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200/80">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/90 text-gray-600 text-xs font-bold uppercase tracking-wider border-b border-gray-200">
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3.5">Nama Bahan Baku</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Jumlah Dipakai</th>
                        <th class="px-4 py-3.5">Keterangan</th>
                        <th class="px-4 py-3.5 text-center w-28 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    @forelse($penggunaanManuals as $index => $m)
                    <tr class="hover:bg-amber-50/20 transition">
                        <td class="px-4 py-4 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-800 text-sm">
                                {{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-[11px] text-gray-400">
                                {{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat('l') }}
                            </div>
                        </td>
                        <td class="px-4 py-4 font-bold text-gray-900">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <span>{{ $m->bahanBaku->nama_bahan ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-rose-50 text-rose-600 border border-rose-200/80">
                                -{{ (floor($m->jumlah) == $m->jumlah) ? number_format($m->jumlah, 0, ',', '.') : number_format($m->jumlah, 2, ',', '.') }} {{ $m->bahanBaku->satuan ?? '' }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/70">
                                    Manual
                                </span>
                                <span class="text-xs text-gray-600">{{ $m->keterangan ?: 'Tanpa keterangan' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('penggunaan-bahan.edit', $m->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition shadow-xs" title="Edit Data">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('penggunaan-bahan.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan penggunaan ini? Stok bahan baku akan dikembalikan.');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition shadow-xs" title="Hapus Data">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                            <div class="w-10 h-10 mx-auto mb-2 bg-amber-50 rounded-full flex items-center justify-center text-amber-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <p class="font-bold text-gray-500">Tidak ada catatan penggunaan manual pada periode ini.</p>
                            <a href="{{ route('penggunaan-bahan.create') }}" class="inline-flex items-center gap-1.5 text-xs text-pink-600 font-bold hover:underline mt-2">
                                + Catat Penggunaan Manual Baru
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Tab Filter Switcher (Semua, Reseller, Manual)
    function setTab(tab) {
        const secReseller = document.getElementById('section-reseller');
        const secManual = document.getElementById('section-manual');
        const btnSemua = document.getElementById('tab-btn-semua');
        const btnReseller = document.getElementById('tab-btn-reseller');
        const btnManual = document.getElementById('tab-btn-manual');

        const activeClass = 'tab-btn px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 bg-pink-600 text-white shadow-sm';
        const inactiveClass = 'tab-btn px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200';

        btnSemua.className = inactiveClass;
        btnReseller.className = inactiveClass;
        btnManual.className = inactiveClass;

        if (tab === 'semua') {
            secReseller.style.display = 'block';
            secManual.style.display = 'block';
            btnSemua.className = activeClass;
        } else if (tab === 'reseller') {
            secReseller.style.display = 'block';
            secManual.style.display = 'none';
            btnReseller.className = activeClass;
        } else if (tab === 'manual') {
            secReseller.style.display = 'none';
            secManual.style.display = 'block';
            btnManual.className = activeClass;
        }
    }

    // View Switcher: Cards vs Chart
    let chartInstance = null;
    function setViewRekap(mode) {
        const viewCards = document.getElementById('container-view-cards');
        const viewChart = document.getElementById('container-view-chart');
        const btnCards = document.getElementById('btn-view-cards');
        const btnChart = document.getElementById('btn-view-chart');

        if (!viewCards || !viewChart) return;

        const activeBtn = 'px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5 bg-white text-gray-800 shadow-xs font-bold';
        const inactiveBtn = 'px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5 text-gray-500 hover:text-gray-800';

        if (mode === 'cards') {
            viewCards.classList.remove('hidden');
            viewChart.classList.add('hidden');
            btnCards.className = activeBtn;
            btnChart.className = inactiveBtn;
        } else {
            viewCards.classList.add('hidden');
            viewChart.classList.remove('hidden');
            btnCards.className = inactiveBtn;
            btnChart.className = activeBtn;

            initChart();
        }
    }

    function initChart() {
        if (chartInstance) return;
        const ctx = document.getElementById('rekapBahanChart');
        if (!ctx) return;

        const rekapData = @json($rekapBulanan->values());
        const labels = rekapData.map(item => item.nama_bahan);
        const dataReseller = rekapData.map(item => Number(item.total_reseller));
        const dataManual = rekapData.map(item => Number(item.total_manual));

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pesanan Reseller',
                        data: dataReseller,
                        backgroundColor: '#ec4899',
                        borderRadius: 6,
                    },
                    {
                        label: 'Input Manual',
                        data: dataManual,
                        backgroundColor: '#fbbf24',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const idx = context.dataIndex;
                                const satuan = rekapData[idx] ? rekapData[idx].satuan : '';
                                return context.dataset.label + ': ' + context.parsed.y + ' ' + satuan;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Outfit', sans-serif",
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: {
                                family: "'Outfit', sans-serif",
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endsection
