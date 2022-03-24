<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\OvertimeSubmission;
use App\Models\PermissionCategory;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OvertimeSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('overtime-submission.index');
    }

    /**
     * Data for datatables.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData(Request $request)
    {
        $userLoginPermissions = [];
        if ($request->session()->has('userLoginPermissions')) {
            $userLoginPermissions = $request->session()->get('userLoginPermissions');
        }

        $statusQuery = $request->query('status');

        $overtimeSubmissions = OvertimeSubmission::with(['employee']);
        if ($statusQuery !== null) {
            $overtimeSubmissions->where('status', $statusQuery);
        }
        $overtimeSubmissions->select('overtime_submissions.*');
        return DataTables::of($overtimeSubmissions)
            ->addColumn('approval', function ($row) use ($userLoginPermissions) {
                $button = '';
                if ($row->status == 'pending') {
                    // if (in_array("approvalOvertimeSubmission", $userLoginPermissions)) {
                    $button .= '
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                            <button type="button" class="btn btn-sm btn-light btn-reject" data-id="' . $row->id . '"><i class="fas fa-fw fa-times"></i></a>
                            <button type="button" class="btn btn-sm btn-light btn-approve" data-id="' . $row->id . '"><i class="fas fa-fw fa-check"></i></button>
                        </div>';
                    // }
                }
                return $button;
            })
            ->addColumn('action', function ($row) use ($userLoginPermissions) {
                $action = '';
                $action .= '<div class="btn-group" role="group" aria-label="Action Buttons">';
                if ($row->status == 'pending') {
                    // if (in_array("editOvertimeSubmission", $userLoginPermissions)) {
                    $action .= '<a href="/overtime-submission/edit/' . $row->id . '" class="btn btn-sm btn-light"><i class="fas fa-fw fa-pencil-alt"></i></a>';
                    // }
                    // if (in_array("deleteOvertimeSubmission", $userLoginPermissions)) {
                    $action .= '<button type="button" class="btn btn-sm btn-light btn-delete" data-id="' . $row->id . '"><i class="fas fa-fw fa-trash"></i></button>';
                    // }
                }

                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['approval', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
        return view('overtime-submission.create', ['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $generalDate = $request->date;

            $startDate = $generalDate;
            $endDate = $generalDate;
            $overtimeStart = $request->overtime_start;
            $overtimeEnd = $request->overtime_end;

            if ($overtimeEnd < $overtimeStart) {
                $endDate = Carbon::parse($endDate)->addDay()->format('Y-m-d');
            }

            $carbonStartDate = Carbon::parse($startDate . ' ' . $overtimeStart);
            $carbonEndDate = Carbon::parse($endDate . ' ' . $overtimeEnd);

            $duration = 0;

            $diffMinutes = $carbonStartDate->diffInMinutes($carbonEndDate);

            $x = $diffMinutes % 30; // 0
            $y = ($diffMinutes - $x) / 30; // 3 

            if ($y > 0) {
                $z = ($diffMinutes - $x) - 30; // (90 - 0) - 30 = 60
                $duration = 1 + floor($z / 60); // 1 + floor(60 / 60) = 2
            }

            $overtimeSubmission = new OvertimeSubmission;
            $overtimeSubmission->date_of_filing = $request->date_of_filing;
            $overtimeSubmission->employee_id = $request->employee_id;
            $overtimeSubmission->date = $request->date;
            $overtimeSubmission->overtime_start = $request->overtime_start;
            $overtimeSubmission->overtime_end = $request->overtime_end;
            $overtimeSubmission->duration = $duration;
            $overtimeSubmission->work = $request->work;
            $overtimeSubmission->note = $request->note;
            $overtimeSubmission->status = $request->status;

            if ($overtimeSubmission->status == 'approved') {
                $overtimeSubmission->approved_by = Auth::id();
                $overtimeSubmission->approved_at = date('Y-m-d H:i:s');
            }

            $overtimeSubmission->save();

            if ($overtimeSubmission->status == 'approved') {
                $checkout = Attendance::query()
                    ->where('date', $generalDate)
                    ->where('employee_id', $request->employee_id)
                    ->where('type', 'check out')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($checkout == null) {
                    throw new Exception('Tidak ditemukan data absensi clock out di tanggal ' . $generalDate);
                }

                $newCheckout = Attendance::find($checkout->id);
                $newCheckout->overtime_submission_duration += $duration;
                $newCheckout->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e->getMessage(),
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
        $overtimeSubmission = OvertimeSubmission::with(['employee', 'approvedBy'])->findOrFail($id);
        $employees = Employee::all();

        return view('overtime-submission.show', [
            'overtime_submission' => $overtimeSubmission,
            'employees' => $employees,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $overtimeSubmission = OvertimeSubmission::findOrFail($id);

        if ($overtimeSubmission->status !== 'pending') {
            abort(401);
        }

        $employees = Employee::all();

        return view('overtime-submission.edit', [
            'overtime_submission' => $overtimeSubmission,
            'employees' => $employees,
        ]);
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
        DB::beginTransaction();
        try {
            $generalDate = $request->date;

            $startDate = $generalDate;
            $endDate = $generalDate;
            $overtimeStart = $request->overtime_start;
            $overtimeEnd = $request->overtime_end;

            if ($overtimeEnd < $overtimeStart) {
                $endDate = Carbon::parse($endDate)->addDay()->format('Y-m-d');
            }

            $carbonStartDate = Carbon::parse($startDate . ' ' . $overtimeStart);
            $carbonEndDate = Carbon::parse($endDate . ' ' . $overtimeEnd);

            $duration = 0;

            $diffMinutes = $carbonStartDate->diffInMinutes($carbonEndDate);

            $x = $diffMinutes % 30; // 0
            $y = ($diffMinutes - $x) / 30; // 3 

            if ($y > 0) {
                $z = ($diffMinutes - $x) - 30; // (90 - 0) - 30 = 60
                $duration = 1 + floor($z / 60); // 1 + floor(60 / 60) = 2
            }

            $overtimeSubmission = OvertimeSubmission::find($id);
            $overtimeSubmission->date_of_filing = $request->date_of_filing;
            $overtimeSubmission->employee_id = $request->employee_id;
            $overtimeSubmission->date = $request->date;
            $overtimeSubmission->overtime_start = $request->overtime_start;
            $overtimeSubmission->overtime_end = $request->overtime_end;
            $overtimeSubmission->duration = $duration;
            $overtimeSubmission->work = $request->work;
            $overtimeSubmission->note = $request->note;
            $overtimeSubmission->status = $request->status;

            if ($overtimeSubmission->status == 'approved') {
                $overtimeSubmission->approved_by = Auth::id();
                $overtimeSubmission->approved_at = date('Y-m-d H:i:s');
            }

            $overtimeSubmission->save();

            if ($overtimeSubmission->status == 'approved') {
                $checkout = Attendance::query()
                    ->where('date', $generalDate)
                    ->where('employee_id', $request->employee_id)
                    ->where('type', 'check out')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($checkout == null) {
                    throw new Exception('Tidak ditemukan data absensi clock out di tanggal ' . $generalDate);
                }

                $newCheckout = Attendance::find($checkout->id);
                $newCheckout->overtime_submission_duration += $duration;
                $newCheckout->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e->getMessage(),
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
        try {
            $overtimeSubmission = OvertimeSubmission::find($id);
            $overtimeSubmission->forceDelete();
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
}
