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

    // Atur lebar widget
    protected int | string | array $columnSpan = 1;

    protected function getType(): string
    {
        return 'pie'; // Bisa juga 'doughnut'
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

        $colors = [
            '#FF6384', // Merah
            '#36A2EB', // Biru
            '#FFCE56', // Kuning
            '#4BC0C0', // Teal
            '#9966FF', // Ungu
            '#FF9900'  // Orange
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 0, // Hilangkan border
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'height' => 150, // Lebih kecil dari SalesChart
            'width' => 150,

            'plugins' => [
                'legend' => [
                    'position' => 'bottom', // Posisi legend
                ],
            ],
        ];
    }
}
