<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'deskripsi',
        'kategori',
        'jenis_obat',
        'stok',
        'harga_beli',
        'harga_jual',
        'tanggal_kadaluarsa',
        'gambar'
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date'
    ];

    public function penjualanDetails(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    public function pembelianDetails(): HasMany
    {
        return $this->hasMany(PembelianDetail::class);
    }
}
