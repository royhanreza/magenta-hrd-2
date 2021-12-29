<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    // public function company()
    // {
    //     return $this->belongsTo(Company::class, 'company_id');
    // }

    // public function department()
    // {
    //     return $this->belongsTo(CompanyDepartment::class, 'department_id');
    // }
    public function superior()
    {
        return $this->belongsTo(Employee::class, 'report_to');
    }

    public function location()
    {
        return $this->belongsTo(CompanyLocation::class, 'company_location_id');
    }

    public function designation()
    {
        return $this->belongsTo(CompanyDesignation::class, 'designation_id');
    }

    public function members()
    {
        return $this->hasMany(EventMember::class);
    }

    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function budgetApplicants()
    {
        return $this->hasMany(EventBudget::class, 'requested_by', 'id');
    }

    public function budgetApprovers()
    {
        return $this->hasMany(EventBudget::class, 'approved_by', 'id');
    }

    public function budgetRepellants()
    {
        return $this->hasMany(EventBudget::class, 'rejected_by', 'id');
    }

    public function attendanceApprovers()
    {
        return $this->hasMany(Attendance::class, 'approved_by', 'id');
    }

    public function attendanceRepellants()
    {
        return $this->hasMany(Attendance::class, 'rejected_by', 'id');
    }

    public function careers()
    {
        return $this->hasMany(Career::class);
    }

    public function activeCareer()
    {
        return $this->hasOne(Career::class)->where('is_active', 1);
    }

    // public function activeCareer()
    // {
    //     return $this->hasMany(Career::class);
    // }

    public function officeShifts()
    {
        return $this->belongsToMany(OfficeShift::class)->withPivot('is_active');
    }

    public function finalPayslips()
    {
        return $this->hasMany(FinalPayslip::class);
    }

    public function sickSubmissions()
    {
        return $this->hasMany(SickSubmission::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function activeLeave()
    {
        return $this->hasOne(Leave::class)->where('is_active', 1);
    }

    public function leaveSubmissions()
    {
        return $this->hasMany(LeaveSubmission::class);
    }

    public function permissionSubmissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function npwp()
    {
        return $this->hasOne(EmployeeNpwp::class);
    }

    public function bpjs()
    {
        return $this->hasOne(EmployeeBpjs::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
