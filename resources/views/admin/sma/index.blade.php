@extends('layouts.app')
@section('title', 'Prediksi SMA & MAPE')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 w-full mx-auto">
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Prediksi Kebutuhan (Single Moving Average) & Evaluasi MAPE</h2>
        <p class="text-sm text-gray-500 mt-1">Gunakan metode SMA untuk memprediksi kebutuhan bahan baku di periode mendatang dan evaluasi akurasi peramalannya menggunakan metode MAPE (Mean Absolute Percentage Error).</p>
    </div>

    @if(session('error'))
    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200 flex items-center gap-2">
        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200 flex items-center gap-2">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Form Input SMA -->
    <form action="{{ route('sma.hitung') }}" method="POST" class="bg-pink-50 p-6 rounded-xl border border-pink-100 mb-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-pink-900 mb-2">Pilih Bahan Baku</label>
                <select name="bahan_baku_id" class="w-full px-4 py-3 rounded-lg border border-pink-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-white" required>
                    <option value="">-- Pilih Bahan Baku --</option>
                    @foreach($bahanBakus as $b)
                        <option value="{{ $b->id }}" {{ (isset($bahan) && $bahan->id == $b->id) ? 'selected' : '' }}>
                            {{ $b->nama_bahan }} (Satuan: {{ $b->satuan }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-pink-900 mb-2">Periode N (Jumlah Bulan Terakhir)</label>
                <input type="number" name="periode" min="2" max="12" value="{{ $periode ?? 3 }}" placeholder="Contoh: 3 untuk pergerakan 3 bulan" class="w-full px-4 py-3 rounded-lg border border-pink-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition bg-white" required>
                <p class="text-xs text-pink-600/80 mt-1">Rekomendasi umum: 2 atau 3 bulan sesuai ketersediaan riwayat transaksi.</p>
            </div>
        </div>
        <button type="submit" class="mt-6 w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3.5 rounded-lg shadow-md transition duration-300 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            <span>Hitung Prediksi SMA & Evaluasi MAPE</span>
        </button>
    </form>

    @if(isset($prediksi))
    @php
        $isBulat = floor($prediksi) == $prediksi;
        $prediksiFormat = $isBulat ? number_format($prediksi, 0, ',', '.') : number_format($prediksi, 2, ',', '.');
        $totalPenggunaanVal = isset($totalPenggunaan) ? $totalPenggunaan : ($penggunaanBulanan->sum('total') ?? 0);
        $totalPenggunaanFormat = (floor($totalPenggunaanVal) == $totalPenggunaanVal) ? number_format($totalPenggunaanVal, 0, ',', '.') : number_format($totalPenggunaanVal, 2, ',', '.');
    @endphp

    <div class="mt-8 border-t border-gray-200 pt-8 space-y-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-pink-600 bg-pink-100 px-3 py-1 rounded-full">Hasil Perhitungan</span>
            <h3 class="text-2xl font-bold text-gray-800 mt-2">Bahan Baku: <span class="text-pink-600">{{ $bahan->nama_bahan }}</span></h3>
        </div>
        
        <!-- 3 Kartu Ringkasan Hasil -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Kartu 1: Hasil Prediksi SMA -->
            <div class="bg-gradient-to-br from-white to-pink-50/40 border border-pink-200/80 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-pink-600 uppercase tracking-wider">Metode SMA</span>
                        <span class="text-xs bg-pink-100 text-pink-700 px-2.5 py-0.5 rounded-full font-semibold">{{ $periode }} Bulan Terakhir</span>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Prediksi Kebutuhan Bulan Depan:</p>
                    
                    <div class="my-4 flex items-baseline gap-2">
                        <span class="text-5xl font-black text-pink-600 tracking-tight">{{ $prediksiFormat }}</span>
                        <span class="text-xl font-bold text-gray-700">{{ $bahan->satuan }}</span>
                    </div>

                    @if($isBulat)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Tepat {{ $prediksiFormat }} {{ $bahan->satuan }} (Bilangan Bulat)</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Saran Order: {{ ceil($prediksi) }} {{ $bahan->satuan }} (Dibulatkan ke atas)</span>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-pink-100/80 text-xs text-gray-500">
                    <span class="font-semibold text-gray-700">Rincian Hitungan:</span><br>
                    Total Pemakaian ({{ $totalPenggunaanFormat }}) ÷ {{ $periode }} Bulan = <span class="font-bold text-pink-700">{{ $prediksiFormat }} {{ $bahan->satuan }}</span>
                </div>
            </div>

            <!-- Kartu 2: Riwayat Penggunaan Dipakai -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Data Historis</span>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-medium">{{ $penggunaanBulanan->count() }} Periode</span>
                    </div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3">Penggunaan yang Dihitung:</h4>
                    
                    <ul class="space-y-2 text-sm max-h-40 overflow-y-auto pr-1">
                        @foreach($penggunaanBulanan as $data)
                            <li class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                                <span class="text-gray-600 font-medium">Bulan {{ $data->bulan }} - {{ $data->tahun }}</span>
                                <span class="font-bold text-gray-900">{{ (floor($data->total) == $data->total) ? number_format($data->total, 0, ',', '.') : number_format($data->total, 2, ',', '.') }} {{ $bahan->satuan }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                    <span class="text-gray-500 font-medium">Total Akumulasi:</span>
                    <span class="font-bold text-gray-800 text-sm">{{ $totalPenggunaanFormat }} {{ $bahan->satuan }}</span>
                </div>
            </div>

            <!-- Kartu 3: Akurasi Prediksi (MAPE) -->
            <div class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Akurasi Peramalan</span>
                        <span class="text-xs bg-purple-100 text-purple-700 px-2.5 py-0.5 rounded-full font-semibold">Metode MAPE</span>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Nilai Error MAPE:</p>

                    @if(isset($mape) && $mape !== null)
                        <div class="my-4 flex items-baseline gap-2">
                            <span class="text-5xl font-black text-purple-700 tracking-tight">{{ number_format($mape, 2, ',', '.') }}%</span>
                        </div>

                        @php
                            if ($mape < 10) {
                                $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            } elseif ($mape <= 20) {
                                $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                            } elseif ($mape <= 50) {
                                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                            } else {
                                $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                            }
                        @endphp
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold border {{ $badgeClass }}">
                            <span>Kategori: {{ $kategoriMape ?? 'Dievaluasi' }}</span>
                        </div>
                    @else
                        <div class="my-3">
                            <span class="text-3xl font-extrabold text-amber-600">-</span>
                            <div class="mt-1 px-3 py-2 bg-amber-50 rounded-lg border border-amber-200 text-amber-800 text-xs leading-relaxed">
                                <span class="font-bold">Periode uji belum mencukupi.</span><br>
                                Data histori yang ada ({{ $totalHistori ?? $periode }} bulan) sama dengan nilai periode N ({{ $periode }} bulan). MAPE membutuhkan minimal {{ $periode + 1 }} bulan data histori agar peramalan masa lalu dapat dievaluasi terhadap data aktual.
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500">
                    @if(isset($mape) && $mape !== null)
                        <span>Dihitung dari <b>{{ $totalBulanDiuji }} periode uji</b> historis.</span>
                    @else
                        <span class="text-pink-600 font-semibold">💡 Tips: Coba gunakan Periode N = {{ max(2, ($totalHistori ?? $periode) - 1) }} untuk melihat skor MAPE dari riwayat yang ada.</span>
                    @endif
                </div>
            </div>

        </div>

        <!-- Tabel Uji Historis MAPE (Jika ada data evaluasi) -->
        @if(!empty($evaluasiMape) && count($evaluasiMape) > 0)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h4 class="text-lg font-bold text-gray-800">Tabel Evaluasi Akurasi Peramalan (Backtesting MAPE)</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Pengujian hasil prediksi SMA periode N={{ $periode }} terhadap data pemakaian aktual di masa lalu.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-500">Rata-rata MAPE:</span>
                    <span class="ml-1 text-sm font-extrabold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-200">{{ number_format($mape, 2, ',', '.') }}%</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-purple-50 text-purple-900 border-b border-purple-100 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-3.5 px-4 text-center">No</th>
                            <th class="py-3.5 px-4">Periode Bulan</th>
                            <th class="py-3.5 px-4 text-right">Data Aktual (A<sub>t</sub>)</th>
                            <th class="py-3.5 px-4 text-right">Prediksi SMA (F<sub>t</sub>)</th>
                            <th class="py-3.5 px-4 text-right">Error Mutlak |A<sub>t</sub> - F<sub>t</sub>|</th>
                            <th class="py-3.5 px-4 text-right">Error Persentase (APE)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($evaluasiMape as $idx => $eval)
                        <tr class="hover:bg-purple-50/30 transition">
                            <td class="py-3 px-4 text-center text-gray-400 font-mono text-xs">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-800">Bulan {{ $eval['bulan'] }} - {{ $eval['tahun'] }}</td>
                            <td class="py-3 px-4 text-right font-medium">{{ (floor($eval['aktual']) == $eval['aktual']) ? number_format($eval['aktual'], 0, ',', '.') : number_format($eval['aktual'], 2, ',', '.') }} {{ $bahan->satuan }}</td>
                            <td class="py-3 px-4 text-right font-medium text-pink-600">{{ (floor($eval['prediksi']) == $eval['prediksi']) ? number_format($eval['prediksi'], 0, ',', '.') : number_format($eval['prediksi'], 2, ',', '.') }} {{ $bahan->satuan }}</td>
                            <td class="py-3 px-4 text-right font-mono">{{ (floor($eval['error']) == $eval['error']) ? number_format($eval['error'], 0, ',', '.') : number_format($eval['error'], 2, ',', '.') }} {{ $bahan->satuan }}</td>
                            <td class="py-3 px-4 text-right font-bold text-purple-700 bg-purple-50/40">{{ number_format($eval['ape'], 2, ',', '.') }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 border-t-2 border-gray-200 font-bold">
                            <td colspan="5" class="py-3.5 px-4 text-right text-gray-700 uppercase tracking-wider text-xs">
                                Mean Absolute Percentage Error (MAPE):
                            </td>
                            <td class="py-3.5 px-4 text-right text-base font-black text-purple-700">
                                {{ number_format($mape, 2, ',', '.') }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        <!-- Card Edukasi: Penjelasan Rumus SMA & Standar MAPE -->
        <div class="bg-gradient-to-br from-pink-50/50 to-purple-50/30 rounded-2xl border border-pink-100 p-6 text-sm text-gray-700">
            <h4 class="font-bold text-gray-800 text-base mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Panduan Rumus Matematis yang Digunakan</span>
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
                <div class="bg-white p-4 rounded-xl border border-pink-100">
                    <p class="font-bold text-pink-700 mb-1">1. Rumus Single Moving Average (SMA):</p>
                    <p class="text-xs text-gray-600 mb-2">Peramalan dihitung dari rata-rata pemakaian sejumlah N periode terakhir:</p>
                    <div class="bg-gray-50 p-2.5 rounded font-mono text-xs text-gray-800 border border-gray-100 text-center font-bold">
                        F<sub>t+1</sub> = (A<sub>t</sub> + A<sub>t-1</sub> + ... + A<sub>t-n+1</sub>) ÷ n
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-purple-100">
                    <p class="font-bold text-purple-700 mb-1">2. Rumus MAPE (Mean Absolute Percentage Error):</p>
                    <p class="text-xs text-gray-600 mb-2">Mengukur persentase rata-rata penyimpangan hasil ramalan terhadap data aktual:</p>
                    <div class="bg-gray-50 p-2.5 rounded font-mono text-xs text-gray-800 border border-gray-100 text-center font-bold">
                        MAPE = (1 ÷ m) × &Sigma; |(A<sub>t</sub> - F<sub>t</sub>) ÷ A<sub>t</sub>| × 100%
                    </div>
                </div>
            </div>

            <!-- Klasifikasi Akurasi Lewis (1982) -->
            <div class="mt-4 pt-4 border-t border-pink-100">
                <p class="text-xs font-bold text-gray-600 mb-2 uppercase tracking-wider">Kriteria Interpretasi Nilai MAPE :</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-2 rounded-lg">
                        <span class="font-black text-sm">&lt; 10%</span><br>
                        <span class="font-medium text-[11px]">Sangat Baik (Akurat Tinggi)</span>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-2 rounded-lg">
                        <span class="font-black text-sm">10% - 20%</span><br>
                        <span class="font-medium text-[11px]">Baik</span>
                    </div>
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 p-2 rounded-lg">
                        <span class="font-black text-sm">20% - 50%</span><br>
                        <span class="font-medium text-[11px]">Layak / Cukup</span>
                    </div>
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-2 rounded-lg">
                        <span class="font-black text-sm">&gt; 50%</span><br>
                        <span class="font-medium text-[11px]">Kurang Akurat</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endif
</div>
@endsection
