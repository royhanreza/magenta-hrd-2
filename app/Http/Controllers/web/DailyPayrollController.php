<?php

namespace App\Http\Controllers\web;

use App\Exports\DailyPayrollMonthlyReportExport;
use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Company;
use App\Models\Employee;
use App\Models\FinalPayslip;
use App\Models\PaySlip;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class DailyPayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $year = date('Y');

        if ($request->query('year') !== null) {
            $year = $request->query('year');
        }
        // $payslips = PaySlip::where('period_type', 'tetap')->get();
        // $finalPayslips = FinalPayslip::with(['employee', 'employee.careers' => function ($query) {
        //     $query->where('is_active', 1);
        // }])->where('type', 'custom_period')->get();
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $finalPayslips = FinalPayslip::where('type', 'custom_period')
            // ->limit(10)
            ->select(['id', 'start_date_period', 'end_date_period'])
            ->whereYear('end_date_period', $year)
            ->get()
            // ->groupBy(function ($payslip) {
            //     return date('m', strtotime($payslip->end_date_period));
            // })->all();
            ->groupBy([function ($payslip) {
                return (int) date('m', strtotime($payslip->end_date_period)) - 1;
            }, function ($payslip) {
                return $payslip->start_date_period . '/' . $payslip->end_date_period;
            }])->sort()
            ->all();

        // return $finalPayslips;
        // return $payslips;
        // return view('daily-payroll.index', ['payslips' => $finalPayslips]);
        return view('daily-payroll.v2.index', [
            'payslips' => $finalPayslips,
            'months' => $months,
            'year' => $year,
        ]);
    }

    public function report(Request $request)
    {

        $month = $request->query('month');
        $year = $request->query('year');

        if ($month == null || $year == null) {
            abort(404);
        }

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $finalPayslips = FinalPayslip::with(['employee', 'employee.careers' => function ($query) {
            $query->where('is_active', 1);
        }])->whereHas('employee', function ($q) {
            $q->where('employee_id', 'like', 'EP' . '%');
        })
            ->where('type', 'custom_period')
            ->whereMonth('end_date_period', $month)
            ->whereYear('end_date_period', $year)
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
            })->groupBy('period')
            // ->groupBy(function ($period) {
            //     $endDatePeriod = explode(' - ', $period)[1];
            //     $month = (int) explode('-', $endDatePeriod)[1];
            //     return $month;
            // })
            ->all();

        // $finalPayslips = FinalPayslip::with(['employee', 'employee.careers' => function ($query) {
        //     $query->where('is_active', 1);
        // }])
        //     ->whereHas('employee', function ($q) {
        //         $q->where('employee_id', 'like', 'EP%');
        //     })
        //     ->where('type', 'custom_period')
        //     ->whereMonth('end_date_period', '08')
        //     ->whereYear('end_date_period', '2021')
        //     ->get();

        // return $finalPayslips;
        return Excel::download(new DailyPayrollMonthlyReportExport($month, $year), 'Laporan Gaji Harian Bulan ' . $months[(int) $month - 1] . ' ' . $year . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payslips = PaySlip::where('period_type', 'tetap')->get();
        // return $payslips;
        return view('daily-payroll.v2.create', ['payslips' => $payslips]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $final_payslip = new FinalPayslip;
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
                'name' => $item['payslip_name'],
                'employee_id' => $item['id'],
                'start_date_period' => $request->start_date,
                'end_date_period' => $request->end_date,
                'type' => 'custom_period',
                'income' => json_encode($item['payments']),
                'deduction' => json_encode($item['deductions']),
                'description' => null,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByDate(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($startDate == null || $endDate == null) {
            abort(404);
        }

        $finalPayslips = FinalPayslip::with(['employee', 'employee.careers' => function ($query) {
            $query->where('is_active', 1);
        }])
            ->where('type', 'custom_period')
            ->where('start_date_period', $startDate)
            ->where('end_date_period', $endDate)
            ->get();

        return view('daily-payroll.v2.show-by-date', [
            'payslips' => $finalPayslips,
        ]);
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
        $finalPayslip = FinalPayslip::find($id);
        try {
            $finalPayslip->delete();
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

    public function generate(Request $request)
    {
        // $startDatePeriod = $request->startDatePeriod;
        // $endDatePeriod = $request->endDatePeriod;
        $startDatePeriod = $request->query('startDate');
        $endDatePeriod = $request->query('endDate');

        // return $request->query('startDate');
        // $startDatePeriod = '2021-05-01';
        // $endDatePeriod = '2021-05-07';

        $periodDiff = Carbon::parse($startDatePeriod)->diffInDays($endDatePeriod);

        $finalPayslips = FinalPayslip::query()
            ->where('type', 'custom_period')
            ->whereBetween('start_date_period', [$startDatePeriod, $endDatePeriod])
            ->orWhereBetween('end_date_period', [$startDatePeriod, $endDatePeriod])
            ->get();

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo', 'daily_money_regular', 'daily_money_holiday', 'overtime_pay_regular', 'overtime_pay_holiday'];

        $employees = Employee::query()->select($employeeColumns)->with(['attendances' => function ($query) use ($startDatePeriod, $endDatePeriod) {
            $query->whereBetween('date', [$startDatePeriod, $endDatePeriod]);
        }, 'officeShifts' => function ($query) {
            $query->where('is_active', 1);
        }, 'careers' => function ($query) {
            $query->with(['jobTitle', 'designation'])->where('is_active', 1);
        }])
            ->where('type', 'non staff')
            ->orWhere('type', 'freelancer')
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
                } else {
                    $dayStatus = $this->getDayStatus($employee, $item, $calendars);
                    if ($dayStatus == 'libur nasional' || $dayStatus == 'cuti bersama') {
                        $dailyMoney = $employee->daily_money_regular;

                        return [
                            'date' => $item,
                            'attendance' => [
                                'status' => null,
                                'pending_category' => null,
                                'clock_in' => null,
                                'clock_out' => null,
                                'working_hours' => null,
                                'date' => $attendancesKeys,
                                'overtime' => null,
                                'day_status' => $dayStatus,
                                'daily_money' => $dailyMoney,
                                'overtime_pay' => null,
                                'category' => null,
                                'minutes_of_delay' => 0,
                            ],
                            'day_status' => $this->getDayStatus($employee, $item, $calendars),
                        ];
                    } else {
                        return [
                            'date' => $item,
                            'attendance' => null,
                            'day_status' => $this->getDayStatus($employee, $item, $calendars),
                        ];
                    }
                }


                // if ($searchResult == false) {
                //     $dayStatus = $this->getDayStatus($employee, $item, $calendars);
                //     if ($dayStatus == 'libur nasional' || $dayStatus == 'cuti bersama') {
                //         $dailyMoney = $employee->daily_money_regular;

                //         return [
                //             'date' => $item,
                //             'attendance' => [
                //                 'status' => null,
                //                 'pending_category' => null,
                //                 'clock_in' => null,
                //                 'clock_out' => null,
                //                 'working_hours' => null,
                //                 'date' => $attendancesKeys,
                //                 'overtime' => null,
                //                 'day_status' => $dayStatus,
                //                 'daily_money' => $dailyMoney,
                //                 'overtime_pay' => null,
                //                 'category' => null,
                //             ],
                //             'day_status' => $this->getDayStatus($employee, $item, $calendars),
                //         ];
                //     } else {
                //         return [
                //             'date' => $item,
                //             'attendance' => null,
                //             'day_status' => $this->getDayStatus($employee, $item, $calendars),
                //         ];
                //     }
                // }

                return [
                    'date' => $item,
                    'attendance' => null,
                    'day_status' => $this->getDayStatus($employee, $item, $calendars),
                ];
            });

            $totalMinutesOfDelay = collect($period)->filter(function ($p) {
                // if ($p['attendance'] == null) {
                //     return false;
                // } else {
                //     if (isset($p['attendance']['category'])) {
                //         if ($p['attendance']['category'] == 'present') {
                //             return true;
                //         } else {
                //             return false;
                //         }
                //     } else {
                //         return false;
                //     }
                // }
                return $p['attendance'] !== null;
            })->sum(function ($p) {
                return $p['attendance']['minutes_of_delay'];
            });

            $deductions = [];

            if ($totalMinutesOfDelay > 0 && $totalMinutesOfDelay <= 60) {
                array_push($deductions, [
                    'name' => 'Denda Keterlambatan ' . $totalMinutesOfDelay . ' Menit',
                    'value' => $employee->daily_money_regular * 0.50,
                ]);
            } else if ($totalMinutesOfDelay > 60) {
                array_push($deductions, [
                    'name' => 'Denda Keterlambatan ' . $totalMinutesOfDelay . ' Menit',
                    'value' => $employee->daily_money_regular,
                ]);
            }

            $employee['payments'] = $period;
            $employee['total_minutes_of_delay'] = $totalMinutesOfDelay;
            $employee['deductions'] = $deductions;
            $employee['payslip_name'] = 'Slip Gaji Periode ' . date_format(date_create($startDatePeriod), "d/m/Y") . ' - ' . date_format(date_create($endDatePeriod), "d/m/Y");
        }

        // $attendancesKeys = collect($employees[0]->attendances)->map(function ($item, $key) {
        //     return $item->date;
        // })->all();

        // return $employees;
        // return $attendancesKeys;
        // $employees = collect($employees)->except(['attendances']);
        // $excludeEmployees = collect($finalPayslips)->map(function ($item, $key) {
        //     return $item->employee_id;
        // });

        // $employees = collect($employees)->map(function ($item, $key) {
        //     return collect($item)->except(['attendances'])->toArray();
        //     // return $item;
        // })->whereNotIn('id', $excludeEmployees)->values();

        // $employees = collect($employees)->whereNotIn('id', $excludeEmployees)->all();
        return response()->json([
            'message' => 'OK',
            'error' => false,
            'code' => 200,
            'data' => [
                'generated_payslips' => $employees,
                'final_payslips' => $finalPayslips,
                'start_date' => $startDatePeriod,
                'end_date' => $endDatePeriod,
                'amount_of_days' => $periodDiff + 1,
            ]
        ]);
        // return $excludeEmployees;
        // return $finalPayslips;
    }

    public function mergeAttendances($attendances, $employee, $calendars)
    {
        return collect($attendances)
            // ->where('category', 'present')
            // ->orWhere('category', 'sick')
            // ->orWhere('category', 'permission')
            // ->orWhere('category', 'leave')
            ->filter(function ($attendance) {
                return $attendance->category == 'present' || $attendance->category == 'sick' || $attendance->category == 'permission' || $attendance->category == 'leave';
            })
            ->sortBy('date')->groupBy('date')->map(function ($item, $key) use ($employee, $calendars) {
                $status = null;
                $pendingCategory = null;
                $clockIn = null;
                $clockOut = null;
                $note = null;
                $overtime = 0;
                $category = '';
                // $images = [];

                foreach ($item as $att) {
                    // $note = $att->note;
                    // if ($att->image || isset($att->image)) {
                    //     $images = array_merge($images, [$att->image]);
                    // }
                    // if ($att->category == 'present') {
                    $category = $att->category;
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
                    // }
                }

                $workingHours = Carbon::parse($clockIn)->diffInHours($clockOut);
                // $overtime = 0;
                $dayStatus = '';
                $currentDayShift = null;

                $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                if (count($employee->officeShifts) > 0) {
                    foreach ($dayNames as $day) {
                        $lowerDay = strtolower($day);
                        if (date('l', strtotime($key)) == $day) {
                            // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['monday'];
                            $dayStatus = $employee->officeShifts[0][$lowerDay . '_status'];
                            $currentDayShift = [
                                'time_in' => $employee->officeShifts[0][$lowerDay . '_in_time'],
                                'time_out' =>  $employee->officeShifts[0][$lowerDay . '_out_time'],
                                'lateness' =>  $employee->officeShifts[0][$lowerDay . '_lateness'],
                            ];
                            break;
                        }
                    }
                }

                // if (count($employee->officeShifts) > 0) {
                //     // Carbon::parse($employee->office_shifts[0]->)->diffInHours($clockOut);
                //     if (date('l', strtotime($key)) == 'Monday') {
                //         // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['monday'];
                //         $dayStatus = $employee->officeShifts[0]->monday_status;
                //         $currentDayShift = [
                //             'time_in' => $employee->officeShifts[0]->monday_in_time,
                //             'time_out' =>  $employee->officeShifts[0]->monday_out_time,
                //         ];
                //     } else if (date('l', strtotime($key)) == 'Tuesday') {
                //         // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['tuesday'];
                //         $dayStatus = $employee->officeShifts[0]->tuesday_status;
                //     } else if (date('l', strtotime($key)) == 'Wednesday') {
                //         // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['wednesday'];
                //         $dayStatus = $employee->officeShifts[0]->wednesday_status;
                //     } else if (date('l', strtotime($key)) == 'Thursday') {
                //         // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['thursday'];
                //         $dayStatus = $employee->officeShifts[0]->thursday_status;
                //     } else if (date('l', strtotime($key)) == 'Friday') {
                //         // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['friday'];
                //         $dayStatus = $employee->officeShifts[0]->friday_status;
                //     } else if (date('l', strtotime($key)) == 'Saturday') {
                //         // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['saturday'];
                //         $dayStatus = $employee->officeShifts[0]->saturday_status;
                //     } else if (date('l', strtotime($key)) == 'Sunday') {
                //         // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['sunday'];
                //         $dayStatus = $employee->officeShifts[0]->sunday_status;
                //     }
                // }

                // Get Calendar At Current Date
                $calendar = collect($calendars)->filter(function ($item) use ($key) {
                    return $item->date == $key && ($item->type == 'libur nasional' || $item->type == 'cuti bersama');
                })->first();

                // If Calendar Exists
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
                if ($dayStatus == 'holiday' || $dayStatus == 'cuti bersama' || $dayStatus == 'libur nasional')
                // if ($dayStatus == 'holiday') 
                {
                    $dailyMoney = $employee->daily_money_holiday;
                    // $overtimePay = $employee->overtime_pay_holiday;
                    $overtimePay = 0;

                    if ($overtime > 0) {
                        $overtimePay = $overtimePay + ($employee->overtime_pay_holiday * $overtime);
                    }
                }

                $clock = date('H:i:s', strtotime($clockIn));
                $minutesOfDelay = 0;
                $upperLimit = date('H:i:s', strtotime('08:00:00'));
                if ($currentDayShift !== null) {
                    $upperLimit = date('H:i:s', strtotime($currentDayShift['time_in']));
                }

                // If clock in is at night
                $nightBottomRange = date('Y-m-d H:i:s', strtotime(date("Y-m-d") . ' 18:00:00'));
                $nightUpperRange = date('Y-m-d H:i:s', strtotime(date("Y-m-d") . ' 04:00:00' . '+1 days'));

                // echo $nightUpperRange;
                $clockWithDate = date('Y-m-d H:i:s', strtotime(date("Y-m-d") . ' ' . $clock));
                if ($clockWithDate >= $nightBottomRange && $clockWithDate <= $nightUpperRange) {
                    $upperLimit = date('H:i:s', strtotime('20:00:00'));
                }

                $lateness = 1;
                if ($currentDayShift !== null) {
                    if (isset($currentDayShift['lateness'])) {
                        $lateness = $currentDayShift['lateness'];
                    }
                }

                if ($lateness == 1) {
                    if ($category == 'present') {
                        if ($clock > $upperLimit) {
                            // $minutesOfDelay = $upperLimit->diff($clock)->format('i');
                            $minutesOfDelay = Carbon::parse($upperLimit)->diffInMinutes($clock);
                        }
                    }
                }

                // If day is holiday
                if ($dayStatus == 'holiday' || $dayStatus == 'cuti bersama' || $dayStatus == 'libur nasional') {
                    $minutesOfDelay = 0;
                }

                // $minutesOfDelay =  $upperLimit

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
                    'category' => $category,
                    'minutes_of_delay' => $minutesOfDelay,
                    'upper_limit' => $upperLimit,
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

    public function print($id)
    {
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        $finalPayslip = FinalPayslip::findOrFail($id);
        $finalPayslip->income = json_decode($finalPayslip->income);
        $finalPayslip->deduction = json_decode($finalPayslip->deduction);

        $finalPayslip->income = collect($finalPayslip->income)->each(function ($item, $key) {
            $item->calendar = $this->dayCalendar($item->day_status);
            // $item->calendar = 'WD';
        })->all();

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

        $pdf = PDF::loadView('daily-payroll.v2.print', $data);
        return $pdf->stream();
        // $mpdf = new \Mpdf\Mpdf();
        // $mpdf->load
        // $mpdf->WriteHTML('<h1>Hello world!</h1>');
        // $mpdf->Output();
    }

    private function dayCalendar($status)
    {
        switch ($status) {
            case 'holiday':
                return 'HOL';
                break;
            case 'workday':
                return 'WD';
                break;
            case 'cuti bersama':
                return 'CB';
                break;
            case 'libur nasional':
                return 'LN';
                break;
            default:
                return '-';
        }
    }
}
