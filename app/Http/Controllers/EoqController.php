<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\HasilEoq;
use Illuminate\Http\Request;

class EoqController extends Controller
{
    public function index()
    {
        $bahanBakus = BahanBaku::all();
        $hasilEoq = HasilEoq::with('bahanBaku')->orderBy('created_at', 'desc')->get();
        return view('admin.eoq.index', compact('bahanBakus', 'hasilEoq'));
    }

    public function hitung(Request $request)
    {
        // Validasi form
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'periode' => 'required|string',
            'kebutuhan_tahunan_d' => 'required|numeric|min:1',
            'biaya_pesan_s' => 'required|numeric|min:1',
            'biaya_simpan_h' => 'required|numeric|min:1',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_baku_id);
        
        $D = $request->kebutuhan_tahunan_d; // Demand Tahunan (D)
        $S = $request->biaya_pesan_s;       // Biaya Pemesanan (S)
        $H = $request->biaya_simpan_h;      // Biaya Penyimpanan (H)
        
        // A. Hitung Economic Order Quantity (EOQ)
        // Rumus: EOQ = sqrt((2 * D * S) / H)
        $eoq = sqrt((2 * $D * $S) / $H);
        
        // B. Hitung Safety Stock (SS)
        // Rumus: SS = (PM - PRR) * LT
        // PM = Pemakaian Maksimum/hari, PRR = Pemakaian Rata-rata/hari, LT = Lead Time (hari)
        $ss = ($bahan->pemakaian_max - $bahan->pemakaian_rata) * $bahan->lead_time;

        // C. Hitung Reorder Point (ROP)
        // Rumus: ROP = (d * LT) + SS
        // d = permintaan harian rata-rata (D / 360) (Asumsi 360 hari kerja dalam setahun)
        $d_harian = $D / 360;
        $rop = ($d_harian * $bahan->lead_time) + $ss;

        // Simpan Hasil ke Database
        HasilEoq::create([
            'bahan_baku_id' => $bahan->id,
            'periode' => $request->periode,
            'kebutuhan_tahunan_d' => $D,
            'biaya_pesan_s' => $S,
            'biaya_simpan_h' => $H,
            'eoq' => round($eoq),
            'safety_stock' => round($ss),
            'reorder_point' => round($rop)
        ]);

        return redirect()->route('eoq.index')->with('success', 'Perhitungan EOQ, Safety Stock, dan ROP berhasil.');
    }

    public function edit($id)
    {
        $eoq = HasilEoq::findOrFail($id);
        $bahanBakus = BahanBaku::all();
        return view('admin.eoq.edit', compact('eoq', 'bahanBakus'));
    }

    public function update(Request $request, $id)
    {
        // Validasi form
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'periode' => 'required|string',
            'kebutuhan_tahunan_d' => 'required|numeric|min:1',
            'biaya_pesan_s' => 'required|numeric|min:1',
            'biaya_simpan_h' => 'required|numeric|min:1',
        ]);

        $eoqRecord = HasilEoq::findOrFail($id);
        $bahan = BahanBaku::findOrFail($request->bahan_baku_id);
        
        $D = $request->kebutuhan_tahunan_d;
        $S = $request->biaya_pesan_s;
        $H = $request->biaya_simpan_h;
        
        // A. Hitung Economic Order Quantity (EOQ)
        $eoq = sqrt((2 * $D * $S) / $H);
        
        // B. Hitung Safety Stock (SS)
        $ss = ($bahan->pemakaian_max - $bahan->pemakaian_rata) * $bahan->lead_time;

        // C. Hitung Reorder Point (ROP)
        $d_harian = $D / 360;
        $rop = ($d_harian * $bahan->lead_time) + $ss;

        // Update Hasil ke Database
        $eoqRecord->update([
            'bahan_baku_id' => $bahan->id,
            'periode' => $request->periode,
            'kebutuhan_tahunan_d' => $D,
            'biaya_pesan_s' => $S,
            'biaya_simpan_h' => $H,
            'eoq' => round($eoq),
            'safety_stock' => round($ss),
            'reorder_point' => round($rop)
        ]);

        return redirect()->route('eoq.index')->with('success', 'Data hasil perhitungan EOQ berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $eoqRecord = HasilEoq::findOrFail($id);
        $eoqRecord->delete();

        return redirect()->route('eoq.index')->with('success', 'Data hasil perhitungan EOQ berhasil dihapus.');
    }
}
