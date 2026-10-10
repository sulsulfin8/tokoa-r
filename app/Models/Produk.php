<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $fillable = ['nama_produk', 'harga', 'stok', 'batch_size', 'deskripsi', 'foto'];

    public function reseps()
    {
        return $this->hasMany(Resep::class);
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
}
