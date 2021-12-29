<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaySlip extends Model
{
    use HasFactory, SoftDeletes;

    public function salaryIncomes()
    {
        return $this->belongsToMany(SalaryIncome::class, 'pay_slip_salary_income');
    }

    public function salaryDeductions()
    {
        return $this->belongsToMany(SalaryDeduction::class, 'pay_slip_salary_deduction');
    }

    public function careers()
    {
        return $this->belongsToMany(Career::class, 'career_pay_slip');
    }
}
