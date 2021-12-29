<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\EventBudget;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class EventBudgetController extends Controller
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
        $budget = new EventBudget;
        $budget->amount = $request->amount;
        $budget->date = $request->transfer_date . ' ' . $request->transfer_time;
        $budget->transfer_to = $request->transfer_to;
        $budget->note = $request->note;
        $budget->type = 'income';
        $budget->event_id = $request->event_id;
        $budget->budget_category_id = $request->budget_category_id;
        $budget->status = 'approved';
        // $budget->budget_category_id = 1;
        // $budget->effective_date = $request->effective_date;
        // $budget->expire_date = $request->expire_date;

        try {
            $budget->save();

            $newBudget = EventBudget::with('bankAccount')->find($budget->id);
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'data' => $newBudget,
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
        $budget = EventBudget::find($id);
        $budget->amount = $request->amount;
        $budget->date = $request->transfer_date . ' ' . $request->transfer_time;
        $budget->transfer_to = $request->transfer_to;
        $budget->note = $request->note;
        $budget->budget_category_id = $request->budget_category_id;
        // $budget->status = $request->status;
        $budget->status = 'approved';
        // $budget->budget_category_id = 1;
        // $budget->type = 'income';
        // $budget->event_id = $request->event_id;

        try {
            $budget->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'data' => $budget,
                // 'data' => $request->all(),
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $budget = EventBudget::find($id);
        try {
            $budget->delete();
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
