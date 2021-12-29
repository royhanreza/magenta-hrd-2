<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\SalaryDeduction;
use App\Models\SalaryIncome;
use App\Models\SalarySetting;
use Exception;
use Illuminate\Http\Request;

class SettingSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salaryIncomes = SalaryIncome::orderByDesc('is_default')->get();
        $salaryDeductions = SalaryDeduction::all();
        $salarySetting = SalarySetting::all()->first();

        // return $salaryIncomes->values()->all();
        // return $salaryIncomes;

        return view('setting.salary.index', [
            'salary_incomes' => $salaryIncomes,
            'salary_deductions' => $salaryDeductions,
            'salary_setting' => $salarySetting,
        ]);
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
    public function update(Request $request, $id = null)
    {
        $setting = SalarySetting::all()->first();

        if ($setting == null) {
            return response()->json([
                'message' => 'Internal Error, Setting Not Found',
                'error' => true,
                'code' => 500,
            ], 500);
        }

        // return $request->all();

        // $setting->has_overtime = $request->has_overtime;
        // $setting->overtime_formula = $request->overtime_formula;
        // $setting->overtime_nominal_per_hour = $request->overtime_nominal_per_hour;
        // $setting->thr_min_months_of_service = $request->thr_min_months_of_service;
        // $setting->thr_amount = $request->thr_amount;
        // $setting->thr_type = $request->thr_type;
        // $setting->thr_for_less_one_year = $request->thr_for_less_one_year;
        // $setting->has_leave = $request->has_leave;
        // $setting->salary_for_career_changes = $request->salary_for_career_changes;
        // $setting->proporsional_formula_career_changes = $request->proporsional_formula_career_changes;
        // $setting->salary_for_middle_out = $request->salary_for_middle_out;
        // $setting->proporsional_formula_middle_out = $request->proporsional_formula_middle_out;
        // $setting->work_day_per_month = $request->work_day_per_month;
        // $setting->work_monday = $request->work_monday;
        // $setting->work_tuesday = $request->work_tuesday;
        // $setting->work_wednesday = $request->work_wednesday;
        // $setting->work_thursday = $request->work_thursday;
        // $setting->work_friday = $request->work_friday;
        // $setting->work_saturday = $request->work_saturday;
        // $setting->work_sunday = $request->work_sunday;
        // $setting->has_digital_account = $request->has_digital_account;
        // $income->company_id = $request->company_id;
        // $income->added_by = $request->added_by;

        try {
            // $setting->save($request->all());
            // $setting->update(['has_overtime' => 1]);
            SalarySetting::where('id', $setting->id)->update($request->all());
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
        //
    }
}
