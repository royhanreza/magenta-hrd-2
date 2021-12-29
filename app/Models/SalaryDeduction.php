<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryDeduction extends Model
{
    use HasFactory, SoftDeletes;

    public function paySlips()
    {
        return $this->belongsToMany(PaySlip::class, 'pay_slip_salary_deduction');
    }
}
