<?php

namespace App\Http\Controllers\web;

use App\Exports\AttendancesExport;
use App\Exports\AttendancesByEmployeeExport;
use App\Exports\AttendancesByEmployeeExport2;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\OvertimeSubmission;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = date("Y-m-d");
        $attendances = $this->getAttendance($date)['attendances'];
        $attendanceSummary = $this->getAttendance($date)['summary'];
        $pendingAttendances = $this->getAttendance($date)['pending_attendances'];

        $overtimeSubmissions = OvertimeSubmission::with(['employee'])->where('date', $date)->get();

        // return $attendances;
        return view('attendance.v2.index', [
            'attendances' => $attendances,
            'summary' => $attendanceSummary,
            'pending_attendances' => $pendingAttendances,
            'overtime_submissions' => $overtimeSubmissions,
        ]);
    }

    public function showByDate(Request $request, $date)
    {
        $attendances = $this->getAttendance($date)['attendances'];
        $attendanceSummary = $this->getAttendance($date)['summary'];
        $pendingAttendances = $this->getAttendance($date)['pending_attendances'];



        $overtimeSubmissions = OvertimeSubmission::with(['employee'])->where('date', date('Y-m-d', strtotime($date)))->get();
        // return $attendances;
        return view('attendance.v2.showbydate', [
            'date' => $date,
            'attendances' => $attendances,
            'summary' => $attendanceSummary,
            'pending_attendances' => $pendingAttendances,
            'overtime_submissions' => $overtimeSubmissions,
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
        $attendances = Employee::select($employeeColumns)
            ->where('employee_id', 'LIKE', $eoOnly ? 'EO%' : null)
            ->with(['careers' => function ($query) {
                $query->with(['designation', 'department', 'jobTitle'])->orderByDesc('effective_date');
            }, 'attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }])->where('is_active', 1)->get();

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
            $item['overtime_submission_duration'] = 0;
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
                            $item['overtime_submission_duration'] = $att->overtime_submission_duration;
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

        $pendingAttendances = Attendance::with(['employee' => function ($query) {
            $query->with(['careers.jobTitle', 'careers.designation']);
        }])->where('status', 'pending')->where('date', $date)->get();

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
            'pending_attendances' => $pendingAttendances
        ];
    }

    public function approve(Request $request, $id)
    {

        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return response()->json([
                'message' => 'timesheet not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        if ($attendance->status == 'rejected') {
            return response()->json([
                'message' => 'item has been rejected, you cannot approve this item',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        try {
            $attendance->status = 'approved';
            $attendance->approved_by = $request->approved_by;
            $attendance->approved_at = Carbon::now()->toDateTimeString();
            $attendance->approval_note = $request->approval_note;
            // SET REJECTION DATA TO NULL IF DATA EXIST
            $attendance->rejected_by = null;
            $attendance->rejected_at = null;
            $attendance->rejection_note = null;

            $attendance->save();
            return response()->json([
                'message' => 'item has been approved',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {

        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return response()->json([
                'message' => 'timesheet not found',
                'error' => true,
                'code' => 404,
            ], 404);
        }

        if ($attendance->status == 'approved') {
            return response()->json([
                'message' => 'item has been approved, you cannot reject this item',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        try {
            $attendance->status = 'rejected';
            $attendance->rejected_by = $request->rejected_by;
            $attendance->rejected_at = Carbon::now()->toDateTimeString();
            $attendance->rejection_note = $request->rejection_note;
            // SET REJECTION DATA TO NULL IF DATA EXIST
            $attendance->approved_by = null;
            $attendance->approved_at = null;
            $attendance->approval_note = null;

            $attendance->save();
            return response()->json([
                'message' => 'item has been rejected',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function updateOvertime(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return response()->json([
                'message' => 'timesheet not found',
                'error' => true,
                'code' => 404,
            ], 404);
        }


        try {
            $attendance->overtime_duration = $request->overtime_duration;

            $attendance->save();
            return response()->json([
                'message' => 'overtime has been updated',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function updateOvertimeSubmission(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return response()->json([
                'message' => 'timesheet not found',
                'error' => true,
                'code' => 404,
            ], 404);
        }


        try {
            $attendance->overtime_submission_duration = $request->overtime_submission_duration;

            $attendance->save();
            return response()->json([
                'message' => 'overtime has been updated',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function updateClockIn(Request $request, $id)
    {
        if ($id == "null") {
            $attendance = new Attendance;

            try {
                $attendance->date = $request->date;
                $attendance->clock_in = $request->date . ' ' . $request->clock_in;
                $attendance->employee_id = $request->employee_id;
                $attendance->type = 'check in';
                $attendance->status = 'approved';
                $attendance->category = 'present';
                $attendance->employee_id = $request->employee_id;
                $attendance->save();
                return response()->json([
                    'message' => 'check in has been added',
                    'error' => false,
                    'data' => $attendance,
                    'code' => 200,
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'internal error',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                ], 500);
            }
        }

        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return response()->json([
                'message' => 'timesheet not found',
                'error' => true,
                'code' => 404,
            ], 404);
        }


        try {
            $attendance->clock_in = $request->date . ' ' . $request->clock_in;

            $attendance->save();
            return response()->json([
                'message' => 'clock in has been updated',
                'error' => false,
                'data' => $attendance,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function updateClockOut(Request $request, $id)
    {
        if ($id == "null") {
            $attendance = new Attendance;

            try {
                $attendance->date = $request->date;
                $attendance->clock_out = $request->date . ' ' . $request->clock_out;
                $attendance->employee_id = $request->employee_id;
                $attendance->type = 'check out';
                $attendance->status = 'approved';
                $attendance->category = 'present';
                $attendance->employee_id = $request->employee_id;
                $attendance->save();
                return response()->json([
                    'message' => 'check out has been added',
                    'error' => false,
                    'data' => $attendance,
                    'code' => 200,
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'internal error',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                ], 500);
            }
        }

        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return response()->json([
                'message' => 'timesheet not found',
                'error' => true,
                'code' => 404,
            ], 404);
        }

        try {
            $attendance->clock_out = $request->date . ' ' . $request->clock_out;

            $attendance->save();
            return response()->json([
                'message' => 'clock out has been updated',
                'error' => false,
                'data' => $attendance,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function resetClock(Request $request, $id)
    {
        if ($id == "null") {
            $attendance = new Attendance;

            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => 'Id cannot be null',
            ], 500);
        }

        try {
            $attendance = Attendance::find($id);
            $attendance->delete();
            return response()->json([
                'message' => 'clock in has been updated',
                'error' => false,
                'data' => null,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function sheetAttendanceByEmployee(Request $request)
    {
        $employee_id = $request->query('employee_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        if ($request->query('employee_id') !== null) {
            $employee = Employee::findOrFail($employee_id);
            return Excel::download(new AttendancesByEmployeeExport2($employee_id, $startDate, $endDate), 'Absensi - ' . $employee->employee_id . '-' . $employee->first_name . '.xlsx');
        }

        return abort(404);
    }


    public function sheetAttendanceAll(Request $request)
    {
        // $employee_id = $request->query('employee_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $date1 = $startDate;
        $date2 = $endDate;

        $employees = Employee::all();

        // $period = [];

        foreach ($employees as $employee) {
            $attendances = $this->getAttendance2($date1, $date2, $employee->id)['attendances'];

            $attendancesKeys = collect($this->getAttendance2($date1, $date2, $employee->id)['attendances'])->map(function ($item, $key) {
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
                })->all()
            ];
        }

        // return $period;

        // $period = collect($period)->flatMap(function ($data) {
        //     return $data;
        // });

        // return $period[0]['employee'];

        // return view('attendance.sheet.attendances', [
        //     'period' => $period,
        // ]);


        return Excel::download(new AttendancesExport(null, $startDate, $endDate), 'Absensi - ' . $startDate . '-' . $endDate . '.xlsx');

        // return abort(404);
    }

    public function upload()
    {
        return view('attendance.upload');
    }

    public function uploadFromMachine()
    {
        return view('attendance.upload-from-machine');
    }

    public function uploadFromMachineApp()
    {
        return view('attendance.upload-from-machine-app');
    }

    private function determineCategory($category)
    {
        switch (strtolower($category)) {
            case 'hadir':
                return 'present';
                break;
            case 'izin':
                return 'permission';
                break;
            case 'cuti':
                return 'leave';
                break;
            case 'sakit':
                return 'sick';
                break;
            default:
                return 'present';
        }
    }

    public function doUpload(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $rejectedData = [];
                $rowIndex = 0;
                $importData = Excel::toCollection(collect([]), $request->file('file'));
                // foreach($importData as $data)
                $finalImportData = $importData[0]->filter(function ($data, $key) {
                    return $key !== 0;
                })
                    ->each(function ($data, $key) use (&$rejectedData) {
                        if ($data[0] == null || $data[1] == null || $data[5] == null) {
                            $rejectedData[] = $key;
                        }
                    })->filter(function ($data) {
                        return $data[0] !== null && $data[1] !== null && $data[5] !== null;
                    })
                    ->map(function ($data, $key) use (&$rejectedData, &$rowIndex) {
                        $rowIndex = $key;

                        $clockIn = null;
                        $clockOut = null;

                        $employeeId = $data[0] !== null ? $data[0] : null;
                        $date = $data[1] !== null ? implode('-', array_reverse(explode('.', $data[1]))) : null;
                        // $clockIn = implode(':', explode('.', $date[2])) . ':00';
                        $clockIn = $data[2] !== null ? $date . ' ' . implode(':', explode('.', $data[2])) . ':00' : null;
                        $clockOut = $data[3] !== null ? $date . ' ' . implode(':', explode('.', $data[3])) . ':00' : null;

                        $hourClockIn = null;
                        $hourClockOut = null;

                        if (is_bool($clockIn)) {
                            return [
                                'index' => $rowIndex,
                                'error' => true,
                            ];
                        }

                        if ($clockIn !== null && $clockOut !== null) {
                            $hourClockIn = explode('.', $data[2])[0];
                            $hourClockOut = explode('.', $data[3])[0];
                            // $hourClockOut = date_format(date_create($clockOut), "H");
                            if ((int) $hourClockOut < (int) $hourClockIn) {
                                $tomorrowFromDate = date('Y-m-d', strtotime($date . " +1 day"));
                                $clockOut = $data[3] !== null ? $tomorrowFromDate . ' ' . implode(':', explode('.', $data[3])) . ':00' : null;
                            }
                        }


                        $overtimeDuration = $data[4] !== null ? $data[4] : 0;
                        $category = $data[5] !== null ? $this->determineCategory($data[5]) : null;

                        $record = [];
                        if ($category == 'present') {
                            if ($data[2] !== null) {
                                array_push($record, [
                                    'employee_id' => $employeeId,
                                    'date' => $date,
                                    'clock_in' => $clockIn,
                                    'clock_out' => null,
                                    'category' => $category,
                                    'type' => 'check in',
                                    'overtime_duration' => 0,
                                    'status' => 'approved',
                                ]);
                            }

                            if ($data[3] !== null) {
                                array_push($record, [
                                    'employee_id' => $employeeId,
                                    'date' => $date,
                                    'clock_in' => null,
                                    'clock_out' => $clockOut,
                                    'category' => $category,
                                    'type' => 'check out',
                                    'overtime_duration' => $overtimeDuration,
                                    'status' => 'approved',
                                    // 'hour_clock_in' => $hourClockIn,
                                    // 'hour_clock_out' => $hourClockOut,
                                ]);
                            }
                        } else {
                            array_push($record, [
                                'employee_id' => $employeeId,
                                'date' => $date,
                                'clock_in' => $clockIn,
                                'clock_out' => null,
                                'category' => $category,
                                'type' => 'check in',
                                'overtime_duration' => 0,
                                'status' => 'approved',
                            ]);
                        }

                        return $record;
                    })
                    ->flatMap(function ($data) {
                        return $data;
                    })
                    ->filter(function ($data) {
                        return $data['error'] = true;
                    })
                    ->all();

                Attendance::insert($finalImportData);
                // $flatImportData->all();s

                // ->function;
                // $importData->each(function ($data, $key) {
                //     $UNIX_DATE = ((int) $data[1] - 25569) * 86400;
                //     $date_column = gmdate("Y-m-d", $UNIX_DATE);
                //     $data[1] = $date_column;
                // });
                // $importData = $importData->flatten();

                return response()->json([
                    'message' => 'file received',
                    'error' => false,
                    'code' => 200,
                    'data' => [
                        'accepted' => $finalImportData,
                        'rejected' => $rejectedData,
                        'row_index' => $rowIndex,
                    ],
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error while uploading',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                ], 500);
            }
        }

        return response()->json([
            'message' => 'no file was sent',
            'error' => true,
            'code' => 500,
            // 'errors' => $e,
        ], 500);
    }

    public function doUploadFromMachine(Request $request)
    {
        $importData = Excel::toCollection(collect([]), $request->file('file'));
        if ($request->hasFile('file')) {
            try {
                $rejectedData = [];
                $rowIndex = 0;
                $importData = Excel::toCollection(collect([]), $request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);
                // foreach($importData as $data)
                $finalImportData = $importData[0]->map(function ($data) {
                    $splittedData = explode(' ', $data[0]);
                    // $employeeId = explode('\\', $splittedData[0])[0];
                    // $employeeId = implode(', ', $splittedData);

                    // First cell contains User ID & date
                    $firstCell = preg_split('/\t/', $splittedData[0]);
                    // First cell contains Clock etc.
                    $secondCell = preg_split('/\t/', $splittedData[1]);

                    $userId = $firstCell[0];
                    $date = DateTime::createFromFormat('d/m/Y', $firstCell[1])->format('Y-m-d');
                    $clock = $secondCell[0] . ':00';
                    // return preg_split('/\t/', $splittedData[1]);
                    return [
                        'user_id' => $userId,
                        'date' => $date,
                        'clock' => $clock,
                    ];
                })->all();

                $tes = [];



                foreach ($finalImportData as $data) {
                    $employee_id = $data['user_id'];
                    $date = $data['date'];
                    $clock = $data['date'] . ' ' . $data['clock'];

                    $employeeExist = Employee::find($employee_id);

                    if ($employeeExist == null) {
                        continue;
                    }

                    // date, employee_id, clock

                    $attendance = new Attendance;

                    $hasCheckedIn = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check in')->where('clock_in', '!=', null)->first();

                    $intervalLimit = 30;

                    // return Carbon::parse($hasCheckedIn->clock_in)->diffInMinutes($clock);

                    if ($hasCheckedIn) {
                        $checkDiff = Carbon::parse($hasCheckedIn->clock_in)->diffInMinutes($clock);
                        if ($checkDiff < $intervalLimit) {
                            continue;
                        }

                        $hasCheckedOut = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check out')->where('clock_out', '!=', null)->first();

                        if ($hasCheckedOut) {
                            continue;
                        } else {

                            $employee = Employee::find($employee_id);

                            $workingHours = Carbon::parse($hasCheckedIn->clock_in)->diffInHours($clock);
                            $workingMinutes = Carbon::parse($hasCheckedIn->clock_in)->diffInMinutes($clock);
                            $overtime = 0;
                            $dayStatus = '';
                            $diffWorkingMinutes = 0;

                            if (count($employee->officeShifts) > 0) {
                                // Carbon::parse($employee->office_shifts[0]->)->diffInHours($clockOut);
                                if (date('l', strtotime($date)) == 'Monday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['monday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->monday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Tuesday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['tuesday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->tuesday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Wednesday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['wednesday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->wednesday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Thursday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['thursday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->thursday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Friday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['friday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->friday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Saturday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['saturday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->saturday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Sunday') {
                                    $shiftWorkingHours = $employee->officeShifts[0]->sunday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                }
                            }

                            $overtime = ($overtime > 0) ? $overtime : 0;

                            try {
                                $attendance->employee_id = $employee_id;
                                $attendance->date = $date;
                                $attendance->clock_out = $clock;
                                $attendance->type = "check out";
                                $attendance->category = 'present';
                                $attendance->status = 'approved';
                                $attendance->overtime_duration = $overtime;
                                // $attendance->save();
                                continue;
                            } catch (Exception $e) {
                                continue;
                            }
                        }
                    }

                    try {
                        $attendance->employee_id = $employee_id;
                        $attendance->date = $date;
                        $attendance->clock_in = $clock;
                        $attendance->type = "check in";
                        $attendance->category = 'present';
                        $attendance->status = 'approved';
                        $attendance->save();
                        continue;
                    } catch (Exception $e) {
                        continue;
                    }
                }

                return response()->json([
                    'message' => 'file received',
                    'error' => false,
                    'code' => 200,
                    'data' => [
                        'imported_data' => $importData,
                        'final_imported_data' => $finalImportData,
                        'tes' => $tes,
                    ],
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error while uploading',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                ], 500);
            }
        }

        return response()->json([
            'message' => 'no file was sent',
            'error' => true,
            'code' => 500,
            // 'errors' => $e,
        ], 500);
    }

    public function doUploadFromMachine2(Request $request)
    {
        $importData = Excel::toCollection(collect([]), $request->file('file'));
        if ($request->hasFile('file')) {
            try {
                $rejectedData = [];
                $rowIndex = 0;
                $importData = Excel::toCollection(collect([]), $request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);
                // foreach($importData as $data)
                $finalImportData = $importData[0]->map(function ($data) {
                    $data = str_replace(' ', '', $data[0]);

                    $splittedData = preg_split('/\t/', $data);

                    $userId = $splittedData[0];
                    $dateTime = $splittedData[1];

                    $date = substr($dateTime, 0, 10);
                    $clock = substr($dateTime, 10);

                    return [
                        'user_id' => $userId,
                        'date' => $date,
                        'clock' => $clock,
                    ];
                    // return $splittedData;
                })->all();

                $tes = [];

                foreach ($finalImportData as $data) {
                    $employee_id = $data['user_id'];
                    $date = $data['date'];
                    $clock = $data['date'] . ' ' . $data['clock'];

                    $data['newdata'] = $employee_id;

                    $employeeExist = Employee::find($employee_id);

                    if ($employeeExist == null) {
                        continue;
                    }

                    // date, employee_id, clock

                    $attendance = new Attendance;

                    $hasCheckedIn = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check in')->where('clock_in', '!=', null)->first();

                    $intervalLimit = 30;

                    // return Carbon::parse($hasCheckedIn->clock_in)->diffInMinutes($clock);

                    if ($hasCheckedIn) {
                        $checkDiff = Carbon::parse($hasCheckedIn->clock_in)->diffInMinutes($clock);
                        if ($checkDiff < $intervalLimit) {
                            continue;
                        }

                        $hasCheckedOut = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check out')->where('clock_out', '!=', null)->first();

                        if ($hasCheckedOut) {
                            continue;
                        } else {

                            $employee = Employee::find($employee_id);

                            $workingHours = Carbon::parse($hasCheckedIn->clock_in)->diffInHours($clock);
                            $workingMinutes = Carbon::parse($hasCheckedIn->clock_in)->diffInMinutes($clock);
                            $overtime = 0;
                            $dayStatus = '';
                            $diffWorkingMinutes = 0;

                            if (count($employee->officeShifts) > 0) {
                                // Carbon::parse($employee->office_shifts[0]->)->diffInHours($clockOut);
                                if (date('l', strtotime($date)) == 'Monday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['monday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->monday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Tuesday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['tuesday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->tuesday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Wednesday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['wednesday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->wednesday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Thursday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['thursday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->thursday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Friday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['friday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->friday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Saturday') {
                                    // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['saturday'];
                                    $shiftWorkingHours = $employee->officeShifts[0]->saturday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                } else if (date('l', strtotime($date)) == 'Sunday') {
                                    $shiftWorkingHours = $employee->officeShifts[0]->sunday_working_hours;

                                    $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                    $x = $diffWorkingMinutes % 30;
                                    $y = ($diffWorkingMinutes - $x) / 30;

                                    if ($y > 0) {
                                        $z = ($diffWorkingMinutes - $x) - 30;
                                        $overtime = 1 + floor($z / 60);
                                    }
                                }
                            }

                            $overtime = ($overtime > 0) ? $overtime : 0;

                            try {
                                $attendance->employee_id = $employee_id;
                                $attendance->date = $date;
                                $attendance->clock_out = $clock;
                                $attendance->type = "check out";
                                $attendance->category = 'present';
                                $attendance->status = 'approved';
                                $attendance->overtime_duration = $overtime;
                                $attendance->save();
                                continue;
                            } catch (Exception $e) {
                                array_push($tes, 'Error saving checkout ID ' . $employee_id);
                                continue;
                            }
                        }
                    }

                    try {
                        $attendance->employee_id = $employee_id;
                        $attendance->date = $date;
                        $attendance->clock_in = $clock;
                        $attendance->type = "check in";
                        $attendance->category = 'present';
                        $attendance->status = 'approved';
                        $attendance->save();
                        continue;
                    } catch (Exception $e) {
                        continue;
                    }
                }

                return response()->json([
                    'message' => 'file received',
                    'error' => false,
                    'code' => 200,
                    'data' => [
                        'imported_data' => $importData,
                        'final_imported_data' => $finalImportData,
                        'tes' => $tes,
                    ],
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error while uploading',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                ], 500);
            }
        }

        return response()->json([
            'message' => 'no file was sent',
            'error' => true,
            'code' => 500,
            // 'errors' => $e,
        ], 500);
    }

    public function doUploadFromMachine3(Request $request)
    {
        $importData = Excel::toCollection(collect([]), $request->file('file'));
        if ($request->hasFile('file')) {
            try {
                $rejectedData = [];
                $rowIndex = 0;
                $importData = Excel::toCollection(collect([]), $request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);
                // foreach($importData as $data)
                $finalImportData = $importData[0]->map(function ($data) {
                    $data = str_replace(' ', '', $data[0]);

                    $splittedData = preg_split('/\t/', $data);

                    $userId = $splittedData[0];
                    $dateTime = $splittedData[1];

                    $date = substr($dateTime, 0, 10);
                    $clock = substr($dateTime, 10);

                    return [
                        'user_id' => $userId,
                        'date' => $date,
                        'clock' => $clock,
                    ];
                    // return $splittedData;
                })->all();

                $tes = [];

                foreach ($finalImportData as $data) {
                    $employee_id = $data['user_id'];
                    $date = $data['date'];
                    $clock = $data['date'] . ' ' . $data['clock'];

                    $data['newdata'] = $employee_id;

                    $employeeExist = Employee::find($employee_id);

                    if ($employeeExist == null) {
                        continue;
                    }

                    // date, employee_id, clock

                    $attendance = new Attendance;

                    // 20 Hours Range
                    $HOURS_RANGE = 20 * 60 * 60;

                    // Match date with clock
                    $newClock = $date . ' ' . date_format(date_create($clock), "H:i:s");

                    // return response()->json([
                    //     'data' => $newClock,
                    // ]);

                    // Substract clock with clock range
                    $backClock = strtotime($newClock) - $HOURS_RANGE;

                    $formattedBackClock = date("Y-m-d H:i:s", $backClock);

                    $backCheckIn = Attendance::query()
                        ->where('employee_id', $employee_id)
                        ->whereBetween('clock_in', [$formattedBackClock, $clock])
                        // ->where('category', 'present')
                        // ->whereDate('clock_in', '>=', $backClock)
                        // ->whereDate('clock_in', '<=', $clock)
                        ->get();

                    $backCheckOut = Attendance::query()
                        ->where('employee_id', $employee_id)
                        ->whereBetween('clock_out', [$formattedBackClock, $clock])
                        // ->where('category', 'present')
                        // ->where('clock_out', '>=', $backClock)
                        // ->where('clock_out', '<=', $clock)
                        ->get();

                    $newestAttendance = collect($backCheckIn)->merge($backCheckOut)->each(function ($attendance) {
                        $attendance['global_clock'] = null;
                        if ($attendance['type'] == 'check in') {
                            $attendance['global_clock'] = $attendance->clock_in;
                        } else {
                            $attendance['global_clock'] = $attendance->clock_out;
                        }
                    })->sortByDesc('global_clock')->first();

                    if ($newestAttendance == null) {
                        $morningBottomRange = date('H:i:s', strtotime('06:00:00'));
                        $morningUpperRange = date('H:i:s', strtotime('08:00:00'));

                        $nightBottomRange = date('H:i:s', strtotime('18:00:00'));
                        $nightUpperRange = date('H:i:s', strtotime('20:00:00'));

                        // $nightBottomRange = date('19:00:00');
                        // $nightUpperRange = date('20:00:00');

                        $newCheckClock = $clock;

                        $checkHour = date('H:i:s', strtotime($clock));

                        if ($checkHour > $morningBottomRange && $checkHour <= $morningUpperRange) {
                            $newCheckClock = date('Y-m-d 08:00:00', strtotime($clock));
                        } else if ($checkHour > $nightBottomRange && $checkHour <= $nightUpperRange) {
                            $newCheckClock = date('Y-m-d 20:00:00', strtotime($clock));
                        }

                        try {
                            $attendance->employee_id = $employee_id;
                            $attendance->date = $date;
                            $attendance->clock_in = $newCheckClock;
                            $attendance->type = "check in";
                            $attendance->category = 'present';
                            $attendance->status = 'approved';
                            $attendance->save();
                            continue;
                        } catch (Exception $e) {
                            continue;
                        }
                    }

                    $intervalLimit = 60 * 4;

                    $checkDiff = Carbon::parse($newestAttendance->global_clock)->diffInMinutes($clock);
                    if ($checkDiff < $intervalLimit) {
                        continue;
                    }

                    if ($newestAttendance->type == 'check in') {
                        $employee = Employee::find($employee_id);

                        $workingHours = Carbon::parse($newestAttendance->clock_in)->diffInHours($clock);
                        $workingMinutes = Carbon::parse($newestAttendance->clock_in)->diffInMinutes($clock);
                        $overtime = 0;
                        $dayStatus = '';
                        $diffWorkingMinutes = 0;

                        if (count($employee->officeShifts) > 0) {
                            // Carbon::parse($employee->office_shifts[0]->)->diffInHours($clockOut);
                            if (date('l', strtotime($date)) == 'Monday') {
                                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['monday'];
                                $shiftWorkingHours = $employee->officeShifts[0]->monday_working_hours;
                                $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                $x = $diffWorkingMinutes % 30;
                                $y = ($diffWorkingMinutes - $x) / 30;

                                if ($y > 0) {
                                    $z = ($diffWorkingMinutes - $x) - 30;
                                    $overtime = 1 + floor($z / 60);
                                }
                            } else if (date('l', strtotime($date)) == 'Tuesday') {
                                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['tuesday'];
                                $shiftWorkingHours = $employee->officeShifts[0]->tuesday_working_hours;
                                $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                $x = $diffWorkingMinutes % 30;
                                $y = ($diffWorkingMinutes - $x) / 30;

                                if ($y > 0) {
                                    $z = ($diffWorkingMinutes - $x) - 30;
                                    $overtime = 1 + floor($z / 60);
                                }
                            } else if (date('l', strtotime($date)) == 'Wednesday') {
                                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['wednesday'];
                                $shiftWorkingHours = $employee->officeShifts[0]->wednesday_working_hours;
                                $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                $x = $diffWorkingMinutes % 30;
                                $y = ($diffWorkingMinutes - $x) / 30;

                                if ($y > 0) {
                                    $z = ($diffWorkingMinutes - $x) - 30;
                                    $overtime = 1 + floor($z / 60);
                                }
                            } else if (date('l', strtotime($date)) == 'Thursday') {
                                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['thursday'];
                                $shiftWorkingHours = $employee->officeShifts[0]->thursday_working_hours;
                                $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                $x = $diffWorkingMinutes % 30;
                                $y = ($diffWorkingMinutes - $x) / 30;

                                if ($y > 0) {
                                    $z = ($diffWorkingMinutes - $x) - 30;
                                    $overtime = 1 + floor($z / 60);
                                }
                            } else if (date('l', strtotime($date)) == 'Friday') {
                                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['friday'];
                                $shiftWorkingHours = $employee->officeShifts[0]->friday_working_hours;
                                $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                $x = $diffWorkingMinutes % 30;
                                $y = ($diffWorkingMinutes - $x) / 30;

                                if ($y > 0) {
                                    $z = ($diffWorkingMinutes - $x) - 30;
                                    $overtime = 1 + floor($z / 60);
                                }
                            } else if (date('l', strtotime($date)) == 'Saturday') {
                                // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['saturday'];
                                $shiftWorkingHours = $employee->officeShifts[0]->saturday_working_hours;
                                $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                $x = $diffWorkingMinutes % 30;
                                $y = ($diffWorkingMinutes - $x) / 30;

                                if ($y > 0) {
                                    $z = ($diffWorkingMinutes - $x) - 30;
                                    $overtime = 1 + floor($z / 60);
                                }
                            } else if (date('l', strtotime($date)) == 'Sunday') {
                                $shiftWorkingHours = $employee->officeShifts[0]->sunday_working_hours;
                                $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                                $x = $diffWorkingMinutes % 30;
                                $y = ($diffWorkingMinutes - $x) / 30;

                                if ($y > 0) {
                                    $z = ($diffWorkingMinutes - $x) - 30;
                                    $overtime = 1 + floor($z / 60);
                                }
                            }
                        }

                        $overtime = ($overtime > 0) ? $overtime : 0;

                        if ($newestAttendance->category == 'present') {

                            $intervalLimit = 10;

                            $checkDiff = Carbon::parse($newestAttendance->clock_in)->diffInMinutes($clock);
                            if ($checkDiff < $intervalLimit) {
                                continue;
                            }

                            $checkinDate = date_format(date_create($newestAttendance->clock_in), "Y-m-d");
                            $checkoutDate = date_format(date_create($clock), "Y-m-d");
                            if ($checkoutDate > $checkinDate) {
                                $date = $checkinDate;
                            }

                            try {
                                $attendance->employee_id = $employee_id;
                                $attendance->date = $date;
                                $attendance->clock_out = $clock;
                                $attendance->type = "check out";
                                $attendance->category = 'present';
                                $attendance->status = 'approved';
                                $attendance->overtime_duration = $overtime;
                                $attendance->save();
                                continue;
                            } catch (Exception $e) {
                                continue;
                            }
                        } else {
                            try {
                                $attendance->employee_id = $employee_id;
                                $attendance->date = $date;
                                $attendance->clock_in = $clock;
                                $attendance->type = "check in";
                                $attendance->category = 'present';
                                $attendance->status = 'approved';
                                $attendance->save();
                                continue;
                            } catch (Exception $e) {
                                continue;
                            }
                        }
                    } else if ($newestAttendance->type == 'check out') {

                        $morningBottomRange = date('H:i:s', strtotime('06:00:00'));
                        $morningUpperRange = date('H:i:s', strtotime('08:00:00'));

                        $nightBottomRange = date('H:i:s', strtotime('18:00:00'));
                        $nightUpperRange = date('H:i:s', strtotime('20:00:00'));

                        // $nightBottomRange = date('19:00:00');
                        // $nightUpperRange = date('20:00:00');

                        $newCheckClock = $clock;

                        $checkHour = date('H:i:s', strtotime($clock));

                        if ($checkHour > $morningBottomRange && $checkHour <= $morningUpperRange) {
                            $newCheckClock = date('Y-m-d 08:00:00', strtotime($clock));
                        } else if ($checkHour > $nightBottomRange && $checkHour <= $nightUpperRange) {
                            $newCheckClock = date('Y-m-d 20:00:00', strtotime($clock));
                        }

                        try {
                            $attendance->employee_id = $employee_id;
                            $attendance->date = $date;
                            $attendance->clock_in = $newCheckClock;
                            $attendance->type = "check in";
                            $attendance->category = 'present';
                            $attendance->status = 'approved';
                            $attendance->save();
                            continue;
                        } catch (Exception $e) {
                            continue;
                        }
                    } else {
                        continue;
                    }

                    continue;
                }

                return response()->json([
                    'message' => 'file received',
                    'error' => false,
                    'code' => 200,
                    'data' => [
                        'imported_data' => $importData,
                        'final_imported_data' => $finalImportData,
                        'tes' => $tes,
                    ],
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error while uploading',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                ], 500);
            }
        }

        return response()->json([
            'message' => 'no file was sent',
            'error' => true,
            'code' => 500,
            // 'errors' => $e,
        ], 500);
    }

    public function doUploadFromMachineApp(Request $request)
    {
        $importData = Excel::toCollection(collect([]), $request->file('file'));
        if ($request->hasFile('file')) {
            try {
                $rejectedData = [];
                $rowIndex = 0;
                $importData = Excel::toCollection(collect([]), $request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);

                // foreach($importData as $data)
                $finalImportData = $importData[0]->map(function ($data) {
                    $data = str_replace(' ', '', $data[0]);

                    $splittedData = preg_split('/\t/', $data);

                    $userId = (int) str_replace('"', '', $splittedData[1]);
                    $dateTime = $splittedData[3];

                    // $date = date("Y-d-m", strtotime(substr($dateTime, 0, 10)));
                    $date = '';
                    if ($userId !== 0) {
                        $date = Carbon::createFromFormat('d/m/Y', substr($dateTime, 0, 10))->toDateString();
                    }

                    $clock = substr($dateTime, 10);

                    return [
                        'user_id' => $userId,
                        'date' => $date,
                        'clock' => $clock,
                    ];
                    // return $splittedData;
                })->all();

                // return response()->json([
                //     'data' => $finalImportData,
                // ]);

                $tes = [];

                foreach ($finalImportData as $data) {

                    $employee_id = $data['user_id'];
                    $date = $data['date'];
                    $clock = $data['date'] . ' ' . $data['clock'];

                    $data['newdata'] = $employee_id;

                    $employeeExist = Employee::find($employee_id);

                    if ($employeeExist == null) {
                        continue;
                    }

                    // date, employee_id, clock

                    $attendance = new Attendance;

                    // 20 Hours Range
                    $HOURS_RANGE = 20 * 60 * 60;

                    // Match date with clock
                    $newClock = $date . ' ' . date_format(date_create($clock), "H:i:s");

                    // return response()->json([
                    //     'data' => $newClock,
                    // ]);

                    // Substract clock with clock range
                    $backClock = strtotime($newClock) - $HOURS_RANGE;

                    $formattedBackClock = date("Y-m-d H:i:s", $backClock);

                    $backCheckIn = Attendance::query()
                        ->where('employee_id', $employee_id)
                        ->whereBetween('clock_in', [$formattedBackClock, $clock])
                        // ->where('category', 'present')
                        // ->whereDate('clock_in', '>=', $backClock)
                        // ->whereDate('clock_in', '<=', $clock)
                        ->get();

                    $backCheckOut = Attendance::query()
                        ->where('employee_id', $employee_id)
                        ->whereBetween('clock_out', [$formattedBackClock, $clock])
                        // ->where('category', 'present')
                        // ->where('clock_out', '>=', $backClock)
                        // ->where('clock_out', '<=', $clock)
                        ->get();

                    $newestAttendance = collect($backCheckIn)->merge($backCheckOut)->each(function ($attendance) {
                        $attendance['global_clock'] = null;
                        if ($attendance['type'] == 'check in') {
                            $attendance['global_clock'] = $attendance->clock_in;
                        } else {
                            $attendance['global_clock'] = $attendance->clock_out;
                        }
                    })->sortByDesc('global_clock')->first();

                    if ($newestAttendance == null) {
                        $morningBottomRange = date('H:i:s', strtotime('06:00:00'));
                        $morningUpperRange = date('H:i:s', strtotime('08:00:00'));

                        $nightBottomRange = date('H:i:s', strtotime('06:00:00'));
                        $nightUpperRange = date('H:i:s', strtotime('08:00:00'));

                        // $nightBottomRange = date('19:00:00');
                        // $nightUpperRange = date('20:00:00');

                        $newCheckClock = $clock;

                        $checkHour = date('H:i:s', strtotime($clock));

                        if ($checkHour > $morningBottomRange && $checkHour <= $morningUpperRange) {
                            $newCheckClock = date('Y-m-d 08:00:00', strtotime($clock));
                        } else if ($checkHour > $nightBottomRange && $checkHour <= $nightUpperRange) {
                            $newCheckClock = date('Y-m-d 20:00:00', strtotime($clock));
                        }

                        try {
                            $attendance->employee_id = $employee_id;
                            $attendance->date = $date;
                            $attendance->clock_in = $newCheckClock;
                            $attendance->type = "check in";
                            $attendance->category = 'present';
                            $attendance->status = 'approved';
                            $attendance->save();
                            continue;
                        } catch (Exception $e) {
                            continue;
                        }
                    }

                    $intervalLimit = 60 * 4;

                    $checkDiff = Carbon::parse($newestAttendance->global_clock)->diffInMinutes($clock);
                    if ($checkDiff < $intervalLimit) {
                        continue;
                    }

                    if ($newestAttendance->type == 'check in') {
                        $employee = Employee::find($employee_id);

                        $workingHours = Carbon::parse($newestAttendance->clock_in)->diffInHours($clock);
                        $workingMinutes = Carbon::parse($newestAttendance->clock_in)->diffInMinutes($clock);
                        $overtime = 0;
                        $workAsOvertime = false;
                        $maxWorkAsOvertime = 0;
                        $dayStatus = '';
                        $diffWorkingMinutes = 0;

                        $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                        $employeeShifts = collect($employee->officeShifts)->filter(function ($shift) {
                            return $shift->pivot->is_active == 1;
                        })->values()->all();
                        if (count($employeeShifts) > 0) {
                            // Carbon::parse($employee->office_shifts[0]->)->diffInHours($clockOut);

                            $activeShift = $employeeShifts[0];
                            foreach ($dayNames as $day) {
                                $lowerDay = strtolower($day);
                                if (date('l', strtotime($date)) == $day) {
                                    // $shiftWorkingHours = $employee->officeShifts[0][$lowerDay . '_working_hours'];
                                    // $shiftWorkingMinutes = $shiftWorkingHours * 60;
                                    // $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;
                                    // $x = $diffWorkingMinutes % 30;
                                    // $y = ($diffWorkingMinutes - $x) / 30;
                                    $workAsOvertime = $activeShift[$lowerDay . '_work_as_overtime'] == 1 ? true : false;
                                    $maxWorkAsOvertime = $activeShift[$lowerDay . '_max_overtime'];

                                    // if ($y > 0) {
                                    //     $z = ($diffWorkingMinutes - $x) - 30;
                                    //     $overtime = 1 + floor($z / 60);
                                    // }
                                    // break;
                                    // -------------------
                                    $shiftOutTime = $activeShift[$lowerDay . '_out_time'];
                                    $checkClock = Carbon::parse($clock)->toTimeString();

                                    $diffShiftClock = 0;
                                    if ($checkClock > $shiftOutTime) {
                                        $diffShiftClock = Carbon::parse($checkClock)->diffInMinutes($shiftOutTime);
                                    }
                                    // 90
                                    // array_push($tes, [
                                    //     'shift_out_time' => $shiftOutTime,
                                    //     'clock' => $checkClock,
                                    // ]);
                                    $x = $diffShiftClock % 30; // 0
                                    $y = ($diffShiftClock - $x) / 30; // 3 

                                    if ($y > 0) {
                                        $z = ($diffShiftClock - $x) - 30; // (90 - 0) - 30 = 60
                                        $overtime = 1 + floor($z / 60); // 1 + floor(60 / 60) = 2
                                    }
                                    break;
                                }
                            }


                            // if (date('l', strtotime($date)) == 'Monday') {
                            //     // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['monday'];
                            //     $shiftWorkingHours = $employee->officeShifts[0]->monday_working_hours;
                            //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                            //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                            //     $x = $diffWorkingMinutes % 30;
                            //     $y = ($diffWorkingMinutes - $x) / 30;

                            //     if ($y > 0) {
                            //         $z = ($diffWorkingMinutes - $x) - 30;
                            //         $overtime = 1 + floor($z / 60);
                            //     }
                            // } else if (date('l', strtotime($date)) == 'Tuesday') {
                            //     // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['tuesday'];
                            //     $shiftWorkingHours = $employee->officeShifts[0]->tuesday_working_hours;
                            //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                            //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                            //     $x = $diffWorkingMinutes % 30;
                            //     $y = ($diffWorkingMinutes - $x) / 30;

                            //     if ($y > 0) {
                            //         $z = ($diffWorkingMinutes - $x) - 30;
                            //         $overtime = 1 + floor($z / 60);
                            //     }
                            // } else if (date('l', strtotime($date)) == 'Wednesday') {
                            //     // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['wednesday'];
                            //     $shiftWorkingHours = $employee->officeShifts[0]->wednesday_working_hours;
                            //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                            //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                            //     $x = $diffWorkingMinutes % 30;
                            //     $y = ($diffWorkingMinutes - $x) / 30;

                            //     if ($y > 0) {
                            //         $z = ($diffWorkingMinutes - $x) - 30;
                            //         $overtime = 1 + floor($z / 60);
                            //     }
                            // } else if (date('l', strtotime($date)) == 'Thursday') {
                            //     // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['thursday'];
                            //     $shiftWorkingHours = $employee->officeShifts[0]->thursday_working_hours;
                            //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                            //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                            //     $x = $diffWorkingMinutes % 30;
                            //     $y = ($diffWorkingMinutes - $x) / 30;

                            //     if ($y > 0) {
                            //         $z = ($diffWorkingMinutes - $x) - 30;
                            //         $overtime = 1 + floor($z / 60);
                            //     }
                            // } else if (date('l', strtotime($date)) == 'Friday') {
                            //     // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['friday'];
                            //     $shiftWorkingHours = $employee->officeShifts[0]->friday_working_hours;
                            //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                            //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                            //     $x = $diffWorkingMinutes % 30;
                            //     $y = ($diffWorkingMinutes - $x) / 30;

                            //     if ($y > 0) {
                            //         $z = ($diffWorkingMinutes - $x) - 30;
                            //         $overtime = 1 + floor($z / 60);
                            //     }
                            // } else if (date('l', strtotime($date)) == 'Saturday') {
                            //     // $overtime = $workingHours - $this->getShiftWorkingHours($employee)['saturday'];
                            //     $shiftWorkingHours = $employee->officeShifts[0]->saturday_working_hours;
                            //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                            //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                            //     $x = $diffWorkingMinutes % 30;
                            //     $y = ($diffWorkingMinutes - $x) / 30;

                            //     if ($y > 0) {
                            //         $z = ($diffWorkingMinutes - $x) - 30;
                            //         $overtime = 1 + floor($z / 60);
                            //     }
                            // } else if (date('l', strtotime($date)) == 'Sunday') {
                            //     $shiftWorkingHours = $employee->officeShifts[0]->sunday_working_hours;
                            //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                            //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;

                            //     $x = $diffWorkingMinutes % 30;
                            //     $y = ($diffWorkingMinutes - $x) / 30;

                            //     if ($y > 0) {
                            //         $z = ($diffWorkingMinutes - $x) - 30;
                            //         $overtime = 1 + floor($z / 60);
                            //     }
                            // }
                        }

                        $overtime = ($overtime > 0) ? $overtime : 0;

                        if ($workAsOvertime) {
                            $overtime = $workingHours;
                            if ($overtime > $maxWorkAsOvertime) {
                                $overtime = $maxWorkAsOvertime;
                            }
                        }

                        if ($newestAttendance->category == 'present') {

                            $intervalLimit = 10;

                            $checkDiff = Carbon::parse($newestAttendance->clock_in)->diffInMinutes($clock);
                            if ($checkDiff < $intervalLimit) {
                                continue;
                            }

                            $checkinDate = date_format(date_create($newestAttendance->clock_in), "Y-m-d");
                            $checkoutDate = date_format(date_create($clock), "Y-m-d");
                            if ($checkoutDate > $checkinDate) {
                                $date = $checkinDate;
                            }

                            try {
                                $attendance->employee_id = $employee_id;
                                $attendance->date = $date;
                                $attendance->clock_out = $clock;
                                $attendance->type = "check out";
                                $attendance->category = 'present';
                                $attendance->status = 'approved';
                                // $attendance->approval_note = 'WORKING HOURS: ' . $workingHours . ', AS OVERTIME: ' . ($workAsOvertime ? '1' : '0') . ' SHIFT:';
                                $attendance->overtime_duration = $overtime;
                                $attendance->save();
                                continue;
                            } catch (Exception $e) {
                                continue;
                            }
                        } else {
                            try {
                                $attendance->employee_id = $employee_id;
                                $attendance->date = $date;
                                $attendance->clock_in = $clock;
                                $attendance->type = "check in";
                                $attendance->category = 'present';
                                $attendance->status = 'approved';
                                $attendance->save();
                                continue;
                            } catch (Exception $e) {
                                continue;
                            }
                        }
                    } else if ($newestAttendance->type == 'check out') {

                        $morningBottomRange = date('H:i:s', strtotime('06:00:00'));
                        $morningUpperRange = date('H:i:s', strtotime('08:00:00'));

                        $nightBottomRange = date('H:i:s', strtotime('18:00:00'));
                        $nightUpperRange = date('H:i:s', strtotime('20:00:00'));

                        // $nightBottomRange = date('19:00:00');
                        // $nightUpperRange = date('20:00:00');

                        $newCheckClock = $clock;

                        $checkHour = date('H:i:s', strtotime($clock));

                        if ($checkHour > $morningBottomRange && $checkHour <= $morningUpperRange) {
                            $newCheckClock = date('Y-m-d 08:00:00', strtotime($clock));
                        } else if ($checkHour > $nightBottomRange && $checkHour <= $nightUpperRange) {
                            $newCheckClock = date('Y-m-d 20:00:00', strtotime($clock));
                        }

                        try {
                            $attendance->employee_id = $employee_id;
                            $attendance->date = $date;
                            $attendance->clock_in = $newCheckClock;
                            $attendance->type = "check in";
                            $attendance->category = 'present';
                            $attendance->status = 'approved';
                            $attendance->save();
                            continue;
                        } catch (Exception $e) {
                            continue;
                        }
                    } else {
                        continue;
                    }

                    continue;
                }

                return response()->json([
                    'message' => 'file received',
                    'error' => false,
                    'code' => 200,
                    'data' => [
                        // 'imported_data' => $importData,
                        // 'final_imported_data' => $finalImportData,
                        'tes' => $tes,
                        'message' => 'OK',
                    ],
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Error while uploading',
                    'error' => true,
                    'code' => 500,
                    'tes' => $tes,
                    'errors' => $e,
                ], 500);
            }
        }

        return response()->json([
            'message' => 'no file was sent',
            'error' => true,
            'code' => 500,
            // 'errors' => $e,
        ], 500);
    }

    private function getAttendance2($date1, $date2, $employee_id = null)
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
