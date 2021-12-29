<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;

class CompanyLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = CompanyLocation::all();
        return view('company-location.index', ['locations' => $locations]);
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

        // return $employees;
        return view('company-location.create', ['companies' => $companies, 'employees' => $employees]);
        // return $companies;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json($request);
        $companyLocation = new CompanyLocation;
        // $companyLocation->company_id = $request->company;
        $companyLocation->location_name = $request->location_name;
        $companyLocation->employee_id = $request->location_head;
        $companyLocation->contact_number = $request->contact_number;
        $companyLocation->email = $request->email;
        $companyLocation->npwp = $request->npwp;
        $companyLocation->address = preg_replace("/(\r|\n)/", "", $request->address);
        $companyLocation->province = $request->province;
        $companyLocation->country = $request->country;
        $companyLocation->city = $request->city;
        $companyLocation->zip_code = $request->zip_code;
        $companyLocation->latitude = $request->latitude;
        $companyLocation->longitude = $request->longitude;
        // $companyLocation->added_by = $request->added_by;
        $companyLocation->added_by = 1;

        try {
            $companyLocation->save();
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
        $location = CompanyLocation::findOrFail($id);
        $companies = Company::all();
        $employees = Employee::all()->map(function ($item) {
            return ['id' => $item->id, 'text' => $item->employee_id . ' | ' . $item->first_name];
        });
        return view('company-location.edit', ['location' => $location, 'companies' => $companies->toJson(), 'employees' => $employees]);
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
        $companyLocation = CompanyLocation::find($id);
        // $companyLocation->company_id = $request->company;
        $companyLocation->location_name = $request->location_name;
        $companyLocation->employee_id = $request->location_head;
        $companyLocation->contact_number = $request->contact_number;
        $companyLocation->email = $request->email;
        $companyLocation->npwp = $request->npwp;
        $companyLocation->address = preg_replace("/(\r|\n)/", "", $request->address);
        $companyLocation->province = $request->province;
        $companyLocation->country = $request->country;
        $companyLocation->city = $request->city;
        $companyLocation->zip_code = $request->zip_code;
        $companyLocation->latitude = $request->latitude;
        $companyLocation->longitude = $request->longitude;
        // $companyLocation->added_by = $request->added_by;
        // $companyLocation->added_by = 1;

        try {
            $companyLocation->save();
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
        $location = CompanyLocation::find($id);
        try {
            $location->delete();
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
