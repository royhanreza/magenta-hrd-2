<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use Exception;
use Illuminate\Http\Request;

class BudgetCategoryApiController extends Controller
{
    public function index(Request $request) {
        // $categories = [];
        try {
            $whereClause = $request->query();
            // $categories = [];
            // if($type) {
            //     $categories = BudgetCategory::where('type', $type)->get();
            // } else {
            //     $categories = BudgetCategory::all();
            // }

            $categories = BudgetCategory::where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
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
