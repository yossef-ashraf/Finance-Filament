<?php

namespace App\Filament\Widgets;

use App\Models\Credit;
use App\Models\Debt;
use App\Models\InvestmentAndSavings;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getCards(): array
    {
        $totalCredits = Credit::sum('amount');
        $totalDebts = Debt::sum('amount');
        $totalInvestmentAndSavings = InvestmentAndSavings::sum('amount');
        $balance = $totalCredits - $totalDebts;

        return [
            Card::make('إجمالي الإيرادات', number_format($totalCredits, 2) . ' جنيه')
                ->description('مجموع كل الإيرادات')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),
            Card::make('إجمالي المصروفات', number_format($totalDebts, 2) . ' جنيه')
                ->description('مجموع كل المصروفات')
                ->descriptionIcon('heroicon-s-credit-card')
                ->color('danger'),
            Card::make('الرصيد الحالي', number_format($balance, 2) . ' جنيه')
                ->description('الفرق بين الإيرادات والمصروفات')
                ->descriptionIcon('heroicon-s-banknotes')
                ->color($balance >= 0 ? 'success' : 'danger'),
            Card::make('إجمالي الاستثمار والادخار', number_format($totalInvestmentAndSavings, 2) . ' جنيه')
            ->description('مجموع كل الاستثمار والادخار')
            ->descriptionIcon('heroicon-s-scale')
            ->color($totalInvestmentAndSavings >= 0 ? 'success' : 'danger'),
        ];
    }
}
