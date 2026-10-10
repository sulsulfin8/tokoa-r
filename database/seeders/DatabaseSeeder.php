<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\Resep;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. DATA AKUN PENGGUNA (ROLES)
        // ==========================================
        
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Pemilik Toko',
            'username' => 'pemilik',
            'password' => Hash::make('password123'),
            'role' => 'pemilik',
        ]);

        User::create([
            'name' => 'Reseller A',
            'username' => 'reseller',
            'password' => Hash::make('password123'),
            'role' => 'reseller',
        ]);

        // ==========================================
        // 2. DATA DUMMY BAHAN BAKU (Untuk Test ROP/EOQ)
        // ==========================================
        
        $terigu = BahanBaku::create([
            'nama_bahan' => 'Tepung Terigu Segitiga Biru',
            'satuan' => 'gram',
            'harga' => 12, // Asumsi: 12 Rupiah per gram (Rp 12.000 / kg)
            'stok' => 20000, // 20 Kg (Contoh: Mendekati ROP agar tampil di dashboard)
            'pemakaian_max' => 5000, // 5 Kg per hari
            'pemakaian_rata' => 3000, // 3 kg per hari
            'lead_time' => 2 // Pesan butuh waktu 2 hari
        ]);

        $gula = BahanBaku::create([
            'nama_bahan' => 'Gula Pasir',
            'satuan' => 'gram',
            'harga' => 15,
            'stok' => 50000, // 50 Kg (Aman)
            'pemakaian_max' => 2000,
            'pemakaian_rata' => 1500,
            'lead_time' => 1
        ]);

        // ==========================================
        // 3. DATA DUMMY PRODUK & RESEP (Untuk Test Penjualan)
        // ==========================================
        
        $cake = Produk::create([
            'nama_produk' => 'Bolu Coklat Spesial',
            'harga' => 55000,
            'deskripsi' => 'Bolu panggang rasa coklat ukuran loyang sedang'
        ]);

        // Komposisi resep Bolu Coklat:
        // - Butuh 500 gram Tepung Terigu
        Resep::create([
            'produk_id' => $cake->id,
            'bahan_baku_id' => $terigu->id,
            'jumlah_dibutuhkan' => 500
        ]);

        // - Butuh 250 gram Gula
        Resep::create([
            'produk_id' => $cake->id,
            'bahan_baku_id' => $gula->id,
            'jumlah_dibutuhkan' => 250
        ]);
    }
}
