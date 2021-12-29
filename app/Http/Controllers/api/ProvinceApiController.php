<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Exception;
use Illuminate\Http\Request;

class ProvinceApiController extends Controller
{
    public function getCities($id)
    {
        $cities = [];
        try {
            $cities = Province::find($id)->cities;
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
                'data' => $cities,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to get data',
                'error' => true,
                'code' => 400,
                'errors' => $e->getMessage(),
            ], 400);
        }
    }
}
