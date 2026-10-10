<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\Pemasok;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBahan = BahanBaku::count();
        $stokMenipis = BahanBaku::where('stok', '<', 50)->orderBy('stok', 'asc')->get();
        
        $pesananHariIni = \App\Models\Penjualan::whereDate('tanggal', \Carbon\Carbon::today())->count();
        $totalReseller = \App\Models\User::where('role', 'reseller')->count();

        // Ambil semua bahan baku untuk grafik stok
        $chartBahan = BahanBaku::all();

        return view('admin.dashboard', compact('totalBahan', 'stokMenipis', 'pesananHariIni', 'totalReseller', 'chartBahan'));
    }
}
