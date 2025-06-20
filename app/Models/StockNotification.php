<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockNotification extends Model
{
    protected $fillable = [
        'obat_id',
        'current_stock',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the obat that owns the notification.
     */
    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }
}