<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\OvertimeSubmission;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeSubmissionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereClause = $request->query();
        try {
            $overtimeSubmissions = OvertimeSubmission::with(['employee'])->where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $overtimeSubmissions,
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
        //
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

    public function approve($id)
    {
        $overtimeSubmission = OvertimeSubmission::find($id);

        $generalDate = $overtimeSubmission->date;
        $employeeId = $overtimeSubmission->employee_id;
        $duration = $overtimeSubmission->duration;

        if ($overtimeSubmission == null) {
            return response()->json([
                'message' => 'Sick submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        try {
            $overtimeSubmission->status = 'approved';
            $overtimeSubmission->approved_by = Auth::id();
            $overtimeSubmission->approved_at = date('Y-m-d H:i:s');
            // $sickSubmission->description = $description;

            $checkout = Attendance::query()
                ->where('date', $generalDate)
                ->where('employee_id', $employeeId)
                ->where('type', 'check out')
                ->orderBy('id', 'desc')
                ->first();

            if ($checkout == null) {
                throw new Error('Tidak ditemukan data absensi clock out di tanggal ' . $generalDate);
            }

            $newCheckout = Attendance::find($checkout->id);
            $newCheckout->overtime_submission_duration += $duration;
            $newCheckout->save();

            $overtimeSubmission->save();
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
        $overtimeSubmissions = OvertimeSubmission::find($id);

        if ($overtimeSubmissions == null) {
            return response()->json([
                'message' => 'Sick submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        try {
            $overtimeSubmissions->status = 'rejected';
            // $sickSubmission->description = $description;
            $overtimeSubmissions->save();
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
