<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        return [
            Stat::make('Total Penjualan Hari Ini', 
                Penjualan::whereDate('created_at', $today)
                    ->sum('total_harga')
            )
                ->description('Penjualan dalam 24 jam terakhir')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([
                    Penjualan::whereDate('created_at', $yesterday)->sum('total_harga'),
                    Penjualan::whereDate('created_at', $today)->sum('total_harga'),
                ])
                ->color('success'),

            Stat::make('Transaksi Hari Ini', 
                Penjualan::whereDate('created_at', $today)->count()
            )
                ->description('Jumlah transaksi hari ini')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([
                    Penjualan::whereDate('created_at', $yesterday)->count(),
                    Penjualan::whereDate('created_at', $today)->count(),
                ])
                ->color('warning'),

            Stat::make('Rata-rata Transaksi', 
                number_format(
                    Penjualan::whereDate('created_at', $today)
                        ->avg('total_harga') ?? 0, 
                    0, ',', '.'
                )
            )
                ->description('Rata-rata nilai transaksi hari ini')
                ->descriptionIcon('heroicon-m-calculator')
                ->chart([
                    Penjualan::whereDate('created_at', $yesterday)->avg('total_harga') ?? 0,
                    Penjualan::whereDate('created_at', $today)->avg('total_harga') ?? 0,
                ])
                ->color('info'),
        ];
    }
}