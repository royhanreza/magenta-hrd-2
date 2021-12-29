<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CompanyNpwp;
use App\Models\PphSetting;
use Exception;
use Illuminate\Http\Request;

class SettingPphController extends Controller
{
    public function index()
    {
        $pphSetting = PphSetting::all()->first();
        $npwps = CompanyNpwp::all();
        return view('setting.pph.index', ['setting' => $pphSetting, 'npwps' => $npwps]);
    }

    public function update(Request $request, $id = null)
    {
        $setting = PphSetting::all()->first();

        if ($setting == null) {
            return response()->json([
                'message' => 'Internal Error, Setting Not Found',
                'error' => true,
                'code' => 500,
            ], 500);
        }

        try {
            PphSetting::where('id', $setting->id)->update($request->all());
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
