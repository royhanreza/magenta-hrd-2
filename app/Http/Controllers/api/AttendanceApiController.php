<?php

namespace App\Http\Controllers\api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceApiController extends Controller
{

    public function index(Request $request)
    {
        $whereClause = $request->query();
        try {
            $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo'];

            $attendances = Attendance::with([
                'employee' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with(['activeCareer' => function ($query) {
                        $query->with(['designation', 'department', 'jobTitle']);
                    },]);
                },
                'approvedBy' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with(['activeCareer' => function ($query) {
                        $query->with(['designation', 'department', 'jobTitle']);
                    },]);
                },
                'rejectedBy' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with(['activeCareer' => function ($query) {
                        $query->with(['designation', 'department', 'jobTitle']);
                    },]);
                }
            ])->where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $attendances,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to get data',
                'error' => true,
                'code' => 400,
                'errors' => $e->getMessage(),
            ], 400);
        }

        // try {
        //     $attendances = $this->getAttendance()['attendances'];
        //     // $attendanceSummary = $this->getAttendance()['summary'];
        //     // $pendingAttendances = $this->getAttendance()['pending_attendances'];
        //     return response()->json([
        //         'message' => 'OK',
        //         'error' => false,
        //         'code' => 200,
        //         'data' => $attendances,
        //     ]);
        // } catch (Exception $e) {
        //     return response()->json([
        //         'message' => 'Failed to get data',
        //         'error' => true,
        //         'code' => 400,
        //         'errors' => $e->getMessage(),
        //     ], 400);
        // }
    }

    private function getAttendance($date = null)
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id', 'photo'];

        $date = date_format(date_create($date), "Y-m-d");

        // $attendances = Attendance::with(['employee' => function ($query) use ($employeeColumns) {
        //     $query->select($employeeColumns)->with('designation.department.company');
        // }])->orderBy('created_at', 'DESC')->get();
        // $attendances = Employee::select($employeeColumns)->with([
        // 'activeCareer' => function ($query) {
        //     $query->with(['designation', 'department', 'jobTitle']);
        // },
        //     'attendances' => function ($query) {
        //         $query->orderByDesc('date');
        //     }
        // ])->get();

        $attendances = Attendance::with(['employee' => function ($query) use ($employeeColumns) {
            $query->select($employeeColumns)->with(['activeCareer' => function ($query) {
                $query->with(['designation', 'department', 'jobTitle']);
            }]);
        }])->orderBy('created_at', 'DESC')->get();

        // return [
        //     'attendances' => $attendances,
        // ];

        // $date = date('Y-m-d');

        // $attendanceSummary = [
        //     'sick_count' => DB::table('attendances')->where('category', 'sick')->where('date', $date)->where('status', 'approved')->count(),
        //     'present_count' => DB::table('attendances')->where('category', 'present')->where('date', $date)->where('type', 'check in')->where('status', 'approved')->count(),
        //     'permission_count' => DB::table('attendances')->where('category', 'permission')->where('date', $date)->where('status', 'approved')->count(),
        //     'leave_count' => DB::table('attendances')->where('category', 'leave')->where('date', $date)->where('status', 'approved')->count(),
        //     'rejected_count' => DB::table('attendances')->where('date', $date)->where('status', 'rejected')->groupBy('employee_id')->count(),
        //     'pending_count' => DB::table('attendances')->where('date', $date)->where('status', 'pending')->groupBy('employee_id')->count(),
        // ];

        // TODO: Change Looping attendances

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

        // $pendingAttendances = Attendance::with(['employee' => function ($query) {
        //     $query->with(['careers.jobTitle', 'careers.designation']);
        // }])->where('status', 'pending')->where('date', $date)->get();

        return [
            'attendances' => $attendances,
            // 'summary' => $attendanceSummary,
            // 'pending_attendances' => $pendingAttendances
        ];
    }

    public function show($id)
    {
        // $whereClause = $request->query();
        try {
            $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo', 'designation_id'];

            $attendance = Attendance::with([
                'employee' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with('designation');
                },
                'approvedBy' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with('designation');
                },
                'rejectedBy' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with('designation');
                }
            ])->find($id);
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $attendance,
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

    public function checkIn(Request $request)
    {

        $employee_id = $request->employee_id;
        $date = $request->date;

        $attendance = new Attendance;
        $attendance->employee_id = $employee_id;
        $attendance->date = $date;
        $attendance->clock_in = $request->clock_in;
        $attendance->clock_in_ip_address = $request->ip();
        $attendance->clock_in_latitude = $request->clock_in_latitude;
        $attendance->clock_in_longitude = $request->clock_in_longitude;
        $attendance->office_latitude = $request->office_latitude;
        $attendance->office_longitude = $request->office_longitude;
        $attendance->status = $request->status;
        $attendance->type = "check in";
        $attendance->note = $request->note;
        // $attendance->image = $request->image;
        $attendance->category = $request->category;

        $hasCheckedIn = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check in')->where('clock_in', '!=', null)->first();

        if ($hasCheckedIn) {
            return response()->json([
                'message' => 'Employee already checked in at ' . $hasCheckedIn->clock_in,
                'error' => true,
                'code' => 400,
                // 'errors' => $e
            ], 400);
        } else {
            // Save Image
            if ($request->image !== null || $request->image !== "") {
                try {
                    $file = base64_decode($request->image);
                    // $folderName = 'public/images/';
                    $safeName = $employee_id . '_checkin_' . time() . '.' . 'png';
                    // $destinationPath = public_path() . $folderName;
                    // $success = file_put_contents(public_path() . '/images/' . $safeName, $file);

                    // $attendance->image = $safeName;

                    // $photo = $request->file('photo');
                    $photoPath = 'attendances/' . time() . '-' . $safeName;
                    Storage::disk('s3')->put($photoPath, $file, 'public');
                    // $employee->photo = $photoPath;
                    $attendance->image = $photoPath;
                } catch (Exception $e) {
                    return response()->json([
                        'message' => '[Internal Error] Error while upload image',
                        'error' => true,
                        'code' => 500,
                        'errors' => $e->getMessage(),
                    ], 500);
                }
            }
            // return response()->json([
            //     'status' => 'OK',
            //     'message' => $success,
            // ]);

            try {
                $attendance->save();
                // return response()->json([
                //     'message' => 'Data has been saved',
                //     'error' => false,
                //     // 'data' => $request->all(),
                //     'code' => 200,
                // ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => '[Internal Error] Error while saving data',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e
                ], 500);
            }

            try {
                $employee = Employee::with(['superior'])->find($employee_id);
                if ($employee !== null && $employee->superior->fcm_registration_token !== null) {
                    $notification = Helper::sendNotification(
                        $employee->superior->fcm_registration_token,
                        'Magenta HRD',
                        $employee->first_name . ' ' . $employee->last_name . '\'s request requires approval',
                        null,
                        [
                            'id' => $attendance->id,
                            'screen' => $request->screen
                        ]
                    );
                    Log::debug($notification);
                }
            } catch (Exception $e) {
                return response()->json([
                    'message' => '[Internal Error] Error while sending notification',
                    'error' => true,
                    'code' => 200,
                    'errors' => $e
                ], 200);
            }

            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                // 'data' => $request->all(),
                'code' => 200,
            ]);
        }
    }

    public function checkOut(Request $request)
    {
        $employee_id = $request->employee_id;
        $date = $request->date;

        $hasCheckedIn = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check in')->where('clock_in', '!=', null)->first();
        // $attendance->employee_id = $request->employee_id;
        // $attendance->date = $request->date;
        // $attendance->clock_out = $request->clock_out;
        if ($hasCheckedIn) {
            $hasCheckedOut = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check out')->where('clock_out', '!=', null)->first();

            if ($hasCheckedOut) {
                return response()->json([
                    'message' => 'Employee already checked out at' . $hasCheckedOut->clock_out,
                    'error' => true,
                    'code' => 400,
                    // 'errors' => $e
                ], 400);
            } else {

                // $attendanceId = $hasCheckedIn->id;

                // $attendance = Attendance::find($attendanceId);
                $attendance = new Attendance;
                $attendance->employee_id = $employee_id;
                $attendance->date = $date;
                $attendance->clock_out = $request->clock_out;
                $attendance->clock_out_ip_address = $request->ip();
                $attendance->clock_out_latitude = $request->clock_out_latitude;
                $attendance->clock_out_longitude = $request->clock_out_longitude;
                $attendance->office_latitude = $request->office_latitude;
                $attendance->office_longitude = $request->office_longitude;
                $attendance->status = $request->status;
                $attendance->type = "check out";
                $attendance->note = $request->note;
                // $attendance->image = $request->image;
                $attendance->category = $request->category;

                // Save Image
                if ($request->image !== null || $request->image !== "") {
                    try {
                        $file = base64_decode($request->image);
                        // $folderName = 'public/images/';
                        $safeName = $employee_id . '_checkout_' . time() . '.' . 'png';
                        // $destinationPath = public_path() . $folderName;
                        // $success = file_put_contents(public_path() . '/images/' . $safeName, $file);
                        $photoPath = 'attendances/' . time() . '-' . $safeName;
                        Storage::disk('s3')->put($photoPath, $file, 'public');
                        // $employee->photo = $photoPath;
                        $attendance->image = $photoPath;

                        // $attendance->image = $safeName;
                    } catch (Exception $e) {
                        return response()->json([
                            'message' => '[Internal Error] Error while upload image',
                            'error' => true,
                            'code' => 500,
                            'errors' => $e
                        ], 500);
                    }
                }

                try {
                    $attendance->save();

                    // return response()->json([
                    //     'message' => 'Data has been saved',
                    //     'error' => false,
                    //     // 'data' => $request->all(),
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

                try {
                    $employee = Employee::with(['superior'])->find($employee_id);
                    if ($employee !== null && $employee->superior->fcm_registration_token !== null) {
                        $notification = Helper::sendNotification(
                            $employee->superior->fcm_registration_token,
                            'Magenta HRD',
                            $employee->first_name . ' ' . $employee->last_name . '\'s request requires approval',
                            null,
                            [
                                'id' => $attendance->id,
                                'screen' => $request->screen
                            ]
                        );
                        Log::debug($notification);
                    }
                } catch (Exception $e) {
                    return response()->json([
                        'message' => '[Internal Error] Error while sending notification',
                        'error' => true,
                        'code' => 200,
                        'errors' => $e
                    ], 200);
                }

                return response()->json([
                    'message' => 'Data has been saved',
                    'error' => false,
                    // 'data' => $request->all(),
                    'code' => 200,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Employee is not check in yet',
                'error' => true,
                'code' => 400,
                // 'errors' => $e
            ], 400);
        }
    }

    public function approve(Request $request, $id)
    {

        $attendance = Attendance::with(['employee'])->find($id);
        $employee = $attendance->employee;

        // return $attendance;

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

            if ($employee->fcm_registration_token !== null) {
                $notification = Helper::sendNotification(
                    $employee->fcm_registration_token,
                    'Magenta HRD',
                    'Your attendance request has been approved',
                    null,
                    [
                        'id' => $attendance->id,
                        'screen' => $request->screen
                    ]
                );
                Log::debug($notification);
            }

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

        $attendance = Attendance::with(['employee'])->find($id);
        $employee = $attendance->employee;

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

            if ($employee->fcm_registration_token !== null) {
                $notification = Helper::sendNotification(
                    $employee->fcm_registration_token,
                    'Magenta HRD',
                    'Your attendance request has been rejected',
                    null,
                    [
                        'id' => $attendance->id,
                        'screen' => $request->screen
                    ]
                );
                Log::debug($notification);
            }

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

    public function hardware(Request $request)
    {
        $employee_id = $request->employee_id;
        $date = $request->date;
        $clock = $request->clock;

        $employeeExist = Employee::find($employee_id);

        if ($employeeExist == null) {
            return response()->json([
                'message' => '[Error] Employee not found',
                'error' => true,
                'code' => 400,
                'deletable' => 0,
                // 'errors' => $e
            ], 400);
        }

        // date, employee_id, clock

        $attendance = new Attendance;
        // $attendance->employee_id = $employee_id;
        // $attendance->date = $date;
        // $attendance->clock_in = $request->clock_in;
        // $attendance->clock_in_ip_address = $request->ip();
        // $attendance->clock_in_latitude = $request->clock_in_latitude;
        // $attendance->clock_in_longitude = $request->clock_in_longitude;
        // $attendance->office_latitude = $request->office_latitude;
        // $attendance->office_longitude = $request->office_longitude;
        // $attendance->status = $request->status;
        // $attendance->type = "check in";
        // $attendance->note = $request->note;
        // // $attendance->image = $request->image;
        // $attendance->category = $request->category;

        $hasCheckedIn = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check in')->where('clock_in', '!=', null)->first();

        $intervalLimit = 10;

        // return Carbon::parse($hasCheckedIn->clock_in)->diffInMinutes($clock);

        if ($hasCheckedIn) {
            $checkDiff = Carbon::parse($hasCheckedIn->clock_in)->diffInMinutes($clock);
            if ($checkDiff < $intervalLimit) {
                return response()->json([
                    'message' => '[Error] Check-in interval less than ' . $intervalLimit . ' minutes',
                    'deletable' => 1,
                    'error' => true,
                    'code' => 400,
                    // 'errors' => $e
                ], 400);
            }

            $hasCheckedOut = Attendance::where('employee_id', $employee_id)->where('date', $date)->where('type', 'check out')->where('clock_out', '!=', null)->first();

            if ($hasCheckedOut) {
                return response()->json([
                    'message' => 'Employee already checked out at' . $hasCheckedOut->clock_out,
                    'error' => true,
                    'code' => 400,
                    'deletable' => 1,
                    // 'errors' => $e
                ], 400);
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
                        // $overtime = $workingHours - $shiftWorkingHours;
                        // $dayStatus = $employee->officeShifts[0]->monday_status;
                        // if ($overtime == 0) {
                        //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                        //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;
                        //     if ($diffWorkingMinutes >= 30) {
                        //         $overtime = 1;
                        //     }
                        // }
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
                        // $overtime = $workingHours - $shiftWorkingHours;
                        // $dayStatus = $employee->officeShifts[0]->tuesday_status;
                        // if ($overtime == 0) {
                        //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                        //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;
                        //     if ($diffWorkingMinutes >= 30) {
                        //         $overtime = 1;
                        //     }
                        // }
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
                        // $overtime = $workingHours - $shiftWorkingHours;
                        // $dayStatus = $employee->officeShifts[0]->wednesday_status;
                        // if ($overtime == 0) {
                        //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                        //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;
                        //     if ($diffWorkingMinutes >= 30) {
                        //         $overtime = 1;
                        //     }
                        // }
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
                        // $overtime = $workingHours - $shiftWorkingHours;
                        // $dayStatus = $employee->officeShifts[0]->thursday_status;
                        // if ($overtime == 0) {
                        //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                        //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;
                        //     if ($diffWorkingMinutes >= 30) {
                        //         $overtime = 1;
                        //     }
                        // }
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
                        // $overtime = $workingHours - $shiftWorkingHours;
                        // $dayStatus = $employee->officeShifts[0]->friday_status;
                        // if ($overtime == 0) {
                        //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                        //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;
                        //     if ($diffWorkingMinutes >= 30) {
                        //         $overtime = 1;
                        //     }
                        // }
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
                        // $overtime = $workingHours - $shiftWorkingHours;
                        // $dayStatus = $employee->officeShifts[0]->saturday_status;
                        // if ($overtime == 0) {
                        //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                        //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;
                        //     if ($diffWorkingMinutes >= 30) {
                        //         $overtime = 1;
                        //     }
                        // }
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
                        // $overtime = $workingHours - $shiftWorkingHours;
                        // $dayStatus = $employee->officeShifts[0]->sunday_status;
                        // if ($overtime == 0) {
                        //     $shiftWorkingMinutes = $shiftWorkingHours * 60;
                        //     $diffWorkingMinutes = $workingMinutes - $shiftWorkingMinutes;
                        //     if ($diffWorkingMinutes >= 30) {
                        //         $overtime = 1;
                        //     }
                        // }
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
                    $attendance->clock_out = $request->clock;
                    $attendance->type = "check out";
                    $attendance->category = 'present';
                    $attendance->status = 'approved';
                    $attendance->overtime_duration = $overtime;
                    $attendance->save();
                    return response()->json([
                        'message' => 'Data has been saved',
                        'error' => false,
                        // 'data' => $request->all(),
                        'working_minutes' => $workingMinutes,
                        'diff_working_minutes' => $diffWorkingMinutes,
                        'overtime' => $overtime,
                        'code' => 200,
                        'deletable' => 0,
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'message' => '[Internal Error] Error while saving data',
                        'error' => true,
                        'code' => 500,
                        'errors' => $e,
                        'deletable' => 0,
                    ], 500);
                }
            }
        }

        try {
            $attendance->employee_id = $employee_id;
            $attendance->date = $date;
            $attendance->clock_in = $request->clock;
            $attendance->type = "check in";
            $attendance->category = 'present';
            $attendance->status = 'approved';
            $attendance->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                // 'data' => $request->all(),
                'code' => 200,
                'deletable' => 0,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => '[Internal Error] Error while saving data',
                'error' => true,
                'code' => 500,
                'errors' => $e,
                'deletable' => 0,
            ], 500);
        }
    }

    public function hardwareExperiment(Request $request)
    {
        $employee_id = $request->employee_id;
        $date = $request->date;
        $clock = $request->clock;

        $employeeExist = Employee::find($employee_id);

        if ($employeeExist == null) {
            return response()->json([
                'message' => '[Error] Employee not found',
                'error' => true,
                'code' => 400,
                'deletable' => 0,
                // 'errors' => $e
            ], 400);
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
            try {
                $attendance->employee_id = $employee_id;
                $attendance->date = $date;
                $attendance->clock_in = $request->clock;
                $attendance->type = "check in";
                $attendance->category = 'present';
                $attendance->status = 'approved';
                $attendance->save();
                return response()->json([
                    'message' => 'Data has been saved',
                    'error' => false,
                    // 'data' => $request->all(),
                    'code' => 200,
                    'deletable' => 0,
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => '[Internal Error] Error while saving data',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                    'deletable' => 0,
                ], 500);
            }
        }

        $intervalLimit = 60 * 4;

        $checkDiff = Carbon::parse($newestAttendance->global_clock)->diffInMinutes($clock);
        if ($checkDiff < $intervalLimit) {
            return response()->json([
                'message' => '[Error] Check-in interval less than ' . $intervalLimit . ' minutes',
                'deletable' => 1,
                'error' => true,
                'code' => 400,
                // 'errors' => $e
            ], 400);
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
                    return response()->json([
                        'message' => '[Error] Check-in interval less than ' . $intervalLimit . ' minutes',
                        'deletable' => 1,
                        'error' => true,
                        'code' => 400,
                        // 'errors' => $e
                    ], 400);
                }

                $checkinDate = date_format(date_create($newestAttendance->clock_in), "Y-m-d");
                $checkoutDate = date_format(date_create($clock), "Y-m-d");
                if ($checkoutDate > $checkinDate) {
                    $date = $checkinDate;
                }

                try {
                    $attendance->employee_id = $employee_id;
                    $attendance->date = $date;
                    $attendance->clock_out = $request->clock;
                    $attendance->type = "check out";
                    $attendance->category = 'present';
                    $attendance->status = 'approved';
                    $attendance->overtime_duration = $overtime;
                    $attendance->save();
                    return response()->json([
                        'message' => 'Data has been saved',
                        'error' => false,
                        // 'data' => $request->all(),
                        'working_minutes' => $workingMinutes,
                        'diff_working_minutes' => $diffWorkingMinutes,
                        'overtime' => $overtime,
                        'x' => $x,
                        'y' => $y,
                        // 'z' => $z,
                        'code' => 200,
                        'deletable' => 0,
                        'type' => 'checkout',
                        'data' => $attendance,
                        'newest_attendance' => $newestAttendance,
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'message' => '[Internal Error] Error while saving data',
                        'error' => true,
                        'code' => 500,
                        'errors' => $e,
                        'deletable' => 0,
                    ], 500);
                }
            } else {
                try {
                    $attendance->employee_id = $employee_id;
                    $attendance->date = $date;
                    $attendance->clock_in = $request->clock;
                    $attendance->type = "check in";
                    $attendance->category = 'present';
                    $attendance->status = 'approved';
                    $attendance->save();
                    return response()->json([
                        'message' => 'Data has been saved',
                        'error' => false,
                        // 'data' => $request->all(),
                        'code' => 200,
                        'deletable' => 0,
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'message' => '[Internal Error] Error while saving data',
                        'error' => true,
                        'code' => 500,
                        'errors' => $e,
                        'deletable' => 0,
                    ], 500);
                }
            }
        } else if ($newestAttendance->type == 'check out') {
            try {
                $attendance->employee_id = $employee_id;
                $attendance->date = $date;
                $attendance->clock_in = $request->clock;
                $attendance->type = "check in";
                $attendance->category = 'present';
                $attendance->status = 'approved';
                $attendance->save();
                return response()->json([
                    'message' => 'Data has been saved',
                    'error' => false,
                    // 'data' => $request->all(),
                    'code' => 200,
                    'deletable' => 0,
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => '[Internal Error] Error while saving data',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e,
                    'deletable' => 0,
                ], 500);
            }
        } else {
            return response()->json([
                'message' => '[Internal Error] Error while saving data',
                'error' => true,
                'code' => 500,
                'erros' => 'type unknown',
                'deletable' => 0,
            ], 500);
        }

        return response()->json([
            'message' => '[Internal Error] Error Occured',
            'error' => true,
            'code' => 500,
            'deletable' => 0,
        ], 500);
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

    public function getShiftWorkingMinutes($employee)
    {
        $mondayWorkingHours = Carbon::parse($employee->officeShifts[0]->monday_in_time)->diffInMinutes($employee->officeShifts[0]->monday_out_time);
        $tuesdayWorkingHours = Carbon::parse($employee->officeShifts[0]->tuesday_in_time)->diffInMinutes($employee->officeShifts[0]->tuesday_out_time);
        $wednesdayWorkingHours = Carbon::parse($employee->officeShifts[0]->wednesday_in_time)->diffInMinutes($employee->officeShifts[0]->wednesday_out_time);
        $thursdayWorkingHours = Carbon::parse($employee->officeShifts[0]->thursday_in_time)->diffInMinutes($employee->officeShifts[0]->thursday_out_time);
        $fridayWorkingHours = Carbon::parse($employee->officeShifts[0]->friday_in_time)->diffInMinutes($employee->officeShifts[0]->friday_out_time);
        $saturdayWorkingHours = Carbon::parse($employee->officeShifts[0]->saturday_in_time)->diffInMinutes($employee->officeShifts[0]->saturday_out_time);
        $sundayWorkingHours = Carbon::parse($employee->officeShifts[0]->sunday_in_time)->diffInMinutes($employee->officeShifts[0]->sunday_out_time);

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

    public function updateOvertime(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return response()->json([
                'message' => 'timesheet not found',
                'error' => true,
                'code' => 404,
            ], 400);
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

    public function updateOvertimeNote(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (is_null($attendance)) {
            return response()->json([
                'message' => 'timesheet not found',
                'error' => true,
                'code' => 404,
            ], 400);
        }

        try {
            $attendance->overtime_note = $request->overtime_note;

            $attendance->save();
            return response()->json([
                'message' => 'overtime note has been updated',
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
}
