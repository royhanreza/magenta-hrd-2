<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Exception;
use Illuminate\Http\Request;
use App\Models\CompanyNpwp;
use App\Models\PphSetting;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
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
        $loan = new Loan;
        $loan->employee_id = $request->employee_id;
        $loan->date = $request->date;
        $loan->payslip_date = $request->payslip_date;
        $loan->amount = $request->amount;
        $loan->type = $request->type;
        $loan->description = $request->description;

        try {
            $loan->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
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
      public function data($id)
    {
         $loan = new Loan;
         $data=DB::table('loans')->where('id',$id)->get();
    
        
        try {
           
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                 'data' => $data,
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
          $loan = Loan::find($id);
        
        $loan->date = $request->date;
        $loan->payslip_date = $request->payslip_date;
        $loan->amount = $request->amount;
 
        $loan->description = $request->description;

        try {
            $loan->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
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
        //
    }
}
