<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\SalaryDeduction;
use Exception;
use Illuminate\Http\Request;

class SalaryDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deduction = new SalaryDeduction;
        $deduction->name = $request->name;
        $deduction->type = $request->type;
        $deduction->pph = $request->pph;
        $deduction->is_active = $request->is_active;
        $deduction->company_id = $request->company_id;
        $deduction->added_by = $request->added_by;

        try {
            $deduction->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $deduction
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $deduction = SalaryDeduction::find($id);
        $deduction->name = $request->name;
        $deduction->type = $request->type;
        $deduction->pph = $request->pph;
        $deduction->is_active = $request->is_active;
        // $deduction->company_id = $request->company_id;
        // $deduction->added_by = $request->added_by;

        try {
            $deduction->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $deduction,
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deduction = SalaryDeduction::find($id);
        try {
            $deduction->delete();
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
