<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\BahanBaku;
use App\Models\PenggunaanBahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    public function index()
    {
        $query = Penjualan::with('detailPenjualans.produk')->latest();
        
        if (auth()->check() && auth()->user()->role === 'reseller') {
            $query->where('user_id', auth()->id());
        }

        $penjualans = $query->get();
        $produks = Produk::all();
        $no_penjualan = 'INV-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        return view('admin.penjualan.index', compact('penjualans', 'produks', 'no_penjualan'));
    }

    public function create()
    {
        $produks = Produk::all();
        // Generate No Penjualan: INV-YYYYMMDD-XXXX
        $no_penjualan = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        return view('admin.penjualan.create', compact('produks', 'no_penjualan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_penjualan' => 'required|unique:penjualans',
            'tanggal' => 'required|date',
            'produk_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            
            $statusTransaksi = (auth()->check() && auth()->user()->role === 'reseller') ? 'pending' : 'selesai';

            // 1. Buat Data Transaksi Header
            $penjualan = Penjualan::create([
                'no_penjualan' => $request->no_penjualan,
                'tanggal' => $request->tanggal,
                'total_harga' => 0, // Diupdate setelah loop produk
                'status' => $statusTransaksi,
                'user_id' => auth()->id() // Menyimpan ID user yang login
            ]);

            // 2. Loop Setiap Produk yang Terjual
            foreach ($request->produk_id as $key => $prod_id) {
                $qty = $request->qty[$key];
                $produk = Produk::findOrFail($prod_id);
                $subtotal = $produk->harga * $qty;
                $totalHarga += $subtotal;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produk->id,
                    'qty' => $qty,
                    'subtotal' => $subtotal
                ]);

                // 3. LOGIKA OTOMATIS: Kurangi Stok Produk HANYA JIKA BUKAN RESELLER
                if ($statusTransaksi === 'selesai') {
                    if ($produk->stok < $qty) {
                        throw new \Exception("Stok produk '{$produk->nama_produk}' tidak cukup. Sisa stok: {$produk->stok}.");
                    }
                    
                    // Update Sisa Stok Produk
                    $produk->stok -= $qty;
                    $produk->save();
                }
            }

            // Update Total Harga Penjualan
            $penjualan->update(['total_harga' => $totalHarga]);

            DB::commit();
            return redirect()->route('penjualan.index')->with('success', 'Transaksi penjualan berhasil. Stok produk otomatis terpotong!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function cekBahan($id)
    {
        $penjualan = Penjualan::with('detailPenjualans.produk.reseps.bahanBaku')->findOrFail($id);
        
        $kebutuhanBahan = [];
        
        foreach ($penjualan->detailPenjualans as $detail) {
            $qty = $detail->qty;
            if($detail->produk) {
                $produk = $detail->produk;
                $batch_size = $produk->batch_size ?? 1;
                $stok_produk = $produk->stok ?? 0;
                
                // Berapa banyak pesanan yang tidak tercover oleh stok produk
                $butuh_produksi = max(0, $qty - $stok_produk);
                
                // Berapa batch yang harus dibuat (kelipatan dari batch_size)
                $batches = ceil($butuh_produksi / $batch_size);

                foreach ($produk->reseps as $resep) {
                    $bahan = $resep->bahanBaku;
                    if (!$bahan) continue;
                    
                    if (!isset($kebutuhanBahan[$bahan->id])) {
                        $kebutuhanBahan[$bahan->id] = [
                            'bahan' => $bahan,
                            'kebutuhan_total_kecil' => 0,
                        ];
                    }
                    // Kebutuhan bahan baku = bahan per batch * jumlah batch
                    $kebutuhanBahan[$bahan->id]['kebutuhan_total_kecil'] += ($resep->jumlah_dibutuhkan * $batches);
                }
            }
        }

        $hasilCek = [];
        foreach ($kebutuhanBahan as $item) {
            $bahan = $item['bahan'];
            $kebutuhanTotalKecil = $item['kebutuhan_total_kecil'];
            $nilaiKonversi = $bahan->nilai_konversi ?? 1;
            $stokTersediaKecil = $bahan->stok * $nilaiKonversi;
            
            $selisihKecil = $stokTersediaKecil - $kebutuhanTotalKecil;
            $status = $selisihKecil >= 0 ? 'Cukup' : 'Kurang';
            
            // Format kebutuhan
            $kebutuhanBesar = floor($kebutuhanTotalKecil / $nilaiKonversi);
            $sisaKecil = $kebutuhanTotalKecil % $nilaiKonversi;
            $teksKebutuhan = "";
            if ($kebutuhanBesar > 0) $teksKebutuhan .= $kebutuhanBesar . " " . $bahan->satuan . " ";
            if ($sisaKecil > 0) $teksKebutuhan .= $sisaKecil . " " . ($bahan->satuan_kecil ?? $bahan->satuan);
            if ($teksKebutuhan == "") $teksKebutuhan = "0 " . $bahan->satuan;

            // Format kurang
            $teksKurang = "";
            if ($selisihKecil < 0) {
                $kurangTotal = abs($selisihKecil);
                $kurangBesar = floor($kurangTotal / $nilaiKonversi);
                $sisaKurangKecil = $kurangTotal % $nilaiKonversi;
                if ($kurangBesar > 0) $teksKurang .= $kurangBesar . " " . $bahan->satuan . " ";
                if ($sisaKurangKecil > 0) $teksKurang .= $sisaKurangKecil . " " . ($bahan->satuan_kecil ?? $bahan->satuan);
            }

            $hasilCek[] = [
                'nama_bahan' => $bahan->nama_bahan,
                'stok_tersedia' => $bahan->stok . " " . $bahan->satuan,
                'kebutuhan_teks' => trim($teksKebutuhan),
                'status' => $status,
                'teks_kurang' => trim($teksKurang)
            ];
        }

        $semuaCukup = true;
        foreach ($hasilCek as $cek) {
            if ($cek['status'] == 'Kurang') $semuaCukup = false;
        }

        return view('admin.penjualan.cek_bahan', compact('penjualan', 'hasilCek', 'semuaCukup'));
    }

    public function approve($id)
    {
        $penjualan = Penjualan::with('detailPenjualans.produk.reseps.bahanBaku')->findOrFail($id);
        
        if ($penjualan->status !== 'pending') {
            return back()->with('error', 'Pesanan ini sudah diproses atau dibatalkan.');
        }

        DB::beginTransaction();
        try {
            $kebutuhanBahan = [];
            
            // Hitung kebutuhan bahan baku
            // Hitung kebutuhan bahan baku dan update stok produk
            foreach ($penjualan->detailPenjualans as $detail) {
                $qty = $detail->qty;
                if($detail->produk) {
                    $produk = $detail->produk;
                    $batch_size = $produk->batch_size ?? 1;
                    $stok_produk = $produk->stok ?? 0;
                    
                    $butuh_produksi = max(0, $qty - $stok_produk);
                    $batches = ceil($butuh_produksi / $batch_size);
                    
                    // Hitung bahan
                    foreach ($produk->reseps as $resep) {
                        $bahan = $resep->bahanBaku;
                        if (!$bahan) continue;
                        
                        if (!isset($kebutuhanBahan[$bahan->id])) {
                            $kebutuhanBahan[$bahan->id] = [
                                'bahan' => $bahan,
                                'kebutuhan_total_kecil' => 0,
                            ];
                        }
                        $kebutuhanBahan[$bahan->id]['kebutuhan_total_kecil'] += ($resep->jumlah_dibutuhkan * $batches);
                    }

                    // Update stok produk: stok_lama - dipesan + (jumlah_batch * per_batch)
                    $produk->stok = $stok_produk - $qty + ($batches * $batch_size);
                    $produk->save();
                }
            }

            // Validasi & Potong Bahan Baku
            foreach ($kebutuhanBahan as $item) {
                $bahan = $item['bahan'];
                $kebutuhanTotalKecil = $item['kebutuhan_total_kecil'];
                $nilaiKonversi = $bahan->nilai_konversi ?? 1;
                $stokTersediaKecil = $bahan->stok * $nilaiKonversi;
                
                if ($stokTersediaKecil < $kebutuhanTotalKecil) {
                    throw new \Exception("Stok bahan baku '{$bahan->nama_bahan}' tidak mencukupi untuk diproses.");
                }

                // Potong stok (karena kolom stok sekarang tipe data float/double)
                $jumlah_potong = $kebutuhanTotalKecil / $nilaiKonversi;
                $bahan->stok -= $jumlah_potong;
                $bahan->save();

                // Catat di penggunaan bahan baku agar terbaca oleh fitur SMA (Prediksi) dan riwayat bulanan
                if ($jumlah_potong > 0) {
                    PenggunaanBahanBaku::create([
                        'bahan_baku_id' => $bahan->id,
                        'jumlah' => $jumlah_potong,
                        'tanggal' => $penjualan->tanggal ?? now()->toDateString(),
                        'keterangan' => 'Otomatis dari Pesanan Reseller #' . ($penjualan->no_penjualan ?? $penjualan->id)
                    ]);
                }
            }

            $penjualan->status = 'selesai';
            $penjualan->save();

            DB::commit();
            return redirect()->route('penjualan.index')->with('success', 'Pesanan berhasil diproses dan bahan baku otomatis terpotong.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
