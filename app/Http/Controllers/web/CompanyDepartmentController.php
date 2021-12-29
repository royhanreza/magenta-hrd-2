<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyDepartment;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;

class CompanyDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = CompanyDepartment::with(['company', 'location'])->get();
        // return $departments;
        return view('company-department.index', ['departments' => $departments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        $employees = Employee::all()->map(function ($item) {
            return ['id' => $item->id, 'text' => $item->employee_id . ' | ' . $item->first_name];
        });
        return view('company-department.create', ['companies' => $companies, 'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $department = new CompanyDepartment;
        // $department->added_by = $request->added_by;
        $department->name = $request->department_name;
        // $department->company_id = $request->company;
        // $department->employee_id = $request->department_head;
        // $department->company_location_id = $request->location;
        // $department->added_by = 1;

        // return response()->json(['requests' => $department]);
        try {
            $department->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $department,
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
        $companies = Company::all();
        $department = CompanyDepartment::findOrFail($id);
        $locations = Company::find($department->company_id)->locations;
        $locations_final = [];

        foreach ($locations as $location) {
            array_push($locations_final, ['id' => $location->id, 'text' => $location->location_name]);
        }
        // return $locations_final;
        return view('company-department.edit', ['department' => $department, 'companies' => $companies, 'locations' => json_encode($locations_final)]);
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
        $department = CompanyDepartment::find($id);

        $department->name = $request->department_name;
        // $department->is_active = $request->is_active;

        // $department->company_id = $request->company;
        // $department->employee_id = $request->department_head;
        // $department->company_location_id = $request->location;

        // return response()->json(['requests' => $department]);
        try {
            $department->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $department,
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
        $department = CompanyDepartment::find($id);
        try {
            $department->delete();
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
