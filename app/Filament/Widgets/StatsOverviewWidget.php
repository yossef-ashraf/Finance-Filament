<?php

namespace App\Filament\Widgets;

use App\Models\Credit;
use App\Models\Debt;
use App\Models\InvestmentAndSavings;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getCards(): array
    {
        $currentYear = Carbon::now()->year;
        
        $totalCredits = Credit::sum('amount');
        $totalDebtAmount = Debt::where('type', 1)->sum('amount');
        $totalInvestmentAmount = Debt::where('type', 2)->sum('amount');
        $totalOtherAmount = Debt::where('type', 0)->sum('amount');
        
        $totalCreditsCurrentYear = Credit::whereHas(
            'month',
            fn ($query) => $query->where('year', $currentYear)
        )->sum('amount');
        $totalDebtAmountCurrentYear = Debt::
        whereHas(
            'month',
            fn ($query) => $query->where('year', $currentYear)
        )->
        where('type', 1)->sum('amount');
        $totalInvestmentAmountCurrentYear = Debt::
        whereHas(
            'month',
            fn ($query) => $query->where('year', $currentYear)
        )->
        where('type', 2)->sum('amount');
        $totalOtherAmountCurrentYear = Debt::
        whereHas(
            'month',
            fn ($query) => $query->where('year', $currentYear)
        )->
        where('type', 0)->sum('amount');
        

        // Calculate amounts for InvestmentAndSavings
        $totalInvestmentAndSavingsAmount = InvestmentAndSavings::sum('amount'); 
        $totalInvestmentAndSavingsPrice = InvestmentAndSavings::sum('price');
        
        $balance = $totalCredits - ( $totalDebtAmount + $totalInvestmentAmount  + $totalOtherAmount );
        $balanceInvestment =   $totalInvestmentAndSavingsAmount -  $totalInvestmentAndSavingsPrice;
        
        return [
            Card::make('Total Revenue', number_format($totalCredits, 2) . ' EGP')
                ->description('Total revenue sum')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),
                
            Card::make('Debts', number_format($totalDebtAmount, 2) . ' EGP')
                ->description('Total debts sum')
                ->descriptionIcon('heroicon-s-credit-card')
                ->color('danger'),
            
            Card::make('Investments', number_format($totalInvestmentAmount, 2) . ' EGP')
                ->description('Total investments sum')
                ->descriptionIcon('heroicon-s-scale')
                ->color('warning'),
            
            Card::make('Other Amounts', number_format($totalOtherAmount, 2) . ' EGP')
                ->description('Total other amounts')
                ->descriptionIcon('heroicon-s-document')
                ->color('info'),


            // ----------------------------------------------

            Card::make("Total Revenue {$currentYear} ", number_format($totalCreditsCurrentYear, 2) . ' EGP')
            ->description("Total revenue sum {$currentYear} ")
            ->descriptionIcon("heroicon-s-currency-dollar")
            ->color("success"),
            
            Card::make("Debts {$currentYear} ", number_format($totalDebtAmountCurrentYear, 2) . ' EGP')
                ->description("Total Debts Sum {$currentYear} ")
                ->descriptionIcon("heroicon-s-credit-card")
                ->color("danger"),
            
            Card::make("Investments {$currentYear} ", number_format($totalInvestmentAmountCurrentYear, 2) . ' EGP')
                ->description("Total Investments Sum {$currentYear} ")
                ->descriptionIcon("heroicon-s-scale")
                ->color("warning"),
            
            Card::make("Other Amounts {$currentYear} ", number_format($totalOtherAmountCurrentYear, 2) . ' EGP')
                ->description("Total other amounts {$currentYear} ")
                ->descriptionIcon("heroicon-s-document")
                ->color("info"),


            // ---------------------------------------------- 
            Card::make('Current Balance', number_format($balance, 2) . ' EGP')
                ->description('Difference between revenue and expenses')
                ->descriptionIcon('heroicon-s-banknotes')
                ->color($balance >= 0 ? 'success' : 'danger'),
                
            Card::make('Total Investment (Amount)', number_format($totalInvestmentAndSavingsAmount, 2) . ' EGP')
                ->description('Total investment amount')
                ->descriptionIcon('heroicon-s-banknotes')
                ->color('success'),
            
            Card::make('Total Investment (Price)', number_format($totalInvestmentAndSavingsPrice, 2) . ' EGP')
                ->description('Total investment price')
                ->descriptionIcon('heroicon-s-chart-bar')
                ->color('primary'),

            Card::make('Total Investment Profit (Profit)', number_format($balanceInvestment, 2) . ' EGP')
                ->description('Total investment profit')
                ->descriptionIcon('heroicon-s-chart-bar')
                ->color('primary'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
