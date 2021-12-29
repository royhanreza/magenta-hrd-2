<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    public function tasks()
    {
        return $this->hasMany(EventTask::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function budgets()
    {
        return $this->hasMany(EventBudget::class);
    }

    public function members()
    {
        return $this->hasMany(EventMember::class);
    }

    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class);
    // }

    // public function freelancer()
    // {
    //     return $this->belongsTo(Freelancer::class);
    // }
}
