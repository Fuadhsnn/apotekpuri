<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    protected $fillable = [
        'nomor_faktur',
        'supplier_id',
        'tanggal_pembelian',
        'total_harga',
        'status_pembayaran',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function pembelianDetails(): HasMany
    {
        return $this->hasMany(PembelianDetail::class);
    }
}