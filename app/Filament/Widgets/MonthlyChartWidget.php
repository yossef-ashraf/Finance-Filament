<?php

namespace App\Filament\Widgets;

use App\Models\Month;
use App\Models\Debt;
use Filament\Widgets\LineChartWidget;

class MonthlyChartWidget extends LineChartWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;
    
    protected static ?string $heading = 'Detailed Monthly Financial Analysis';

    protected function getData(): array
    {
        $data = Month::with(['credits', 'debts'])
            ->orderBy('id')
            ->get()
            ->map(function ($month) {
                // Classifying debts by type
                $debtTypes = [
                    'total_debts' => $month->debts->sum('amount'),
                    'debt_type' => $month->debts->where('type', 1)->sum('amount'),
                    'investment_type' => $month->debts->where('type', 2)->sum('amount'),
                    'other_type' => $month->debts->where('type', 0)->sum('amount')
                ];

                return [
                    'x' => $month->month . ' ' . $month->year,
                    'y' => [
                        'credits' => $month->credits->sum('amount'),
                        'total_debts' => $debtTypes['total_debts'],
                        'debt_type' => $debtTypes['debt_type'],
                        'investment_type' => $debtTypes['investment_type'],
                        'other_type' => $debtTypes['other_type']
                    ],
                ];
            });

        return [
            'datasets' => [
                // Revenue
                [
                    'label' => 'Revenue',
                    'data' => $data->pluck('y.credits'),
                    'borderColor' => '#10B981', // Bright green
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)', // Light green background
                ],
                // Total Expenses
                // [
                //     'label' => 'Total Expenses',
                //     'data' => $data->pluck('y.total_debts'),
                //     'borderColor' => '#EF4444', // Bright red
                //     'backgroundColor' => 'rgba(239, 68, 68, 0.2)', // Red transparent background
                // ],
                // Debts
                [
                    'label' => 'Debts',
                    'data' => $data->pluck('y.debt_type'),
                    'borderColor' => '#EF4444', // Bright red
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)', // Red transparent background
                ],
                // Investments
                [
                    'label' => 'Investments',
                    'data' => $data->pluck('y.investment_type'),
                    'borderColor' => '#6366F1', // Purple
                    'backgroundColor' => 'rgba(99, 102, 241, 0.2)',
                ],
                // Other Amounts
                [
                    'label' => 'Other Amounts',
                    'data' => $data->pluck('y.other_type'),
                    'borderColor' => '#0EA5E9', // Blue
                    'backgroundColor' => 'rgba(14, 165, 233, 0.2)',
                ]
            ],
            'labels' => $data->pluck('x'),
        ];
    }
}
