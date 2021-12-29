<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TransactionAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionAccountApiController extends Controller
{
    public function index(Request $request)
    {
        $whereClause = $request->query();
        try {
            $transactions = TransactionAccount::with(['bankAccounts'])->where($whereClause)->get();
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $transactions,
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
        $transaction = TransactionAccount::find($id);

        if ($transaction == null) {
            return response()->json([
                'message' => 'Transaction not found',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        try {
            // DB::table('attendances')->insert($attendances);
            return response()->json([
                'message' => 'OK',
                'error' => false,
                'code' => 200,
                'data' => $transaction,
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

    public function store(Request $request)
    {
        $source = $request->source;

        // $dataCount = TransactionAccount::query()->where('date', date("Y-m-d"))->get()->count();
        // $number = 'IO' . '-' . date('d') . date('m') . date("y") . sprintf('%04d', $dataCount + 1);

        if ($source == 'add') {
            $transactions = [
                [
                    'date' => $request->date,
                    'amount' => $this->clearThousandFormat($request->amount),
                    'description' => $request->description,
                    'image' => null,
                    'type' => 'in',
                    'account_id' => $request->in_account,
                    'transaction_id' => null,
                ],
                [
                    'date' => $request->date,
                    'amount' => $this->clearThousandFormat($request->amount),
                    'description' => $request->description,
                    'image' => null,
                    'type' => 'out',
                    'account_id' => $request->out_account,
                    'transaction_id' => null,
                ]
            ];

            try {
                DB::table('transaction_account')->insert($transactions);
                return response()->json([
                    'message' => 'Data has been saved',
                    'error' => true,
                    'code' => 200,
                    'data' => $transactions,
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Internal Error',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e
                ], 500);
            }
        } else if ($source == 'transaction') {
            $transaction = new TransactionAccount;
            $transaction->date = $request->date;
            $transaction->amount = $request->amount;
            $transaction->description = $request->description;
            $transaction->image = $request->image;
            $transaction->type = $request->type;
            $transaction->account_id = $request->account_id;
            $transaction->transaction_id = $request->transaction_id;

            try {
                $transaction->save();
                return response()->json([
                    'message' => 'Data has been saved',
                    'error' => true,
                    'code' => 200,
                    'data' => $transaction,
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Internal Error',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unknown source',
                'error' => true,
                'code' => 400,
            ], 400);
        }

        return response()->json([
            'message' => 'Unknown source',
            'error' => true,
            'code' => 400,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $transaction = TransactionAccount::find($id);

        if ($transaction == null) {
            return response()->json([
                'message' => 'Transaction not found',
                'error' => true,
                'code' => 500,
            ], 500);
        }

        $transaction->date = $request->date;
        $transaction->amount = $this->clearThousandFormat($request->amount);
        $transaction->description = $request->description;
        $transaction->image = $request->image;
        $transaction->type = $request->type;
        $transaction->account_id = $request->account_id;
        $transaction->transaction_id = $request->transaction_id;

        try {
            $transaction->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
                'data' => $transaction,
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
        $transaction = TransactionAccount::find($id);

        if ($transaction == null) {
            return response()->json([
                'message' => 'Transaction not found',
                'error' => true,
                'code' => 500,
            ], 500);
        }

        try {
            $transaction->delete();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
                'data' => $transaction,
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

    private function clearThousandFormat($number)
    {
        return str_replace(".", "", $number);
    }

    private function getRecordNumber($model, $prefix = '')
    {
        $dataCount = $model::withTrashed()->where('date', date("Y-m-d"))->get()->count();
        $number = $prefix . '-' . date('d') . date('m') . date("y") . sprintf('%04d', $dataCount + 1);

        return $number;
    }
}
