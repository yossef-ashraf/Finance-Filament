<?php

namespace App\Filament\Widgets;

use App\Models\Debt;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyDebtsChartWidget extends BarChartWidget
{
    protected static bool $isDiscovered = false;
    protected static ?string $heading = 'المصروفات الشهرية';

    protected function getData(): array
    {
        $data = Debt::select(
            DB::raw('SUM(amount) as total'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->whereYear('created_at', '>=', now()->subYear()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'المصروفات',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => '#EF4444',
                ],
            ],
            'labels' => $data->pluck('month')->map(function($month) {
                return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('M Y');
            }),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => "function(value) { return value.toLocaleString('ar-SA', { style: 'currency', currency: 'SAR' }); }",
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) { return context.parsed.y.toLocaleString('ar-SA', { style: 'currency', currency: 'SAR' }); }",
                    ],
                ],
            ],
        ];
    }
}