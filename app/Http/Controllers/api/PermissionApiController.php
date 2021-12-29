<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\PermissionCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PermissionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereClause = $request->query();

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'work_placement', 'photo'];

        try {
            $permissionSubmissions = Permission::with(['employee' => function ($query) use ($employeeColumns) {
                $query->select($employeeColumns);
            }, 'permissionCategory'])->where($whereClause)->get();
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
        $permissionDates = explode(",", $request->permission_dates);;
        $attachment = $request->attachment;
        $status = 'pending';

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
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        try {
            $permission = new Permission;
            $permission->date_of_filing = $request->date_of_filing;
            $permission->employee_id = $request->employee_id;
            $permission->permission_category_id = $request->permission_category_id;
            $permission->permission_dates = $request->permission_dates;
            $permission->number_of_days = $request->number_of_days;
            $permission->attachment = $request->attachment;
            $permission->description = $request->description;
            $permission->status = $status;

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
        $permission = Permission::find($id);

        if ($permission == null) {
            return response()->json([
                'message' => 'Permission submission not found',
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
        $permission = Permission::find($id);

        if ($permission == null) {
            return response()->json([
                'message' => 'Permission submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        $employeeId = $request->employee_id;
        $dateOfFiling = $request->date_of_filing;
        $permissionDates = explode(",", $request->permission_dates);
        $oldPermissionDates = explode(",", $request->old_permission_dates);
        $attachment = $request->attachment;
        $description = $request->description;
        $status = 'pending';

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
            ]);
        }

        try {
            Attendance::query()
                ->where('category', 'permission')
                // ->where('status', 'pending')
                ->whereIn('date', $oldPermissionDates)
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
                $filePath = 'submissions/' . time() . '-pms-' . $file->getClientOriginalName();
                Storage::disk('s3')->delete($permission->attachment);
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
            $permission->date_of_filing = $request->date_of_filing;
            $permission->employee_id = $request->employee_id;
            $permission->permission_category_id = $request->permission_category_id;
            $permission->permission_dates = $request->permission_dates;
            $permission->number_of_days = count($permissionDates);
            // $permission->attachment = $request->attachment;
            $permission->description = $request->description;
            $permission->status = $status;
            // $permission->description = $description;
            $permission->save();
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
        $permission = Permission::find($id);
        $permissionDates = explode(",", $permission->permission_dates);

        try {
            Attendance::query()
                ->where('category', 'permission')
                // ->where('status', 'pending')
                ->whereIn('date', $permissionDates)
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
            $permission->forceDelete();
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
        $permission = Permission::find($id);

        if ($permission == null) {
            return response()->json([
                'message' => 'Permission submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }

        // $employeeId = $request->employee_id;
        // $dateOfFiling = $request->date_of_filing;
        // $oldSickDates = explode(",", $request->old_sick_dates);
        // $attachment = $request->attachment;
        // $description = $request->description;

        $permissionDates = explode(",", $permission->permission_dates);
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
                ->where('category', 'permission')
                // ->where('status', 'pending')
                ->whereIn('date', $permissionDates)
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
            $permission->status = $status;
            // $permission->description = $description;
            $permission->save();
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
        $permission = Permission::find($id);

        if ($permission == null) {
            return response()->json([
                'message' => 'Permission submission not found',
                'error' => true,
                'code' => 500,
            ]);
        }


        $permissionDates = explode(",", $permission->permission_dates);
        $status = 'rejected';


        try {
            Attendance::query()
                ->where('category', 'permission')
                // ->where('status', 'pending')
                ->whereIn('date', $permissionDates)
                ->update(['status' => 'rejected']);
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
            $permission->status = $status;
            // $permission->description = $description;
            $permission->save();
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
