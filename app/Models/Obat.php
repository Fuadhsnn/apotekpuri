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
    
    public function stockNotifications(): HasMany
    {
        return $this->hasMany(StockNotification::class);
    }
    
    /**
     * Cek apakah stok obat menipis (kurang dari atau sama dengan threshold)
     *
     * @param int $threshold
     * @return bool
     */
    public function isLowStock(int $threshold = 10): bool
    {
        return $this->stok <= $threshold;
    }
}
