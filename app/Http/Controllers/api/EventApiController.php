<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventBudget;
use Exception;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index(Request $request)
    {
        $whereClause = $request->query();
        try {
            $events = Event::with(['city.province', 'members.employee', 'members.freelancer'])->where($whereClause)->get()->each(function ($event, $key) {
                if (count($event->tasks) > 0) {
                    $tasks = collect($event->tasks);
                    $taskCompleted = $tasks->sum(function ($task) {
                        return ($task->status == 'completed') ? 1 : 0;
                    });
                    $event['progress'] = round(($taskCompleted / count($tasks)) * 100);
                } else {
                    $event['progress'] = 0;
                }

                // GET TOTAL INCOME, EXPENSE, AND BALANCE
                $balance = 0;
                $totalIncome = 0;
                $totalExpense = 0;

                if (count($event->budgets) > 0) {
                    $totalIncome = $event->budgets->sum(function ($budget) {
                        return ($budget->type == 'income') ? $budget->amount : 0;
                    });
                    $totalExpense = $event->budgets->sum(function ($budget) {
                        return ($budget->type == 'expense') ? $budget->amount : 0;
                    });
                    $balance = $totalIncome - $totalExpense;
                }

                $event['budget_summary'] = [
                    'total_income' => $totalIncome,
                    'total_expense' => $totalExpense,
                    'balance' => $balance,
                ];
            });


            // $events = Event::with(['city.province'])->where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
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

    public function getAllBudgets(Request $request, $id)
    {
        $whereClause = $request->query();
        $event = Event::find($id);
        if (!$event) {
            return response()->json([
                'message' => 'Failed to get data',
                'error' => true,
                'code' => 400,
                'errors' => 'Event with id ' . $id . ' not found',
            ], 400);
        } else {
            try {
                $balance = 0;
                $total_income = 0;
                $total_expense = 0;

                $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id'];

                $budgets = EventBudget::with([
                    'budgetCategory',
                    'bankAccount',
                    'requestedBy' => function ($query) use ($employeeColumns) {
                        $query->select($employeeColumns)->with('designation');
                    },
                    'approvedBy' => function ($query) use ($employeeColumns) {
                        $query->select($employeeColumns)->with('designation');
                    },
                    'rejectedBy' => function ($query) use ($employeeColumns) {
                        $query->select($employeeColumns)->with('designation');
                    }
                ])->where('event_id', $id)->where($whereClause)->get();
                $budgets = $budgets->sortBy('date')->values()->all();

                foreach ($budgets as $budget) {
                    if ($budget->type == 'income') {
                        $balance += $budget->amount;
                        $total_income += $budget->amount;
                    } else {
                        $balance -= $budget->amount;
                        $total_expense += $budget->amount;
                    }
                    $budget['balance'] = $balance;
                };

                return response()->json([
                    'message' => 'OK',
                    'error' => false,
                    'code' => 200,
                    'data' => [
                        'total_income' => $total_income,
                        'total_expense' => $total_expense,
                        'balance' => $balance,
                        'cash_flow' => $budgets,
                    ],
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
}
