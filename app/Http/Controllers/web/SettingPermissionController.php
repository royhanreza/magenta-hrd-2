<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\PermissionCategory;
use Exception;
use Illuminate\Http\Request;

class SettingPermissionController extends Controller
{
    public function index()
    {
        $permissions = PermissionCategory::all();
        return view('setting.permission.index', [
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $category = new PermissionCategory;
        $category->name = $request->name;
        $category->max_day = $request->max_day;
        $category->is_active = $request->is_active;

        try {
            $category->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $category,
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

    public function destroy($id)
    {
        $category = PermissionCategory::find($id);
        try {
            $category->delete();
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
