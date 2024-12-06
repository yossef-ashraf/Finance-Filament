<?php
namespace App\Exports;

use App\Models\Month;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\View;

class FinancialReportExport implements FromView, WithHeadings
{
    public function view(): \Illuminate\View\View
    {
        // Retrieve the data for the current and previous years
        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->subYear()->year;

        // Get the months for both years
        $monthsCurrentYear = Month::where('year', $currentYear)->with(['credits', 'debts'])->get();
        $monthsPreviousYear = Month::where('year', $previousYear)->with(['credits', 'debts'])->get();
        
        // Return the view and pass both data sets
        return View::make('financial_report', compact('monthsCurrentYear', 'monthsPreviousYear'));
    }

    public function headings(): array
    {
        return [
            'Year',
            'Month',
            'Total Monthly Revenues',
            'Monthly Liabilities',
            'Monthly Investments',
            'Other Monthly Expenses',
            'Monthly Balance',
            'Monthly Balance Percentage',
        ];
    }
}
