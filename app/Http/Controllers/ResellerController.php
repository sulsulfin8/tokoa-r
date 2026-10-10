<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResellerController extends Controller {
    public function index() {
        $resellers = User::where('role', 'reseller')->latest()->get();
        return view('admin.reseller.index', compact('resellers'));
    }
    public function create() {
        return view('admin.reseller.create');
    }
    public function store(Request $request) {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','username'=>'required|unique:users','password'=>'required']);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'reseller'
        ]);
        return back()->with('success','Reseller berhasil ditambahkan.');
    }
    public function edit(User $reseller) {
        return view('admin.reseller.edit', compact('reseller'));
    }
    public function update(Request $request, User $reseller) {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$reseller->id,
            'username'=>'required|unique:users,username,'.$reseller->id
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];
        if($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $reseller->update($data);
        return redirect()->route('reseller.index')->with('success','Reseller berhasil diperbarui.');
    }
    public function destroy(User $reseller) {
        $reseller->delete();
        return back()->with('success','Reseller dihapus.');
    }
}
