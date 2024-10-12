<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startYear = 2023;
        $startMonth = 10; // أكتوبر
        $endYear = 2025;

        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        // حلقة لإضافة الأشهر من أكتوبر 2023 إلى نهاية 2025
        for ($year = $startYear; $year <= $endYear; $year++) {
            foreach ($months as $key => $monthName) {
                // يبدأ من شهر أكتوبر في 2023 فقط
                if ($year == 2023 && $key < $startMonth) {
                    continue;
                }
                Month::create([
                    'month' => $monthName,
                    'year' => $year,
                ]);
            }
        }
    }
}
