<?php

namespace App\Filament\Widgets;

use App\Models\Debt;
use Filament\Widgets\PieChartWidget;

class ExpenseDistributionWidget extends PieChartWidget
{
    protected static ?int $sort = 4;
    
    protected static ?string $heading = 'توزيع المصروفات';

    protected function getData(): array
    {
        $debts = Debt::selectRaw('name, SUM(amount) as total')
            ->groupBy('name')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $debts->pluck('total'),
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                    ],
                ],
            ],
            'labels' => $debts->pluck('name'),
        ];
    }
}
