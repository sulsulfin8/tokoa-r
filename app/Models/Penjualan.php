<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $fillable = ['no_penjualan', 'tanggal', 'total_harga', 'status', 'user_id'];

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
