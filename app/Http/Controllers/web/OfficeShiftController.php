<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\OfficeShift;
use Exception;
use Illuminate\Http\Request;

class OfficeShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = OfficeShift::with('company')->get();
        return view('office-shift.index', ['shifts' => $shifts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        $companies_final = [['id' => '', 'text' => 'Choose Company']];
        foreach ($companies as $company) {
            array_push($companies_final, ['id' => $company->id, 'text' => $company->name]);
        }
        return view('office-shift.create', ['companies' => json_encode($companies_final)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shift = new OfficeShift;
        $shift->name = $request->name;
        // $shift->company_id = $request->company;
        $shift->monday_in_time = $request->monday_in_time;
        $shift->monday_out_time = $request->monday_out_time;
        $shift->monday_status = $request->monday_status;
        $shift->monday_working_hours = $request->monday_working_hours;
        $shift->monday_working_hours_editable = $request->monday_working_hours_editable;

        $shift->tuesday_in_time = $request->tuesday_in_time;
        $shift->tuesday_out_time = $request->tuesday_out_time;
        $shift->tuesday_status = $request->tuesday_status;
        $shift->tuesday_working_hours = $request->tuesday_working_hours;
        $shift->tuesday_working_hours_editable = $request->tuesday_working_hours_editable;

        $shift->wednesday_in_time = $request->wednesday_in_time;
        $shift->wednesday_out_time = $request->wednesday_out_time;
        $shift->wednesday_status = $request->wednesday_status;
        $shift->wednesday_working_hours = $request->wednesday_working_hours;
        $shift->wednesday_working_hours_editable = $request->wednesday_working_hours_editable;

        $shift->thursday_in_time = $request->thursday_in_time;
        $shift->thursday_out_time = $request->thursday_out_time;
        $shift->thursday_status = $request->thursday_status;
        $shift->thursday_working_hours = $request->thursday_working_hours;
        $shift->thursday_working_hours_editable = $request->thursday_working_hours_editable;

        $shift->friday_in_time = $request->friday_in_time;
        $shift->friday_out_time = $request->friday_out_time;
        $shift->friday_status = $request->friday_status;
        $shift->friday_working_hours = $request->friday_working_hours;
        $shift->friday_working_hours_editable = $request->friday_working_hours_editable;

        $shift->saturday_in_time = $request->saturday_in_time;
        $shift->saturday_out_time = $request->saturday_out_time;
        $shift->saturday_status = $request->saturday_status;
        $shift->saturday_working_hours = $request->saturday_working_hours;
        $shift->saturday_working_hours_editable = $request->saturday_working_hours_editable;

        $shift->sunday_in_time = $request->sunday_in_time;
        $shift->sunday_out_time = $request->sunday_out_time;
        $shift->sunday_status = $request->sunday_status;
        $shift->sunday_working_hours = $request->sunday_working_hours;
        $shift->sunday_working_hours_editable = $request->sunday_working_hours_editable;

        try {
            $shift->save();
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
        $shift = OfficeShift::find($id);
        $companies = Company::all();
        $companies_final = [['id' => '', 'text' => 'Choose Company']];
        foreach ($companies as $company) {
            array_push($companies_final, ['id' => $company->id, 'text' => $company->name]);
        }
        return view('office-shift.edit', ['shift' => $shift, 'companies' => json_encode($companies_final)]);
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
        $shift = OfficeShift::find($id);
        $shift->name = $request->name;
        // $shift->company_id = $request->company;
        $shift->monday_in_time = $request->monday_in_time;
        $shift->monday_out_time = $request->monday_out_time;
        $shift->monday_status = $request->monday_status;
        $shift->monday_working_hours = $request->monday_working_hours;
        $shift->monday_working_hours_editable = $request->monday_working_hours_editable;

        $shift->tuesday_in_time = $request->tuesday_in_time;
        $shift->tuesday_out_time = $request->tuesday_out_time;
        $shift->tuesday_status = $request->tuesday_status;
        $shift->tuesday_working_hours = $request->tuesday_working_hours;
        $shift->tuesday_working_hours_editable = $request->tuesday_working_hours_editable;

        $shift->wednesday_in_time = $request->wednesday_in_time;
        $shift->wednesday_out_time = $request->wednesday_out_time;
        $shift->wednesday_status = $request->wednesday_status;
        $shift->wednesday_working_hours = $request->wednesday_working_hours;
        $shift->wednesday_working_hours_editable = $request->wednesday_working_hours_editable;

        $shift->thursday_in_time = $request->thursday_in_time;
        $shift->thursday_out_time = $request->thursday_out_time;
        $shift->thursday_status = $request->thursday_status;
        $shift->thursday_working_hours = $request->thursday_working_hours;
        $shift->thursday_working_hours_editable = $request->thursday_working_hours_editable;

        $shift->friday_in_time = $request->friday_in_time;
        $shift->friday_out_time = $request->friday_out_time;
        $shift->friday_status = $request->friday_status;
        $shift->friday_working_hours = $request->friday_working_hours;
        $shift->friday_working_hours_editable = $request->friday_working_hours_editable;

        $shift->saturday_in_time = $request->saturday_in_time;
        $shift->saturday_out_time = $request->saturday_out_time;
        $shift->saturday_status = $request->saturday_status;
        $shift->saturday_working_hours = $request->saturday_working_hours;
        $shift->saturday_working_hours_editable = $request->saturday_working_hours_editable;

        $shift->sunday_in_time = $request->sunday_in_time;
        $shift->sunday_out_time = $request->sunday_out_time;
        $shift->sunday_status = $request->sunday_status;
        $shift->sunday_working_hours = $request->sunday_working_hours;
        $shift->sunday_working_hours_editable = $request->sunday_working_hours_editable;

        try {
            $shift->save();
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
        $shift = OfficeShift::find($id);
        try {
            $shift->delete();
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
