<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveSubmission;
use App\Models\Permission;
use App\Models\PermissionCategory;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo'];
        $leaves = Leave::whereHas('employee')->with(['employee' => function ($q) use ($employeeColumns) {
            $q->select($employeeColumns);
        }, 'employee.careers' => function ($query) {
            $query->with(['jobTitle', 'designation', 'department'])->where('is_active', 1);
        }])->where('is_active', 1)->get();
        // return $permissions;

        // return $leaves;
        return view('leave.index', ['leaves' => $leaves]);
    }

    /**
     * Display a listing of the resource v2.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexV2()
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo'];
        // $leaves = Leave::whereHas('employee')->with(['employee' => function ($q) use ($employeeColumns) {
        //     $q->select($employeeColumns);
        // }, 'employee.careers' => function ($query) {
        //     $query->with(['jobTitle', 'designation', 'department'])->where('is_active', 1);
        // }])->where('is_active', 1)->get();

        $employees = Employee::query()->whereHas('activeLeave')->with(['activeLeave', 'leaveSubmissions' => function ($q) {
            $q->where('status', 'approved')->where('leave_dates', 'like', '%' . date("Y") . '%');
        }])->select($employeeColumns)->get()->each(function ($employee) {
            $leaveSubmissionsMonthly = collect($employee->leaveSubmissions)->map(function ($leaveSubmission) {
                $dates = explode(',', $leaveSubmission->leave_dates);
                // return collect($dates)->flatten();
                return collect($dates)->filter(function ($date) {
                    return date($date) >= date("Y-01-01") && date($date) <= date("Y-12-30");
                });
            })->flatten()->groupBy(function ($date) {
                // return Carbon::parse($date)->month();
                $month = (int) explode('-', $date)[1];
                return $month;
            })->map(function ($month, $key) {
                return count($month);
            })->all();
            $employee->leave_monthly = $leaveSubmissionsMonthly;
        });
        // return $permissions;

        return $employees;
        return view('leave.v2.index', ['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
        $categories = PermissionCategory::all();
        return view('leave.create', ['employees' => $employees, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return [
        //     'data' => $request->all(),
        // ];


        $employeeId = $request->employee_id;
        $dateOfFiling = $request->date_of_filing;
        $leaveDates = explode(",", $request->full_day_leave_dates);
        // $attachment = $request->attachment;
        $description = $request->description;
        $status = $request->status;

        $employee = Employee::find($employeeId);

        if ($employee == null) {
            return response()->json([
                'message' => 'Employee not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }


        $attendances = [];
        foreach ($leaveDates as $leaveDate) {
            array_push($attendances, [
                'employee_id' => $employeeId,
                'date' => $leaveDate,
                'clock_in' => date('Y-m-d H:i:s'),
                'clock_in_ip_address' => $request->ip(),
                'status' => $status,
                'type' => 'check in',
                // 'image' => $attachment,
                'category' => 'leave',
            ]);
        }

        // return $attendances;

        try {
            DB::table('attendances')->insert($attendances);
            // return response()->json([
            //     'message' => 'Data has been saved',
            //     'error' => true,
            //     'code'=> 200,
            // ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        if ($status == 'approved') {
            try {
                $leave = $employee->activeLeave;
                $newLeave = Leave::find($leave->id);
                // $newLeave->total_leave -= count($leaveDates);
                $newLeave->taken_leave += count($leaveDates);
                $newLeave->save();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Internal Error',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e
                ], 500);
            }
        }

        try {
            $leaveSubmission = new LeaveSubmission;
            $leaveSubmission->date_of_filing = $dateOfFiling;
            $leaveSubmission->employee_id = $employeeId;
            $leaveSubmission->leave_dates = $request->full_day_leave_dates;
            // $leaveSubmission->attachment = $attachment;
            $leaveSubmission->description = $description;
            $leaveSubmission->status = $status;
            $leaveSubmission->save();
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
        try {
            $leave = Leave::find($id);
            $leave->total_leave = $request->total_leave;
            $leave->taken_leave = $request->taken_leave;
            // $leave->total_carry_forward = $request->total_carry_forward;
            $leave->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
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
        //
    }

    public function submission()
    {
        $leaveSubmissions = LeaveSubmission::with(['employee'])->get();
        // return $permissions;
        return view('leave-submission.index', ['leave_submissions' => $leaveSubmissions]);
    }

    public function editSubmission($id)
    {
        $leaveSubmission = LeaveSubmission::findOrFail($id);

        $employees = Employee::all();
        // $categories = PermissionCategory::all();
        $employeeId = $leaveSubmission->employee_id;
        $employee = Employee::find($employeeId);

        if ($employee == null) {
            abort(500);
        }

        $leave = $employee->leaves->where('is_active', 1)->first();
        $takenLeaveCurrentMonth = $employee->attendances->where('category', 'leave')->where('status', 'approved')->whereBetween('date', [date("Y-m-01"), date("Y-m-t")])->all();

        $remainingLeaves = [
            'total_leave' => $leave->total_leave,
            'taken_leave' => $leave->taken_leave,
            'taken_leave_current_month' => count($takenLeaveCurrentMonth),
        ];

        return view('leave-submission.edit', ['employees' => $employees, 'leave_submission' => $leaveSubmission, 'remaining_leaves' => $remainingLeaves]);
    }
}
