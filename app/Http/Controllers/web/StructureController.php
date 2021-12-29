<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CompanyDepartment;
use App\Models\CompanyDesignation;
use App\Models\JobTitle;
use App\Models\SalaryIncome;
use App\Models\SalarySetting;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function index()
    {
        $salaryIncomes = SalaryIncome::orderByDesc('is_default')->get();
        $salarySetting = SalarySetting::all()->first();

        $departments = CompanyDepartment::all();
        $designations = CompanyDesignation::all();
        $jobTitles = JobTitle::all();

        // return $salaryIncomes->values()->all();
        // return $salaryIncomes;

        return view('structure.index', [
            'departments' => $departments,
            'designations' => $designations,
            'job_titles' => $jobTitles,
            'salary_incomes' => $salaryIncomes,
            'salary_setting' => $salarySetting,
        ]);
    }
}
