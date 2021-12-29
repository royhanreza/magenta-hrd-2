<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\TransactionAccount;
use Exception;
use Illuminate\Http\Request;

class BankAccountApiController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::all();
        // return view('bank-account.index', ['bank_accounts' => $bankAccounts]);
        try {
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $bankAccounts,
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

    public function create()
    {
        // return view('bank-account.create');
    }

    public function store(Request $request)
    {
        $bankAccount = new BankAccount;
        $bankAccount->account_owner = $request->account_owner;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->bank_code = $request->bank_code;
        $bankAccount->bank_branch = $request->bank_branch;
        $bankAccount->account_balance = $request->account_balance;
        $bankAccount->type = $request->type;

        try {
            $bankAccount->save();
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

    public function show($id)
    {
        $bankAccount = BankAccount::find($id);
        // return view('bank-account.index', ['bank_accounts' => $bankAccounts]);
        try {
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $bankAccount,
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

    public function edit($id)
    {
        // $bankAccount = BankAccount::findOrFail($id);
        // return view('bank-account.edit', ['bank_account' => $bankAccount]);
    }

    public function update(Request $request, $id)
    {
        $bankAccount = BankAccount::find($id);
        $bankAccount->account_owner = $request->account_owner;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->bank_code = $request->bank_code;
        $bankAccount->bank_branch = $request->bank_branch;
        $bankAccount->account_balance = $request->account_balance;
        $bankAccount->type = $request->type;

        try {
            $bankAccount->save();
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

    public function destroy($id)
    {
        $bankAccount = BankAccount::find($id);
        try {
            $bankAccount->delete();
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

    public function transactions($id)
    {
        // $bankAccount = BankAccount::find($id);

        // if ($bankAccount == null) {
        //     return response()->json([
        //         'message' => 'Bank account not found',
        //         'error' => true,
        //         'code' => 400,
        //     ], 400);
        // }

        // $transactions = $bankAccount->transactions;
        $balance = 0;
        $totalIn = 0;
        $totalOut = 0;

        $transactions = TransactionAccount::query()
            ->where('account_id', $id)
            ->orderBy('date', 'asc')
            ->get()
            ->each(function ($item) use (&$balance, &$totalIn, &$totalOut) {
                if ($item->type == 'in') {
                    $balance += $item->amount;
                    $totalIn += $item->amount;
                } else {
                    $balance -= $item->amount;
                    $totalOut += $item->amount;
                }
                $item['balance'] = $balance;
            })->sortByDesc('date')->values()->all();

        try {
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => [
                    'transactions' => $transactions,
                    'total_in' => $totalIn,
                    'total_out' => $totalOut,
                ],
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
