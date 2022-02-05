<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Calendar;
use App\Models\Employee;
use App\Models\LeaveSubmission;
use App\Models\PermissionCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index()
    {
        $calendars = Calendar::all()->sortBy('date')->values();
        return view('setting.calendar.index', [
            'calendars' => $calendars,
        ]);
    }

    public function store(Request $request)
    {

        // FLOW = Calendar => Submissions => Attendance

        $employees = Employee::query()->where('is_active', 1)->get();
        $leaveSubmissions = [];
        $attendances = [];

        $calendarType = $request->type;
        $makeLeaveSubmission = $request->make_leave_submission;

        // return response()->json([
        //     'request' => $request->all(),
        // ]);

        if ($calendarType == 'cuti bersama' && $makeLeaveSubmission) {
            foreach ($employees as $employee) {
                $date = $request->date;
                array_push(
                    $attendances,
                    [
                        'employee_id' => $employee->id,
                        'date' => $date,
                        'clock_in' => date('Y-m-d 08:00:00'),
                        'status' => 'approved',
                        'type' => 'check in',
                        'category' => 'leave',
                        'source' => 'calendar'
                    ]
                );

                array_push($leaveSubmissions, [
                    'date_of_filing' => date("Y-m-d"),
                    'employee_id' => $employee->id,
                    'leave_dates' => $date,
                    'description' => 'Cuti bersama',
                    'status' => 'approved',
                    'source' => 'calendar'
                ]);
            }
        }


        DB::beginTransaction();

        try {
            if (count($attendances) > 0) {
                DB::table('attendances')->insert($attendances);
            }

            if (count($leaveSubmissions) > 0) {
                DB::table('leave_submissions')->insert($leaveSubmissions);
            }

            $calendar = new Calendar;
            $calendar->name = $request->name;
            $calendar->date = $request->date;
            $calendar->type = $request->type;
            $calendar->with_leave_submission = $makeLeaveSubmission ? 1 : 0;
            $calendar->save();

            DB::commit();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $calendar,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $calendar = Calendar::find($id);
        $calendar->name = $request->name;
        $calendar->date = $request->date;
        $calendar->type = $request->type;

        try {
            $calendar->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $calendar,
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

    public function destroy($id)
    {
        $calendar = Calendar::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($calendar->type == 'cuti bersama') {
                $leaveSubmissionsIds = LeaveSubmission::query()
                    ->where('leave_dates', $calendar->date)
                    ->where('source', 'calendar')
                    ->get()
                    ->pluck('id')
                    ->all();

                $attendancesIds = Attendance::query()
                    ->where('date', $calendar->date)
                    ->where('category', 'leave')
                    ->where('source', 'calendar')
                    ->get()
                    ->pluck('id')
                    ->all();

                if (count($leaveSubmissionsIds) > 0) {
                    LeaveSubmission::query()->whereIn('id', $leaveSubmissionsIds)->forceDelete();
                }
                if (count($attendancesIds) > 0) {
                    Attendance::query()->whereIn('id', $attendancesIds)->forceDelete();
                }
            }
            $calendar->delete();

            DB::commit();

            return response()->json(
                [
                    'message' => 'data has been deleted',
                    'error' => false,
                    'code' => 200,
                ]
            );
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'internal error',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                ],
                500
            );
        }
    }
}
