<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyDesignation;
use Exception;
use Illuminate\Http\Request;

class CompanyDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designations = CompanyDesignation::with('department.company')->get();
        // return $designations;
        return view('company-designation.index', ['designations' => $designations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('company-designation.create', ['companies' => $companies]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $designation = new CompanyDesignation;
        $designation->name = $request->name;
        // $designation->company_id = $request->company;
        // $designation->department_id = $request->department;
        // $designation->description = $request->description;
        // $designation->added_by = 1;

        try {
            $designation->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $designation,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        // return response()->json(['requests' => $designation]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designation = CompanyDesignation::where('id', $id)->with('department.company')->firstOrFail();
        $companies = Company::all();
        $departments = Company::find($designation->department->company_id)->departments;
        $departments_final = [];

        foreach ($departments as $department) {
            array_push($departments_final, ['id' => $department->id, 'text' => $department->name]);
        }
        // $departments = '';
        return view('company-designation.edit', ['designation' => $designation, 'companies' => $companies, 'departments' => json_encode($departments_final)]);
        // return $designation;
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
        $designation = CompanyDesignation::find($id);
        $designation->name = $request->name;
        // $designation->company_id = $request->company;
        // $designation->department_id = $request->department;
        // $designation->description = $request->description;
        // $designation->added_by = 1;

        try {
            $designation->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $designation,
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
        $designation = CompanyDesignation::find($id);
        try {
            $designation->delete();
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
