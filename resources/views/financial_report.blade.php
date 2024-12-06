<div class="container">

<h3>Total Financial Report {{  Carbon\Carbon::now()->subYear()->year }}</h3>
<table class="table table-bordered">
        <thead>
            <tr>
                <th>Year</th>
                <th>Total Monthly Revenues</th>
                <th>Total Monthly Liabilities</th>
                <th>Total Monthly Investments</th>
                <th>Total Other Monthly Expenses</th>
                <th>Total Monthly Balance</th>
                <th>Monthly Balance Percentage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @php
                    $year = $monthsPreviousYear->first()->year ?? Carbon\Carbon::now()->subYear()->year ;
                    $total_revenue = $monthsPreviousYear->sum(function($month) { return $month->credits->sum('amount'); });
                    $total_liability = $monthsPreviousYear->sum(function($month) { return $month->debts->where('type', 1)->sum('amount'); });
                    $total_investment = $monthsPreviousYear->sum(function($month) { return $month->debts->where('type', 2)->sum('amount'); });
                    $total_expense = $monthsPreviousYear->sum(function($month) { return $month->debts->where('type', 0)->sum('amount'); });
                    $total_balance = $monthsPreviousYear->sum(function($month) { return $month->credits->sum('amount'); }) - $monthsPreviousYear->sum(function($month) { return $month->debts->sum('amount'); });
                    $total_balance_percentage = calculateBalancePercentageTotal($total_revenue ,( $total_liability +$total_investment +$total_expense ) );
                @endphp
                <td>{{$year   }}</td>
                <td>{{$total_revenue  }}</td>
                <td>{{$total_liability  }}</td>
                <td>{{$total_investment  }}</td>
                <td>{{$total_expense  }}</td>
                <td>{{$total_balance  }}</td>
                <td>{{ $total_balance_percentage }}%</td>
            </tr>
        </tbody>
</table>


<h3>Total Financial Report {{ Carbon\Carbon::now()->year }}</h3>
<table class="table table-bordered">
        <thead>
            <tr>
                <th>Year</th>
                <th>Total Monthly Revenues</th>
                <th>Total Monthly Liabilities</th>
                <th>Total Monthly Investments</th>
                <th>Total Other Monthly Expenses</th>
                <th>Total Monthly Balance</th>
                <th>Monthly Balance Percentage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @php
                    $year = $monthsCurrentYear->first()->year;
                    $total_revenue = $monthsCurrentYear->sum(function($month) { return $month->credits->sum('amount'); });
                    $total_liability = $monthsCurrentYear->sum(function($month) { return $month->debts->where('type', 1)->sum('amount'); });
                    $total_investment = $monthsCurrentYear->sum(function($month) { return $month->debts->where('type', 2)->sum('amount'); });
                    $total_expense = $monthsCurrentYear->sum(function($month) { return $month->debts->where('type', 0)->sum('amount'); });
                    $total_balance = $monthsCurrentYear->sum(function($month) { return $month->credits->sum('amount'); }) - $monthsCurrentYear->sum(function($month) { return $month->debts->sum('amount'); });
                    $total_balance_percentage = calculateBalancePercentageTotal($total_revenue ,( $total_liability +$total_investment +$total_expense ) );
                @endphp
                <td>{{$year  }}</td>
                <td>{{$total_revenue  }}</td>
                <td>{{$total_liability  }}</td>
                <td>{{$total_investment  }}</td>
                <td>{{$total_expense  }}</td>
                <td>{{$total_balance  }}</td>
                <td>{{ $total_balance_percentage }}%</td>
            </tr>
        </tbody>
</table>


<h3>Financial Report for {{ Carbon\Carbon::now()->year }}</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Year</th>
            <th>Month</th>
            <th>Total Monthly Revenues</th>
            <th>Monthly Liabilities</th>
            <th>Monthly Investments</th>
            <th>Other Monthly Expenses</th>
            <th>Monthly Balance</th>
            <th>Monthly Balance Percentage</th>
        </tr>
    </thead>
    <tbody>
        @foreach($monthsCurrentYear as $month)
        <tr>
            <td>{{ $month->year }}</td>
            <td>{{ $month->month }}</td>
            <td>{{ $month->credits->sum('amount') }}</td>
            <td>{{ $month->debts->where('type', 1)->sum('amount') }}</td>
            <td>{{ $month->debts->where('type', 2)->sum('amount') }}</td>
            <td>{{ $month->debts->where('type', 0)->sum('amount') }}</td>
            <td>{{ $month->credits->sum('amount') - $month->debts->sum('amount') }}</td>
            <td>{{ calculateBalancePercentage($month) }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>



<h3> Financial Analysis Report Detailed</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Credits</th>
            <th>Debt</th>
            <th>Cach</th>
            
            <th>Investment And Savings Amount</th>
            <th>Investment And Savings Price</th>
            <th>Balance Investment</th>

            <th>Balance Amount</th>

        </tr>
    </thead>
    <tbody>

        <tr>
            <td>{{ $totalCredits }}</td>
            <td>{{ $totalDebtAmount }}</td>
            <td>{{ $cach }}</td>

            <td>{{ $totalInvestmentAndSavingsAmount }}</td>
            <td>{{ $totalInvestmentAndSavingsPrice }}</td>
            <td>{{ $balanceInvestment }}</td>

            <td>{{ $balance }}</td>

        </tr>

    </tbody>
</table>




</div>

@php
    function calculateBalancePercentage($month)
    {
        $credits = $month->credits->sum('amount');
        $debts = $month->debts->sum('amount');
        return $credits > 0 ? round(($credits - $debts) / $credits * 100, 2) : 0;
    }

    function calculateBalancePercentageTotal($credits , $debts)
    {

        return $credits > 0 ? round(($credits - $debts) / $credits * 100, 2) : 0;
    }

@endphp
