<?php

namespace App\Helpers;

class PenjualanHelper
{
    /**
     * Menghitung total jumlah item dari penjualan details
     * 
     * @param \Illuminate\Database\Eloquent\Collection $penjualanDetails
     * @return int
     */
    public static function calculateTotalItems($penjualanDetails)
    {
        if (!$penjualanDetails || $penjualanDetails->isEmpty()) {
            return 0;
        }

        $total = 0;
        foreach ($penjualanDetails as $detail) {
            $total += (int) ($detail->jumlah ?? 0);
        }

        return $total;
    }

    /**
     * Menghitung total item dari array penjualan
     * 
     * @param array $penjualans
     * @return int
     */
    public static function calculateTotalItemsFromArray($penjualans)
    {
        $total = 0;
        foreach ($penjualans as $penjualan) {
            if (is_array($penjualan) && isset($penjualan['jumlah_item'])) {
                $total += (int) $penjualan['jumlah_item'];
            } elseif (is_object($penjualan) && isset($penjualan->jumlah_item)) {
                $total += (int) $penjualan->jumlah_item;
            }
        }

        return $total;
    }

    /**
     * Format angka untuk display
     * 
     * @param int|float $number
     * @return string
     */
    public static function formatNumber($number)
    {
        return number_format($number, 0, ',', '.');
    }

    /**
     * Format mata uang Rupiah
     * 
     * @param int|float $amount
     * @return string
     */
    public static function formatCurrency($amount)
    {
        return 'Rp ' . self::formatNumber($amount);
    }
}