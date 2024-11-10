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

//user register
//User Login
//User view/update Profile /Password
//Manage user's Vechiels
//get prices for zones /area
//Start/stop parking
//view total price
//**** payments


//*** what are dealing with applications
//Users
//Cars/Vehicles
//Parking Zones / area with itis prices
//Parking Events strat:stop


//
