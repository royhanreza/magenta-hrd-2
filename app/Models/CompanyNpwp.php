<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyNpwp extends Model
{
    use HasFactory, SoftDeletes;

    public function employeeNpwps()
    {
        return $this->hasMany(EmployeeNpwp::class);
    }
}
