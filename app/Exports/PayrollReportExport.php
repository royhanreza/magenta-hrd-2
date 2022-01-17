<?php

namespace App\Exports;

// use App\Exports\Sheets\PayrollPerCompanySheet;

use App\Exports\Sheets\PayrollPerCompanySheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollReportExport implements WithMultipleSheets, ShouldAutoSize
{
    use Exportable;

    protected $startDatePeriod;
    protected $endDatePeriod;
    protected $staffOnly;

    public function __construct($startDatePeriod, $endDatePeriod, $staffOnly)
    {
        $this->startDatePeriod = $startDatePeriod;
        $this->endDatePeriod = $endDatePeriod;
        $this->staffOnly = $staffOnly;
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
            $sheets[] = new PayrollPerCompanySheet($initial, $this->startDatePeriod, $this->endDatePeriod, $this->staffOnly);
        }

        return $sheets;
    }
}

namespace App\Exports\Sheets;

use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PayrollPerCompanySheet implements FromView, WithTitle, ShouldAutoSize
{
    private $initial;
    protected $startDatePeriod;
    protected $endDatePeriod;
    protected $staffOnly;

    public function __construct($initial, $startDatePeriod, $endDatePeriod, $staffOnly)
    {
        $this->initial = $initial;
        $this->startDatePeriod = $startDatePeriod;
        $this->endDatePeriod = $endDatePeriod;
        $this->staffOnly = $staffOnly;
    }

    /**
     * @return Builder
     */
    public function view(): View
    {
        // $startDatePeriod = date('Y-m-d', strtotime($this->year . '-' . $this->month . '-'  . $this->firstDay . ' -1 month'));
        // $endDatePeriod = date('Y-m-d', strtotime($this->year . '-' . $this->month . '-'  . $this->firstDay . ' -1 day'));
        try {
            $permissions = json_decode(Auth::user()->role->role_permissions);
        } catch (\Throwable $th) {
            abort(500, $th);
        }

        $excludeType = ['freelancer'];
        $staffOnly = $this->staffOnly;
        if (!in_array("staffSalary", $permissions)) {
            array_push($excludeType, 'staff');
        } else {
            if ($staffOnly !== null && $staffOnly == "true") {
                $excludeType = ['freelancer', 'non staff'];
            }
        }

        $employees = Employee::with(['npwp', 'activeCareer', 'loans', 'activeCareer' => function ($query) {
            $query->with(['designation', 'department', 'jobTitle']);
        }, 'finalPayslips' => function ($query) {
            $query->where('type', 'fix_period')->where('start_date_period', $this->startDatePeriod)->where('end_date_period', $this->endDatePeriod);
        }])
            ->whereNotIn('type', $excludeType)
            ->where('employee_id', 'like', $this->initial . '%')
            ->where('is_active', 1)
            ->get()
            ->each(function ($employee) {
                $basicSalary = 0;
                $positionAllowance = 0;
                $attendanceAllowance = 0;
                $loan = 0;
                $excessLeave = 0;
                $total = 0;
                $tes = 'PAYSLIP < 0';
                $totalLoan = collect($employee->loans)->where('type', 'loan')->where('payslip_date', '<', $this->endDatePeriod)->sum('amount');
                $totalPayment = collect($employee->loans)->where('type', 'payment')->where('payslip_date', '<', $this->endDatePeriod)->sum('amount');
                $loanBalance = $totalLoan - $totalPayment;

                if (count($employee->finalPayslips) > 0) {
                    $payslip = collect($employee->finalPayslips)->first();
                    $incomes = json_decode($payslip->income);
                    $deductions = json_decode($payslip->deduction);
                    $basicSalary = collect($incomes)->where('type', 'gaji pokok')->sum('value');
                    $positionAllowance = collect($incomes)->where('name', 'Tunjangan Jabatan')->sum('value');
                    $attendanceAllowance = collect($incomes)->where('name', 'Insentif Kehadiran')->sum('value');
                    $loan = collect($deductions)->where('is_loan', 1)->sum('value');
                    $excessLeave = collect($deductions)->where('is_excess_leave', 1)->sum('value');
                    $total = ($basicSalary + $positionAllowance + $attendanceAllowance) - ($loan + $excessLeave);
                    // $attendanceAllowance = collect($incomes)->where('name', 'Insentif Kehadiran');
                    $tes = 'PAYSLIP > 0';
                }

                $employee['basic_salary'] = $basicSalary;
                $employee['position_allowance'] = $positionAllowance;
                $employee['attendance_allowance'] = $attendanceAllowance;
                $employee['loan'] = $loan;
                $employee['total_loan'] = $totalLoan;
                $employee['loan_balance'] = $loanBalance;
                $employee['excess_leave'] = $excessLeave;
                // $employee['tes'] = 'sadasdasd';
                $employee['total'] = $total;
                $employee['tes'] = $tes;
            });
        return view('payroll.report.monthly', [
            // 'invoices' => Invoice::all()
            'employees' => $employees,
            'start_date' => $this->startDatePeriod,
            'end_date' => $this->endDatePeriod,
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

    // public function exportMonthlyReport(Request $request)
    // {
    //     $startDatePeriod = $request->query('start_date_period');
    //     $endDatePeriod = $request->query('end_date_period');

    //     $employees = Employee::with(['npwp', 'activeCareer', 'activeCareer' => function ($query) {
    //         $query->with(['designation', 'department', 'jobTitle']);
    //     }, 'finalPayslips' => function ($query) use ($startDatePeriod, $endDatePeriod) {
    //         $query->where('type', 'fix_period')->where('start_date_period', $startDatePeriod)->where('end_date_period', $endDatePeriod);
    //     }])->get();
    //     // ->where('employee_id', 'like', 'MM' . '%')
    //     // ->get()->each(function ($employee) {
    //     //     $basicSalary = 0;
    //     //     $positionAllowance = 0;
    //     //     $attendanceAllowance = 0;
    //     //     $loan = 0;
    //     //     $excessLeave = 0;
    //     //     $total = 0;

    //     //     if (count($employee->finalPayslips) > 0) {
    //     //         $payslip = collect($employee->finalPayslips)->first();
    //     //         $incomes = json_decode($payslip->income);
    //     //         $deductions = json_decode($payslip->deduction);
    //     //         $basicSalary = collect($incomes)->where('type', 'gaji pokok')->sum('value');
    //     //         $positionAllowance = collect($incomes)->where('name', 'Tunjangan Jabatan')->sum('value');
    //     //         $attendanceAllowance = collect($incomes)->where('name', 'Insentif Kehadiran')->sum('value');
    //     //         $loan = collect($deductions)->where('is_loan', 1)->sum('value');
    //     //         $excessLeave = collect($deductions)->where('is_excess_leave', 1)->sum('value');
    //     //         $total = ($basicSalary + $positionAllowance + $attendanceAllowance) - ($loan + $excessLeave);
    //     //         $attendanceAllowance = collect($incomes)->where('name', 'Insentif Kehadiran');
    //     //     }

    //     //     $employee['basic_salary'] = $basicSalary;
    //     //     $employee['position_allowance'] = $positionAllowance;
    //     //     $employee['attendance_allowance'] = $attendanceAllowance;
    //     //     $employee['loan'] = $loan;
    //     //     $employee['excess_leave'] = $excessLeave;
    //     //     $employee['total'] = $total;
    //     // });

    //     // return $employees;

    //     return Excel::download(new PayrollReportExport($startDatePeriod, $endDatePeriod), 'Laporan Gaji ' . $startDatePeriod . ' - ' . $endDatePeriod . '.xlsx');
    // }
}
