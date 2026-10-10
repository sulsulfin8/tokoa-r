<?php
namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller {
    public function index() {
        $pemasoks = Pemasok::latest()->get();
        return view('admin.pemasok.index', compact('pemasoks'));
    }
    public function create() {
        return view('admin.pemasok.create');
    }
    public function store(Request $request) {
        $request->validate(['nama_pemasok'=>'required','no_telp'=>'required','alamat'=>'required']);
        Pemasok::create($request->all());
        return redirect()->route('pemasok.index')->with('success','Pemasok berhasil ditambahkan.');
    }
    public function edit(Pemasok $pemasok) {
        return view('admin.pemasok.edit', compact('pemasok'));
    }
    public function update(Request $request, Pemasok $pemasok) {
        $request->validate(['nama_pemasok'=>'required','no_telp'=>'required','alamat'=>'required']);
        $pemasok->update($request->all());
        return redirect()->route('pemasok.index')->with('success','Pemasok berhasil diupdate.');
    }
    public function destroy(Pemasok $pemasok) {
        $pemasok->delete();
        return back()->with('success','Pemasok dihapus.');
    }
}
