<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Exception;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::all();
        return view('bank-account.index', ['bank_accounts' => $bankAccounts]);
    }

    public function create()
    {
        return view('bank-account.create');
    }

    public function store(Request $request) 
    {
        $bankAccount = new BankAccount;
        $bankAccount->account_owner = $request->account_owner;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->bank_code = $request->bank_code;
        $bankAccount->bank_branch = $request->bank_branch;

        try {
            $bankAccount->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
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

    public function edit($id)
    {
        $bankAccount = BankAccount::findOrFail($id);
        return view('bank-account.edit', ['bank_account' => $bankAccount]);
    }

    public function update(Request $request, $id)
    {
        $bankAccount = BankAccount::find($id);
        $bankAccount->account_owner = $request->account_owner;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->bank_code = $request->bank_code;
        $bankAccount->bank_branch = $request->bank_branch;

        try {
            $bankAccount->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
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
}
