<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTask;
use App\Models\Province;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::with(['tasks', 'city.province'])->get()->each(function ($event, $key) {
            if (count($event->tasks) > 0) {
                $tasks = collect($event->tasks);
                $taskCompleted = $tasks->sum(function ($task) {
                    return ($task->status == 'completed') ? 1 : 0;
                });
                $event['progress'] = round(($taskCompleted / count($tasks)) * 100);
            } else {
                $event['progress'] = 0;
            }
        });

        return view('event.index', ['events' => $events]);
        // return $events;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (count($request->query()) < 1) {
            abort(404);
        }

        if (count($request->query()) !== 1 || is_null($request->query('quotation'))) {
            abort(404);
        }

        $quotationId = $request->query('quotation');

        $response = Http::get(env('BASE_URL_EO') . 'api/quotation/' . $quotationId);
        $quotation = $response->json();

        if ($quotation['code'] == 404) {
            abort(404);
        }

        // return $quotation['data'][0]['event_date'];

        $event = Event::where('created_at', 'like', date('Y-m-d') . "%")->get();
        $counter = sprintf("%03d", count($event) + 1);
        $project_number = 'PN-' . date('d') . date('m') . date('Y') . '-' . $counter;

        $provinces = Province::all();
        $provinces_final = [['id' => '', 'text' => 'Choose Province']];
        foreach ($provinces as $province) {
            array_push($provinces_final, ['id' => $province->id, 'text' => $province->name]);
        }

        return view('event.create', ['quotation' => $quotation['data'], 'project_number' => $project_number,  'provinces' => json_encode($provinces_final)]);

        // return $request->query('quotation');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json(['requests' => $request->all()]);
        $event = new Event;
        // $event->quotation_id = $request->quotation_id;
        $event->quotation_number = $request->quotation_number;
        $event->city_id = $request->city_id;
        // $event->budget = $request->budget;
        $event->po_number = $request->po_number;
        $event->po_date = $request->po_date;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->description = $request->description;
        $event->status = 'pending';
        $event->budget_effective_date = $request->start_date;
        $event->budget_expire_date = $request->end_date;
        $event->number = $request->number;
        // $event->quotation_event_date = date_format(date_create($request->quotation_event_date), "Y-m-d");
        $event->quotation_event_date = $request->quotation_event_date;
        $event->title = $request->title;
        $event->customer = $request->customer;
        $event->quotation_status = $request->quotation_status;
        $event->quotation_note = $request->quotation_note;
        $event->quotation_pic_event = $request->quotation_pic_event;

        try {
            $event->save();
            $event_id = $event->id;
            $tasks = $request->tasks;
            if (count($tasks) > 0) {
                for ($i = 0; $i < count($tasks); $i++) {
                    $tasks[$i]['event_id'] = $event_id;
                    $tasks[$i]['created_at'] = Carbon::now()->toDateTimeString();
                    $tasks[$i]['updated_at'] = Carbon::now()->toDateTimeString();
                }
                EventTask::insert($tasks);
            }
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
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
        $event = Event::with(['city.province', 'tasks'])->findOrFail($id);
        $provinceId = $event->city->province->id;

        $provinces = Province::all();
        $provinces_final = [['id' => '', 'text' => 'Choose Province']];
        foreach ($provinces as $province) {
            array_push($provinces_final, ['id' => $province->id, 'text' => $province->name]);
        }

        $cities = Province::find($provinceId)->cities;
        $cities_final = [['id' => '', 'text' => 'Choose City']];
        foreach ($cities as $city) {
            array_push($cities_final, ['id' => $city->id, 'text' => $city->name]);
        }

        return view('event.edit', ['event' => $event, 'provinces' => json_encode($provinces_final), 'cities' => json_encode($cities_final)]);
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
        // return response()->json(['requests' => $request->all()]);
        $event = Event::find($id);
        // $event->quotation_id = $request->quotation_id;
        $event->city_id = $request->city_id;
        // $event->budget = $request->budget;
        $event->po_number = $request->po_number;
        $event->po_date = $request->po_date;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->description = $request->description;
        $event->budget_effective_date = $request->effective_date;
        $event->budget_expire_date = $request->expire_date;

        try {
            $event->save();
            // $event_id = $event->id;
            // $tasks = $request->tasks;
            // if(count($tasks) > 0) {
            //     for($i = 0; $i < count($tasks); $i++) {
            //         $tasks[$i]['event_id'] = $event_id;
            //         $tasks[$i]['created_at'] = Carbon::now()->toDateTimeString();
            //         $tasks[$i]['updated_at'] = Carbon::now()->toDateTimeString();
            //     }
            //     EventTask::insert($tasks);
            // }
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
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

    public function updateBudgetDate(Request $request, $id)
    {
        // return response()->json(['requests' => $request->all()]);
        $event = Event::find($id);
        $event->budget_effective_date = $request->effective_date;
        $event->budget_expire_date = $request->expire_date;

        try {
            $event->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
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
        $event = Event::find($id);
        try {
            $event->delete();
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
        // return response()->json(['requests' => $request->all()]);
        $event = Event::find($id);

        try {
            if ($event->status == 'closed') {
                return response()->json([
                    'message' => 'This event has been closed, you cannot approve this event. Please refresh the page',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            if ($event->status == 'rejected') {
                return response()->json([
                    'message' => 'This event has been closed, you cannot approve this event. Please refresh the page',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            $event->status = 'approved';
            $event->save();

            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
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

    public function reject(Request $request, $id)
    {
        // return response()->json(['requests' => $request->all()]);
        $event = Event::find($id);

        try {
            if ($event->status == 'closed') {
                return response()->json([
                    'message' => 'This event has been closed, you cannot reject this event. Please refresh the page',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            if ($event->status == 'approved') {
                return response()->json([
                    'message' => 'This event has been approved, you cannot reject this event. Please refresh the page',
                    'error' => true,
                    'code' => 400,
                ], 400);
            }

            $event->status = 'rejected';
            $event->save();

            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
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

    public function close(Request $request, $id)
    {
        // return response()->json(['requests' => $request->all()]);
        $event = Event::find($id);
        $event->status = 'closed';

        try {
            $event->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
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
}
