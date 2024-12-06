<?php
namespace App\Filament\Widgets;

use App\Models\Debt;
use Filament\Widgets\PieChartWidget;

class ExpenseDistributionWidget extends PieChartWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Expense and Investment Breakdown';

    protected function getData(): array
    {
        // Collect and group debts for detailed breakdown
        $debts = Debt::selectRaw('name, type, SUM(amount) as total')
            ->groupBy('name', 'type')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                // Map debt type labels
                $typeLabel = match ($item->type) {
                    1 => 'Liability',
                    2 => 'Investment',
                    0 => 'Other',
                    default => 'Undefined'
                };

                return [
                    'name' => $item->name,
                    'total' => $item->total,
                    'type' => $typeLabel
                ];
            });

        return [
            'datasets' => [
                [
                    'data' => $debts->pluck('total'),
                    'backgroundColor' => $this->generateColorPalette($debts->count()),
                ],
            ],
            'labels' => $debts->map(function ($item) {
                return "{$item['name']} ({$item['type']})";
            }),
        ];
    }

    // Generate a gradient and consistent color palette
    private function generateColorPalette($count)
    {
        $baseColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#E7E9ED', '#42A5F5', '#66BB6A', '#FFA726'
        ];

        // Extend the colors if more are needed
        $colors = array_merge(
            $baseColors,
            array_map(function ($color) {
                return $this->adjustBrightness($color, 0.7);
            }, $baseColors)
        );

        return array_slice($colors, 0, $count);
    }

    // Adjust the brightness of a given color
    private function adjustBrightness($hexColor, $adjustment)
    {
        // Remove the '#' if present
        $hexColor = ltrim($hexColor, '#');

        // Convert HEX to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        // Adjust brightness
        $r = max(0, min(255, $r * $adjustment));
        $g = max(0, min(255, $g * $adjustment));
        $b = max(0, min(255, $b * $adjustment));

        // Convert RGB back to HEX
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    // Provide additional chart description
    public function getDescription(): string
    {
        $totalDebts = Debt::sum('amount');
        $debtTypeSummary = Debt::selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                $typeLabel = match ($item->type) {
                    1 => 'Liabilities',
                    2 => 'Investments',
                    0 => 'Other Expenses',
                    default => 'Undefined'
                };
                return [$typeLabel => $item->total];
            });

        $descriptionParts = [];
        foreach ($debtTypeSummary as $type => $amount) {
            $percentage = ($amount / $totalDebts) * 100;
            $descriptionParts[] = sprintf('%s: %.2f%%', $type, $percentage);
        }

        return 'Distribution: ' . implode(' | ', $descriptionParts);
    }
}
