<?php
namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PenggunaanBahanBaku;
use Illuminate\Http\Request;

class SmaController extends Controller {
    public function index() {
        $bahanBakus = BahanBaku::all();
        
        $cacheKey = 'sma_result_' . auth()->id();
        if (cache()->has($cacheKey)) {
            $cachedData = cache()->get($cacheKey);
            return view('admin.sma.index', array_merge(compact('bahanBakus'), $cachedData));
        }

        return view('admin.sma.index', compact('bahanBakus'));
    }

    public function hitung(Request $request) {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id', 
            'periode' => 'required|numeric|min:2|max:12'
        ]);
        
        $bahan = BahanBaku::findOrFail($request->bahan_baku_id);
        $periode = (int) $request->periode;
        
        // Mengambil semua riwayat penggunaan bulanan secara kronologis (dari terlama ke terbaru)
        $semuaPenggunaan = PenggunaanBahanBaku::where('bahan_baku_id', $bahan->id)
            ->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(jumlah) as total')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        if ($semuaPenggunaan->count() < $periode) {
            return back()->with('error', 'Data histori tidak cukup untuk perhitungan ' . $periode . ' bulan terakhir. Tersedia ' . $semuaPenggunaan->count() . ' bulan riwayat penggunaan, dibutuhkan minimal ' . $periode . ' bulan.');
        }

        // Ambil $periode bulan terakhir untuk meramal periode mendatang
        $penggunaanTerakhir = $semuaPenggunaan->slice(-$periode)->values();
        
        // Kalkulasi Single Moving Average (SMA)
        $totalPenggunaan = $penggunaanTerakhir->sum('total');
        $prediksi = $totalPenggunaan / $periode;

        // ==========================================
        // KALKULASI MAPE (Mean Absolute Percentage Error)
        // ==========================================
        $evaluasiMape = [];
        $totalApe = 0;
        $totalBulanDiuji = 0;
        $totalHistori = $semuaPenggunaan->count();

        // Pengujian historis (backtesting) jika data histori lebih besar dari nilai periode N
        if ($totalHistori > $periode) {
            for ($i = $periode; $i < $totalHistori; $i++) {
                $subsetSebelumnya = $semuaPenggunaan->slice($i - $periode, $periode);
                $prediksiHistoris = $subsetSebelumnya->sum('total') / $periode;
                $aktualHistoris = (float) $semuaPenggunaan[$i]->total;

                $error = abs($aktualHistoris - $prediksiHistoris);
                $ape = $aktualHistoris > 0 ? ($error / $aktualHistoris) * 100 : 0;

                $evaluasiMape[] = [
                    'tahun' => $semuaPenggunaan[$i]->tahun,
                    'bulan' => $semuaPenggunaan[$i]->bulan,
                    'aktual' => $aktualHistoris,
                    'prediksi' => $prediksiHistoris,
                    'error' => $error,
                    'ape' => $ape
                ];

                $totalApe += $ape;
                $totalBulanDiuji++;
            }
        }

        $mape = null;
        $kategoriMape = null;
        if ($totalBulanDiuji > 0) {
            $mape = round($totalApe / $totalBulanDiuji, 2);
            if ($mape < 10) {
                $kategoriMape = 'Sangat Baik (Akurasi Tinggi)';
            } elseif ($mape <= 20) {
                $kategoriMape = 'Baik';
            } elseif ($mape <= 50) {
                $kategoriMape = 'Cukup / Layak';
            } else {
                $kategoriMape = 'Kurang Akurat';
            }
        }

        // Data riwayat bulan yang dipakai dalam peramalan (diurutkan terbaru di atas untuk display)
        $penggunaanBulanan = $penggunaanTerakhir->reverse()->values();
        
        $resultData = compact(
            'prediksi', 
            'bahan', 
            'periode', 
            'penggunaanBulanan', 
            'totalPenggunaan',
            'totalHistori',
            'evaluasiMape', 
            'mape', 
            'kategoriMape', 
            'totalBulanDiuji'
        );
        cache()->put('sma_result_' . auth()->id(), $resultData, now()->addDays(30));

        return redirect()->route('sma.index')->with('success', 'Prediksi SMA dan evaluasi MAPE berhasil dihitung!');
    }
}
