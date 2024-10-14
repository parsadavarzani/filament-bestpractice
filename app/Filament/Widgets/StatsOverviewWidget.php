<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Revenue', Product::count())
                ->chart([1, 5, 7, 32, 54])
                ->color('success')
                ->description('10% Increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('New customers ', Product::sum('qty'))
                ->chart([1, 5, 7, 2])
                ->color('danger')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->description('4% Decrease'),
            Stat::make('New orders ', Product::avg('price'))
                ->chart([1, 5, 7, 87])
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->description('20% Increase'),
        ];
    }
}
