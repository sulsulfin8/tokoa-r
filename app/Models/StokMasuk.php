<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;
    protected $fillable = ['bahan_baku_id', 'pemasok_id', 'jumlah', 'tanggal', 'total_biaya'];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class);
    }
}
