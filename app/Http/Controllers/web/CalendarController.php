<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\PermissionCategory;
use Exception;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $calendars = Calendar::all()->sortBy('date')->values();
        return view('setting.calendar.index', [
            'calendars' => $calendars,
        ]);
    }

    public function store(Request $request)
    {
        $calendar = new Calendar;
        $calendar->name = $request->name;
        $calendar->date = $request->date;
        $calendar->type = $request->type;

        try {
            $calendar->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $calendar,
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

    public function update(Request $request, $id)
    {
        $calendar = Calendar::find($id);
        $calendar->name = $request->name;
        $calendar->date = $request->date;
        $calendar->type = $request->type;

        try {
            $calendar->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $calendar,
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

    public function destroy($id)
    {
        $calendar = Calendar::find($id);
        try {
            $calendar->delete();
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
