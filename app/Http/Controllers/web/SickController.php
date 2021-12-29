<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Permission;
use App\Models\PermissionCategory;
use App\Models\SickSubmission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $permissions = Permission::with(['employee', 'permissionCategory'])->get();
        $sickSubmissions = SickSubmission::with(['employee'])->get();
        // return $permissions;
        return view('sick.index', ['sick_submissions' => $sickSubmissions]);
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
        return view('sick.create', ['employees' => $employees, 'categories' => $categories]);
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
        $sickDates = explode(",", $request->sick_dates);
        $attachment = $request->attachment;
        $description = $request->description;
        $status = $request->status;

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

        $sickSubmission = new SickSubmission;

        if ($request->hasFile('attachment')) {
            try {
                $file = $request->file('attachment');
                $filePath = 'submissions/' . time() . '-' . $file->getClientOriginalName();
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

        $sickSubmission = SickSubmission::findOrFail($id);

        $employees = Employee::all();
        $categories = PermissionCategory::all();

        return view('sick.edit', ['employees' => $employees, 'categories' => $categories, 'sick_submission' => $sickSubmission]);
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
    }
}
