<?php
namespace App\Filament\Widgets;

use App\Models\Credit;
use App\Models\Debt;
use Filament\Widgets\PieChartWidget;

class ExpenseRatioWidget extends PieChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Detailed Financial Analysis';

    protected function getData(): array
    {
        $totalCredits = Credit::sum('amount');
        $totalDebts = Debt::sum('amount');
        
        // Breakdown of expenses by type
        $debtTypes = [
            'Debts' => Debt::where('type', 1)->sum('amount'),
            'Investments' => Debt::where('type', 2)->sum('amount'),
            'Other Expenses' => Debt::where('type', 0)->sum('amount')
        ];

        $remaining = max(0, $totalCredits - $totalDebts);

        return [
            'datasets' => [
                [
                    'data' => [
                        $debtTypes['Debts'], 
                        $debtTypes['Investments'], 
                        $debtTypes['Other Expenses'], 
                        $remaining
                    ],
                    'backgroundColor' => [
                        '#FF6384',  // Pink for debts
                        '#36A2EB',  // Light blue for investments
                        '#FFCE56',  // Yellow for other expenses
                        '#10B981'   // Green for remaining balance
                    ],
                ],
            ],
            'labels' => [
                'Debts', 
                'Investments', 
                'Other Expenses', 
                'Remaining Balance'
            ],
        ];
    }

    public function getDescription(): string
    {
        $totalCredits = Credit::sum('amount');
        $totalDebts = Debt::sum('amount');
        $remainingPercentage = $totalCredits > 0 
            ? (($totalCredits - $totalDebts) / $totalCredits) * 100 
            : 0;

        return sprintf(
            'Total Revenue: %.2f EGP | Total Expenses: %.2f EGP | Remaining Percentage: %.2f%%', 
            $totalCredits, 
            $totalDebts, 
            $remainingPercentage
        );
    }
}
