<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Career;
use App\Models\FinalPayslip;
use App\Models\SalarySetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeavePayroll extends Controller
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
        $massLeaves = Calendar::query()->where('type', 'cuti bersama')->get();
        $massLeavesCount = count($massLeaves);

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo', 'daily_money_regular'];

        $user = Auth::user();
        $eoOnly = $user->eo_only;

        $previewPayslips = Career::query()
            ->whereHas('employee', function ($q) use ($minimumDate, $eoOnly) {
                $q->where('type', 'staff')->orWhere('type', 'non staff')->where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null);
            })
            ->whereHas('payslips')
            ->where('is_active', 1)
            ->with(['employee' => function ($q) use ($employeeColumns) {
                $q->select($employeeColumns)->with(['npwp', 'leaves']);
            }, 'payslips', 'designation', 'jobTitle'])
            ->get();

        $previewPayslips = $previewPayslips->each(function ($item, $key) use ($proporsionalMethod, $thrAmount, $massLeavesCount) {

            if (count($item->payslips) > 0) {

                $activeLeave = collect($item->employee->leaves)->firstWhere('is_active', 1);

                $remainingLeaves = 0;

                if ($activeLeave !== null) {
                    $remainingLeaves = $activeLeave->total_leave - $activeLeave->taken_leave;
                }

                foreach ($item->payslips as $payslip) {
                    $payslip->pivot->incomes = json_decode($payslip->pivot->incomes);
                    $payslip->pivot->deductions = json_decode($payslip->pivot->deductions);

                    $basicSalary = collect($payslip->pivot->incomes)->firstWhere('type', 'gaji pokok');

                    $item['amount'] = 0;

                    $finalLeave = $remainingLeaves - $massLeavesCount;

                    $item['final_leave'] = $finalLeave;


                    if ($basicSalary !== null) {

                        $item['basic_salary'] = $basicSalary->value;

                        $totalWorkDay = 26;

                        if ($finalLeave > 0) {
                            $dailyMoney = $item->employee->daily_money_regular;
                            $item['amount'] = round(((($dailyMoney * $totalWorkDay) + $basicSalary->value) / $totalWorkDay) * $finalLeave);
                            // $item['amount'] = $dailyMoney;

                            break;
                        }
                    }
                }
            }
        })
            ->filter(function ($item, $key) {
                return $item->final_leave > 0;
            })
            ->map(function ($item, $key) {
                return collect($item)->except(['payslips'])->toArray();
            })->all();

        // return $previewPayslips;

        // $payslip = PaySlip::findOrFail($id);

        $startDatePeriod = date($year . '-01-01');
        $endDatePeriod = date(($year + 1) . '-01-01');

        $finalPayslips = FinalPayslip::query()
            ->whereHas('employee', function (Builder $q) use ($eoOnly) {
                $q->where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null);
            })
            ->with(['employee' => function ($q) use ($employeeColumns) {
                $q->select($employeeColumns);
            }])
            ->where('type', 'leave')
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


        return view('leave-payroll.index', [
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
                'name' => 'Pembayaran Cuti Tahun ' . date_format(date_create($request->start_date), "Y"),
                'employee_id' => $item['employee_id'],
                'start_date_period' => $request->start_date,
                'end_date_period' => $request->end_date,
                'type' => 'leave',
                'income' => json_encode([
                    [
                        'name' => 'Sisa Cuti (' . $item['final_leave'] . ' Hari)',
                        'type' => 'leave',
                        'type_a1' => null,
                        'pph21' => 0,
                        'is_active' => 1,
                        'final_leave' => $item['final_leave'],
                        'value' => $item['amount'],
                    ]
                ]),
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
