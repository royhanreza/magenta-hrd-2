<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\SalaryIncome;
use Exception;
use Illuminate\Http\Request;

class SalaryIncomeController extends Controller
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
        $income = new SalaryIncome;
        $income->name = $request->name;
        $income->type = $request->type;
        $income->pph21 = $request->pph21;
        $income->type_a1 = $request->type_a1;
        $income->thr_income = $request->thr_income;
        $income->is_active = $request->is_active;
        $income->is_default = 0;
        $income->company_id = $request->company_id;
        $income->added_by = $request->added_by;

        try {
            $income->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $income
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
        $income = SalaryIncome::find($id);
        $income->name = $request->name;
        $income->type = $request->type;
        $income->pph21 = $request->pph21;
        $income->type_a1 = $request->type_a1;
        $income->thr_income = $request->thr_income;
        $income->is_active = $request->is_active;
        // $income->company_id = $request->company_id;
        // $income->added_by = $request->added_by;

        try {
            $income->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $income,
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
        $income = SalaryIncome::find($id);
        try {
            $income->delete();
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
