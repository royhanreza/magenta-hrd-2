<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        return view('company.index', ['companies' => $companies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = new Company;
        $company->name = $request->name;
        $company->registration_number = $request->registration_number;
        $company->contact_number = $request->contact_number;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->npwp = $request->npwp;
        $company->address = $request->address;
        $company->province = $request->province;
        $company->country = $request->country;
        $company->city = $request->city;
        $company->zip_code = $request->zip_code;
        // $company->logo = $request->logo;
        // $company->added_by = $request->added_by;
        $company->logo = 'magenta-logo.png';
        $company->added_by = 1;

        try {
            $company->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code'=> 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code'=> 500,
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
        $company = Company::findOrFail($id);
        return view('company.edit', ['company' => $company]);
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
        $company = Company::find($id);
        $company->name = $request->name;
        $company->registration_number = $request->registration_number;
        $company->contact_number = $request->contact_number;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->npwp = $request->npwp;
        $company->address = $request->address;
        $company->province = $request->province;
        $company->country = $request->country;
        $company->city = $request->city;
        $company->zip_code = $request->zip_code;
        // $company->logo = $request->logo;
        // $company->added_by = $request->added_by;
        // $company->logo = 'magenta-logo.png';
        // $company->added_by = 1;

        try {
            $company->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code'=> 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code'=> 500,
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
        $company = Company::find($id);
        try {
            $company->delete();
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
