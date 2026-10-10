<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilEoq extends Model
{
    use HasFactory;
    protected $fillable = [
        'bahan_baku_id', 'periode', 'kebutuhan_tahunan_d', 
        'biaya_pesan_s', 'biaya_simpan_h', 'eoq', 
        'safety_stock', 'reorder_point'
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
