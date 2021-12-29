<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BudgetCategory;
use App\Models\Employee;
use App\Models\Event;
use App\Models\EventBudget;
use App\Models\EventMember;
use App\Models\Province;
use Illuminate\Http\Request;

class MappingEventController extends Controller
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

        return view('mapping-event.index', ['events' => $events]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

        return view('mapping-event.view', ['event' => $event, 'provinces' => json_encode($provinces_final), 'cities' => json_encode($cities_final)]);
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

    /**
     * Display budget event
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function finance($id)
    {
        $balance = 0;
        $total_income = 0;
        $total_expense = 0;
        $event = Event::find($id);
        $budgets = EventBudget::where('event_id', $id)->where('status', 'approved')->get();
        // $budgets = $budgets->sortBy('date')->values()->all();

        // return $budgets;

        foreach ($budgets as $budget) {
            if ($budget->type == 'income') {
                $balance += $budget->amount;
                $total_income += $budget->amount;
            } else {
                $balance = $balance - $budget->amount;
                $total_expense += $budget->amount;
            }
            $budget['balance'] = $balance;
        }

        // Unconfirmed Budgets
        $pendingBudgets = EventBudget::where('event_id', $id)->where('status', 'pending')->get();
        $rejectedBudgets = EventBudget::where('event_id', $id)->where('status', 'rejected')->get();

        return view('mapping-event.finance', ['event' => $event, 'budgets' => $budgets, 'total_income' => $total_income, 'total_expense' => $total_expense, 'pending_budgets' => $pendingBudgets, 'rejected_budgets' => $rejectedBudgets]);

        // return $budgets;
    }

    public function task($id)
    {
        $event = Event::findOrFail($id);

        return view('mapping-event.task', ['event' => $event]);
    }

    public function budget($id)
    {
        $event = Event::findOrFail($id);

        $budgets = EventBudget::with('bankAccount')->where('event_id', $id)->get();

        $bankAccounts = BankAccount::all();
        $bankAccountsFinal = [['id' => '', 'text' => 'Choose Bank Account']];
        foreach ($bankAccounts as $account) {
            array_push($bankAccountsFinal, ['id' => $account->id, 'text' => $account->bank_name . ' ' . $account->account_number . ' (' . $account->account_owner . ')']);
        }

        $budgetCategories = BudgetCategory::where('type', 'income')->get();
        $budgetCategoriesFinal = [['id' => '', 'text' => 'Choose Category']];
        foreach ($budgetCategories as $category) {
            array_push($budgetCategoriesFinal, ['id' => $category->id, 'text' => $category->name]);
        }

        return view('mapping-event.budget', ['event' => $event, 'budgets' => $budgets, 'bank_accounts' => json_encode($bankAccountsFinal), 'budget_categories' => json_encode($budgetCategoriesFinal)]);

        // return $budgets;
    }

    public function member($id)
    {
        $event = Event::findOrFail($id);
        $members = Event::with(['members.freelancer', 'members.employee'])->find($id);
        $employees = Employee::all();
        $employees_final = [['id' => '', 'text' => 'Choose Person']];
        foreach ($employees as $employee) {
            array_push($employees_final, ['id' => $employee->id, 'text' => $employee->first_name . ' ' . $employee->last_name]);
        }

        return view('mapping-event.member', ['event' => $event, 'employees' => json_encode($employees_final), 'event_member' => $members]);

        // return $members;
    }
}
