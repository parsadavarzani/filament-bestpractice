<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {

        $orderStat = $this->orderStat();

        return [
            Stat::make('Orders', $orderStat['orderCount'])
                ->chart([$orderStat['previousMonthOrderCount'], $orderStat['currentMonthOrderCount']])
                ->color($orderStat['color'])
                ->description($orderStat['description'])
                ->descriptionIcon($orderStat['descriptionIcon']),
            Stat::make('Open Orders', Order::where('status', 'open')->count()),
            Stat::make('Average Price', Order::avg('total_price')),
        ];
    }

    protected function orderStat(): array
    {
        $orderCount = Order::count();

        $currentMonth = Order::where('created_at', now()->month)->count();
        $previousMonth = Order::where('created_at', now()->subMonth()->month)->count();

        $percentageChange = 0;

        if ($previousMonth > 0) {

            $percentageChange = (($currentMonth - $previousMonth) / $percentageChange) * 100;
        }

        $isDecreasing = $percentageChange < 0;
        $color = $isDecreasing ? 'danger' : 'success';

        $description = $isDecreasing
            ? 'Decreased by ' . abs(round($percentageChange, 2)) . '%'
            : 'Increased by ' . round($percentageChange, 2) . '%';

        $descriptionIcon = $isDecreasing
            ? 'heroicon-m-arrow-trending-down'
            : 'heroicon-m-arrow-trending-up';

        return [
            'orderCount' => $orderCount,
            'currentMonthOrderCount' => $currentMonth,
            'previousMonthOrderCount' => $previousMonth,
            'color' => $color,
            'description' => $description,
            'descriptionIcon' => $descriptionIcon,
        ];
    }
}
