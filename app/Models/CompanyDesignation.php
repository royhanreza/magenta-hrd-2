<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyDesignation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(CompanyDepartment::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function careers()
    {
        return $this->hasMany(Career::class);
    }
}
