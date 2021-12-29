<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Freelancer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FreelancerApiController extends Controller
{
    public function index()
    {
        $freelancers = [];
        try {
            $freelancers = Freelancer::all();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
                'data' => $freelancers,
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

    public function getAllEvents(Request $request, $id)
    {
        $whereClause = $request->query();
        try {
            $events = Event::whereHas('members', function($q) use($id) {
                $q->where('freelancer_id', $id);
            })->where($whereClause)->get();
            
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code'=> 200,
                'data' => $events,
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
}
