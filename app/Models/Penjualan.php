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
        'nama_dokter',
        'nomor_resep',
        'total_harga',
        'metode_pembayaran',
        'bayar',
        'kembalian',
        'keterangan',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // 'user_id' adalah nama kolom foreign key di tabel 'penjualans'
    }

    protected $casts = [
        'tanggal_penjualan' => 'date'
    ];

    public function penjualanDetails(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id');
    }

    /**
     * Accessor untuk menghitung total item
     */
    public function getTotalItemsAttribute()
    {
        return $this->penjualanDetails->sum('jumlah');
    }

    /**
     * Accessor untuk menghitung jumlah jenis obat
     */
    public function getJumlahJenisObatAttribute()
    {
        return $this->penjualanDetails->count();
    }
}
