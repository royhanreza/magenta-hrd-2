<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\EventTask;
use Exception;
use Illuminate\Http\Request;

class EventTaskApiController extends Controller
{
    public function finish($id)
    {
        $task = EventTask::find($id);
        if(is_null($task)) {
            return response()->json([
                'message' => 'Task with id ' . $id . ' not found',
                'error' => true,
                'code'=> 400,
            ], 400);
        }

        try {
            $task->status = 'completed';
            $task->save();

            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code'=> 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => true,
                'code'=> 500,
                'errors' => $e
            ], 500);
        }

    } 
}
