<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;
class AppCount extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Product::count();
        $totalordeer = Order::count();
        return [
           Stat::make("total Product" , $total),
           Stat::make("total order" , $totalordeer)
        ];
    }
}
