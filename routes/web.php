<?php

use App\Services\GoldApiService;
use Illuminate\Support\Facades\Route;
use App\Exports\FinancialReportExport;
use Maatwebsite\Excel\Facades\Excel;

// Home route
Route::get('/', function () {
    // Uncomment if you want to use GoldApiService
    // $GoldApiService = new GoldApiService();
    // $GoldApiService->handle();
    return view('welcome');
});

// Route for exporting financial report
Route::get('/export/financial-report', function () {
    return Excel::download(new FinancialReportExport(), 'financial_report_' . now()->format('Y_m_d') . '.xlsx');
})->name('financial_report');