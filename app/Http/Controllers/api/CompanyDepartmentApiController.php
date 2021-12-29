<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CompanyDepartment;
use Exception;
use Illuminate\Http\Request;

class CompanyDepartmentApiController extends Controller
{
    public function getDesignations($id)
    {
        $designations = [];
        try {
            $designations = CompanyDepartment::find($id)->designations;
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
                'data' => $designations,
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
