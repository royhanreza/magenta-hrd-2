<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function locations()
    {
        return $this->hasMany(CompanyLocation::class);
    }

    public function departments()
    {
        return $this->hasMany(CompanyDepartment::class);
    }

    public function designations()
    {
        return $this->hasMany(CompanyDesignation::class);
    }

    public function officeShifts()
    {
        return $this->hasMany(OfficeShift::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function employees()
    {
        return $this->belongsTo(Employee::class);
    }
}
