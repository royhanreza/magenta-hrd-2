<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use HasFactory, SoftDeletes;

    public function payslips()
    {
        return $this->belongsToMany(PaySlip::class, 'career_pay_slip')->withPivot('incomes', 'deductions');;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(CompanyDepartment::class);
    }

    public function designation()
    {
        return $this->belongsTo(CompanyDesignation::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }
}
