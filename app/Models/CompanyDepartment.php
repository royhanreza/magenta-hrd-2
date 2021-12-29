<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyDepartment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function location()
    {
        return $this->belongsTo(CompanyLocation::class, 'company_location_id');
    }

    public function designations()
    {
        return $this->hasMany(CompanyDesignation::class, 'department_id');
    }

    public function careers()
    {
        return $this->hasMany(Career::class);
    }
}
