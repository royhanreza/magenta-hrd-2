<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\EventMember;
use Exception;
use Illuminate\Http\Request;

class EventMemberController extends Controller
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
        $member = new EventMember;
        
        // $member->freelancer_id = ;
        if($request->type == 'employees') {
            $member->employee_id = $request->employee;
        } else {
            $member->freelancer_id = $request->employee;
        }
        $member->event_id = $request->event_id;
        $member->daily_money = $request->daily_money;
        $member->role = $request->role;

        try {
            $member->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'data' => EventMember::with(['employee', 'freelancer'])->find($member->id),
                'code'=> 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code'=> 500,
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
        $member = EventMember::find($id);
        
        // $member->freelancer_id = ;
        // if($request->type == 'employees') {
        //     $member->employee_id = $request->employee;
        // } else {
        //     $member->freelancer_id = $request->employee;
        // }
        // $member->event_id = $request->event_id;
        $member->daily_money = $request->daily_money;
        $member->role = $request->role;

        try {
            $member->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'data' => $member,
                'code'=> 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code'=> 500,
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
        $member = EventMember::find($id);
        try {
            $member->delete();
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
