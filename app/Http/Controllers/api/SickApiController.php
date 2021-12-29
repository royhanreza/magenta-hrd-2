<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\SickSubmission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SickApiController extends Controller
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
            $sickSubmissions = SickSubmission::with(['employee'])->where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $sickSubmissions,
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
        $employeeId = $request->employee_id;
        $dateOfFiling = $request->date_of_filing;
        $sickDates = explode(",", $request->sick_dates);
        $attachment = $request->attachment;
        $description = $request->description;
        // $status = $request->status;
        $status = 'pending';

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
        // $attendance->image = $request->image;
        // $attendance->category = $request->category;

        $attendances = [];
        foreach ($sickDates as $sickDate) {
            array_push($attendances, [
                'employee_id' => $employeeId,
                'date' => $sickDate,
                'clock_in' => date('Y-m-d H:i:s'),
                'clock_in_ip_address' => $request->ip(),
                'status' => $status,
                'type' => 'check in',
                'image' => $attachment,
                'category' => 'sick',
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

        try {
            $sickSubmission = new SickSubmission;
            $sickSubmission->date_of_filing = $dateOfFiling;
            $sickSubmission->employee_id = $employeeId;
            $sickSubmission->sick_dates = $request->sick_dates;
            $sickSubmission->attachment = $attachment;
            $sickSubmission->description = $description;
            $sickSubmission->status = $status;
            // $sickSubmission->description = $description;
            $sickSubmission->save();
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

        $sickSubmission = SickSubmission::find($id);

        if ($sickSubmission == null) {
            return response()->json([
                'message' => 'Sick submission not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        try {
            // DB::table('attendances')->insert($attendances);
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $sickSubmission,
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

        $sickSubmission = SickSubmission::find($id);

        if ($sickSubmission == null) {
            return response()->json([
                'message' => 'Sick submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        $employeeId = $request->employee_id;
        $dateOfFiling = $request->date_of_filing;
        $sickDates = explode(",", $request->sick_dates);
        $oldSickDates = explode(",", $request->old_sick_dates);
        $attachment = $request->attachment;
        $description = $request->description;
        $status = 'pending';

        $attendances = [];
        foreach ($sickDates as $sickDate) {
            array_push($attendances, [
                'employee_id' => $employeeId,
                'date' => $sickDate,
                'clock_in' => date('Y-m-d H:i:s'),
                'clock_in_ip_address' => $request->ip(),
                'status' => $status,
                'type' => 'check in',
                'image' => $attachment,
                'category' => 'sick',
            ]);
        }

        try {
            Attendance::query()
                ->where('category', 'sick')
                // ->where('status', 'pending')
                ->whereIn('date', $oldSickDates)
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

        if ($request->hasFile('attachment')) {
            try {
                $file = $request->file('attachment');
                $filePath = 'submissions/' . time() . '-sck-' . $file->getClientOriginalName();
                Storage::disk('s3')->delete($sickSubmission->attachment);
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                $sickSubmission->attachment = $filePath;
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
            $sickSubmission->date_of_filing = $dateOfFiling;
            $sickSubmission->employee_id = $employeeId;
            $sickSubmission->sick_dates = $request->sick_dates;
            // $sickSubmission->attachment = $attachment;
            $sickSubmission->description = $description;
            $sickSubmission->status = $status;
            // $sickSubmission->description = $description;
            $sickSubmission->save();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sickSubmission = SickSubmission::find($id);
        $sickDates = explode(",", $sickSubmission->sick_dates);

        try {
            Attendance::query()
                ->where('category', 'sick')
                // ->where('status', 'pending')
                ->whereIn('date', $sickDates)
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

            $sickSubmission->forceDelete();
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
        $sickSubmission = SickSubmission::find($id);

        if ($sickSubmission == null) {
            return response()->json([
                'message' => 'Sick submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        // $employeeId = $request->employee_id;
        // $dateOfFiling = $request->date_of_filing;
        // $oldSickDates = explode(",", $request->old_sick_dates);
        // $attachment = $request->attachment;
        // $description = $request->description;

        $sickDates = explode(",", $sickSubmission->sick_dates);
        $status = 'approved';
        // $attendances = [];
        // foreach ($sickDates as $sickDate) {
        //     array_push($attendances, [
        //         'employee_id' => $employeeId,
        //         'date' => $sickDate,
        //         'clock_in' => date('Y-m-d H:i:s'),
        //         'clock_in_ip_address' => $request->ip(),
        //         'status' => $status,
        //         'type' => 'check in',
        //         'image' => $attachment,
        //         'category' => 'sick',
        //     ]);
        // }

        try {
            Attendance::query()
                ->where('category', 'sick')
                // ->where('status', 'pending')
                ->whereIn('date', $sickDates)
                ->update(['status' => 'approved']);

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


        // try {
        //     DB::table('attendances')->insert($attendances);

        // } catch (Exception $e) {
        //     return response()->json([
        //         'message' => 'Internal Error',
        //         'error' => true,
        //         'code' => 500,
        //         'errors' => $e
        //     ], 500);
        // }

        try {
            $sickSubmission->status = $status;
            // $sickSubmission->description = $description;
            $sickSubmission->save();
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
        $sickSubmission = SickSubmission::find($id);

        if ($sickSubmission == null) {
            return response()->json([
                'message' => 'Sick submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        // $employeeId = $request->employee_id;
        // $dateOfFiling = $request->date_of_filing;
        // $oldSickDates = explode(",", $request->old_sick_dates);
        // $attachment = $request->attachment;
        // $description = $request->description;

        $sickDates = explode(",", $sickSubmission->sick_dates);
        $status = 'rejected';
        // $attendances = [];
        // foreach ($sickDates as $sickDate) {
        //     array_push($attendances, [
        //         'employee_id' => $employeeId,
        //         'date' => $sickDate,
        //         'clock_in' => date('Y-m-d H:i:s'),
        //         'clock_in_ip_address' => $request->ip(),
        //         'status' => $status,
        //         'type' => 'check in',
        //         'image' => $attachment,
        //         'category' => 'sick',
        //     ]);
        // }

        try {
            Attendance::query()
                ->where('category', 'sick')
                // ->where('status', 'pending')
                ->whereIn('date', $sickDates)
                ->update(['status' => 'rejected']);

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


        // try {
        //     DB::table('attendances')->insert($attendances);

        // } catch (Exception $e) {
        //     return response()->json([
        //         'message' => 'Internal Error',
        //         'error' => true,
        //         'code' => 500,
        //         'errors' => $e
        //     ], 500);
        // }

        try {
            $sickSubmission->status = $status;
            // $sickSubmission->description = $description;
            $sickSubmission->save();
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
