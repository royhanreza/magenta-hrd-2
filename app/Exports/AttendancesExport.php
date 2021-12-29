<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Career;
use App\Models\Employee;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AttendancesExport implements FromView
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
        // $employee = Employee::findOrFail($this->employee_id);

        // $date1 = date("Y-m-01");
        // $date2 = date("Y-m-t");
        $date1 = $this->startDate;
        $date2 = $this->endDate;

        $employees = Employee::all();

        $period = [];

        foreach ($employees as $employee) {
            $attendances = $this->getAttendance($date1, $date2, $employee->id)['attendances'];

            $attendancesKeys = collect($this->getAttendance($date1, $date2, $employee->id)['attendances'])->map(function ($item, $key) {
                return $key;
            })->all();


            $period[] = [
                'employee' => $employee,
                'attendances' => collect($this->getDatesFromRange($date1, $date2))->map(function ($item, $key) use ($attendancesKeys, $attendances) {
                    $searchResult = array_search($item, $attendancesKeys);
                    if ($searchResult !== false) {
                        return [
                            'date' => $item,
                            'attendance' => $attendances[$searchResult],
                        ];
                    }

                    return [
                        'date' => $item,
                        'attendance' => null
                    ];
                })->values()->all()
            ];
        }

        // $period = collect($period)->flatMap(function ($data) {
        //     return $data;
        // });

        // return $period;


        return view('attendance.sheet.attendances', [
            'employees' => $period,
        ]);
    }

    private function getAttendance($date1, $date2, $employee_id = null)
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id', 'photo'];

        $attendances = Attendance::query()
            // ->with(['employee'])
            ->where('employee_id', $employee_id)
            ->where('date', '>=', $date1)
            ->where('date', '<=', $date2)
            ->get()->sortBy('date')->groupBy('date')->map(function ($item, $key) {
                // $item['status'] = null;
                // $item['pending_category'] = null;
                // $item['clock_in'] = null;
                // $item['clock_out'] = null;
                // $item['note'] = null;
                // $item['images'] = [];
                $status = null;
                $pendingCategory = null;
                $clockIn = null;
                $clockOut = null;
                $note = null;
                $images = [];

                // foreach ($item as $att) {
                //     if ($att->clock_in !== null) {
                //         $item['checkin'] = $att;
                //     } else {
                //         $item['checkout'] = $att;
                //     }
                // }
                foreach ($item as $att) {
                    $note = $att->note;
                    if ($att->image || isset($att->image)) {
                        $images = array_merge($images, [$att->image]);
                    }
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
                        }
                    } else if ($att->category == 'sick') {
                        if ($att->status == 'approved') {
                            $status = 'sick';
                        } else if ($att->status == 'pending') {
                            $status = 'pending';
                            $pendingCategory = 'Sakit';
                        } else {
                            $status = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'permission') {
                        if ($att->status == 'approved') {
                            $status = 'permission';
                        } else if ($att->status == 'pending') {
                            $status = 'pending';
                            $pendingCategory = 'Izin';
                        } else {
                            $status = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'leave') {
                        if ($att->status == 'approved') {
                            $status = 'leave';
                        } else if ($att->status == 'pending') {
                            $status = 'pending';
                            $pendingCategory = 'Cuti';
                        } else {
                            $status = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    }
                }

                // return $item['checkin'];

                return [
                    'status' => $status,
                    'pending_category' => $pendingCategory,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'note' => $note,
                    'images' => $images,
                ];
            })->all();

        return [
            'attendances' => $attendances,
            // 'summary' => $attendanceSummary,
            // 'pending_attendances' => $pendingAttendances
        ];
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
}
