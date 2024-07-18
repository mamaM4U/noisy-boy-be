<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    private function getPrecentate(int $from, int $to){
        return $to-$from/($to+$from/2)*100;
    }
    protected function getStats(): array
    {
        $newListing = Listing::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $transactions = Transaction::whereStatus('approved')->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year);
        $prevTransactions = Transaction::whereStatus('approved')->whereMonth('start_date', Carbon::now()->subMonth()->month)->whereYear('start_date', Carbon::now()->subMonth()->year);
        $transactionsPrecentage = $this->getPrecentate($transactions->count(), $prevTransactions->count());
        $revenuePrecentage = $this->getPrecentate($transactions->sum('total_price'), $prevTransactions->sum('total_price'));
        return [
            Stat::make('New listing of the month', $newListing),
            Stat::make('Transaction of the month', $transactions->count())
                ->description($transactionsPrecentage > 0? Number::percentage($transactionsPrecentage,2)." increased" : Number::percentage($transactionsPrecentage,2)." decreased")
                ->descriptionIcon($transactionsPrecentage > 0? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($transactionsPrecentage > 0? 'success' : 'danger'),
            Stat::make('Revenue of the month', Number::currency($transactions->sum('total_price'), 'USD'))
                ->description($revenuePrecentage > 0? Number::percentage($revenuePrecentage,2)." increased" :Number::percentage($revenuePrecentage,2)." decreased")
                ->descriptionIcon($revenuePrecentage > 0? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenuePrecentage > 0? 'success' : 'danger')    
        ];
    }
}
