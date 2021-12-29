<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveSubmission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $whereClause = $request->query();
            // $categories = [];
            // if($type) {
            //     $categories = BudgetCategory::where('type', $type)->get();
            // } else {
            //     $categories = BudgetCategory::all();
            // }
            $leaves = Leave::with(['employee', 'employee.careers' => function ($query) {
                $query->with(['jobTitle', 'designation', 'department'])->where('is_active', 1);
            }])->where($whereClause)->get();
            // $categories = BudgetCategory::where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $leaves,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to get data',
                'error' => true,
                'code' => 400,
                'errors' => $e->getMessage(),
            ], 400);
        }
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
        $employeeId = $request->employee_id;
        $dateOfFiling = $request->date_of_filing;
        $leaveDates = explode(",", $request->full_day_leave_dates);
        // $attachment = $request->attachment;
        $description = $request->description;
        $status = 'pending';

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
                $leave = $employee->leaves->where('is_active', 1)->first();
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

    public function leaveSubmissions(Request $request)
    {
        try {
            $whereClause = $request->query();
            $leaveSubmissions = LeaveSubmission::with(['employee'])->where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $leaveSubmissions,
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

    public function updateLeaveSubmission(Request $request, $id)
    {
        $leaveSubmission = LeaveSubmission::find($id);

        if ($leaveSubmission == null) {
            return response()->json([
                'message' => 'Leave submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        $employeeId = $request->employee_id;
        $dateOfFiling = $request->date_of_filing;
        $leaveDates = explode(",", $request->full_day_leave_dates);
        $oldLeaveDates = explode(",", $request->old_full_day_leave_dates);
        $attachment = $request->attachment;
        $description = $request->description;
        $status = 'pending';

        $attendances = [];
        foreach ($leaveDates as $leaveDate) {
            array_push($attendances, [
                'employee_id' => $employeeId,
                'date' => $leaveDate,
                'clock_in' => date('Y-m-d H:i:s'),
                'clock_in_ip_address' => $request->ip(),
                'status' => $status,
                'type' => 'check in',
                'image' => $attachment,
                'category' => 'leave',
            ]);
        }

        try {
            Attendance::query()
                ->where('category', 'leave')
                // ->where('status', 'pending')
                ->whereIn('date', $oldLeaveDates)
                ->forceDelete();

            // return [
            //     'message' => 'data has been deleted',
            //     'error' => false,
            //     'code' => 200,
            // ];
        } catch (Exception $e) {
            return [
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ];
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
        
        // if ($status == 'approved') {
        //     try {
        //         $leave = $employee->leaves->where('is_active', 1)->first();
        //         $newLeave = Leave::find($leave->id);
        //         // $newLeave->total_leave -= count($leaveDates);
        //         $newLeave->taken_leave += count($leaveDates);
        //         $newLeave->save();
        //     } catch (Exception $e) {
        //         return response()->json([
        //             'message' => 'Internal Error',
        //             'error' => true,
        //             'code' => 500,
        //             'errors' => $e
        //         ], 500);
        //     }
        // }

        try {

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

    public function deleteLeaveSubmission($id)
    {
        $leaveSubmission = LeaveSubmission::find($id);
        $leaveDates = explode(",", $leaveSubmission->leave_dates);

        try {
            Attendance::query()
                ->where('category', 'leave')
                // ->where('status', 'pending')
                ->whereIn('date', $leaveDates)
                ->forceDelete();

            // return [
            //     'message' => 'data has been deleted',
            //     'error' => false,
            //     'code' => 200,
            // ];
        } catch (Exception $e) {
            return [
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ];
        }

        try {

            $leaveSubmission->forceDelete();
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

    public function approve($id)
    {
        $leaveSubmission = LeaveSubmission::find($id);

        if ($leaveSubmission == null) {
            return response()->json([
                'message' => 'Sick submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        $leaveDates = explode(",", $leaveSubmission->leave_dates);
        $status = 'approved';

        try {
            $employee = Employee::find($leaveSubmission->employee_id);
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


        try {
            Attendance::query()
                ->where('category', 'leave')
                // ->where('status', 'pending')
                ->whereIn('date', $leaveDates)
                ->update(['status' => 'approved']);
        } catch (Exception $e) {
            return [
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ];
        }

        try {
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

    public function reject($id)
    {
        $leaveSubmission = LeaveSubmission::find($id);

        if ($leaveSubmission == null) {
            return response()->json([
                'message' => 'Sick submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        $leaveDates = explode(",", $leaveSubmission->leave_dates);
        $status = 'rejected';


        try {
            Attendance::query()
                ->where('category', 'leave')
                // ->where('status', 'pending')
                ->whereIn('date', $leaveDates)
                ->update(['status' => 'rejected']);
        } catch (Exception $e) {
            return [
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ];
        }

        try {
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

    public function showLeaveSubmission($id)
    {
        $leaveSubmission = LeaveSubmission::find($id);

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

        if ($leaveSubmission == null) {
            return response()->json([
                'message' => 'Leave submission not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        $leaveSubmission['remaining_leaves'] = $remainingLeaves;

        try {
            // DB::table('attendances')->insert($attendances);
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $leaveSubmission,
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
}
