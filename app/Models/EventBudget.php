<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventBudget extends Model
{
    use HasFactory, SoftDeletes;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function budgetCategory()
    {
        return $this->belongsTo(BudgetCategory::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'transfer_to', 'id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(Employee::class, 'requested_by', 'id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(Employee::class, 'rejected_by', 'id');
    }
}
