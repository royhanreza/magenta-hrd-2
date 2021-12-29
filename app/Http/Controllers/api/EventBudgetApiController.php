<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\EventBudget;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class EventBudgetApiController extends Controller
{
    public function index(Request $request)
    {
        $whereClause = $request->query();

        try {
            // $balance = 0;
            // $total_income = 0;
            // $total_expense = 0;

            $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id'];

            $budgets = EventBudget::with([
                'event',
                'budgetCategory',
                'bankAccount',
                'requestedBy' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with('designation');
                },
                'approvedBy' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with('designation');
                },
                'rejectedBy' => function ($query) use ($employeeColumns) {
                    $query->select($employeeColumns)->with('designation');
                }
            ])->where($whereClause)->get();
            $budgets = $budgets->sortBy('date')->values()->all();

            // foreach ($budgets as $budget) {
            //     if ($budget->type == 'income') {
            //         $balance += $budget->amount;
            //         $total_income += $budget->amount;
            //     } else {
            //         $balance -= $budget->amount;
            //         $total_expense += $budget->amount;
            //     }
            //     $budget['balance'] = $balance;
            // };

            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $budgets,
                // 'data' => [
                //     'total_income' => $total_income,
                //     'total_expense' => $total_expense,
                //     'balance' => $balance,
                //     'cash_flow' => $budgets,
                // ],
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

    public function store(Request $request)
    {
        $budget = new EventBudget;
        $budget->amount = $request->amount;
        $budget->date = $request->date;
        $budget->transfer_to = $request->transfer_to;
        $budget->note = $request->note;
        $budget->type = $request->type;
        $budget->event_id = $request->event_id;
        $budget->budget_category_id = $request->budget_category_id;
        $budget->status = $request->status;
        $budget->requested_by = $request->requested_by;
        $budget->requested_at = Carbon::now()->toDateTimeString();

        try {
            $budget->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'data' => $budget,
                'code' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }
    }

    public function show($id)
    {
        // $employees = [];
        try {
            $budget = EventBudget::with(['event', 'budgetCategory', 'bankAccount'])->find($id);
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $budget,
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

    public function update(Request $request, $id)
    {
        $budget = EventBudget::find($id);
        $budget->amount = $request->amount;
        $budget->date = $request->date;
        $budget->transfer_to = $request->transfer_to;
        $budget->note = $request->note;
        $budget->type = $request->type;
        // $budget->event_id = $request->event_id;
        $budget->budget_category_id = $request->budget_category_id;
        $budget->status = $request->status;
        // $budget->requested_by = $request->requested_by;
        // $budget->requested_at = $request->requested_at;

        try {
            $budget->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
                'data' => $budget,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }
    }

    public function destroy($id)
    {
        $budget = EventBudget::find($id);
        try {
            $budget->delete();
            return response()->json([
                'message' => 'data has been deleted',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function approve(Request $request, $id)
    {

        $budget = EventBudget::find($id);

        if (is_null($budget)) {
            return response()->json([
                'message' => 'item not found',
                'error' => true,
                'code' => 404,
            ], 404);
        }

        if ($budget->status == 'rejected') {
            return response()->json([
                'message' => 'item has been rejected, you cannot approve this item',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        try {
            $budget->status = 'approved';
            $budget->approved_by = $request->approved_by;
            $budget->approved_at = Carbon::now()->toDateTimeString();
            $budget->approval_note = $request->approval_note;
            // SET REJECTION DATA TO NULL IF DATA EXIST
            $budget->rejected_by = null;
            $budget->rejected_at = null;
            $budget->rejection_note = null;

            $budget->save();
            return response()->json([
                'message' => 'item has been approved',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {

        $budget = EventBudget::find($id);

        if (is_null($budget)) {
            return response()->json([
                'message' => 'item not found',
                'error' => true,
                'code' => 404,
            ], 404);
        }

        if ($budget->status == 'approved') {
            return response()->json([
                'message' => 'item has been approved, you cannot reject this item',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        try {
            $budget->status = 'rejected';
            $budget->rejected_by = $request->rejected_by;
            $budget->rejected_at = Carbon::now()->toDateTimeString();
            $budget->rejection_note = $request->rejection_note;
            // SET REJECTION DATA TO NULL IF DATA EXIST
            $budget->approved_by = null;
            $budget->approved_at = null;
            $budget->approval_note = null;

            $budget->save();
            return response()->json([
                'message' => 'item has been rejected',
                'error' => false,
                'code' => 200,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ], 500);
        }
    }
}
