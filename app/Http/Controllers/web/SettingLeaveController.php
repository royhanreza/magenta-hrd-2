<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\LeaveSetting;
use Exception;
use Illuminate\Http\Request;

class SettingLeaveController extends Controller
{
    public function index()
    {
        $setting = LeaveSetting::all()->sortByDesc('effective_date')->first();
        return view('setting.leave.index', ['setting' => $setting]);
    }

    public function update(Request $request, $id = null)
    {
        $setting = LeaveSetting::all()->first();

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
            LeaveSetting::where('id', $setting->id)->update($request->all());
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
