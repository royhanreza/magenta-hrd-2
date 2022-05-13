<?php

namespace App\Http\Controllers\web;

use App\Exports\PayrollReportExport;
use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Career;
use App\Models\Company;
use App\Models\Employee;
use App\Models\FinalPayslip;
use App\Models\PaySlip;
use App\Models\SalaryDeduction;
use App\Models\SalaryIncome;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        $payslips = PaySlip::where('period_type', 'tetap')->get();
        // return $payslips;
        return view('payroll.index', ['companies' => $companies, 'payslips' => $payslips]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        // $company = new Company;
        // $company->name = $request->name;
        // $company->registration_number = $request->registration_number;
        // $company->contact_number = $request->contact_number;
        // $company->email = $request->email;
        // $company->website = $request->website;
        // $company->npwp = $request->npwp;
        // $company->address = $request->address;
        // $company->province = $request->province;
        // $company->country = $request->country;
        // $company->city = $request->city;
        // $company->zip_code = $request->zip_code;
        // // $company->logo = $request->logo;
        // // $company->added_by = $request->added_by;
        // $company->logo = 'magenta-logo.png';
        // $company->added_by = 1;

        // $final_payslip = new FinalPayslip;
        // $final_payslip->name = $request->payslip_name;
        // $final_payslip->employee_id = $request->id;
        // $final_payslip->start_date_period = $request->start_date;
        // $final_payslip->end_date_period = $request->end_date;
        // $final_payslip->type = 'custom_period';
        // $final_payslip->income = $request->payslips->payments;
        // $final_payslip->deduction = null;
        // $final_payslip->description = $request->description;
        // return $request->payslips;
        $data = collect($request->payslips)->map(function ($item, $key) use ($request) {
            return [
                'name' => 'Slip Gaji Bulanan Periode ' . date_format(date_create($request->start_date), "d/m/Y") . '-' . date_format(date_create($request->start_date), "d/m/Y"),
                'employee_id' => $item['employee_id'],
                'start_date_period' => $request->start_date,
                'end_date_period' => $request->end_date,
                'type' => 'fix_period',
                'income' => json_encode($item['payslips'][0]['pivot']['incomes']),
                'deduction' => json_encode($item['payslips'][0]['pivot']['deductions']),
                'description' => null,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'pay_slip_id' => $request->payslip_id,
            ];
        })->all();

        $noStatusAttendances = $request->no_status_attendances;
        $index = 0;
        foreach ($noStatusAttendances as $item) {
            if ($item['amount'] !== null && $item['amount'] > 0) {
                $finalRemainingLeave = $item['remaining_leaves'] - $item['amount'];
                if ($finalRemainingLeave < 0) {
                    $finalRemainingLeave = 0;
                }

                // !IMPORTANT
                // $data[$index]['remaining_leaves'] = $item['remaining_leaves'];

                try {
                    // $final_payslip->save();
                    // DB::table('leaves')
                    //     ->where('employee_id', $item['employee_id'])
                    //     ->update(['total_leave' => $finalRemainingLeave]);
                    // return response()->json([
                    //     'message' => 'Data has been saved',
                    //     'error' => true,
                    //     'code' => 200,
                    // ]);
                } catch (Exception $e) {
                    return response()->json([
                        'message' => 'Internal Error',
                        'error' => true,
                        'code' => 500,
                        'errors' => $e
                    ], 500);
                }
            }
            $index++;
        }

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

        // try {
        //     $company->save();
        //     return response()->json([
        //         'message' => 'Data has been saved',
        //         'error' => true,
        //         'code' => 200,
        //     ]);
        // } catch (Exception $e) {
        //     return response()->json([
        //         'message' => 'Internal Error',
        //         'error' => true,
        //         'code' => 500,
        //         'errors' => $e
        //     ], 500);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        try {
            $permissions = json_decode(Auth::user()->role->role_permissions);
        } catch (\Throwable $th) {
            abort(500, $th);
        }

        $month = $request->query('month');
        $year = $request->query('year');
        $staffOnly = $request->query('staffonly');

        $payslips = PaySlip::where('period_type', 'tetap')->get();

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo', 'daily_money_regular', 'is_active'];

        $payslip = PaySlip::findOrFail($id);

        $startDatePeriod = date('Y-m-d', strtotime(request()->query('year') . '-' . request()->query('month') . '-'  . $payslip->monthly_first_day . ' -1 month'));
        $endDatePeriod = date('Y-m-d', strtotime(request()->query('year') . '-' . request()->query('month') . '-'  . $payslip->monthly_first_day . ' -1 day'));
        // $startDatePeriod = '2021-06-08';
        // $endDatePeriod = '2021-06-10';
        // $months = ['Januari', 'Februari', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        // return $payslips;
        $user = Auth::user();
        $eoOnly = $user->eo_only;

        $previewPayslips = Career::whereHas('payslips', function ($q) use ($id) {
            $q->where('pay_slip_id', $id);
        })
            ->whereHas('employee', function (Builder $q) use ($id, $permissions, $staffOnly, $eoOnly) {
                // $q->where('type', 'non staff')->orWhere('type', 'staff')->where('is_active', 1);
                // $q->where('type', '!=', 'freelancer')->where('is_active', 1);
                $excludeType = ['freelancer'];
                if (!in_array("staffSalary", $permissions)) {
                    array_push($excludeType, 'staff');
                } else {
                    if ($staffOnly !== null && $staffOnly == "true") {
                        $excludeType = ['freelancer', 'non staff'];
                    }
                }

                $q->whereNotIn('type', $excludeType)->where('is_active', 1)->where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null);
            })
            ->where('is_active', 1)
            ->with(['employee' => function ($q) use ($employeeColumns, $startDatePeriod, $endDatePeriod) {
                $q->select($employeeColumns)->with([
                    'npwp',
                    'loans' => function ($q) use ($startDatePeriod, $endDatePeriod) {
                        $q->whereBetween('payslip_date', [$startDatePeriod, $endDatePeriod]);
                    },
                    'attendances' => function ($query) use ($startDatePeriod, $endDatePeriod) {
                        $query->where('status', 'approved')->whereBetween('date', [$startDatePeriod, $endDatePeriod]);
                    },
                    'activeLeave',
                    'officeShifts',
                ]);
            }, 'payslips' => function ($q) use ($id) {
                $q->where('pay_slip_id', $id);
            }, 'designation', 'jobTitle'])
            ->get();

        $calendars = Calendar::all();
        $massLeaveCount = $calendars->where('type', 'cuti bersama')->count();

        // return $previewPayslips->employee;

        foreach ($previewPayslips as $previewPayslip) {

            $employee = $previewPayslip->employee;

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
            })->all();

            $employee['period_attendances'] = $period;

            $isGetPresenceIncentive = true;


            // $allDayStatus = [];
            // Check if there is no attendances in workday
            $nullAttendanceWorkDay = collect($period)->filter(function ($item) {
                return $item['day_status'] == 'workday' && $item['attendance'] == null;
            })->count();

            // $attendanceBesidePresent = 0;
            $previewPayslip['no_status_attendance'] = $nullAttendanceWorkDay;

            if ($nullAttendanceWorkDay > 0) {
                $isGetPresenceIncentive = false;

                $remainingLeaves = 0;
                if ($employee->activeLeave !== null) {
                    $remainingLeaves = $employee->activeLeave->total_leave - $employee->activeLeave->taken_leave;
                }

                $previewPayslip['remaining_leaves'] = $remainingLeaves;

                // if ($remainingLeaves > 0) {
                //     $remainingLeaves -= $massLeaveCount;
                // }

                $excessLeave = $remainingLeaves - $nullAttendanceWorkDay;
                $previewPayslip['excess_leave'] = 0;
                if ($excessLeave < 0) {
                    $previewPayslip['excess_leave'] = abs($excessLeave);
                }
            } else {
                $attendanceBesidePresent = collect($period)->filter(function ($item) {
                    // return $item['day_status'] == 'workday' && $item['attendance'] !== null;
                    return $item['day_status'] == 'workday';
                })->filter(function ($item) {
                    return $item['attendance'] == null || $item['attendance']['category'] !== 'present';
                })->count();

                // $previewPayslip['beside_present'] = $attendanceBesidePresent;

                // $shift = collect($employee->officeShift)->where('is_active', 1)->first();
                $shift = collect($employee->officeShifts)->filter(function ($shift) {
                    return $shift->pivot->is_active == 1;
                })->first();

                // $previewPayslip['shift'] = $shift;

                if ($shift == null) {
                    $isGetPresenceIncentive = false;
                } else {
                    if ($attendanceBesidePresent > 0) {
                        $isGetPresenceIncentive = false;
                    }
                }
            }

            $presenceIncentive = 0;

            if ($isGetPresenceIncentive) {
                $presenceIncentive = $previewPayslip->employee->daily_money_regular * 2;
            }

            // ->filter(function ($item) {
            //     return $item['attendance']['category'] !== 'present';
            // })->count();

            $previewPayslip['presence_incentive'] = $presenceIncentive;
            // $employee['payslip_name'] = 'Slip Gaji Periode ' . date_format(date_create($startDatePeriod), "d/m/Y") . ' - ' . date_format(date_create($endDatePeriod), "d/m/Y");
        }
        // return $previewPayslips[10];



        $finalPayslips = FinalPayslip::query()
            ->with(['employee'])
            ->where('type', 'fix_period')
            ->whereBetween('start_date_period', [$startDatePeriod, $endDatePeriod])
            ->orWhereBetween('end_date_period', [$startDatePeriod, $endDatePeriod])
            ->get()
            ->where('pay_slip_id', $id)
            ->filter(function ($payslip) use ($permissions, $staffOnly) {
                $excludeType = ['freelancer'];
                if (!in_array("staffSalary", $permissions)) {
                    array_push($excludeType, 'staff');
                } else {
                    if ($staffOnly !== null && $staffOnly == "true") {
                        $excludeType = ['freelancer', 'non staff'];
                    }
                }
                if (isset($payslip->employee)) {
                    return !in_array($payslip->employee->type, $excludeType);
                }
                return false;
            })
            ->each(function ($item, $key) {
                $item->income = json_decode($item->income);
                $item->deduction = json_decode($item->deduction);
            })->values()->all();

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

        // $previewPayslips = collect($previewPayslips)->map(function ($item, $key) {
        //     collect($item->employee)->except(['attendances', 'period_attendances'])->all();
        //     // return $item;
        // })->all();

        // $employees = collect($employees)->map(function ($item, $key) {
        //     return collect($item)->except(['attendances'])->toArray();
        //     // return $item;
        // })->whereNotIn('id', $excludeEmployees)->values();

        // return collect($previewPayslips)->where('employee_id', 1)->all();

        $salaryIncomes = SalaryIncome::all();
        $salaryDeductions = SalaryDeduction::all();

        collect($previewPayslips)->each(function ($item, $key) use ($salaryIncomes, $salaryDeductions) {
            // $item['salary_incomes'] = $salaryIncomes;
            $loans = collect($item->employee->loans)->where('type', 'loan')->map(function ($item, $key) {
                return [
                    // "name":"Kasbon","value":"1000000","is_loan":1,"is_added":1,"loan_id":11}
                    'name' => 'Kasbon tanggal ' . Carbon::parse($item->date)->isoFormat('LL'),
                    'value' => $item->amount,
                    'is_loan' => 1,
                    'is_added' => 1,
                    'loan_id' => $item->id,
                ];
            })->all();
            $payments = collect($item->employee->loans)->where('type', 'payment')->map(function ($item, $key) {
                return [
                    // "name":"Kasbon","value":"1000000","is_loan":1,"is_added":1,"loan_id":11}
                    'name' => 'Bayar Kasbon tanggal ' . Carbon::parse($item->date)->isoFormat('LL'),
                    'value' => $item->amount,
                    'is_loan' => 1,
                    'is_added' => 1,
                    'loan_id' => $item->id,
                ];
            })->all();

            if (count($item->payslips) > 0) {
                $item->payslips[0]->pivot->incomes = json_decode($item->payslips[0]->pivot->incomes);
                $item->payslips[0]->pivot->deductions = json_decode($item->payslips[0]->pivot->deductions);


                // !----------------------------------------------------
                // ? MAPPING INCOMES
                // $incomeIds = collect($item->payslips[0]->pivot->incomes)->pluck('id')->all();
                // $incomeValues = collect($item->payslips[0]->pivot->incomes)->map(function ($item, $key) {
                //     return [
                //         'id' => $item->id,
                //         'value' => $item->value,
                //     ];
                // })->all();
                // $item['income_values'] = $incomeValues;

                // ? MAPPING DEDUCTIONS
                // $deductionIds = collect($item->payslips[0]->pivot->deductions)->pluck('id');
                // $deductionValues = collect($item->payslips[0]->pivot->deductions)->map(function ($item, $key) {
                //     return [
                //         'id' => $item->id,
                //         'value' => $item->value,
                //     ];
                // })->all();


                // ? IF INCOME TYPE = MANUAL SET TO ZERO 
                // $item->payslips[0]->pivot->incomes = $salaryIncomes
                //     ->whereIn('id', $incomeIds)
                //     ->each(function ($income, $key) use ($incomeValues) {
                //         if ($income->type == 'Manual') {
                //             $income['value'] = 0;
                //         } else {
                //             $income['value'] = collect($incomeValues)->firstWhere('id', $income->id)['value'];
                //         }
                //     })->all();

                // ? IF DEDUCTION TYPE = MANUAL SET TO ZERO 
                // $item->payslips[0]->pivot->deductions = $salaryDeductions
                //     ->whereIn('id', $deductionIds)
                //     ->each(function ($deduction, $key) use ($deductionValues) {
                //         if ($deduction->type == 'Manual') {
                //             $deduction['value'] = 0;
                //         } else {
                //             $deduction['value'] = collect($deductionValues)->firstWhere('id', $deduction->id)['value'];
                //         }
                //     })->all();

                $basicSalary = collect($item->payslips[0]->pivot->incomes)->firstWhere('type', 'gaji pokok');

                // $item['basic_salary'] = $basicSalary;

                $excessLeaveAmount = 0;

                if ($basicSalary !== null) {
                    $excessLeaveAmount = round($basicSalary->value / 26) * $item->excess_leave;
                    // $excessLeaveAmount = $item->excess_leave;
                }

                // $item['excess_leave_amount'] = $excessLeaveAmount;

                // if (count($loans) > 0) {
                //     $item['loans_ff'] = $loans;
                // }
                // if (count($payments) > 0) {
                //     $item['payments_ff'] = $payments;
                // }

                // $item['type_loans'] = gettype($loans);
                // $item['type_incomes'] = gettype($item->payslips[0]->pivot->incomes);
                $item['count_loans'] = count($loans);

                // $item->payslips[0]->pivot->incomes = array_merge($item->payslips[0]->pivot->incomes, $loans);
                // array_push($item->payslips[0]->pivot->incomes, [
                //     'asdasd' => 'asdasd',
                // ]);
                if ($item->presence_incentive > 0) {
                    $item->payslips[0]->pivot->incomes = collect($item->payslips[0]->pivot->incomes)->push([
                        'name' => 'Insentif Kehadiran',
                        'value' => $item->presence_incentive,
                        'is_added' => 1,
                    ])->all();
                }

                if ($excessLeaveAmount > 0) {
                    $item->payslips[0]->pivot->deductions = collect($item->payslips[0]->pivot->deductions)->push([
                        'name' => 'Kelebihan Cuti (' . $item->excess_leave . ' Hari)',
                        'value' => $excessLeaveAmount,
                        'is_added' => 1,
                        'is_excess_leave' => 1,
                        'excess_leave' => $item->excess_leave,
                    ])->all();
                }


                if (count($loans) > 0) {
                    $item->payslips[0]->pivot->incomes = array_merge($item->payslips[0]->pivot->incomes, $loans);
                }
                if (count($payments) > 0) {
                    $item->payslips[0]->pivot->deductions = array_merge($item->payslips[0]->pivot->deductions, $payments);
                }

                // !----------------------------------------------------
            }
        });

        // $previewPayslips = collect($previewPayslips)->map(function ($item, $key) {
        //     return collect($item)->except(['employee'])->all();
        //     // return $item;
        // })->all();

        // return $previewPayslips;

        // return [
        //     'payslips' => $payslips,
        //     'payslip' => $payslip,
        //     'preview_payslips' => $previewPayslips,
        //     'final_payslips' => $finalPayslips,
        //     'start_date_period' => $startDatePeriod,
        //     'end_date_period' => $endDatePeriod,
        // ];

        // return $previewPayslips;
        // return $previewPayslips;
        $totalPreviewPayslips = collect($previewPayslips)->map(function ($career) {
            if (count($career->payslips) < 1) {
                return 0;
            }

            $totalIncomes = collect($career->payslips[0]->pivot->incomes)->sum('value');
            $totalDeductions = collect($career->payslips[0]->pivot->deductions)->sum('value');

            $thp = $totalIncomes - $totalDeductions;
            return $thp;
        })->sum();

        $totalFinalPayslips = collect($finalPayslips)->map(function ($payslip) {
            $totalIncomes = collect($payslip->income)->sum('value');
            $totalDeductions = collect($payslip->deduction)->sum('value');
            $thp = $totalIncomes - $totalDeductions;
            return $thp;
        })->sum();

        // return $totalFinalPayslips;

        return view('payroll.show', [
            'payslips' => $payslips,
            'payslip' => $payslip,
            'preview_payslips' => $previewPayslips,
            'total_preview_payslips' => $totalPreviewPayslips,
            'total_final_payslips' => $totalFinalPayslips,
            'final_payslips' => $finalPayslips,
            'start_date_period' => $startDatePeriod,
            'end_date_period' => $endDatePeriod,
        ]);
    }

    public function mergeAttendances($attendances, $employee, $calendars)
    {
        return collect($attendances)->sortBy('date')->groupBy('date')->map(function ($item, $key) use ($employee, $calendars) {
            $category = null;
            $pendingCategory = null;
            $clockIn = null;
            $clockOut = null;
            $note = null;
            // $overtime = 0;
            // $images = [];

            foreach ($item as $att) {
                // $note = $att->note;
                // if ($att->image || isset($att->image)) {
                //     $images = array_merge($images, [$att->image]);
                // }
                if ($att->category == 'present') {
                    if ($att->status == 'approved') {
                        $category = 'present';
                    } else if ($att->status == 'pending') {
                        $category = 'pending';
                        $pendingCategory = 'Hadir';
                    } else {
                        $category = 'rejected';
                    }
                    if ($att->type == 'check in') {
                        $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                    } else if ($att->type == 'check out') {
                        $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                    }
                } else if ($att->category == 'sick') {
                    if ($att->status == 'approved') {
                        $category = 'sick';
                    } else if ($att->status == 'pending') {
                        $category = 'pending';
                        $pendingCategory = 'Sakit';
                    } else {
                        $category = 'rejected';
                    }
                    if ($att->type == 'check in') {
                        $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                    } else if ($att->type == 'check out') {
                        $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                    }
                } else if ($att->category == 'permission') {
                    if ($att->status == 'approved') {
                        // $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                        $category = 'permission';
                    } else if ($att->status == 'pending') {
                        $category = 'pending';
                        $pendingCategory = 'Izin';
                    } else {
                        $category = 'rejected';
                    }
                    if ($att->type == 'check in') {
                        $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                    } else if ($att->type == 'check out') {
                        $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                    }
                } else if ($att->category == 'leave') {
                    if ($att->status == 'approved') {
                        $category = 'leave';
                    } else if ($att->status == 'pending') {
                        $category = 'pending';
                        $pendingCategory = 'Cuti';
                    } else {
                        $category = 'rejected';
                    }
                    if ($att->type == 'check in') {
                        $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                    } else if ($att->type == 'check out') {
                        $clockOut = date_format(date_create($att->clock_out), "H:i:s");
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
            // $dailyMoney = $employee->daily_money_regular;
            // $overtimePay = 0;

            // if ($overtime > 0) {
            //     $overtimePay = $overtimePay + ($employee->overtime_pay_regular * $overtime);
            // }

            // // If day is holiday
            // if ($dayStatus == 'holiday' || $dayStatus == 'cuti bersama' || $dayStatus == 'libur nasional') {
            //     $dailyMoney = $employee->daily_money_holiday;
            //     // $overtimePay = $employee->overtime_pay_holiday;
            //     $overtimePay = 0;

            //     if ($overtime > 0) {
            //         $overtimePay = $overtimePay + ($employee->overtime_pay_holiday * $overtime);
            //     }
            // }

            // Return End-Result
            return [
                'category' => $category,
                'pending_category' => $pendingCategory,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                // 'working_hours' => $workingHours,
                'date' => $key,
                // 'overtime' => $overtime,
                'day_status' => $dayStatus,
                // 'daily_money' => $dailyMoney,
                // 'overtime_pay' => $overtimePay,
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('company.edit', ['company' => $company]);
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
        $company = Company::find($id);
        $company->name = $request->name;
        $company->registration_number = $request->registration_number;
        $company->contact_number = $request->contact_number;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->npwp = $request->npwp;
        $company->address = $request->address;
        $company->province = $request->province;
        $company->country = $request->country;
        $company->city = $request->city;
        $company->zip_code = $request->zip_code;
        // $company->logo = $request->logo;
        // $company->added_by = $request->added_by;
        // $company->logo = 'magenta-logo.png';
        // $company->added_by = 1;

        try {
            $company->save();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);
        try {
            $company->delete();
            return [
                'message' => 'data has been deleted',
                'error' => false,
                'code' => 200,
            ];
        } catch (Exception $e) {
            return [
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function batchDestroy(Request $request)
    {
        try {
            // $finalPayslip->delete();
            $ids = json_decode($request->query('ids'));
            FinalPayslip::query()->whereIn('id', $ids)->forceDelete();
            return [
                'data' => $request->all(),
                'message' => 'data has been deleted',
                'error' => false,
                'code' => 200,
            ];
        } catch (Exception $e) {
            return [
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ];
        }
    }

    public function print($id)
    {
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        $finalPayslip = FinalPayslip::findOrFail($id);
        $finalPayslip->income = json_decode($finalPayslip->income);
        $finalPayslip->deduction = json_decode($finalPayslip->deduction);

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo', 'daily_money_regular', 'daily_money_holiday', 'overtime_pay_regular', 'overtime_pay_holiday'];

        $employee = Employee::query()->select($employeeColumns)->with(['careers' => function ($query) {
            $query->with(['jobTitle', 'designation', 'department'])->where('is_active', 1);
        }])->find($finalPayslip->employee_id);

        if ($employee == null) {
            abort(500);
        }
        // return $finalPayslip;

        $data = [
            'employee' => $employee,
            'final_payslip' => $finalPayslip,
        ];
        // return $data;

        $pdf = PDF::loadView('payroll.print', $data);
        return $pdf->stream();
        // $mpdf = new \Mpdf\Mpdf();
        // $mpdf->load
        // $mpdf->WriteHTML('<h1>Hello world!</h1>');
        // $mpdf->Output();
    }

    public function exportMonthlyReport(Request $request)
    {
        $startDatePeriod = $request->query('start_date_period');
        $endDatePeriod = $request->query('end_date_period');
        $staffOnly = $request->query('staffonly');

        $employees = Employee::with(['npwp', 'activeCareer', 'activeCareer' => function ($query) {
            $query->with(['designation', 'department', 'jobTitle']);
        }, 'finalPayslips' => function ($query) use ($startDatePeriod, $endDatePeriod) {
            $query->where('type', 'fix_period')->where('start_date_period', $startDatePeriod)->where('end_date_period', $endDatePeriod);
        }])->get();
        // ->where('employee_id', 'like', 'MM' . '%')
        // ->get()->each(function ($employee) {
        //     $basicSalary = 0;
        //     $positionAllowance = 0;
        //     $attendanceAllowance = 0;
        //     $loan = 0;
        //     $excessLeave = 0;
        //     $total = 0;

        //     if (count($employee->finalPayslips) > 0) {
        //         $payslip = collect($employee->finalPayslips)->first();
        //         $incomes = json_decode($payslip->income);
        //         $deductions = json_decode($payslip->deduction);
        //         $basicSalary = collect($incomes)->where('type', 'gaji pokok')->sum('value');
        //         $positionAllowance = collect($incomes)->where('name', 'Tunjangan Jabatan')->sum('value');
        //         $attendanceAllowance = collect($incomes)->where('name', 'Insentif Kehadiran')->sum('value');
        //         $loan = collect($deductions)->where('is_loan', 1)->sum('value');
        //         $excessLeave = collect($deductions)->where('is_excess_leave', 1)->sum('value');
        //         $total = ($basicSalary + $positionAllowance + $attendanceAllowance) - ($loan + $excessLeave);
        //         $attendanceAllowance = collect($incomes)->where('name', 'Insentif Kehadiran');
        //     }

        //     $employee['basic_salary'] = $basicSalary;
        //     $employee['position_allowance'] = $positionAllowance;
        //     $employee['attendance_allowance'] = $attendanceAllowance;
        //     $employee['loan'] = $loan;
        //     $employee['excess_leave'] = $excessLeave;
        //     $employee['total'] = $total;
        // });

        // return $employees;

        return Excel::download(new PayrollReportExport($startDatePeriod, $endDatePeriod, $staffOnly), 'Laporan Gaji ' . $startDatePeriod . ' - ' . $endDatePeriod . '.xlsx');
    }
}
