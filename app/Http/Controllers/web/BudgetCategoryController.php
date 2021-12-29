<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use Exception;
use Illuminate\Http\Request;

class BudgetCategoryController extends Controller
{
    public function index()
    {
        $budgetCategories = BudgetCategory::all();
        return view('budget-category.index', ['budget_categories' => $budgetCategories]);
    }

    public function create()
    {
        return view('budget-category.create');
    }

    public function store(Request $request)
    {
        $budgetCategory = new BudgetCategory;
        $budgetCategory->name = $request->name;
        $budgetCategory->type = $request->type;

        try {
            $budgetCategory->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code'=> 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code'=> 500,
                'errors' => $e
            ], 500);
        }
    }

    public function edit($id)
    {
        $budgetCategory = BudgetCategory::findOrFail($id);
        return view('budget-category.edit', ['budget_category' => $budgetCategory]);
    }

    public function update(Request $request, $id)
    {
        $budgetCategory = BudgetCategory::find($id);
        $budgetCategory->name = $request->name;
        $budgetCategory->type = $request->type;

        try {
            $budgetCategory->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code'=> 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code'=> 500,
                'errors' => $e
            ], 500);
        }
    }

    public function destroy($id)
    {
        $budgetCategory = BudgetCategory::find($id);
        try {
            $budgetCategory->delete();
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
