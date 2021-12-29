<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAccount extends Model
{
    use HasFactory;

    protected $table = 'transaction_account';

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }
}
