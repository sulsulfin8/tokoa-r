<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\Resep;
use Illuminate\Http\Request;

class ProdukController extends Controller {
    public function index() {
        $produks = Produk::with('reseps.bahanBaku')->latest()->get();
        $bahanBakus = BahanBaku::all();
        return view('admin.produk.index', compact('produks', 'bahanBakus'));
    }
    public function getByName($nama_produk) {
        $produk = Produk::with('reseps')->where('nama_produk', $nama_produk)->first();
        return response()->json($produk);
    }
    public function create() {
        $bahanBakus = BahanBaku::all();
        return view('admin.produk.create', compact('bahanBakus'));
    }
    public function store(Request $request) {
        if($request->has('harga')) {
            $request->merge(['harga' => str_replace('.', '', $request->harga)]);
        }
        
        $request->validate([
            'nama_produk'=>'required',
            'harga'=>'required|numeric',
            'foto'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bahan_baku_id'=>'required|array',
            'jumlah_dibutuhkan'=>'required|array'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk_fotos', 'public');
        }

        $stok_dibuat = $request->stok ?? 0;

        foreach($request->bahan_baku_id as $key => $bahan_id) {
            if($bahan_id) {
                if (!isset($request->jumlah_dibutuhkan[$key]) || $request->jumlah_dibutuhkan[$key] === '') {
                    return back()->with('error', "Ada bahan baku yang dicentang namun takarannya kosong! Harap isi jumlah takarannya.");
                }
                $jumlah = $request->jumlah_dibutuhkan[$key];
                $bahan = BahanBaku::find($bahan_id);
                if ($bahan && $bahan->stok <= 0) {
                    return back()->with('error', "Bahan baku '{$bahan->nama_bahan}' sudah habis! Tidak dapat diproses.");
                }
            }
        }

        $produk = Produk::where('nama_produk', $request->nama_produk)->first();

        if ($produk) {
            $produk->stok += $stok_dibuat;
            $produk->batch_size = $request->batch_size ?? 1;
            $produk->harga = $request->harga;
            if ($fotoPath) $produk->foto = $fotoPath;
            $produk->save();
            
            $produk->reseps()->delete();
        } else {
            $produk = Produk::create([
                'nama_produk' => $request->nama_produk, 
                'harga' => $request->harga,
                'stok' => $stok_dibuat,
                'batch_size' => $request->batch_size ?? 1,
                'foto' => $fotoPath
            ]);
        }
        
        foreach($request->bahan_baku_id as $key => $bahan_id) {
            if($bahan_id && isset($request->jumlah_dibutuhkan[$key]) && $request->jumlah_dibutuhkan[$key] !== '') {
                $jumlah = $request->jumlah_dibutuhkan[$key];
                Resep::create([
                    'produk_id' => $produk->id,
                    'bahan_baku_id' => $bahan_id,
                    'jumlah_dibutuhkan' => $jumlah
                ]);


            }
        }
        return back()->with('success','Produk dan resepnya berhasil ditambahkan.');
    }
    public function edit(Produk $produk) {
        $bahanBakus = BahanBaku::all();
        $produk->load('reseps');
        return view('admin.produk.edit', compact('produk', 'bahanBakus'));
    }

    public function update(Request $request, Produk $produk) {
        if($request->has('harga')) {
            $request->merge(['harga' => str_replace('.', '', $request->harga)]);
        }
        
        $request->validate([
            'nama_produk'=>'required',
            'harga'=>'required|numeric',
            'foto'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bahan_baku_id'=>'required|array',
            'jumlah_dibutuhkan'=>'required|array'
        ]);

        $fotoPath = $produk->foto;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk_fotos', 'public');
        }

        foreach($request->bahan_baku_id as $key => $bahan_id) {
            if($bahan_id) {
                if (!isset($request->jumlah_dibutuhkan[$key]) || $request->jumlah_dibutuhkan[$key] === '') {
                    return back()->with('error', "Ada bahan baku yang dicentang namun takarannya kosong! Harap isi jumlah takarannya.");
                }
                $bahan = BahanBaku::find($bahan_id);
                if ($bahan && $bahan->stok <= 0) {
                    return back()->with('error', "Bahan baku '{$bahan->nama_bahan}' sudah habis! Tidak dapat diproses.");
                }
            }
        }

        $produk->update([
            'nama_produk' => $request->nama_produk, 
            'harga' => $request->harga,
            'stok' => $request->stok ?? 0,
            'batch_size' => $request->batch_size ?? 1,
            'foto' => $fotoPath
        ]);
        
        $produk->reseps()->delete();

        foreach($request->bahan_baku_id as $key => $bahan_id) {
            if($bahan_id && isset($request->jumlah_dibutuhkan[$key]) && $request->jumlah_dibutuhkan[$key] !== '') {
                Resep::create([
                    'produk_id' => $produk->id,
                    'bahan_baku_id' => $bahan_id,
                    'jumlah_dibutuhkan' => $request->jumlah_dibutuhkan[$key]
                ]);
            }
        }
        return redirect()->route('produk.index')->with('success','Produk dan resepnya berhasil diperbarui.');
    }

    public function destroy(Produk $produk) {
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
