<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveSubmission;
use App\Models\Permission;
use App\Models\SickSubmission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // return $this->getPendingSubmission();
        $user = Auth::user();
        $eoOnly = $user->eo_only;

        $allEmployees = Employee::where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null)->get();
        $activeEmployees = $allEmployees->filter(function ($item, $key) {
            return $item->is_active == 1;
        })->count();
        $inactiveEmployees = $allEmployees->filter(function ($item, $key) {
            return $item->is_active == 0;
        })->count();

        $date = date("Y-m-d");
        $attendances = $this->getAttendance($date)['attendances'];
        $attendanceSummary = $this->getAttendance($date)['summary'];

        $pendingSubmissions = $this->getPendingSubmission();
        // return $employees;
        return view('dashboard.index', [
            'total_employees' => $allEmployees->count(),
            'active_employees' => $activeEmployees,
            'inactive_employees' => $inactiveEmployees,
            'attendances' => $attendances,
            'summary' => $attendanceSummary,
            'pending_submissions' => $pendingSubmissions
        ]);
    }

    private function getAttendance($date)
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id', 'photo'];

        $date = date_format(date_create($date), "Y-m-d");

        $user = Auth::user();
        $eoOnly = $user->eo_only;

        // $attendances = Attendance::with(['employee' => function ($query) use ($employeeColumns) {
        //     $query->select($employeeColumns)->with('designation.department.company');
        // }])->orderBy('created_at', 'DESC')->get();
        $attendances = Employee::select($employeeColumns)->with(
            ['careers' => function ($query) {
                $query->with(['designation', 'department', 'jobTitle'])->orderByDesc('effective_date');
            }, 'attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }]
        )->where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null)
            ->get();

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
            $item['checkin_id'] = null;
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
                            $item['checkin_id'] = $att->id;
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
                            $item['checkin_id'] = $att->id;
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                            $item['checkout_id'] = $att->id;
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
                            $item['checkin_id'] = $att->id;
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                            $item['checkout_id'] = $att->id;
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
                            $item['checkin_id'] = $att->id;
                        } else if ($att->type == 'check out') {
                            $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
                            $item['checkout_id'] = $att->id;
                        }
                    }
                }
            }
        });

        $attendanceSummary = [
            'sick_count' => collect($attendances)->where('status', 'sick')->count(),
            'present_count' => collect($attendances)->where('status', 'present')->count(),
            'permission_count' => collect($attendances)->where('status', 'permission')->count(),
            'leave_count' => collect($attendances)->where('status', 'leave')->count(),
            'rejected_count' => collect($attendances)->where('status', 'rejected')->count(),
            'pending_count' => collect($attendances)->where('status', 'pending')->count(),
        ];

        return [
            'attendances' => $attendances,
            'summary' => $attendanceSummary,
        ];
    }

    private function inject(Collection $items, $key, $value)
    {
        return $items->map(function ($item) use ($key, $value) {
            $item[$key] = $value;
            return $item;
        });
    }

    private function getPendingSubmission()
    {
        $user = Auth::user();
        $eoOnly = $user->eo_only;

        $sickSubmissions = SickSubmission::whereHas('employee', function (Builder $q) use ($eoOnly) {
            $q->where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null);
        })->with(['employee'])->where('status', 'pending')->get()->map(function ($submission) {
            $submission['type'] = 'sakit';
            return $submission;
        });
        $permissionSubmissions = Permission::whereHas('employee', function (Builder $q) use ($eoOnly) {
            $q->where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null);
        })->with(['employee'])->where('status', 'pending')->get()->map(function ($submission) {
            $submission['type'] = 'izin';
            return $submission;
        });;
        $leaveSubmissions = LeaveSubmission::whereHas('employee', function (Builder $q) use ($eoOnly) {
            $q->where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null);
        })->with(['employee'])->where('status', 'pending')->get()->map(function ($submission) {
            $submission['type'] = 'cuti';
            return $submission;
        });;

        $submissions = $sickSubmissions->merge($permissionSubmissions->merge($leaveSubmissions))->sortByDesc('date_of_filing')->values()->all();

        return $submissions;
    }
}
