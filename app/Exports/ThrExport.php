<?php

namespace App\Exports;

// use App\Exports\Sheets\PayrollPerCompanySheet;

use App\Exports\Sheets\PayrollPerCompanySheet;
use App\Exports\Sheets\ThrPerCompanySheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ThrExport implements WithMultipleSheets, ShouldAutoSize
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
            $sheets[] = new ThrPerCompanySheet($initial, $this->startDatePeriod, $this->endDatePeriod, $this->staffOnly);
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

class ThrPerCompanySheet implements FromView, WithTitle, ShouldAutoSize
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
            $query->where('type', 'thr')->where('start_date_period', $this->startDatePeriod)->where('end_date_period', $this->endDatePeriod);
        }])
            ->whereNotIn('type', $excludeType)
            ->where('employee_id', 'like', $this->initial . '%')
            ->where('is_active', 1)
            ->get()
            ->each(function ($employee) {
                $basicSalary = 0;

                if (count($employee->finalPayslips) > 0) {
                    $payslip = collect($employee->finalPayslips)->first();
                    $incomes = json_decode($payslip->income);
                    $value = collect($incomes)->first();
                    if (isset($value->value)) {
                        $basicSalary = $value->value;
                    }
                }

                $employee['basic_salary'] = $basicSalary;
            });
        return view('thr.report', [
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
}
