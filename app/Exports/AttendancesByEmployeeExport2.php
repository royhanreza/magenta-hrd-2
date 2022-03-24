<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Calendar;
use App\Models\Career;
use App\Models\Employee;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AttendancesByEmployeeExport2 implements FromView
{
    private $employee_id;
    private $startDate;
    private $endDate;

    public function __construct($employee_id, $startDate, $endDate)
    {
        $this->employee_id = $employee_id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $employee = Employee::findOrFail($this->employee_id);

        // $date1 = date("Y-m-01");
        // $date2 = date("Y-m-t");
        $employeeId = $this->employee_id;
        $date1 = $this->startDate;
        $date2 = $this->endDate;
        // $attendances = $this->getAttendance($date1, $date2, $this->employee_id)['attendances'];

        // $attendancesKeys = collect($this->getAttendance($date1, $date2, $this->employee_id)['attendances'])->map(function ($item, $key) {
        //     return $key;
        // })->all();
        // // $attendanceSummary = $this->getAttendance($date1, $date2, $this->employee_id)['summary'];
        // $pendingAttendances = $this->getAttendance($date1, $date2, $this->employee_id)['pending_attendances'];

        // // return $attendances;

        // $period = collect($this->getDatesFromRange($date1, $date2))->map(function ($item, $key) use ($attendancesKeys, $attendances) {
        //     $searchResult = array_search($item, $attendancesKeys);
        //     if ($searchResult !== false) {
        //         return [
        //             'date' => $item,
        //             'attendance' => $attendances[$searchResult],
        //         ];
        //     }

        //     return [
        //         'date' => $item,
        //         'attendance' => null
        //     ];
        // });

        $calendars = Calendar::all();

        $employeeAttendances = Attendance::query()->where('employee_id', $employeeId)->whereBetween('date', [$date1, $date2])->get();

        $attendances = $this->mergeAttendances($employeeAttendances, $employee, $calendars);

        $attendancesKeys = collect($attendances)->map(function ($item, $key) {
            return $key;
            // return $item->date;
        })->all();

        $period = collect($this->getDatesFromRange($date1, $date2))->map(function ($item, $key) use ($attendancesKeys, $attendances, $employee, $calendars) {
            $searchResult = array_search($item, $attendancesKeys);

            $dayStatus = $this->getDayStatus($employee, $item, $calendars);
            if ($searchResult !== false) {
                return [
                    'date' => $item,
                    'attendance' => $attendances[$searchResult],
                    'day_status' => $dayStatus,
                ];
            }

            return [
                'date' => $item,
                'attendance' => null,
                'day_status' => $dayStatus,
            ];
        });

        // return $period;

        // return [
        //     'period' => $period,
        //     'summary' => $attendanceSummary,
        //     'pending_attendances' => $pendingAttendances
        // ];

        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $this->employee_id)->max('id'));


        // return view('employee.attendance', [
        //     'period' => $period,
        //     'summary' => $attendanceSummary,
        //     'pending_attendances' => $pendingAttendances,
        //     'employee' => $employee,
        //     'last_career' => $lastCareer,
        // ]);
        return view('attendance.sheet.attendancebyemployee', [
            'period' => $period,
            // 'summary' => $attendanceSummary,
            // 'pending_attendances' => $pendingAttendances,
            'employee' => $employee,
            'last_career' => $lastCareer,
        ]);
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
                        $status = $category;
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
                        // !OVERTIME_SUBMISSION
                        // $overtime = $att->overtime_duration;
                        $overtime = $att->overtime_submission_duration;
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
}
