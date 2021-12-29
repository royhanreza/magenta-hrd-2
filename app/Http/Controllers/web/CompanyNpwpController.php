<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CompanyNpwp;
use Exception;
use Illuminate\Http\Request;

class CompanyNpwpController extends Controller
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
        $npwp = new CompanyNpwp;
        $npwp->company_npwp_name = $request->company_npwp_name;
        $npwp->company_npwp_number = $request->company_npwp_number;
        $npwp->leader_npwp_name = $request->leader_npwp_name;
        $npwp->leader_npwp_number = $request->leader_npwp_number;

        try {
            $npwp->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $npwp,
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
        $npwp = CompanyNpwp::find($id);
        $npwp->company_npwp_name = $request->company_npwp_name;
        $npwp->company_npwp_number = $request->company_npwp_number;
        $npwp->leader_npwp_name = $request->leader_npwp_name;
        $npwp->leader_npwp_number = $request->leader_npwp_number;

        try {
            $npwp->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $npwp,
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
        $npwp = CompanyNpwp::find($id);
        try {
            $npwp->delete();
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
