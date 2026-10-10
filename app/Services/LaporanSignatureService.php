<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanSignatureService
{
    /**
     * Mendapatkan data tanda tangan resmi pemilik toko
     */
    public static function getSignatureData(string $jenis = 'laporan'): array
    {
        // Mengambil nama akun pemilik toko secara dinamis dari database / akun login pemilik
        $pemilik = null;
        if (Auth::check() && Auth::user()->role === 'pemilik') {
            $pemilik = Auth::user();
        } else {
            $pemilik = User::where('role', 'pemilik')->first();
        }
        
        $namaPemilik = $pemilik ? $pemilik->name : 'Pemilik Toko';
        
        // Menggunakan zona waktu WITA (Makassar) sesuai lokasi Toko A&R Bakery
        $now = Carbon::now('Asia/Makassar');

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $tanggalCetak = $now->format('d') . ' ' . $bulanIndo[(int)$now->format('m')] . ' ' . $now->format('Y');
        $waktuCetak = $now->format('H:i:s') . ' WITA';

        // Mengambil tanda tangan asli dari storage/ttd.png (tanpa stempel otomatis sistem)
        $ttdSrc = self::getSignatureImage();

        return [
            'namaPemilik' => $namaPemilik,
            'tanggalCetak' => $tanggalCetak,
            'waktuCetak' => $waktuCetak,
            'ttdSrc' => $ttdSrc,
        ];
    }

    /**
     * Mengambil file ttd.png asli dari storage
     */
    public static function getSignatureImage(): string
    {
        $ttdPath = storage_path('ttd.png');
        if (!file_exists($ttdPath)) {
            $altPath = storage_path('app/public/ttd.png');
            if (file_exists($altPath)) {
                $ttdPath = $altPath;
            } elseif (file_exists(public_path('storage/ttd.png'))) {
                $ttdPath = public_path('storage/ttd.png');
            } else {
                return '';
            }
        }

        $rawContent = file_get_contents($ttdPath);
        return 'data:image/png;base64,' . base64_encode($rawContent);
    }
}
