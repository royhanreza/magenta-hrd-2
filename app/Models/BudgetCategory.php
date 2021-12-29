<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetCategory extends Model
{
    use HasFactory, SoftDeletes;

    public function eventBudgets()
    {
        return $this->hasMany(EventBudget::class);
    }
}
