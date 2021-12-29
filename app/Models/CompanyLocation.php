<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function departments()
    {
        return $this->hasMany(CompanyDepartment::class);
    }
}
