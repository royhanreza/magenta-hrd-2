<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BpjsKesehatan;
use App\Models\BpjsKetenagakerjaan;
use App\Models\BpjsSetting;
use App\Models\ProvinceMinimumWage;
use Exception;
use Illuminate\Http\Request;

class SettingBpjsController extends Controller
{
    public function index()
    {
        $wages = ProvinceMinimumWage::all();

        // $setting = BpjsSetting::first();
        $bpjsKesehatan = BpjsKesehatan::first();
        $bpjsKetenagakerjaan = BpjsKetenagakerjaan::first();

        // return $bpjsKesehatan;

        if ($bpjsKesehatan == null) {
            abort(500);
        }
        if ($bpjsKetenagakerjaan == null) {
            abort(500);
        }

        return view('setting.bpjs.index', ['wages' => $wages, 'bpjs_kesehatan' => $bpjsKesehatan, 'bpjs_ketenagakerjaan' => $bpjsKetenagakerjaan]);
    }

    public function updateBpjsKetenagakerjaan(Request $request, $id)
    {
        $bpjs = BpjsKetenagakerjaan::find($id);
        $bpjs->has_bpjs_ketenagakerjaan = $request->has_bpjs_ketenagakerjaan;
        $bpjs->npp = $request->npp;
        $bpjs->base_multiplier = $request->base_multiplier;
        $bpjs->is_compare_salary_ump = $request->is_compare_salary_ump;
        $bpjs->jkk = $request->jkk;
        $bpjs->jkm = $request->jkm;
        $bpjs->jht_company = $request->jht_company;
        $bpjs->jht_employee = $request->jht_employee;
        $bpjs->is_jht_company_pph = $request->is_jht_company_pph;
        $bpjs->has_jp = $request->has_jp;
        $bpjs->is_jp_pph = $request->is_jp_pph;
        $bpjs->jp_company = $request->jp_company;
        $bpjs->jp_employee = $request->jp_employee;
        $bpjs->max_jp_multiplier = $request->max_jp_multiplier;
        $bpjs->is_foreigner_has_jp = $request->is_foreigner_has_jp;
        $bpjs->is_old_employee_has_jp = $request->is_old_employee_has_jp;
        $bpjs->effective_date = $request->effective_date;

        try {
            $bpjs->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $bpjs,
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

    public function updateBpjsKesehatan(Request $request, $id)
    {
        $bpjs = BpjsKesehatan::find($id);
        $bpjs->has_bpjs_kesehatan = $request->has_bpjs_kesehatan;
        $bpjs->business_code = $request->business_code;
        $bpjs->company_percentage = $request->company_percentage;
        $bpjs->employee_percentage = $request->employee_percentage;
        $bpjs->base_multiplier = $request->base_multiplier;
        $bpjs->max_multiplier = $request->max_multiplier;
        $bpjs->effective_date = $request->effective_date;

        try {
            $bpjs->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $bpjs,
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
