<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Employee;
use App\Models\FinalPayslip;
use App\Models\PaySlip;
use App\Models\SalaryDeduction;
use App\Models\SalaryIncome;
use App\Models\SalarySetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $month = $request->query('month');
        $year = $request->query('year');

        $id = 2;

        $setting = SalarySetting::first();
        // return $setting;
        if ($setting == null) {
            // abort(500);
            $minMonthWork = 3;
            $thrAmount = 1;
            $proporsionalMethod = 'month';
        }

        $minMonthWork = $setting->thr_min_months_of_service;
        $thrAmount = $setting->thr_amount;
        $proporsionalMethod = $setting->thr_for_less_one_year;

        $minimumDate = Carbon::today()->subMonth($minMonthWork)->toDateString();

        // return $minimumDate;
        // return date_create($minimumDate);

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo'];

        $previewPayslips = Career::query()
            ->whereHas('employee', function ($q) use ($minimumDate) {
                $q->where('start_work_date', '<=', $minimumDate);
            })
            ->whereHas('payslips')
            ->where('is_active', 1)
            ->with(['employee' => function ($q) use ($employeeColumns) {
                $q->select($employeeColumns)->with('npwp');
            }, 'payslips', 'designation', 'jobTitle'])
            ->get();



        $previewPayslips = $previewPayslips->each(function ($item, $key) use ($proporsionalMethod, $thrAmount) {

            if (count($item->payslips) > 0) {
                foreach ($item->payslips as $payslip) {
                    $payslip->pivot->incomes = json_decode($payslip->pivot->incomes);
                    $payslip->pivot->deductions = json_decode($payslip->pivot->deductions);

                    $basicSalary = collect($payslip->pivot->incomes)->firstWhere('type', 'gaji pokok');

                    $allowances = collect($payslip->pivot->incomes)->where('thr_income', '1')->toArray();

                    $item['thr_amount'] = 0;

                    if ($basicSalary !== null) {

                        $finalIncomes = [];

                        $item['basic_salary'] = $basicSalary->value;

                        $startWorkDate = $item->employee->start_work_date;
                        $yearWork = Carbon::today()->diffInYears($startWorkDate);
                        $monthWork = Carbon::today()->diffInMonths($startWorkDate);
                        $dayWork = Carbon::today()->diffInDays($startWorkDate);

                        $item['years_work'] = $yearWork;
                        $item['months_work'] = $monthWork;
                        $item['days_work'] = $dayWork;

                        // $item['year_work'] = 0;
                        $item['thr_amount'] = $thrAmount * $basicSalary->value;
                        $item['thr_calculation_method'] = 'normal';

                        if ($yearWork < 1) {
                            $item['thr_calculation_method'] = 'proporsional';
                            if ($proporsionalMethod == 'month') {
                                $item['thr_amount'] = round(($monthWork / 12) * ($thrAmount * $basicSalary->value));
                                // break;
                            } else {
                                $item['thr_amount'] = round(($dayWork / 365) * ($thrAmount * $basicSalary->value));
                                // break;
                            }
                        }

                        array_push($finalIncomes, [
                            'name' => 'Tunjangan Hari Raya',
                            'value' => $item['thr_amount']
                        ]);

                        $finalIncomes = array_merge($finalIncomes, $allowances);

                        $item['incomes'] = $finalIncomes;
                        // $item['allowances'] = $allowances;
                        // $item['thr_amount'] = $thrAmount * $basicSalary->value;
                        // $item['thr_calculation_method'] = 'normal';
                        break;
                    }


                    // if (count($payslip->pivot->incomes) > 0) {
                    //     $amount = collect($payslip->pivot->incomes)->firstWhere('type', 'gaji pokok');

                    //     if ($amount == null) {
                    //         $payslip->pivot->incomes = [[
                    //             'name' => 'THR',
                    //             'type' => 'thr',
                    //             'type_a1' => 'type_a1_5',
                    //             'pph21' => 0,
                    //             'is_active' => 1,
                    //             'value' => 0,
                    //         ]];
                    //         $payslip->pivot->deductions = [];
                    //     } else {
                    //         $payslip->pivot->incomes = [[
                    //             'name' => 'THR',
                    //             'type' => 'thr',
                    //             'type_a1' => 'type_a1_5',
                    //             'pph21' => 0,
                    //             'is_active' => 1,
                    //             'value' => $amount->value,
                    //         ]];
                    //         $payslip->pivot->deductions = [];
                    //     }
                    // }
                }
            }
        })->map(function ($item, $key) {
            return collect($item)->except(['payslips'])->toArray();
        })->all();

        // return $previewPayslips;

        // $payslip = PaySlip::findOrFail($id);

        $startDatePeriod = date($year . '-01-01');
        $endDatePeriod = date(($year + 1) . '-01-01');

        $finalPayslips = FinalPayslip::query()
            ->with(['employee' => function ($q) use ($employeeColumns) {
                $q->select($employeeColumns);
            }])
            ->where('type', 'thr')
            // ->whereIn('pay_slip_id', [2])
            ->where('start_date_period', '>=', $startDatePeriod)
            ->where('end_date_period', '<', $endDatePeriod)
            ->get()
            ->each(function ($item, $key) {
                $item->income = json_decode($item->income);
                $item->deduction = json_decode($item->deduction);
            });

        // return $finalPayslips;

        // $previewPayslips = collect($previewPayslips)->each(function ($item, $key) {
        //     if (count($item->payslips) > 0) {
        //         $item->payslips[0]->pivot->incomes = json_decode($item->payslips[0]->pivot->incomes);
        //         $item->payslips[0]->pivot->deductions = json_decode($item->payslips[0]->pivot->deductions);
        //     }
        // });

        $excludeEmployees = collect($finalPayslips)->map(function ($item, $key) {
            return $item->employee_id;
        });

        $previewPayslips = collect($previewPayslips)->whereNotIn('employee_id', $excludeEmployees)->values();

        // return [
        //     'preview_payslips' => $previewPayslips,
        //     'final_payslips' => $finalPayslips,
        //     'start_date_period' => $startDatePeriod,
        //     'end_date_period' => Carbon::parse($endDatePeriod)->subDay()->toDateString(),
        // ];

        // return $previewPayslips;


        return view('thr.index', [
            // 'payslips' => $payslips,
            // 'payslip' => $payslip,
            'preview_payslips' => $previewPayslips,
            'final_payslips' => $finalPayslips,
            'start_date_period' => $startDatePeriod,
            'end_date_period' => Carbon::parse($endDatePeriod)->subDay()->toDateString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = collect($request->payslips)->map(function ($item, $key) use ($request) {
            return [
                // 'name' => 'Slip Tunjangan hari Raya (THR) Periode ' . date_format(date_create($request->start_date), "d/m/Y") . '-' . date_format(date_create($request->start_date), "d/m/Y"),
                'name' => 'Slip Tunjangan hari Raya (THR) Tahun ' . date_format(date_create($request->start_date), "Y"),
                'employee_id' => $item['employee_id'],
                'start_date_period' => $request->start_date,
                'end_date_period' => $request->end_date,
                'type' => 'thr',
                // 'income' => json_encode([
                //     [
                //         'name' => 'Tunjangan Hari Raya (THR)',
                //         'type' => 'thr',
                //         'type_a1' => 'type_a1_5',
                //         'pph21' => 0,
                //         'is_active' => 1,
                //         'value' => $item['thr_amount'],
                //     ]
                // ]),
                'income' => json_encode($item['incomes']),
                'deduction' => json_encode([]),
                'description' => null,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                // 'pay_slip_id' => $request->payslip_id,
            ];
        })->all();

        // return $data;

        try {
            // $final_payslip->save();
            DB::table('final_payslips')->insert($data);
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
