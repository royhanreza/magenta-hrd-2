<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PermissionCategory;
use Exception;
use Illuminate\Http\Request;

class PermissionCategoryApiController extends Controller
{
    public function index()
    {
        $categories = [];
        try {
            $categories = PermissionCategory::all();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $categories,
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
