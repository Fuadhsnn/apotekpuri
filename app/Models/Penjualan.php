<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $fillable = [
        'nomor_nota',
        'tanggal_penjualan',
        'nama_pelanggan',
        'total_harga',
        'bayar',
        'kembalian',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date'
    ];

    public function penjualanDetails(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}