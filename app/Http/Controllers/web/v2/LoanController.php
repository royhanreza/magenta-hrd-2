<?php

namespace App\Http\Controllers\web\v2;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Exception;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function store(Request $request)
    {
        $loan = new Loan;
        $loan->employee_id = $request->employee_id;
        $loan->date = $request->date;
        $loan->payslip_date = $request->payslip_date;
        $loan->amount = $request->amount;
        $loan->type = $request->type;
        $loan->description = $request->description;

        try {
            $loan->save();
            // return response()->json([
            //     'message' => 'Data has been saved',
            //     'error' => false,
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

        $term = $request->term;
        $paymentPerMonth = round($request->amount / $term);

        if ($term > 0) {
            for ($i = 1; $i <= $term; $i++) {
                $newPayslipDate = date("Y-m-d", strtotime("+" . $i . " month", strtotime($request->payslip_date)));

                $payment = new Loan;
                $payment->employee_id = $request->employee_id;
                $payment->date = $request->date;
                $payment->payslip_date = $newPayslipDate;
                $payment->amount = $paymentPerMonth;
                $payment->type = 'payment';
                $payment->description = '-';

                try {
                    $payment->save();
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

        return response()->json([
            'message' => 'Data has been saved',
            'error' => false,
            'code' => 200,
        ]);
    }

    public function hold(Request $request, $id)
    {
        $heldPayment = Loan::findOrFail($id);

        $latestPayment = Loan::where('type', 'payment')->where('employee_id', $heldPayment->employee_id)->latest('payslip_date')->first();

        $loan = new Loan;
        $loan->employee_id = $heldPayment->employee_id;
        $loan->date = $heldPayment->date;
        $loan->payslip_date = date("Y-m-d", strtotime("+1 month", strtotime($latestPayment->payslip_date)));;
        $loan->amount = $heldPayment->amount;
        $loan->type = $heldPayment->type;
        $loan->description = $heldPayment->description;

        try {
            $loan->save();
            // return response()->json([
            //     'message' => 'Data has been saved',
            //     'error' => false,
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

        try {
            $heldPayment->delete();
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
        ]);
    }
}
