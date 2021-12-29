<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\Career;
use App\Models\FinalPayslip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

class BpjsReportExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    protected $month;
    protected $year;

    function __construct($request, $month, $year)
    {
        $this->request = $request;
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember'];

        $bpjsReport = $this->generateBpjs($this->request);

        return view('report.bpjs.excel', [
            'bpjs' => $bpjsReport,
            'month' => $months[(int) $this->month - 1],
            'year' => $this->year,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'D' => '#,##0.00_-',
            'E' => '#,##0.00_-',
            'F' => '#,##0.00_-',
            'G' => '#,##0.00_-',
            'H' => '#,##0.00_-',
            'I' => '#,##0.00_-',
            'J' => '#,##0.00_-',
            'K' => '#,##0.00_-',
            'L' => '#,##0.00_-',
            'M' => '#,##0.00_-',
            'N' => '#,##0.00_-',
            'O' => '#,##0.00_-',
            'P' => '#,##0.00_-',
            'Q' => '#,##0.00_-',
            'R' => '#,##0.00_-',
            'S' => '#,##0.00_-',
            'T' => '#,##0.00_-',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'C') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // if (is_numeric($value)) {
        //     $cell->setValueExplicit($value, DataType::TYPE_STRING);

        //     return true;
        // }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function generateBpjs($request)
    {
        // $month = $request->query('month');
        // $year = $request->query('year');
        $month = $this->month;
        $year = $this->year;

        $finalPayslips = FinalPayslip::query()
            ->with(['employee'])
            ->where('type', 'fix_period')
            // ->whereIn('pay_slip_id', [2])
            // ->whereBetween('start_date_period', [$startDatePeriod, $endDatePeriod])
            // ->orWhereBetween('end_date_period', [$startDatePeriod, $endDatePeriod])
            ->whereMonth('end_date_period', $month)
            ->whereYear('end_date_period', $year)
            ->get()
            // ->where('pay_slip_id', $id)
            ->each(function ($item, $key) {
                $item->income = json_decode($item->income);
                $item->deduction = json_decode($item->deduction);
            });

        // return $finalPayslips;

        // return $minimumDate;
        // return date_create($minimumDate);

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo'];

        $previewPayslips = Career::query()
            ->whereHas('payslips')
            ->where('is_active', 1)
            ->with(['employee' => function ($q) use ($employeeColumns) {
                $q->select($employeeColumns)->with('npwp', 'bpjs');
            }, 'payslips', 'designation', 'jobTitle'])
            ->get();



        $previewPayslips = $previewPayslips->each(function ($item, $key) use ($finalPayslips) {

            if (count($item->payslips) > 0) {
                foreach ($item->payslips as $payslip) {
                    $payslip->pivot->incomes = json_decode($payslip->pivot->incomes);
                    $payslip->pivot->deductions = json_decode($payslip->pivot->deductions);

                    $basicSalary = collect($payslip->pivot->incomes)->firstWhere('type', 'gaji pokok');

                    // $allowances = collect($payslip->pivot->incomes)->where('thr_income', '1')->toArray();
                    $allowances = collect($payslip->pivot->incomes)->whereNotIn('type', ['gaji pokok'])->sum('value');

                    $finalPayslipAllowances = collect($finalPayslips)->where('employee_id', $item->employee_id)->flatMap(function ($finalPayslip, $key) {
                        return $finalPayslip->income;
                    })->where('type', 'Manual')->where('type_a1', 'type_a1_1')->sum('value');

                    // $basicSalaryValue = 0;
                    $item['basic_salary'] = 0;
                    $item['basic_salary_bpjs'] = 0;
                    $item['allowances'] = 0;
                    $item['incentive'] = 0;
                    $item['total_salary'] = 0;
                    $item['jkm_company'] = 0;
                    $item['jkk_company'] = 0;
                    $item['jht_company'] = 0;
                    $item['jp_company'] = 0;
                    $item['kesehatan_company'] = 0;
                    $item['total_income_salary'] = 0;
                    $item['jht_employee'] = 0;
                    $item['kesehatan_employee'] = 0;
                    $item['jp_employee'] = 0;
                    $item['total_deduction_salary'] = 0;
                    $item['total_bpjs'] = 0;
                    $item['received_salary'] = 0;


                    if ($basicSalary !== null) {
                        // Gapok
                        $basicSalaryValue = $basicSalary->value;
                        // Gapok BPJS
                        $basicSalaryBpjs = $item->employee->bpjs->wage;
                        // Tunjangan
                        $finalAllowances = $basicSalaryValue - $basicSalaryBpjs;
                        // if ($finalAllowances < 0) {
                        //     $basicSalaryBpjs = $basicSalaryValue;
                        // }
                        // Insentif
                        $incentive = $allowances + $finalPayslipAllowances;
                        // Total Gaji
                        $totalSalary = $basicSalaryBpjs + $finalAllowances + $incentive;
                        // JKM (COM)
                        $jkmCompany = $basicSalaryBpjs * ($item->employee->bpjs->jkm_company_percentage / 100);
                        // JKK (COM)
                        $jkkCompany = $basicSalaryBpjs * ($item->employee->bpjs->jkk_company_percentage / 100);
                        // JHT (COM)
                        $jhtCompany = $basicSalaryBpjs * ($item->employee->bpjs->jht_company_percentage / 100);
                        // JP (COM)
                        $jpCompany = $basicSalaryBpjs * ($item->employee->bpjs->jp_company_percentage / 100);
                        // Kesehatan (COM)
                        $kesehatanCompany = $basicSalaryBpjs * ($item->employee->bpjs->kesehatan_company_percentage / 100);
                        // Total Penambahan Gaji
                        $totalIncomeSalary = $jkmCompany + $jkkCompany + $jhtCompany + $jpCompany + $kesehatanCompany;
                        // JHT (EMP)
                        $jhtEmployee = $basicSalaryBpjs * ($item->employee->bpjs->jht_personal_percentage / 100);
                        // Kesehatan (EMP)
                        $kesehatanEmployee = $basicSalaryBpjs * ($item->employee->bpjs->kesehatan_personal_percentage / 100);
                        // Pensiun (EMP)
                        $jpEmployee = $basicSalaryBpjs * ($item->employee->bpjs->jp_personal_percentage / 100);
                        // Total Pengurangan Gaji
                        $totalDeductionSalary = $jhtEmployee + $kesehatanEmployee + $jpEmployee;
                        // Total Pembayaran Ke BPJS
                        $totalBpjs = $totalIncomeSalary + $totalDeductionSalary;
                        // Gaji yang diterima
                        $receivedSalary  = $totalSalary - $totalIncomeSalary;

                        $item['basic_salary'] = $basicSalaryValue;
                        $item['basic_salary_bpjs'] = $basicSalaryBpjs;
                        $item['allowances'] = $finalAllowances;
                        $item['incentive'] = $incentive;
                        $item['total_salary'] = $totalSalary;
                        $item['jkm_company'] = $jkmCompany;
                        $item['jkk_company'] = $jkkCompany;
                        $item['jht_company'] = $jhtCompany;
                        $item['jp_company'] = $jpCompany;
                        $item['kesehatan_company'] = $kesehatanCompany;
                        $item['total_income_salary'] = $totalIncomeSalary;
                        $item['jht_employee'] = $jhtEmployee;
                        $item['kesehatan_employee'] = $kesehatanEmployee;
                        $item['jp_employee'] = $jpEmployee;
                        $item['total_deduction_salary'] = $totalDeductionSalary;
                        $item['total_bpjs'] = $totalBpjs;
                        $item['received_salary'] = $receivedSalary;

                        break;
                    }
                }
            }
        })->map(function ($item, $key) {
            return collect($item)->except(['payslips'])->toArray();
        })->all();

        return $previewPayslips;
        // return collect($previewPayslips)->map(function ($item, $key) {
        //     return [

        //     ]
        // });

        // return [
        //     'preview_payslips' => $previewPayslips,
        //     'final_payslip' => $finalPayslips,
        // ];

        // return view('report.bpjs.excel', [
        //     // 'payslips' => $payslips,
        //     // 'payslip' => $payslip,
        //     'name' => ,
        // ]);

        // $payslip = PaySlip::findOrFail($id);

        // $startDatePeriod = date($year . '-01-01');
        // $endDatePeriod = date(($year + 1) . '-01-01');


        // return [
        //     'preview_payslips' => $previewPayslips,
        //     'final_payslips' => $finalPayslips,
        //     'start_date_period' => $startDatePeriod,
        //     'end_date_period' => Carbon::parse($endDatePeriod)->subDay()->toDateString(),
        // ];

        // return $previewPayslips;


        // return view('thr.index', [
        //     // 'payslips' => $payslips,
        //     // 'payslip' => $payslip,
        //     'preview_payslips' => $previewPayslips,
        //     'start_date_period' => $startDatePeriod,
        //     'end_date_period' => Carbon::parse($endDatePeriod)->subDay()->toDateString(),
        // ]);
    }
}
