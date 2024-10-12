<?php

namespace App\Filament\Widgets;

use App\Models\Month;
use Filament\Widgets\LineChartWidget;

class MonthlyChartWidget extends LineChartWidget
{
    protected static ?int $sort = 2;
    
    protected static ?string $heading = 'الإيرادات والمصروفات الشهرية';

    protected function getData(): array
    {
        $data = Month::with(['credits', 'debts'])
            ->orderBy('id')
            // ->orderBy('month')
            ->get()
            ->map(function ($month) {
                return [
                    'x' => $month->month . ' ' . $month->year,
                    'y' => [
                        $month->credits->sum('amount'),
                        $month->debts->sum('amount'),
                    ],
                ];
            });

        return [
            'datasets' => [
                [
                    'label' => 'الإيرادات',
                    'data' => $data->pluck('y.0'),
                    'borderColor' => '#10B981',
                ],
                [
                    'label' => 'المصروفات',
                    'data' => $data->pluck('y.1'),
                    'borderColor' => '#EF4444',
                ],
            ],
            'labels' => $data->pluck('x'),
        ];
    }
}
