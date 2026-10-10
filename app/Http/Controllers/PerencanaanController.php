<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class PerencanaanController extends Controller {
    public function index() {
        $produks = Produk::all();
        $produk = null;
        
        $cacheKey = 'perencanaan_result_v2_' . auth()->id();
        if (cache()->has($cacheKey)) {
            $cachedData = cache()->get($cacheKey);
            $produk = Produk::find($cachedData['perencanaan_produk_id']);
            $jumlah = $cachedData['perencanaan_jumlah'];
            $hasil = $cachedData['perencanaan_hasil'];
            $info = $cachedData['perencanaan_info'] ?? null;
            return view('admin.perencanaan.index', compact('produks', 'produk', 'jumlah', 'hasil', 'info'));
        }

        $jumlah = null;
        $hasil = null;
        $info = null;
        return view('admin.perencanaan.index', compact('produks', 'produk', 'jumlah', 'hasil', 'info'));
    }

    public function hitung(Request $request) {
        $request->validate([
            'produk_id' => 'required',
            'jumlah_produksi' => 'required|numeric|min:1'
        ]);

        $produk = Produk::with('reseps.bahanBaku')->findOrFail($request->produk_id);
        $jumlah = $request->jumlah_produksi;
        $produks = Produk::all();

        $stok_produk = $produk->stok ?? 0;
        $butuh_produksi = max(0, $jumlah - $stok_produk);
        $batch_size = $produk->batch_size ?? 1;
        $batches = ceil($butuh_produksi / $batch_size);
        $produksi_real = $batches * $batch_size;

        $hasil = [];
        foreach($produk->reseps as $resep) {
            $bahan = $resep->bahanBaku;
            if(!$bahan) continue;
            
            $konv = $bahan->nilai_konversi ?? 1;
            
            $kebutuhanTotalKecil = $resep->jumlah_dibutuhkan * $batches;
            $stokTersediaKecil = $bahan->stok * $konv;
            $selisihKecil = $stokTersediaKecil - $kebutuhanTotalKecil;
            
            // Format kebutuhan
            $kebutuhanBesar = floor($kebutuhanTotalKecil / $konv);
            $sisaKecil = $kebutuhanTotalKecil % $konv;
            $teksKebutuhan = "";
            if ($kebutuhanBesar > 0) $teksKebutuhan .= $kebutuhanBesar . " " . $bahan->satuan . " ";
            if ($sisaKecil > 0 && $bahan->satuan_kecil) $teksKebutuhan .= $sisaKecil . " " . $bahan->satuan_kecil;
            if ($teksKebutuhan == "") $teksKebutuhan = "0 " . $bahan->satuan;

            // Format selisih
            $teksSelisih = "";
            $absSelisih = abs($selisihKecil);
            $selisihBesar = floor($absSelisih / $konv);
            $sisaSelisih = $absSelisih % $konv;
            if ($selisihBesar > 0) $teksSelisih .= $selisihBesar . " " . $bahan->satuan . " ";
            if ($sisaSelisih > 0 && $bahan->satuan_kecil) $teksSelisih .= $sisaSelisih . " " . $bahan->satuan_kecil;
            if ($teksSelisih == "") $teksSelisih = "0 " . $bahan->satuan;
            
            $hasil[] = [
                'nama_bahan' => $bahan->nama_bahan,
                'stok_tersedia_teks' => $bahan->stok . " " . $bahan->satuan,
                'kebutuhan_teks' => trim($teksKebutuhan),
                'selisih_teks' => trim($teksSelisih),
                'status' => $selisihKecil < 0 ? 'Kurang' : 'Cukup'
            ];
        }

        $cacheData = [
            'perencanaan_produk_id' => $produk->id,
            'perencanaan_jumlah' => $jumlah,
            'perencanaan_hasil' => $hasil,
            'perencanaan_info' => [
                'stok' => $stok_produk,
                'butuh' => $butuh_produksi,
                'batches' => $batches,
                'produksi_real' => $produksi_real,
                'batch_size' => $batch_size
            ]
        ];
        
        cache()->put('perencanaan_result_v2_' . auth()->id(), $cacheData, now()->addDays(30));

        return redirect()->route('perencanaan.index');
    }
}
