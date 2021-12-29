<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\FinalPayslip;
use App\Models\Loan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class FinalPayslipController extends Controller
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $finalPayslip = FinalPayslip::find($id);
        try {
            $finalPayslip->delete();
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

    public function setting($id)
    {
        $payslip = FinalPayslip::with(['employee', 'employee.activeCareer'])->findOrFail($id);
        // $payslip->income = json_decode($payslip->income);
        // $payslip->deduction = json_decode($payslip->deduction);

        // return $payslip;
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id', 'photo'];

        $loans = Loan::with(['employee' => function ($query) use ($employeeColumns) {
            $query->select($employeeColumns);
        }, 'finalPayslip'])
            ->where('employee_id', $payslip->employee_id)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalLoan = $loans->where('type', 'loan')->sum('amount');
        $totalPayment = $loans->where('type', 'payment')->sum('amount');
        $remainingLoan = $totalLoan - $totalPayment;

        return view('final-payslip.setting', [
            'payslip' => $payslip,
            'total_loan' => $totalLoan,
            'total_payment' => $totalPayment,
            'remaining_loan' => $remainingLoan,
        ]);
    }

    public function addIncome(Request $request, $id)
    {
        $finalPayslip = FinalPayslip::find($id);
        $finalPayslip->income = $request->income;

        try {
            $finalPayslip->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'data' => $finalPayslip,
                'code' => 200,
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

    public function deleteIncome(Request $request, $id)
    {
        $finalPayslip = FinalPayslip::find($id);
        $finalPayslip->income = $request->income;

        try {
            $finalPayslip->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'data' => $finalPayslip,
                'code' => 200,
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

    public function addDeduction(Request $request, $id)
    {
        $finalPayslip = FinalPayslip::find($id);
        $finalPayslip->deduction = $request->deduction;

        try {
            $finalPayslip->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'data' => $finalPayslip,
                'code' => 200,
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

    public function deleteDeduction(Request $request, $id)
    {
        $finalPayslip = FinalPayslip::find($id);
        $finalPayslip->deduction = $request->deduction;

        try {
            $finalPayslip->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'data' => $finalPayslip,
                'code' => 200,
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

    public function addLoan(Request $request, $id)
    {
        $finalPayslip = FinalPayslip::find($id);
        // $finalPayslip->income = $request->income;
        if ($finalPayslip == null) {
            return response()->json([
                'message' => 'Data not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        $incomes = $request->income;
        $amount = $request->amount;

        $loan = new Loan;
        try {
            $loan->employee_id = $finalPayslip->employee_id;
            $loan->final_payslip_id = $finalPayslip->id;
            $loan->date = $finalPayslip->end_date_period;
            $loan->type = 'loan';
            $loan->amount = $amount;
            $loan->description = 'Kasbon ditambahkan pada slip gaji periode ' . Carbon::parse($finalPayslip->start_date_period)->isoFormat('LL') . ' - ' . Carbon::parse($finalPayslip->end_date_period)->isoFormat('LL');
            $loan->save();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        array_push($incomes, [
            'name' => 'Kasbon',
            'value' => $amount,
            'is_loan' => 1,
            'is_added' => 1,
            'loan_id' => $loan->id,
        ]);

        try {
            $finalPayslip->income = $incomes;
            $finalPayslip->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'data' => $finalPayslip,
                'code' => 200,
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

    public function deleteLoan(Request $request, $id)
    {
        $finalPayslip = FinalPayslip::find($id);
        if ($finalPayslip == null) {
            return response()->json([
                'message' => 'Data not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        $loanId = $request->loan_id;
        $loan = Loan::find($loanId);

        try {
            $loan->delete();
            // return response()->json([
            //     'message' => 'Data has been saved',
            //     'error' => true,
            //     'data' => $finalPayslip,
            //     'code' => 200,
            // ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        $finalPayslip->income = $request->income;

        try {
            $finalPayslip->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'data' => $finalPayslip,
                'code' => 200,
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

    public function addPayment(Request $request, $id)
    {
        $finalPayslip = FinalPayslip::find($id);
        // $finalPayslip->income = $request->income;
        if ($finalPayslip == null) {
            return response()->json([
                'message' => 'Data not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        $deductions = $request->deduction;
        $amount = $request->amount;

        $loan = new Loan;
        try {
            $loan->employee_id = $finalPayslip->employee_id;
            $loan->final_payslip_id = $finalPayslip->id;
            $loan->date = $finalPayslip->end_date_period;
            $loan->type = 'payment';
            $loan->amount = $amount;
            $loan->description = 'Kasbon ditambahkan pada slip gaji periode ' . Carbon::parse($finalPayslip->start_date_period)->isoFormat('LL') . ' - ' . Carbon::parse($finalPayslip->end_date_period)->isoFormat('LL');
            $loan->save();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        array_push($deductions, [
            'name' => 'Bayar Kasbon',
            'value' => $amount,
            'is_loan' => 1,
            'is_added' => 1,
            'loan_id' => $loan->id,
        ]);

        try {
            $finalPayslip->deduction = $deductions;
            $finalPayslip->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'data' => $finalPayslip,
                'code' => 200,
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

    public function deletePayment(Request $request, $id)
    {
        $finalPayslip = FinalPayslip::find($id);
        if ($finalPayslip == null) {
            return response()->json([
                'message' => 'Data not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        $loanId = $request->loan_id;
        $loan = Loan::find($loanId);

        try {
            $loan->delete();
            // return response()->json([
            //     'message' => 'Data has been saved',
            //     'error' => true,
            //     'data' => $finalPayslip,
            //     'code' => 200,
            // ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        $finalPayslip->deduction = $request->deduction;

        try {
            $finalPayslip->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'data' => $finalPayslip,
                'code' => 200,
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
}
