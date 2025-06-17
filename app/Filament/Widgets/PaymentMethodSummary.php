<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentMethodSummary extends ChartWidget
{
    protected static ?string $heading = 'Proporsi Metode Pembayaran (Bulan Ini)';

    protected static ?int $sort = 4;

    protected function getType(): string
    {
        return 'pie'; // Atau 'doughnut'
    }

    protected function getData(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $paymentMethodsData = Penjualan::select('metode_pembayaran', DB::raw('COUNT(*) as total_transactions'))
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('metode_pembayaran')
            ->get();

        $labels = $paymentMethodsData->pluck('metode_pembayaran')->toArray();
        $data = $paymentMethodsData->pluck('total_transactions')->toArray();

        // Warna yang berbeda untuk setiap slice
        $colors = [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9900'
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)), // Ambil warna sesuai jumlah data
                ],
            ],
        ];
    }
}
