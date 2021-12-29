<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Event;
use App\Models\FinalPayslip;
use App\Models\Freelancer;
use App\Models\Leave;
use App\Models\LeaveSubmission;
use App\Models\Permission;
use App\Models\SickSubmission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeApiController extends Controller
{
    // GET ALL EMPLOYEES
    public function index(Request $request)
    {
        $employees = [];
        $whereClause = $request->query();
        try {
            $employees = Employee::with(['activeCareer' => function ($query) {
                $query->with(['designation', 'department', 'jobTitle']);
            }, 'npwp', 'bpjs'])->where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $employees,
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

    // GET SPECIFIC EMPLOYEE
    public function show($id)
    {
        try {
            $employee = Employee::with(['activeCareer' => function ($query) {
                $query->with(['designation', 'department', 'jobTitle']);
            }, 'npwp', 'bpjs', 'location'])->find($id);
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $employee,
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

    // GET EVENTS BY EMPLOYEE
    public function getAllEvents(Request $request, $id)
    {
        $whereClause = $request->query();
        try {
            $events = Event::with('members.employee', 'city.province')->whereHas('members', function ($q) use ($id) {
                $q->where('employee_id', $id);
            })->where($whereClause)->get()->each(function ($event, $key) {

                // GET TASK PROGRESS 
                if (count($event->tasks) > 0) {
                    $tasks = collect($event->tasks);
                    $taskCompleted = $tasks->sum(function ($task) {
                        return ($task->status == 'completed') ? 1 : 0;
                    });
                    $event['progress'] = round(($taskCompleted / count($tasks)) * 100);
                } else {
                    $event['progress'] = 0;
                }

                // GET TOTAL INCOME, EXPENSE, AND BALANCE
                $balance = 0;
                $totalIncome = 0;
                $totalExpense = 0;

                if (count($event->budgets) > 0) {
                    $totalIncome = $event->budgets->sum(function ($budget) {
                        return ($budget->type == 'income') ? $budget->amount : 0;
                    });
                    $totalExpense = $event->budgets->sum(function ($budget) {
                        return ($budget->type == 'expense') ? $budget->amount : 0;
                    });
                    $balance = $totalIncome - $totalExpense;
                }

                $event['budget_summary'] = [
                    'total_income' => $totalIncome,
                    'total_expense' => $totalExpense,
                    'balance' => $balance,
                ];
            });;

            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $events,
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

    // GET COMPANY BY EMPLOYEE
    public function getCompany($id)
    {
        try {
            $employee = Employee::find($id);
            $company = $employee->designation->department->company;
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $company,
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

    // GET ALL ATTENDANCES BY EMPLOYEE
    public function getAllAttendances(Request $request, $id)
    {
        // $whereClause = $request->query();
        // try {
        //     $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo', 'designation_id'];

        //     $attendances = Attendance::with([
        //         'approvedBy' => function ($query) use ($employeeColumns) {
        //             $query->select($employeeColumns)->with('designation');
        //         },
        //         'rejectedBy' => function ($query) use ($employeeColumns) {
        //             $query->select($employeeColumns)->with('designation');
        //         },
        //         'employee' => function ($query) use ($employeeColumns) {
        //             $query->select($employeeColumns)->with('designation');
        //         }
        //     ])->where('employee_id', $id)->where($whereClause)->get();
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
        $whereClause = $request->query();
        $status = $request->query('status');
        try {
            $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo', 'designation_id'];

            $attendances = $this->getAttendance(null, $id, $status)['attendances'];
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
    }

    // PRIVATE METHOD: MAPPING ATTENDANCES
    private function getAttendance($date = null, $employee_id, $status = '')
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name',  'photo'];

        $date = date_format(date_create($date), "Y-m-d");

        $attendances = Attendance::with(['approvedBy' => function ($q) use ($employeeColumns) {
            $q->select($employeeColumns);
        }, 'rejectedBy' => function ($q) use ($employeeColumns) {
            $q->select($employeeColumns);
        }])->where('employee_id', $employee_id)->orderBy('date', 'DESC')->get()->groupBy('date')->map(function ($group, $date) {

            // return $attendance;
            // return $attendance->id;\
            // $date = $group->date;
            $checkInStatus = null;
            $checkOutStatus = null;
            // $pendingCategory = null;
            $clockIn = null;
            $clockOut = null;
            $overtimeDuration = 0;
            $checkInId = null;
            $checkoutId = null;
            $checkInLatitude = null;
            $checkInLongitude = null;
            $checkOutLatitude = null;
            $checkOutLongitude = null;

            $checkInCategory = null;
            $checkOutCategory = null;

            $checkInApprovedBy = null;
            $checkInApprovedAt = null;
            $checkInRejectedBy = null;
            $checkInRejectedAt = null;
            $checkInApprovalNote = null;
            $checkInRejectionNote = null;
            $checkInOfficeLatitude = null;
            $checkInOfficeLongitude = null;
            $checkInImage = null;

            $checkOutApprovedBy = null;
            $checkOutApprovedAt = null;
            $checkOutRejectedBy = null;
            $checkOutRejectedAt = null;
            $checkOutApprovalNote = null;
            $checkOutRejectionNote = null;
            $checkOutOfficeLatitude = null;
            $checkOutOfficeLongitude = null;
            $checkOutImage = null;
            // $note = null;
            $images = [];


            foreach ($group as $attendance) {
                // $note = $attendance->note;
                // if ($attendance->image || isset($attendance->image)) {
                //     $images = array_merge($images, [Storage::disk('s3')->url($attendance->image)]);
                // }
                $status = null;
                $category = null;

                if ($attendance->status == 'approved') {
                    $status = 'approved';
                } else if ($attendance->status == 'pending') {
                    $status = 'pending';
                } else if ($attendance->status == 'rejected') {
                    $status = 'rejected';
                }

                if ($attendance->category == 'present') {
                    $category = 'present';
                } else if ($attendance->category == 'sick') {
                    $category = 'sick';
                } else if ($attendance->category == 'permission') {
                    $category = 'permission';
                } else if ($attendance->category == 'leave') {
                    $category = 'leave';
                }
                $photos=$attendance->photo;

                if ($attendance->type == 'check in') {
                    $checkInStatus = $status;
                    $checkInCategory = $category;

                    $clockIn = date_format(date_create($attendance->clock_in), "H:i:s");
                    $checkInId = $attendance->id;
                    $checkInLatitude = $attendance->clock_in_latitude;
                    $checkInLongitude = $attendance->clock_in_longitude;

                    $checkInApprovedBy = $attendance->approvedBy;
                    $checkInApprovedAt = $attendance->approved_at;
                    $checkInRejectedBy = $attendance->rejectedBy;
                    $checkInRejectedAt = $attendance->rejected_at;
                    $checkInApprovalNote = $attendance->approval_note;
                    $checkInRejectionNote = $attendance->rejection_note;
                    $checkInOfficeLatitude = $attendance->office_latitude;
                    $checkInOfficeLongitude = $attendance->office_longitude;
                    // if($attendance->image !== null) {
                        $checkInImage = $attendance->image;
                    // }
                } else if ($attendance->type == 'check out') {
                    $checkOutStatus = $status;
                    $checkOutCategory = $category;

                    $clockOut = date_format(date_create($attendance->clock_out), "H:i:s");
                    $overtimeDuration = $attendance->overtime_duration;
                    $checkoutId = $attendance->id;
                    $checkOutLatitude = $attendance->clock_out_latitude;
                    $checkOutLongitude = $attendance->clock_out_longitude;

                    $checkOutApprovedBy = $attendance->approvedBy;
                    $checkOutApprovedAt = $attendance->approved_at;
                    $checkOutRejectedBy = $attendance->rejectedBy;
                    $checkOutRejectedAt = $attendance->rejected_at;
                    $checkOutApprovalNote = $attendance->approval_note;
                    $checkOutRejectionNote = $attendance->rejection_note;
                    $checkOutOfficeLatitude = $attendance->office_latitude;
                    $checkOutOfficeLongitude = $attendance->office_longitude;
                    // if($attendance->image !== null) {
                        $checkOutImage = $attendance->image;
                    // }
                    
                }

            }

            return [
             
                'date' => $date,
                'checkin_category' => $checkInCategory,
                'checkout_category' => $checkOutCategory,
                'checkin_status' => $checkInStatus,
                'checkout_status' => $checkOutStatus,
                // 'pending_category' => $pendingCategory,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'overtime_duration' => $overtimeDuration,
                'checkin_id' => $checkInId,
                'checkout_id' => $checkoutId,
                'checkin_latitude' => $checkInLatitude,
                'checkin_longitude' => $checkInLongitude,
                'checkout_latitude' => $checkOutLatitude,
                'checkout_longitude' => $checkOutLongitude,

                'checkin_approved_by' => $checkInApprovedBy,
                'checkin_approved_at' => $checkInApprovedAt,
                'checkin_rejected_by' => $checkInRejectedBy,
                'checkin_rejected_at' => $checkInRejectedAt,
                'checkin_approval_note' => $checkInApprovalNote,
                'checkin_rejection_note' => $checkInRejectionNote,
                'checkin_office_latitude' => $checkInOfficeLatitude,
                'checkin_office_longitude' => $checkInOfficeLongitude,
                'checkin_image' => $checkInImage,

                'checkout_approved_by' => $checkOutApprovedBy,
                'checkout_approved_at' => $checkOutApprovedAt,
                'checkout_rejected_by' => $checkOutRejectedBy,
                'checkout_rejected_at' => $checkOutRejectedAt,
                'checkout_approval_note' => $checkOutApprovalNote,
                'checkout_rejection_note' => $checkOutRejectionNote,
                'checkout_office_latitude' => $checkOutOfficeLatitude,
                'checkout_office_longitude' => $checkOutOfficeLongitude,
                'checkout_image' => $checkOutImage,

                // 'images' => $images,
                // 'note' => $note,
            ];
        })->filter(function ($attendance) use ($status) {
            if (!empty($status)) {
                return $attendance['checkin_status'] == $status || $attendance['checkout_status'] == $status;
            }
            return true;
        })->values()->all();

        return [
            'attendances' => $attendances,
        ];


    }

    // CHANGE PASSWORD
    public function editAccount(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (is_null($employee)) {
            return response()->json([
                'message' => 'Employee not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        $employee->email = $request->email;
        $employee->username = $request->username;
        $employee->pin = $request->pin;
        $employee->password = !is_null($request->password) ? Hash::make($request->password) : null;

        $emailExist = Employee::where('email', $request->email)->whereNotIn('id', [$id])->first();
        $usernameExist = Employee::where('username', $request->username)->whereNotIn('id', [$id])->first();

        $existCredentialErrors = [];
        if ($emailExist) {
            array_push($existCredentialErrors, ['field' => 'email', 'error_message' => 'Email already used']);
        }

        if ($usernameExist) {
            array_push($existCredentialErrors, ['field' => 'username', 'error_message' => 'Username already used']);
        }

        if (count($existCredentialErrors) > 0) {
            return response()->json([
                'message' => 'Validation errors',
                'error' => true,
                'code' => 400,
                'errors' => $existCredentialErrors
            ], 400);
        } else {
            try {
                $employee->save();
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
    }

    public function payslip($id)
    {
    }

    public function getAllSickSubmissions(Request $request, $id)
    {
        $whereClause = $request->query();
        try {
            $employee = Employee::find($id);

            if ($employee == null) {
                return response()->json([
                    'message' => 'Employee not found',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo'];


            $sickSubmissions = SickSubmission::with(['employee' => function ($query) use ($employeeColumns) {
                $query->select($employeeColumns);
            }])->where('employee_id', $id)->where($whereClause)->get();

            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $sickSubmissions
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

    public function getAllPermissionSubmissions(Request $request, $id)
    {
        $whereClause = $request->query();
        try {
            $employee = Employee::find($id);

            if ($employee == null) {
                return response()->json([
                    'message' => 'Employee not found',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo'];

            $permissionSubmissions = Permission::with(['permissionCategory', 'employee' => function ($query) use ($employeeColumns) {
                $query->select($employeeColumns);
            }])->where('employee_id', $id)->where($whereClause)->get();

            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $permissionSubmissions,
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

    public function getAllLeaveSubmissions(Request $request, $id)
    {
        $whereClause = $request->query();
        try {
            $employee = Employee::find($id);

            if ($employee == null) {
                return response()->json([
                    'message' => 'Employee not found',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo'];

            $leaveSubmissions = LeaveSubmission::with(['employee' => function ($query) use ($employeeColumns) {
                $query->select($employeeColumns);
            }])->where('employee_id', $id)->where($whereClause)->get();

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

    public function getRemainingLeaves($id)
    {
        try {
            $employee = Employee::find($id);

            if ($employee == null) {
                return response()->json([
                    'message' => 'Employee not found',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            $leave = $employee->leaves->where('is_active', 1)->first();
            $takenLeaveCurrentMonth = $employee->attendances->where('category', 'leave')->where('status', 'approved')->whereBetween('date', [date("Y-m-01"), date("Y-m-t")])->all();

            $remainingLeaves = [
                'total_leave' => $leave->total_leave,
                'taken_leave' => $leave->taken_leave,
                'taken_leave_current_month' => count($takenLeaveCurrentMonth),
            ];

            // return $remainingLeaves;

            // $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo'];

            // $permissionSubmissions = Permission::with(['permissionCategory', 'employee' => function ($query) use ($employeeColumns) {
            //     $query->select($employeeColumns);
            // }])->where('employee_id', $id)->get();

            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $remainingLeaves,
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

    public function getActiveLeave($id)
    {
        try {
            // $whereClause = $request->query();
            // $categories = [];
            // if($type) {
            //     $categories = BudgetCategory::where('type', $type)->get();
            // } else {
            //     $categories = BudgetCategory::all();
            // }
            // $leaves = Leave::with(['employee', 'employee.careers' => function($query) {
            //     $query->with(['jobTitle', 'designation', 'department'])->where('is_active', 1);
            // }])->where($whereClause)->get();
            $employee = Employee::find($id);

            if ($employee == null) {
                return response()->json([
                    'message' => 'Employee not found',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            $activeLeave = Leave::with(['employee'])->where('employee_id', $id)->where('is_active', 1)->first();

            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $activeLeave,
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

    public function payslips(Request $request, $id)
    {
        $whereClause = $request->query();
        try {
            $employee = Employee::find($id);

            if ($employee == null) {
                return response()->json([
                    'message' => 'Employee not found',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            // $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo'];
            $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'start_work_date', 'photo', 'daily_money_regular', 'daily_money_holiday', 'overtime_pay_regular', 'overtime_pay_holiday'];

            $payslips = FinalPayslip::query()
                ->with(['employee' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns);
                }, 'employee.careers' => function ($query) {
                    $query->with(['jobTitle', 'designation', 'department'])->where('is_active', 1);
                }])
                ->where('employee_id', $id)
                ->where($whereClause)
                ->get()->each(function ($item, $key) {
                    $income = json_decode($item->income);
                    $deduction = json_decode($item->deduction);
                    $item['total_incomes'] = collect($income)->sum('value');
                    $item['total_deductions'] = collect($deduction)->sum('value');
                })->all();

            // $payslips->each(function ($item, $key) {
            //     $item->income = json_decode($item->income);
            //     $item->deduction = json_decode($item->deduction);
            // })->all();

            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $payslips,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function loans(Request $request, $id)
    {
        $employee = Employee::find($id);

        if ($employee == null) {
            return response()->json([
                'message' => 'Employee not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }
        try {

            $loans = $employee->loans;

            $totalLoans = collect($loans)->where('type', 'loan')->sum('amount');
            $totalPayments = collect($loans)->where('type', 'payment')->sum('amount');
            $remainingLoans = $totalLoans - $totalPayments;

            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => [
                    'total_loans' => $totalLoans,
                    'total_payments' => $totalPayments,
                    'remaining_loans' => $remainingLoans,
                    'loans' => $loans,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to get data',
                'error' => true,
                'code' => 500,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
