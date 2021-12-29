<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;

class CompanyApiController extends Controller
{
    public function getOfficeShifts($id)
    {
      $shifts = [];
        try {
            $shifts = Company::find($id)->officeShifts;
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
                'data' => $shifts,
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

    public function getRoles($id)
    {
      $roles = [];
        try {
            $roles = Company::find($id)->roles;
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
                'data' => $roles,
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

    public function getLocations($id)
    {
      $locations = [];
        try {
            $locations = Company::find($id)->locations;
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
                'data' => $locations,
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

    public function getDepartments($id)
    {
      $departments = [];
        try {
            $departments = Company::find($id)->departments;
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
                'data' => $departments,
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
