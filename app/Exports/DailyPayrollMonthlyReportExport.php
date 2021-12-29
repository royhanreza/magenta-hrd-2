<?php

namespace App\Exports;

// use App\Exports\Sheets\PayrollPerCompanySheet;

use App\Exports\Sheets\DailyPayrollPerCompanySheet;
use App\Exports\Sheets\PayrollPerCompanySheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DailyPayrollMonthlyReportExport implements WithMultipleSheets, ShouldAutoSize
{
    use Exportable;

    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $companyInitials = ['MM', 'UL', 'SRC', 'BIS', 'EO', 'OELLO'];

        // for ($month = 1; $month <= 12; $month++) {
        //     $sheets[] = new PayrollPerCompanySheet($this->year, $month);
        // }
        foreach ($companyInitials as $initial) {
            $sheets[] = new DailyPayrollPerCompanySheet($initial, $this->month, $this->year);
        }

        return $sheets;
    }
}

namespace App\Exports\Sheets;

use App\Models\Employee;
use App\Models\FinalPayslip;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailyPayrollPerCompanySheet implements FromView, WithTitle, ShouldAutoSize
{
    protected $initial;
    protected $month;
    protected $year;

    public function __construct($initial, $month, $year)
    {
        $this->initial = $initial;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @return Builder
     */
    public function view(): View
    {

        $finalPayslips = FinalPayslip::with(['employee', 'employee.careers' => function ($query) {
            $query->where('is_active', 1);
        }])
            ->whereHas('employee', function ($q) {
                $q->where('employee_id', 'like', $this->initial . '%');
            })
            ->where('type', 'custom_period')
            ->whereMonth('end_date_period', $this->month)
            ->whereYear('end_date_period', $this->year)
            ->get()
            ->each(function ($payslip) {
                $payslip['period'] = $payslip->start_date_period . ' - ' . $payslip->end_date_period;
                $payslip['income'] = json_decode($payslip->income);

                $filteredIncome = collect($payslip->income)->filter(function ($income) {
                    return $income->attendance !== null;
                })->all();

                $payslip['total_daily_money'] = collect($filteredIncome)->map(function ($income) {
                    return $income->attendance->daily_money;
                })->sum();

                $payslip['total_overtime_pay'] = collect($filteredIncome)->map(function ($income) {
                    return $income->attendance->overtime_pay;
                })->sum();

                $payslip['amount'] = $payslip['total_daily_money'] + $payslip['total_overtime_pay'];
            })
            ->groupBy('period')
            ->all();

        return view('daily-payroll.report.monthly', [
            'final_payslips' => $finalPayslips,
            // 'invoices' => Invoice::all()
            // 'employees' => $employees,
            // 'start_date' => $this->startDatePeriod,
            // 'end_date' => $this->endDatePeriod,
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->initial;
    }

    // public function determineCompany($initial)
    // {
    //     switch($initial) {
    //         case 'MM':
    //             return 'Magenta Mediatama';
    //     }
    // }
}
