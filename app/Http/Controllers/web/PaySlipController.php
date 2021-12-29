<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\PaySlip;
use Exception;
use Illuminate\Http\Request;

class PaySlipController extends Controller
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payslip = new PaySlip;
        $payslip->name = $request->name;
        $payslip->period_type = $request->period_type;
        $payslip->long_period = $request->long_period;
        $payslip->monthly_first_day = $request->monthly_first_day;
        $payslip->weekly_first_day = $request->weekly_first_day;
        $payslip->daily_number_of_days = $request->daily_number_of_days;
        $payslip->daily_previous_payslip_date = $request->daily_previous_payslip_date;
        $payslip->income_last_day_attendance = $request->income_last_day_attendance;
        $salaryIncomes = collect($request->salary_incomes)->pluck('id');
        $salaryDeductions = collect($request->salary_deductions)->pluck('id');

        try {
            $payslip->save();
            $payslip->salaryIncomes()->attach($salaryIncomes);
            $payslip->salaryDeductions()->attach($salaryDeductions);
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $payslip,
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
        $payslip = PaySlip::find($id);
        $payslip->name = $request->name;
        $payslip->period_type = $request->period_type;
        $payslip->long_period = $request->long_period;
        $payslip->monthly_first_day = $request->monthly_first_day;
        $payslip->weekly_first_day = $request->weekly_first_day;
        $payslip->daily_number_of_days = $request->daily_number_of_days;
        $payslip->daily_previous_payslip_date = $request->daily_previous_payslip_date;
        $payslip->income_last_day_attendance = $request->income_last_day_attendance;
        $salaryIncomes = collect($request->salary_incomes)->pluck('id');
        $salaryDeductions = collect($request->salary_deductions)->pluck('id');

        try {
            $payslip->save();
            $payslip->salaryIncomes()->detach();
            $payslip->salaryDeductions()->detach();
            $payslip->salaryIncomes()->attach($salaryIncomes);
            $payslip->salaryDeductions()->attach($salaryDeductions);
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $payslip,
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
        $payslip = PaySlip::find($id);
        try {
            $payslip->delete();
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

    public function deleteIncome($id, $incomeId)
    {
        $payslip = PaySlip::find($id);
        try {
            $payslip->salaryIncomes()->detach($incomeId);
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

    public function deleteDeduction($id, $deductionId)
    {
        $payslip = PaySlip::find($id);
        try {
            $payslip->salaryDeductions()->detach($deductionId);
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
