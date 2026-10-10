<?php
namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\BahanBaku;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class StokMasukController extends Controller {
    public function index() {
        $stokMasuks = StokMasuk::with('bahanBaku', 'pemasok')->latest()->get();
        $bahanBakus = BahanBaku::all();
        $pemasoks = Pemasok::all();
        return view('admin.stok-masuk.index', compact('stokMasuks', 'bahanBakus', 'pemasoks'));
    }
    public function create() {
        $bahanBakus = BahanBaku::all();
        $pemasoks = Pemasok::all();
        return view('admin.stok-masuk.create', compact('bahanBakus', 'pemasoks'));
    }

    public function store(Request $request) {
        $request->merge([
            'total_biaya' => preg_replace('/[^0-9]/', '', $request->total_biaya)
        ]);

        $request->validate([
            'bahan_baku_id' => 'required',
            'pemasok_id' => 'required',
            'jumlah' => 'required|numeric',
            'total_biaya' => 'required|numeric',
            'tanggal_masuk' => 'required|date'
        ]);

        StokMasuk::create([
            'bahan_baku_id' => $request->bahan_baku_id,
            'pemasok_id' => $request->pemasok_id,
            'jumlah' => $request->jumlah,
            'total_biaya' => $request->total_biaya,
            'tanggal' => $request->tanggal_masuk,
        ]);

        $bahan = BahanBaku::find($request->bahan_baku_id);
        $bahan->stok += $request->jumlah;
        $bahan->save();

        return back()->with('success', 'Stok Masuk berhasil ditambahkan dan stok bahan baku bertambah!');
    }
    
    public function edit(StokMasuk $stokMasuk) {
        $bahanBakus = BahanBaku::all();
        $pemasoks = Pemasok::all();
        return view('admin.stok-masuk.edit', compact('stokMasuk', 'bahanBakus', 'pemasoks'));
    }

    public function update(Request $request, StokMasuk $stokMasuk) {
        $request->merge([
            'total_biaya' => preg_replace('/[^0-9]/', '', $request->total_biaya)
        ]);

        $request->validate([
            'bahan_baku_id' => 'required',
            'pemasok_id' => 'required',
            'jumlah' => 'required|numeric',
            'total_biaya' => 'required|numeric',
            'tanggal_masuk' => 'required|date'
        ]);

        // Revert old stock
        $oldBahan = BahanBaku::find($stokMasuk->bahan_baku_id);
        if ($oldBahan) {
            $oldBahan->stok -= $stokMasuk->jumlah;
            $oldBahan->save();
        }

        $stokMasuk->update([
            'bahan_baku_id' => $request->bahan_baku_id,
            'pemasok_id' => $request->pemasok_id,
            'jumlah' => $request->jumlah,
            'total_biaya' => $request->total_biaya,
            'tanggal' => $request->tanggal_masuk,
        ]);

        // Add new stock
        $newBahan = BahanBaku::find($request->bahan_baku_id);
        if ($newBahan) {
            $newBahan->stok += $request->jumlah;
            $newBahan->save();
        }

        return redirect()->route('stok-masuk.index')->with('success', 'Data Stok Masuk berhasil diperbarui!');
    }
    
    public function destroy(StokMasuk $stokMasuk) {
        $bahan = BahanBaku::find($stokMasuk->bahan_baku_id);
        if($bahan) {
            $bahan->stok -= $stokMasuk->jumlah;
            $bahan->save();
        }
        $stokMasuk->delete();
        return back()->with('success', 'Data dihapus dan stok dikembalikan.');
    }
}
