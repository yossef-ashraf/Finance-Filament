<?php

namespace App\Filament\Widgets;

use App\Models\Credit;
use App\Models\Debt;
use Filament\Widgets\PieChartWidget;

class ExpenseRatioWidget extends PieChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'نسبة المصروفات من الإيرادات';

    protected function getData(): array
    {
        $totalCredits = Credit::sum('amount');
        $totalDebts = Debt::sum('amount');
        $remaining = max(0, $totalCredits - $totalDebts);

        return [
            'datasets' => [
                [
                    'data' => [$totalDebts, $remaining],
                    'backgroundColor' => ['#EF4444', '#10B981'],
                ],
            ],
            'labels' => ['المصروفات', 'المتبقي'],
        ];
    }
}
