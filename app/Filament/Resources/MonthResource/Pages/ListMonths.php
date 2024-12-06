<?php

namespace App\Filament\Resources\MonthResource\Pages;

use App\Filament\Export\MonthResourceExporter;
use App\Filament\Resources\MonthResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel; // تأكد من استيراد المكتبة
use App\Exports\FinancialReportExport; // استيراد كلاس التصدير

class ListMonths extends ListRecords
{
    protected static string $resource = MonthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('export')
                ->label('Export to Excel')
                ->icon('heroicon-o-cube-transparent') // يمكنك اختيار أي أيقونة تفضلها
                ->action(function () {
                    return Excel::download(new FinancialReportExport(), 'financial_report_' . now()->format('Y_m_d') . '.xlsx');
                }),
        ];
    }
}