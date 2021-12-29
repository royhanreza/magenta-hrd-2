<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    public function eventBudgets()
    {
        return $this->hasMany(EventBudget::class);
    }

    public function transactions()
    {
        return $this->hasMany(TransactionAccount::class, 'account_id');
    }
}
