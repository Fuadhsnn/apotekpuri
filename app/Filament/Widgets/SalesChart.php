<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Penjualan Bulanan';

    protected static ?int $sort = 2; // Atur urutan tampilan widget

    protected function getType(): string
    {
        return 'bar'; // Atau 'line'
    }

    protected function getData(): array
    {
        $months = [];
        $salesData = [];

        // Ambil data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->translatedFormat('M Y'); // Contoh: Jan 2025

            $monthlyTotal = Penjualan::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_harga');
            $salesData[] = $monthlyTotal;
        }

        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => $salesData,
                    'backgroundColor' => '#36A2EB', // Warna bar
                    'borderColor' => '#9BD0F5',
                ],
            ],
        ];
    }
}
