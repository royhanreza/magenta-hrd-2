<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Freelancer;
use Exception;
use Illuminate\Http\Request;

class FreelancerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $freelancers = Freelancer::all();
        return view('freelancer.index', ['freelancers' => $freelancers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $freelancer = Freelancer::where('created_at', 'like', date('Y-m-d') . "%")->get();
        $counter = sprintf("%03d", count($freelancer) + 1);
        $freelancer_id = 'FL-' . date('d') . date('m') . date('Y') . '-' . $counter;

        return view('freelancer.create', ['freelancer_id' => $freelancer_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $freelancer = new Freelancer;
        $freelancer->freelancer_id = $request->employee_id;
        $freelancer->identity_number = $request->identity_number;
        $freelancer->first_name = $request->first_name;
        $freelancer->last_name = $request->last_name;
        $freelancer->email =  $request->email;
        $freelancer->contact_number =  $request->contact_number;
        $freelancer->gender = $request->gender;
        $freelancer->marital_status = $request->marital_status;
        $freelancer->date_of_birth = $request->date_of_birth;
        $freelancer->status = $request->status;
        $freelancer->date_joining = $request->date_joining;
        $freelancer->religion = $request->religion;
        $freelancer->address = $request->address;
        $freelancer->province = $request->province;
        $freelancer->city = $request->city;
        $freelancer->zip_code = $request->zip_code;
        $freelancer->country = $request->country;
        $freelancer->photo = 'user.png';
        $freelancer->identity_image = 'identity.png';

        try {
            $freelancer->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                // 'data' => $request->all(),
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
        $freelancer = Freelancer::findOrFail($id);
        return view('freelancer.edit', ['freelancer' => $freelancer]);
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
        $freelancer = Freelancer::find($id);
        $freelancer->employee_id = $request->employee_id;
        $freelancer->identity_number = $request->identity_number;
        $freelancer->first_name = $request->first_name;
        $freelancer->last_name = $request->last_name;
        $freelancer->email =  $request->email;
        $freelancer->contact_number =  $request->contact_number;
        $freelancer->gender = $request->gender;
        $freelancer->marital_status = $request->marital_status;
        $freelancer->date_of_birth = $request->date_of_birth;
        $freelancer->status = $request->status;
        $freelancer->date_joining = $request->date_joining;
        $freelancer->religion = $request->religion;
        $freelancer->address = $request->address;
        $freelancer->province = $request->province;
        $freelancer->city = $request->city;
        $freelancer->zip_code = $request->zip_code;
        // $freelancer->country = $request->country;
        // $freelancer->photo = 'user.png';
        // $freelancer->identity_image = 'identity.png';

        try {
            $freelancer->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                // 'data' => $request->all(),
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
        $freelancer = Freelancer::find($id);
        try {
            $freelancer->delete();
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
