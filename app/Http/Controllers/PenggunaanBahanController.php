<?php

namespace App\Http\Controllers;

use App\Models\PenggunaanBahanBaku;
use App\Models\BahanBaku;
use Illuminate\Http\Request;

class PenggunaanBahanController extends Controller
{
    public function index(Request $request)
    {
        $bulanDipilih = $request->get('bulan', '');
        $tahunDipilih = $request->get('tahun', date('Y'));

        // 1. Ambil Pesanan Reseller yang sudah selesai (Otomatis Memotong Bahan Baku)
        $resellerSalesQuery = \App\Models\Penjualan::where('status', 'selesai')
            ->whereHas('user', fn($q) => $q->where('role', 'reseller'))
            ->with(['user', 'detailPenjualans.produk.reseps.bahanBaku'])
            ->latest('tanggal')
            ->latest('id');

        if ($request->filled('bulan')) {
            $resellerSalesQuery->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $resellerSalesQuery->whereYear('tanggal', $request->tahun);
        }

        $pesananResellers = $resellerSalesQuery->get();

        // Rincian bahan baku yang terpotong per pesanan reseller
        $riwayatReseller = [];
        foreach ($pesananResellers as $sale) {
            $bahanDipakai = [];
            $kueList = [];

            foreach ($sale->detailPenjualans as $detail) {
                $produk = $detail->produk;
                if (!$produk) continue;
                $qty = $detail->qty;
                $batchSize = $produk->batch_size ?? 1;
                $batches = ceil($qty / $batchSize);
                $kueList[] = [
                    'nama_produk' => $produk->nama_produk,
                    'qty' => $qty,
                    'batches' => $batches,
                    'batch_size' => $batchSize,
                ];

                foreach ($produk->reseps as $resep) {
                    $bahan = $resep->bahanBaku;
                    if (!$bahan) continue;
                    $konv = $bahan->nilai_konversi ?? 1;
                    $jumlahKecil = $resep->jumlah_dibutuhkan * $batches;
                    $jumlahBesar = $jumlahKecil / $konv;

                    if (!isset($bahanDipakai[$bahan->id])) {
                        $bahanDipakai[$bahan->id] = [
                            'nama_bahan' => $bahan->nama_bahan,
                            'satuan' => $bahan->satuan,
                            'satuan_kecil' => $bahan->satuan_kecil,
                            'jumlah' => 0,
                            'jumlah_kecil' => 0,
                        ];
                    }
                    $bahanDipakai[$bahan->id]['jumlah'] += $jumlahBesar;
                    $bahanDipakai[$bahan->id]['jumlah_kecil'] += $jumlahKecil;
                }
            }

            // Pastikan tercatat juga ke tabel penggunaan_bahan_bakus agar terbaca di SMA
            foreach ($bahanDipakai as $bId => $bData) {
                if ($bData['jumlah'] > 0) {
                    PenggunaanBahanBaku::firstOrCreate(
                        [
                            'bahan_baku_id' => $bId,
                            'tanggal' => $sale->tanggal,
                            'keterangan' => 'Otomatis dari Pesanan Reseller #' . ($sale->no_penjualan ?? $sale->id)
                        ],
                        [
                            'jumlah' => $bData['jumlah']
                        ]
                    );
                }
            }

            $riwayatReseller[] = [
                'sale' => $sale,
                'kue_list' => $kueList,
                'bahan_dipakai' => $bahanDipakai,
            ];
        }

        // 2. Ambil Riwayat Penggunaan Manual (bukan dari pesanan reseller)
        $manualQuery = PenggunaanBahanBaku::with('bahanBaku')
            ->where(function($q) {
                $q->whereNull('keterangan')
                  ->orWhere('keterangan', 'not like', '%Pesanan Reseller%');
            })
            ->latest('tanggal')
            ->latest('id');

        if ($request->filled('bulan')) {
            $manualQuery->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $manualQuery->whereYear('tanggal', $request->tahun);
        }

        $penggunaanManuals = $manualQuery->get();
        $bahanBakus = BahanBaku::all();

        // 3. Rekapitulasi Gabungan Penggunaan per Bahan Baku
        $rekapGabungan = [];
        foreach ($bahanBakus as $b) {
            $rekapGabungan[$b->id] = [
                'nama_bahan' => $b->nama_bahan,
                'satuan' => $b->satuan,
                'total_reseller' => 0,
                'total_manual' => 0,
                'total_semua' => 0,
                'frekuensi' => 0,
            ];
        }

        foreach ($riwayatReseller as $item) {
            foreach ($item['bahan_dipakai'] as $bId => $bData) {
                if (isset($rekapGabungan[$bId])) {
                    $rekapGabungan[$bId]['total_reseller'] += $bData['jumlah'];
                    $rekapGabungan[$bId]['total_semua'] += $bData['jumlah'];
                    $rekapGabungan[$bId]['frekuensi'] += 1;
                }
            }
        }

        foreach ($penggunaanManuals as $m) {
            if (isset($rekapGabungan[$m->bahan_baku_id])) {
                $rekapGabungan[$m->bahan_baku_id]['total_manual'] += $m->jumlah;
                $rekapGabungan[$m->bahan_baku_id]['total_semua'] += $m->jumlah;
                $rekapGabungan[$m->bahan_baku_id]['frekuensi'] += 1;
            }
        }

        // Hanya tampilkan bahan yang memiliki penggunaan > 0 pada periode ini
        $rekapBulanan = collect(array_filter($rekapGabungan, fn($r) => $r['total_semua'] > 0));

        // 4. Daftar Tahun untuk Filter
        $tahunListPenjualan = \App\Models\Penjualan::selectRaw('YEAR(tanggal) as tahun')->distinct()->pluck('tahun')->filter()->toArray();
        $tahunListPenggunaan = PenggunaanBahanBaku::selectRaw('YEAR(tanggal) as tahun')->distinct()->pluck('tahun')->filter()->toArray();
        $tahunList = array_unique(array_merge([(int)date('Y')], $tahunListPenjualan, $tahunListPenggunaan));
        rsort($tahunList);

        return view('admin.penggunaan-bahan.index', compact(
            'riwayatReseller',
            'penggunaanManuals',
            'rekapBulanan',
            'bahanBakus',
            'bulanDipilih',
            'tahunDipilih',
            'tahunList'
        ));
    }

    public function create()
    {
        $bahanBakus = BahanBaku::all();
        return view('admin.penggunaan-bahan.create', compact('bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        PenggunaanBahanBaku::create($request->all());

        // Potong stok bahan baku
        $bahan = BahanBaku::find($request->bahan_baku_id);
        if ($bahan) {
            $bahan->stok -= $request->jumlah;
            $bahan->save();
        }

        return redirect()->route('penggunaan-bahan.index')->with('success', 'Penggunaan dicatat dan stok berhasil dikurangi.');
    }

    public function edit($id)
    {
        $penggunaan = PenggunaanBahanBaku::findOrFail($id);
        $bahanBakus = BahanBaku::all();
        return view('admin.penggunaan-bahan.edit', compact('penggunaan', 'bahanBakus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $penggunaan = PenggunaanBahanBaku::findOrFail($id);

        // 1. Kembalikan stok lama ke bahan baku lama
        $bahanLama = BahanBaku::find($penggunaan->bahan_baku_id);
        if ($bahanLama) {
            $bahanLama->stok += $penggunaan->jumlah;
            $bahanLama->save();
        }

        // 2. Kurangi stok baru pada bahan baku terpilih
        $bahanBaru = BahanBaku::find($request->bahan_baku_id);
        if ($bahanBaru) {
            $bahanBaru->stok -= $request->jumlah;
            $bahanBaru->save();
        }

        // 3. Perbarui catatan penggunaan
        $penggunaan->update([
            'bahan_baku_id' => $request->bahan_baku_id,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('penggunaan-bahan.index')->with('success', 'Data penggunaan manual berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penggunaan = PenggunaanBahanBaku::findOrFail($id);

        // Kembalikan stok yang sebelumnya terpotong
        $bahan = BahanBaku::find($penggunaan->bahan_baku_id);
        if ($bahan) {
            $bahan->stok += $penggunaan->jumlah;
            $bahan->save();
        }

        $penggunaan->delete();

        return redirect()->route('penggunaan-bahan.index')->with('success', 'Catatan penggunaan berhasil dihapus dan stok telah dikembalikan.');
    }
}
