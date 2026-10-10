<?php
namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahanBakus = BahanBaku::latest()->get();
        return view('admin.bahan-baku.index', compact('bahanBakus'));
    }

    public function create()
    {
        return view('admin.bahan-baku.create');
    }

    public function store(Request $request)
    {
        if($request->has('harga')) {
            $request->merge(['harga' => str_replace('.', '', $request->harga)]);
        }

        $request->validate([
            'nama_bahan' => 'required',
            'satuan' => 'required',
            'satuan_kecil' => 'nullable|string',
            'nilai_konversi' => 'nullable|numeric|min:1',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'pemakaian_max' => 'required|numeric',
            'pemakaian_rata' => 'required|numeric',
            'lead_time' => 'required|numeric',
        ]);

        BahanBaku::create($request->all());
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan Baku berhasil ditambahkan.');
    }

    public function edit(BahanBaku $bahanBaku)
    {
        return view('admin.bahan-baku.edit', compact('bahanBaku'));
    }

    public function update(Request $request, BahanBaku $bahanBaku)
    {
        if($request->has('harga')) {
            $request->merge(['harga' => str_replace('.', '', $request->harga)]);
        }

        $request->validate([
            'nama_bahan' => 'required',
            'satuan' => 'required',
            'satuan_kecil' => 'nullable|string',
            'nilai_konversi' => 'nullable|numeric|min:1',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'pemakaian_max' => 'required|numeric',
            'pemakaian_rata' => 'required|numeric',
            'lead_time' => 'required|numeric',
        ]);

        $bahanBaku->update($request->all());
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan Baku berhasil diperbarui.');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        $bahanBaku->delete();
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan Baku berhasil dihapus.');
    }
}
