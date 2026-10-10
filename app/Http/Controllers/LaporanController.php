<?php
namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StokMasuk;
use App\Models\HasilEoq;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $bahanBakus = BahanBaku::all();
        $stokMasuks = StokMasuk::with('bahanBaku', 'pemasok')->latest()->get();
        $hasilEoqs = HasilEoq::with('bahanBaku')->latest()->get();
        $penjualans = Penjualan::with('detailPenjualans.produk.reseps.bahanBaku')->latest()->get();

        return view('admin.laporan.index', compact('bahanBakus', 'stokMasuks', 'hasilEoqs', 'penjualans'));
    }

    public function download($jenis, $format)
    {
        $data = [];
        $view = '';
        $filename = '';

        if ($jenis == 'bahan_baku') {
            $data['bahanBakus'] = BahanBaku::all();
            $view = 'admin.laporan.export_bahan_baku';
            $filename = 'Laporan_Stok_Bahan_Baku';
        } elseif ($jenis == 'stok_masuk') {
            $data['stokMasuks'] = StokMasuk::with('bahanBaku', 'pemasok')->latest()->get();
            $view = 'admin.laporan.export_stok_masuk';
            $filename = 'Laporan_Stok_Masuk';
        } elseif ($jenis == 'penjualan') {
            $data['penjualans'] = Penjualan::with('detailPenjualans.produk')->latest()->get();
            $view = 'admin.laporan.export_penjualan';
            $filename = 'Laporan_Penjualan';
        }

        $data['format'] = $format;
        $data['jenis'] = $jenis;

        // Tanda tangan & verifikasi resmi sistem hanya untuk format Word dan Cetak (tidak untuk Excel)
        if (in_array($format, ['word', 'print'])) {
            $sigData = \App\Services\LaporanSignatureService::getSignatureData($jenis);
            $data = array_merge($data, $sigData);
        }

        $html = view($view, $data)->render();

        if ($format == 'excel') {
            return response($html)
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.xls"');
        } elseif ($format == 'word') {
            return response($html)
                ->header('Content-Type', 'application/vnd.ms-word')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.doc"');
        } elseif ($format == 'print') {
            return $html;
        }
    }
}
