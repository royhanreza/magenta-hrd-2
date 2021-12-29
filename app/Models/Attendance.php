<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
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
