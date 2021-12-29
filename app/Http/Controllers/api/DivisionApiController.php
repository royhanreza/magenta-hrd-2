<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class DivisionApiController extends Controller
{
    public function attendances($id)
    {
        $date = date("Y-m-d");

        $attendances = $this->getAttendance($date, $id)['attendances'];
        $attendanceSummary = $this->getAttendance($date, $id)['summary'];
        // $pendingAttendances = $this->getAttendance($date)['pending_attendances'];
        // return view('attendance.index', [
        //     'attendances' => $attendances,
        //     'summary' => $attendanceSummary,
        //     'pending_attendances' => $pendingAttendances
        // ]);

        return response()->json([
            'status' => 'OK',
            'error' => false,
            'code' => 200,
            'data' => [
                'attendances' => $attendances,
                'summary' => $attendanceSummary,
                // 'pending_attendances' => $pendingAttendances
            ]
        ]);
    }

    public function attendanceByDivisions(Request $request)
    {
        $date = $request->query('date');
        if ($date == null) {
            $date = date("Y-m-d");
        }
        // $date = "2021-06-16";

        $requestIds = $request->query('id');
        $divisionIds = explode(",", $requestIds);

        // return $divisionIds;

        $attendances = $this->getAttendanceByDivisions($date, $divisionIds)['attendances'];
        $attendanceSummary = $this->getAttendanceByDivisions($date, $divisionIds)['summary'];
        // $pendingAttendances = $this->getAttendance($date)['pending_attendances'];
        // return view('attendance.index', [
        //     'attendances' => $attendances,
        //     'summary' => $attendanceSummary,
        //     'pending_attendances' => $pendingAttendances
        // ]);

        return response()->json([
            'status' => 'OK',
            'error' => false,
            'code' => 200,
            'data' => [
                'attendances' => $attendances,
                'summary' => $attendanceSummary,
                // 'pending_attendances' => $pendingAttendances
            ]
        ]);
    }

    private function getAttendance($date, $divisionId = null)
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id', 'photo'];

        $date = date_format(date_create($date), "Y-m-d");

        // $attendances = Attendance::with(['employee' => function ($query) use ($employeeColumns) {
        //     $query->select($employeeColumns)->with('designation.department.company');
        // }])->orderBy('created_at', 'DESC')->get();
        $attendances = Employee::query()
            ->select($employeeColumns)
            ->whereHas('careers.designation', function ($query) use ($divisionId) {
                $query->where('id', $divisionId);
            })
            ->with(['careers' => function ($query) {
                $query->with(['designation', 'department', 'jobTitle'])
                    ->where('is_active', 1)
                    ->orderByDesc('effective_date');
            }, 'attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }])->get();

        $filteredAttendances = $attendances->flatMap(function ($item, $key) {
            return $item->attendances;
        })->all();

        $sickCount = collect($filteredAttendances)->where('category', 'sick')->where('date', $date)->where('status', 'approved')->count();
        $presentCount = collect($filteredAttendances)->where('date', $date)->where('type', 'check in')->where('status', 'approved')->count();
        $permissionCount = collect($filteredAttendances)->where('category', 'permission')->where('date', $date)->where('status', 'approved')->count();
        $leaveCount = collect($filteredAttendances)->where('category', 'leave')->where('date', $date)->where('status', 'approved')->count();
        $rejectedCount = collect($filteredAttendances)->where('date', $date)->where('status', 'rejected')->groupBy('employee_id')->count();
        $pendingCount = collect($filteredAttendances)->where('date', $date)->where('status', 'pending')->groupBy('employee_id')->count();

        $attendanceSummary = [
            'sick_count' => $sickCount,
            'present_count' => $presentCount,
            'permission_count' => $permissionCount,
            'leave_count' => $leaveCount,
            'rejected_count' => $rejectedCount,
            'pending_count' => $pendingCount,
        ];

        // return $attendances;

        // $date = date('Y-m-d');

        // $attendanceSummary = [
        //     'sick_count' => DB::table('attendances')->where('category', 'sick')->where('date', $date)->where('status', 'approved')->count(),
        //     'present_count' => DB::table('attendances')->where('category', 'present')->where('date', $date)->where('type', 'check in')->where('status', 'approved')->count(),
        //     'permission_count' => DB::table('attendances')->where('category', 'permission')->where('date', $date)->where('status', 'approved')->count(),
        //     'leave_count' => DB::table('attendances')->where('category', 'leave')->where('date', $date)->where('status', 'approved')->count(),
        //     'rejected_count' => DB::table('attendances')->where('date', $date)->where('status', 'rejected')->groupBy('employee_id')->count(),
        //     'pending_count' => DB::table('attendances')->where('date', $date)->where('status', 'pending')->groupBy('employee_id')->count(),
        // ];

        $attendances->each(function ($item, $key) {
            $item['status'] = null;
            $item['pending_category'] = null;
            $item['clock_in'] = null;
            $item['clock_out'] = null;
            $item['overtime_duration'] = 0;
            $item['checkout_id'] = null;
            $item['note'] = null;
            $item['images'] = [];

            if (count($item->attendances) > 0) {
                foreach ($item->attendances as $att) {
                    $item['note'] = $att->note;
                    if ($att->image || isset($att->image)) {
                        $item['images'] = array_merge($item['images'], [$att->image]);
                    }
                    if ($att->category == 'present') {
                        if ($att->status == 'approved') {
                            $item['status'] = 'present';
                        } else if ($att->status == 'pending') {
                            $item['status'] = 'pending';
                            $item['pending_category'] = 'Hadir';
                        } else {
                            $item['status'] = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                            $item['overtime_duration'] = $att->overtime_duration;
                            $item['checkout_id'] = $att->id;
                        }
                    } else if ($att->category == 'sick') {
                        if ($att->status == 'approved') {
                            $item['status'] = 'sick';
                        } else if ($att->status == 'pending') {
                            $item['status'] = 'pending';
                            $item['pending_category'] = 'Sakit';
                        } else {
                            $item['status'] = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'permission') {
                        if ($att->status == 'approved') {
                            $item['status'] = 'permission';
                        } else if ($att->status == 'pending') {
                            $item['status'] = 'pending';
                            $item['pending_category'] = 'Izin';
                        } else {
                            $item['status'] = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'leave') {
                        if ($att->status == 'approved') {
                            $item['status'] = 'leave';
                        } else if ($att->status == 'pending') {
                            $item['status'] = 'pending';
                            $item['pending_category'] = 'Cuti';
                        } else {
                            $item['status'] = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    }
                }
            }
        });

        // $pendingAttendances = Attendance::with(['employee' => function ($query) {
        //     $query->with(['careers.jobTitle', 'careers.designation']);
        // }])->where('status', 'pending')->where('date', $date)->get();

        return [
            'attendances' => $attendances,
            'summary' => $attendanceSummary,
            // 'pending_attendances' => $pendingAttendances
        ];
    }

    private function getAttendanceByDivisions($date, $divisionIds = null)
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id', 'photo'];

        $date = date_format(date_create($date), "Y-m-d");

        // $attendances = Attendance::with(['employee' => function ($query) use ($employeeColumns) {
        //     $query->select($employeeColumns)->with('designation.department.company');
        // }])->orderBy('created_at', 'DESC')->get();
        $attendances = Employee::query()
            ->select($employeeColumns)
            ->whereHas('careers.designation', function ($query) use ($divisionIds) {
                $query->whereIn('id', $divisionIds);
            })
            ->with(['careers' => function ($query) {
                $query->with(['designation', 'department', 'jobTitle'])
                    ->where('is_active', 1)
                    ->orderByDesc('effective_date');
            }, 'attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }])->get();

        $filteredAttendances = $attendances->flatMap(function ($item, $key) {
            return $item->attendances;
        })->all();

        $sickCount = collect($filteredAttendances)->where('category', 'sick')->where('date', $date)->where('status', 'approved')->count();
        $presentCount = collect($filteredAttendances)->where('date', $date)->where('type', 'check in')->where('status', 'approved')->count();
        $permissionCount = collect($filteredAttendances)->where('category', 'permission')->where('date', $date)->where('status', 'approved')->count();
        $leaveCount = collect($filteredAttendances)->where('category', 'leave')->where('date', $date)->where('status', 'approved')->count();
        $rejectedCount = collect($filteredAttendances)->where('date', $date)->where('status', 'rejected')->groupBy('employee_id')->count();
        $pendingCount = collect($filteredAttendances)->where('date', $date)->where('status', 'pending')->groupBy('employee_id')->count();

        $attendanceSummary = [
            'sick_count' => $sickCount,
            'present_count' => $presentCount,
            'permission_count' => $permissionCount,
            'leave_count' => $leaveCount,
            'rejected_count' => $rejectedCount,
            'pending_count' => $pendingCount,
        ];

        // return $attendances;

        // $date = date('Y-m-d');

        // $attendanceSummary = [
        //     'sick_count' => DB::table('attendances')->where('category', 'sick')->where('date', $date)->where('status', 'approved')->count(),
        //     'present_count' => DB::table('attendances')->where('category', 'present')->where('date', $date)->where('type', 'check in')->where('status', 'approved')->count(),
        //     'permission_count' => DB::table('attendances')->where('category', 'permission')->where('date', $date)->where('status', 'approved')->count(),
        //     'leave_count' => DB::table('attendances')->where('category', 'leave')->where('date', $date)->where('status', 'approved')->count(),
        //     'rejected_count' => DB::table('attendances')->where('date', $date)->where('status', 'rejected')->groupBy('employee_id')->count(),
        //     'pending_count' => DB::table('attendances')->where('date', $date)->where('status', 'pending')->groupBy('employee_id')->count(),
        // ];

        $attendances->each(function ($item, $key) {
            $item['status'] = null;
            $item['pending_category'] = null;
            $item['clock_in'] = null;
            $item['clock_out'] = null;
            $item['overtime_duration'] = 0;
            $item['checkout_id'] = null;
            $item['note'] = null;
            $item['images'] = [];

            if (count($item->attendances) > 0) {
                foreach ($item->attendances as $att) {
                    $item['note'] = $att->note;
                    if ($att->image || isset($att->image)) {
                        $item['images'] = array_merge($item['images'], [$att->image]);
                    }
                    if ($att->category == 'present') {
                        if ($att->status == 'approved') {
                            $item['status'] = 'present';
                        } else if ($att->status == 'pending') {
                            $item['status'] = 'pending';
                            $item['pending_category'] = 'Hadir';
                        } else {
                            $item['status'] = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                            $item['overtime_duration'] = $att->overtime_duration;
                            $item['checkout_id'] = $att->id;
                        }
                    } else if ($att->category == 'sick') {
                        if ($att->status == 'approved') {
                            $item['status'] = 'sick';
                        } else if ($att->status == 'pending') {
                            $item['status'] = 'pending';
                            $item['pending_category'] = 'Sakit';
                        } else {
                            $item['status'] = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'permission') {
                        if ($att->status == 'approved') {
                            $item['status'] = 'permission';
                        } else if ($att->status == 'pending') {
                            $item['status'] = 'pending';
                            $item['pending_category'] = 'Izin';
                        } else {
                            $item['status'] = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'leave') {
                        if ($att->status == 'approved') {
                            $item['status'] = 'leave';
                        } else if ($att->status == 'pending') {
                            $item['status'] = 'pending';
                            $item['pending_category'] = 'Cuti';
                        } else {
                            $item['status'] = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    }
                }
            }
        });

        // $pendingAttendances = Attendance::with(['employee' => function ($query) {
        //     $query->with(['careers.jobTitle', 'careers.designation']);
        // }])->where('status', 'pending')->where('date', $date)->get();

        return [
            'attendances' => $attendances,
            'summary' => $attendanceSummary,
            // 'pending_attendances' => $pendingAttendances
        ];
    }
}
