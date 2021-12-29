<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeFileController extends Controller
{
    public function store(Request $request)
    {
        $employeeFile = new EmployeeFile;
        $employeeFile->name = $request->name;
        // $employeeFile->path = $request->path;
        $employeeFile->employee_id = $request->employee_id;

        if (!$request->hasFile('path')) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
            ], 500);
        }

        $employee = Employee::findOrFail($request->employee_id);

        $employeeName = implode('-', explode(' ', strtolower($employee->first_name)));

        $file = $request->file('path');
        $filePath = 'employee-files/' . time() . '-' . strtolower($request->name) . '-' . $employee->id . '-' . $employeeName . '.' . $file->extension();
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $employeeFile->path = $filePath;

        try {
            $employeeFile->save();

            $employeeFile->url = Storage::disk("s3")->url($filePath);
            $employeeFile->extension = $file->extension();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
                'data' => $employeeFile,
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
        $employeeFile = EmployeeFile::findOrFail($id);

        try {
            Storage::disk('s3')->delete($employeeFile->path);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        try {
            $employeeFile->delete();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        return response()->json([
            'message' => 'Data has been saved',
            'error' => false,
            'code' => 200,
            'data' => $employeeFile,
        ]);
    }
}
