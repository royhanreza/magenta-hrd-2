<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Permission;
use App\Models\PermissionCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::with(['employee', 'permissionCategory'])->get();

        // return $permissions;
        // return $permissions;
        return view('permission.index', ['permissions' => $permissions]);
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
        return view('permission.create', ['employees' => $employees, 'categories' => $categories]);
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
        $permissionDates = explode(',', $request->permission_dates);
        $attachment = $request->attachment;
        $status = $request->status;

        $permissionName = PermissionCategory::find($request->permission_category_id);

        $attendances = [];
        foreach ($permissionDates as $permissionDate) {
            array_push($attendances, [
                'employee_id' => $employeeId,
                'date' => $permissionDate,
                'clock_in' => date('Y-m-d H:i:s'),
                'clock_in_ip_address' => $request->ip(),
                'status' => $status,
                'type' => 'check in',
                'image' => $attachment,
                'category' => 'permission',
                'note' => ($permissionName == null) ? null : $permissionName->name,
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
                'message' => '[Internal Error] Error while saving attendances',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        $permission = new Permission;

        if ($request->hasFile('attachment')) {
            try {
                $file = $request->file('attachment');
                $filePath = 'submissions/' . time() . '-' . $file->getClientOriginalName();
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                $permission->attachment = $filePath;
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
            // $permission = new Permission;
            $permission->date_of_filing = $request->date_of_filing;
            $permission->employee_id = $request->employee_id;
            $permission->permission_category_id = $request->permission_category_id;
            $permission->permission_dates = $request->permission_dates;
            $permission->number_of_days = count($permissionDates);
            // $permission->attachment = $request->attachment;
            $permission->description = $request->description;
            $permission->status = $request->status;

            $permission->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $permission,
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
        $permission = Permission::findOrFail($id);

        $employees = Employee::all();
        $categories = PermissionCategory::all();

        // return explode("submissions", $permission->attachment);
        // return Storage::disk('s3')->url($permission->attachment);


        return view('permission.edit', ['employees' => $employees, 'categories' => $categories, 'permission' => $permission]);
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
}
