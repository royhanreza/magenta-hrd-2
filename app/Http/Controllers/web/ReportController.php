<?php

namespace App\Http\Controllers\web;

use App\Exports\BpjsReportExport;
use App\Exports\PphReportExport;
use App\Http\Controllers\Controller;
use App\Models\BpjsReport;
use App\Models\Calendar;
use App\Models\Career;
use App\Models\Employee;
use App\Models\FinalPayslip;
use App\Models\PphReport;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    public function bpjs(Request $request)
    {
        $year = $request->query('year');
        if ($year == null) {
            $year = date("Y");
        }

        $monthIndex = [];
        for ($i = 1; $i <= 12; $i++) {
            array_push($monthIndex, sprintf('%02d', $i));
        }

        $reports = BpjsReport::query()
            ->where('year', $year)
            ->whereIn('month', $monthIndex)
            ->get();

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $monthlyReports = collect($monthIndex)->map(function ($index, $key) use ($reports, $year) {
            $report = $reports->where('month', $index)->first();
            if ($report !== null) {
                return $report;
            }

            return [
                'month' => $index,
                'year' => $year,
                'file' => null,
            ];
        })->all();

        // return $monthlyReports;

        return view('report.bpjs', [
            'reports' => $monthlyReports,
            'months' => $months,
            'year' => $year,
        ]);
    }

    public function pph(Request $request)
    {
        $year = $request->query('year');
        if ($year == null) {
            $year = date("Y");
        }

        $monthIndex = [];
        for ($i = 1; $i <= 12; $i++) {
            array_push($monthIndex, sprintf('%02d', $i));
        }

        $reports = PphReport::query()
            ->where('year', $year)
            ->whereIn('month', $monthIndex)
            ->get();

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $monthlyReports = collect($monthIndex)->map(function ($index, $key) use ($reports, $year) {
            $report = $reports->where('month', $index)->first();
            if ($report !== null) {
                return $report;
            }

            return [
                'month' => $index,
                'year' => $year,
                'file' => null,
            ];
        })->all();

        // return $monthlyReports;

        return view('report.pph', [
            'reports' => $monthlyReports,
            'months' => $months,
            'year' => $year,
        ]);
    }

    public function generateBpjs(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

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

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo', 'type'];

        $previewPayslips = Career::query()
            ->whereHas('payslips')
            ->whereHas('employee', function ($q) {
                $q->where('type', 'staff')->orWhere('type', 'non staff');
            })
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

        // return true;

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

    public function generatePph(Request $request, $month, $year)
    {
        // $month = $request->query('month');
        // $year = $request->query('year');
        // $startDatePeriod = '2021-05-01';
        // $endDatePeriod = '2021-05-31';
        // $month = $request->query('month');
        // $year = $request->query('year');

        // return $this->generateDailySalary(2, $startDatePeriod, $endDatePeriod);

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

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo', 'type'];

        $previewPayslips = Career::query()
            ->whereHas('payslips')
            ->whereHas('employee', function ($q) {
                $q->where('type', 'staff')->orWhere('type', 'non staff');
            })
            ->where('is_active', 1)
            ->with(['employee' => function ($q) use ($employeeColumns) {
                $q->select($employeeColumns)->with('npwp', 'bpjs');
            }, 'payslips', 'designation', 'jobTitle'])
            ->get();



        $previewPayslips = $previewPayslips->each(function ($item, $key) use ($finalPayslips, $month, $year) {

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
                    // * NEW
                    $item['weekly_salary'] = 0;
                    $item['total_salary'] = 0;
                    $item['bruto'] = 0;
                    $item['position_allowance'] = 0;
                    $item['total_jht_jp'] = 0;
                    $item['netto'] = 0;
                    $item['marital'] = 0;
                    $item['ptkp'] = 0;
                    $item['pkp'] = 0;
                    $item['tarif_pph'] = 0;
                    $item['non_npwp'] = 0;
                    $item['pph_per_year'] = 0;
                    $item['pph_per_month'] = 0;
                    $item['net'] = 0;

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
                        // $totalSalary = $basicSalaryBpjs + $finalAllowances + $incentive;
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
                        // $receivedSalary  = $totalSalary - $totalIncomeSalary;

                        // Honor Mingguan
                        // !FATAL: PLEASE REFACTOR!
                        $startDate = date('Y-m-d', strtotime($year . '-' . $month . '-'  . $payslip->monthly_first_day . ' -1 month'));
                        $endDate = date('Y-m-d', strtotime($year . '-' . $month . '-'  . $payslip->monthly_first_day . ' -1 day'));

                        $weeklySalary = 0;
                        if ($item->employee->type == 'non staff') {
                            $weeklySalary = $this->generateDailySalary($item->employee->id, $startDate, $endDate);
                        }

                        // Total gaji
                        $totalSalary = $basicSalaryValue + $incentive + $weeklySalary + $jkkCompany + $jkmCompany;
                        // Bruto
                        $bruto = $totalSalary * 12;
                        // Biaya Jabatan
                        $positionAllowance = $bruto * (5 / 100);
                        // Biaya Jabatan Final
                        $finalPositionAllowance = $positionAllowance;
                        if ($positionAllowance > 6000000) {
                            $finalPositionAllowance = 6000000;
                        }

                        // Total JHT JP
                        $totalJhtJp = ($jhtCompany + $jpCompany) * 12;

                        // Netto
                        $netto = $bruto - $finalPositionAllowance - $totalJhtJp;

                        // PTKP
                        $ptkp = 0;
                        $employee_marital = $item->employee->npwp->type;
                        if ($employee_marital !== null) {
                            switch ($employee_marital) {
                                case 'TK0':
                                    $ptkp = 54000000;
                                    break;
                                case 'TK1':
                                case 'K0':
                                    $ptkp = 58500000;
                                    break;
                                case 'TK2':
                                case 'K1':
                                    $ptkp = 63000000;
                                    break;
                                case 'TK3':
                                case 'K2':
                                    $ptkp = 67500000;
                                    break;
                                default:
                                    $ptkp = 72000000;
                            }
                        }

                        // PKP
                        $pkp = $netto - $ptkp;

                        // Tarif Pph
                        $tarifPph = 0;
                        if ($pkp > 0 && $pkp <= 50000000) {
                            $tarifPph = $pkp * (5 / 100);
                        } else if ($pkp > 50000000 && $pkp <= 250000000) {
                            $tarifPph = ($pkp - 50000000) * (15 / 100) + 2500000;
                        } else if ($pkp > 250000000 && $pkp <=  500000000) {
                            $tarifPph = ($pkp - 250000000) * (25 / 100) + 30000000;
                        } else {
                            $tarifPph = ($pkp - 250000000) * (30 / 100) + 62500000 + 30000000 + 2500000;
                        }

                        // Non NPWP
                        $nonNpwp = 1.2; // 120%

                        if ($item->employee->npwp->number !== null) {
                            $nonNpwp = 1; // 100%
                        }

                        // PPh Per Tahun
                        $pphPerYear = 0;

                        if ($tarifPph !== 0) {
                            $pphPerYear = $tarifPph * $nonNpwp;
                        }

                        // PPh per bulan
                        $pphPerMonth = $pphPerYear / 12;

                        // Net
                        $net = $totalSalary - $pphPerMonth;


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
                        // $item['received_salary'] = $receivedSalary;

                        // ?----------- NEW ----------------

                        $item['weekly_salary'] = $weeklySalary;
                        $item['total_salary'] = $totalSalary;
                        $item['bruto'] = $bruto;
                        $item['position_allowance'] = round($finalPositionAllowance);
                        $item['total_jht_jp'] = round($totalJhtJp);
                        $item['netto'] = round($netto);
                        $item['marital'] = $employee_marital;
                        $item['ptkp'] = $ptkp;
                        $item['pkp'] = round($pkp);
                        $item['tarif_pph'] = round($tarifPph);
                        $item['non_npwp'] = $nonNpwp;
                        $item['pph_per_year'] = round($pphPerYear);
                        $item['pph_per_month'] = round($pphPerMonth);
                        $item['net'] = $net;

                        break;
                    }
                }
            }
        })
            ->map(function ($item, $key) {
                return collect($item)->except(['payslips'])->toArray();
            })->all();
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember'];

        return [
            'pph' => $previewPayslips,
            'month' => $months[(int) $month - 1],
            'year' => $year
        ];

        // return $previewPayslips;
    }

    public function exportBpjsExcel(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        return Excel::download(new BpjsReportExport($request, $month, $year), 'Laporan BPJS.xlsx');
    }

    public function storeReportBpjs(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember'];

        $report = new BpjsReport;
        $report->month = sprintf('%02d', $month);
        $report->year = $year;

        $filename = 'Laporan BPJS ' . $months[(int) $month - 1] . ' ' . $year . '.xlsx';
        $path = 'reports/' . $filename;

        try {
            Excel::store(new BpjsReportExport($request, sprintf('%02d', $month), $year), $path, 's3');
        } catch (Exception $e) {
            return response()->json([
                'message' => '[Internal Error] Error while uploading file',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        // return response()->json([
        //     'message' => 'data has been saved',
        //     'error' => false,
        //     'code' => 200,
        // ], 200);

        $report->file = $path;

        try {
            $report->save();
            return response()->json([
                'message' => 'data has been saved',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => '[Internal Error] Error while saving data',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }
    }

    public function exportPphExcel(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        return Excel::download(new PphReportExport($request, $month, $year), 'Laporan PPH 21.xlsx');
    }

    public function storeReportPph(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember'];

        $report = new PphReport;
        $report->month = sprintf('%02d', $month);
        $report->year = $year;

        $filename = 'Laporan PPh 21 ' . $months[(int) $month - 1] . ' ' . $year . '.xlsx';
        $path = 'reports/' . $filename;

        try {
            Excel::store(new PphReportExport($request, sprintf('%02d', $month), $year), $path, 's3');
        } catch (Exception $e) {
            return response()->json([
                'message' => '[Internal Error] Error while uploading file',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        // return response()->json([
        //     'message' => 'data has been saved',
        //     'error' => false,
        //     'code' => 200,
        // ], 200);

        $report->file = $path;

        try {
            $report->save();
            return response()->json([
                'message' => 'data has been saved',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => '[Internal Error] Error while saving data',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }
    }

    public function generateDailySalary($employee_id, $startDatePeriod, $endDatePeriod)
    {
        // $startDatePeriod = $request->startDatePeriod;
        // $endDatePeriod = $request->endDatePeriod;
        // $startDatePeriod = $request->query('startDate');
        // $endDatePeriod = $request->query('endDate');

        // return $request->query('startDate');
        // $startDatePeriod = '2021-05-01';
        // $endDatePeriod = '2021-05-07';

        $periodDiff = Carbon::parse($startDatePeriod)->diffInDays($endDatePeriod);

        // $finalPayslips = FinalPayslip::query()
        //     ->where('type', 'custom_period')
        //     ->whereBetween('start_date_period', [$startDatePeriod, $endDatePeriod])
        //     ->orWhereBetween('end_date_period', [$startDatePeriod, $endDatePeriod])
        //     ->get();

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo', 'daily_money_regular', 'daily_money_holiday', 'overtime_pay_regular', 'overtime_pay_holiday'];

        $employees = Employee::query()->select($employeeColumns)->with(['attendances' => function ($query) use ($startDatePeriod, $endDatePeriod) {
            $query->whereBetween('date', [$startDatePeriod, $endDatePeriod]);
        }, 'officeShifts' => function ($query) {
            $query->where('is_active', 1);
        }, 'careers' => function ($query) {
            $query->with(['jobTitle', 'designation'])->where('is_active', 1);
        }])
            ->where('id', $employee_id)
            // ->where('type', 'non staff')
            // ->orWhere('type', 'freelancer')
            ->get();

        // return $attendances;
        // return $periodDiff + 1;
        // $period = CarbonPeriod::create(Carbon::parse($startDatePeriod)->addDay(), Carbon::parse($endDatePeriod)->addDay());

        // return $this->getDatesFromRange($startDatePeriod, $endDatePeriod);
        $calendars = Calendar::all();

        foreach ($employees as $employee) {

            $attendances = $this->mergeAttendances($employee->attendances, $employee, $calendars);

            $attendancesKeys = collect($attendances)->map(function ($item, $key) {
                return $key;
                // return $item->date;
            })->all();

            $period = collect($this->getDatesFromRange($startDatePeriod, $endDatePeriod))->map(function ($item, $key) use ($attendancesKeys, $attendances, $employee, $calendars) {
                $searchResult = array_search($item, $attendancesKeys);
                if ($searchResult !== false) {
                    return [
                        'date' => $item,
                        'attendance' => $attendances[$searchResult],
                        'day_status' => $this->getDayStatus($employee, $item, $calendars),
                    ];
                }

                return [
                    'date' => $item,
                    'attendance' => null,
                    'day_status' => $this->getDayStatus($employee, $item, $calendars),
                ];
            });

            $employee['payments'] = $period;
            $employee['payslip_name'] = 'Slip Gaji Periode ' . date_format(date_create($startDatePeriod), "d/m/Y") . ' - ' . date_format(date_create($endDatePeriod), "d/m/Y");
        }



        $total = collect($employees)->each(function ($item, $key) {
            $item['total_daily_salary'] = collect($item['payments'])->where('attendance', '!==', null)->map(function ($payment) {
                return $payment['attendance']['daily_money'] + $payment['attendance']['overtime_pay'];
            })->sum();
        });

        $total = collect($total)->map(function ($item, $key) {
            return collect($item)->except(['attendances'])->toArray();
            // return $item;
        })->values();

        return $total[0]['total_daily_salary'];

        // $attendancesKeys = collect($employees[0]->attendances)->map(function ($item, $key) {
        //     return $item->date;
        // })->all();

        // return $attendancesKeys;
        // $employees = collect($employees)->except(['attendances']);
        // $excludeEmployees = collect($finalPayslips)->map(function ($item, $key) {
        //     return $item->employee_id;
        // });


        // $employees = collect($employees)->whereNotIn('id', $excludeEmployees)->all();
        // return $employees;
        // return response()->json([
        //     'message' => 'OK',
        //     'error' => false,
        //     'code' => 200,
        //     'data' => [
        //         'generated_payslips' => $employees,
        //         'final_payslips' => $finalPayslips,
        //         'start_date' => $startDatePeriod,
        //         'end_date' => $endDatePeriod,
        //         'amount_of_days' => $periodDiff + 1,
        //     ]
        // ]);
        // return $excludeEmployees;
        // return $finalPayslips;
    }

    public function mergeAttendances($attendances, $employee, $calendars)
    {
        return collect($attendances)->where('category', 'present')->sortBy('date')->groupBy('date')->map(function ($item, $key) use ($employee, $calendars) {
            $status = null;
            $pendingCategory = null;
            $clockIn = null;
            $clockOut = null;
            $note = null;
            $overtime = 0;
            // $images = [];

            foreach ($item as $att) {
                // $note = $att->note;
                // if ($att->image || isset($att->image)) {
                //     $images = array_merge($images, [$att->image]);
                // }
                if ($att->category == 'present') {
                    if ($att->status == 'approved') {
                        $status = 'present';
                    } else if ($att->status == 'pending') {
                        $status = 'pending';
                        $pendingCategory = 'Hadir';
                    } else {
                        $status = 'rejected';
                    }
                    if ($att->type == 'check in') {
                        $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                    } else if ($att->type == 'check out') {
                        $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                        $overtime = $att->overtime_duration;
                    }
                }
            }

            $workingHours = Carbon::parse($clockIn)->diffInHours($clockOut);
            // $overtime = 0;
            $dayStatus = '';

            if (count($employee->officeShifts) > 0) {
                // Carbon::parse($employee->office_shifts[0]->)->diffInHours($clockOut);
                if (date('l', strtotime($key)) == 'Monday') {
                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['monday'];
                    $dayStatus = $employee->officeShifts[0]->monday_status;
                } else if (date('l', strtotime($key)) == 'Tuesday') {
                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['tuesday'];
                    $dayStatus = $employee->officeShifts[0]->tuesday_status;
                } else if (date('l', strtotime($key)) == 'Wednesday') {
                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['wednesday'];
                    $dayStatus = $employee->officeShifts[0]->wednesday_status;
                } else if (date('l', strtotime($key)) == 'Thursday') {
                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['thursday'];
                    $dayStatus = $employee->officeShifts[0]->thursday_status;
                } else if (date('l', strtotime($key)) == 'Friday') {
                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['friday'];
                    $dayStatus = $employee->officeShifts[0]->friday_status;
                } else if (date('l', strtotime($key)) == 'Saturday') {
                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['saturday'];
                    $dayStatus = $employee->officeShifts[0]->saturday_status;
                } else if (date('l', strtotime($key)) == 'Sunday') {
                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['sunday'];
                    $dayStatus = $employee->officeShifts[0]->sunday_status;
                }
            }

            $calendar = collect($calendars)->filter(function ($item) use ($key) {
                return $item->date == $key && ($item->type == 'libur nasional' || $item->type == 'cuti bersama');
            })->first();

            if ($calendar !== null) {
                $dayStatus = $calendar->type;
            }

            // $overtime = ($overtime > 0) ? $overtime : 0;

            // Calculate Daily Salary

            // If day is workday
            $dailyMoney = $employee->daily_money_regular;
            $overtimePay = 0;

            if ($overtime > 0) {
                $overtimePay = $overtimePay + ($employee->overtime_pay_regular * $overtime);
            }

            // If day is holiday
            if ($dayStatus == 'holiday' || $dayStatus == 'cuti bersama' || $dayStatus == 'libur nasional') {
                $dailyMoney = $employee->daily_money_holiday;
                // $overtimePay = $employee->overtime_pay_holiday;
                $overtimePay = 0;

                if ($overtime > 0) {
                    $overtimePay = $overtimePay + ($employee->overtime_pay_holiday * $overtime);
                }
            }

            // Return End-Result
            return [
                'status' => $status,
                'pending_category' => $pendingCategory,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'working_hours' => $workingHours,
                'date' => $key,
                'overtime' => $overtime,
                'day_status' => $dayStatus,
                'daily_money' => $dailyMoney,
                'overtime_pay' => $overtimePay,
                // 'note' => $note,
                // 'images' => $images,
            ];
        })->all();
    }


    public function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach ($period as $date) {
            $array[] = $date->format($format);
        }

        return $array;
    }

    public function getShiftWorkingHours($employee)
    {
        $mondayWorkingHours = Carbon::parse($employee->officeShifts[0]->monday_in_time)->diffInHours($employee->officeShifts[0]->monday_out_time);
        $tuesdayWorkingHours = Carbon::parse($employee->officeShifts[0]->tuesday_in_time)->diffInHours($employee->officeShifts[0]->tuesday_out_time);
        $wednesdayWorkingHours = Carbon::parse($employee->officeShifts[0]->wednesday_in_time)->diffInHours($employee->officeShifts[0]->wednesday_out_time);
        $thursdayWorkingHours = Carbon::parse($employee->officeShifts[0]->thursday_in_time)->diffInHours($employee->officeShifts[0]->thursday_out_time);
        $fridayWorkingHours = Carbon::parse($employee->officeShifts[0]->friday_in_time)->diffInHours($employee->officeShifts[0]->friday_out_time);
        $saturdayWorkingHours = Carbon::parse($employee->officeShifts[0]->saturday_in_time)->diffInHours($employee->officeShifts[0]->saturday_out_time);
        $sundayWorkingHours = Carbon::parse($employee->officeShifts[0]->sunday_in_time)->diffInHours($employee->officeShifts[0]->sunday_out_time);

        return [
            'monday' => $mondayWorkingHours,
            'tuesday' => $tuesdayWorkingHours,
            'wednesday' => $wednesdayWorkingHours,
            'thursday' => $thursdayWorkingHours,
            'friday' => $fridayWorkingHours,
            'saturday' => $saturdayWorkingHours,
            'sunday' => $sundayWorkingHours,
        ];
    }

    public function getDayStatus($employee, $date, $calendars)
    {
        $dayStatus = null;
        if (count($employee->officeShifts) > 0) {
            // Carbon::parse($employee->office_shifts[0]->)->diffInHours($clockOut);
            if (date('l', strtotime($date)) == 'Monday') {
                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['monday'];
                $dayStatus = $employee->officeShifts[0]->monday_status;
            } else if (date('l', strtotime($date)) == 'Tuesday') {
                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['tuesday'];
                $dayStatus = $employee->officeShifts[0]->tuesday_status;
            } else if (date('l', strtotime($date)) == 'Wednesday') {
                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['wednesday'];
                $dayStatus = $employee->officeShifts[0]->wednesday_status;
            } else if (date('l', strtotime($date)) == 'Thursday') {
                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['thursday'];
                $dayStatus = $employee->officeShifts[0]->thursday_status;
            } else if (date('l', strtotime($date)) == 'Friday') {
                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['friday'];
                $dayStatus = $employee->officeShifts[0]->friday_status;
            } else if (date('l', strtotime($date)) == 'Saturday') {
                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['saturday'];
                $dayStatus = $employee->officeShifts[0]->saturday_status;
            } else if (date('l', strtotime($date)) == 'Sunday') {
                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['sunday'];
                $dayStatus = $employee->officeShifts[0]->sunday_status;
            }
        }

        // $calendar = collect($calendars)->where('date', $date)->where('type', 'libur nasional')->orWhere('type', 'cuti bersama')->first();
        $calendar = collect($calendars)->filter(function ($item, $key) use ($date) {
            return $item->date == $date && ($item->type == 'libur nasional' || $item->type == 'cuti bersama');
        })->first();

        if ($calendar !== null) {
            $dayStatus = $calendar->type;
        }

        return $dayStatus;
    }
}
