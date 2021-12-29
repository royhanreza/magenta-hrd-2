<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\CompanyDepartment;
use App\Models\CompanyDesignation;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\PaySlip;
use App\Models\ProvinceMinimumWage;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $departments = CompanyDepartment::all();
        $designations = CompanyDesignation::all();
        $jobTitles = JobTitle::all();
        $minimumWages = ProvinceMinimumWage::all();
        $payslips = PaySlip::with(['salaryIncomes', 'salaryDeductions'])->get();

        // return $payslips;
        // $lastCareer = Career::find(DB::table('careers')->where('employee_id', $employee_id)->max('id'));
        $activeCareer = Career::where('employee_id', $employee_id)->where('is_active', 1)->first();

        $activeCareerEffectiveDate = null;

        if ($activeCareer !== null) {
            $activeCareerEffectiveDate = Carbon::createFromFormat('Y-m-d', $activeCareer->effective_date)->addDays()->toDateString();
        }

        // Carbon::createFromFormat('Y-m-d H', '1975-05-21 22')->toDateTimeString();
        // return $lastCareer;

        // return $activeCareer = Career::where('employee_id', 5)->where('is_active', 1)->first();

        // return $activeCareerEffectiveDate->addDays()->toDateString();


        return view('career.create', [
            'employee' => $employee,
            'departments' => $departments,
            'designations' => $designations,
            'job_titles' => $jobTitles,
            'minimum_wages' => $minimumWages,
            'payslips' => $payslips,
            'effective_date' => $activeCareerEffectiveDate,
            'last_career' => $activeCareer,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $career = new Career;
        $career->employee_id = $request->employee_id;
        $career->employee_status = $request->employee_status;
        $career->type = $request->type;
        $career->department_id = $request->department;
        $career->designation_id = $request->designation;
        $career->job_title_id = $request->job_title;
        $career->golongan = $request->golongan;
        $career->effective_date = $request->effective_date;
        $career->end_of_employement_date = $request->end_of_employement_date;
        $career->end_of_employee_status_reminder = $request->end_of_employee_status_reminder;
        $career->province_minimum_wage_id = $request->minimum_wage;
        $career->tax_calculation_method = $request->tax_calculation_method;
        // pay_slips
        // pay_slips_id
        $payslips = collect($request->pay_slips)->mapWithKeys(function ($item, $key) {
            return [
                $item['id'] => [
                    'incomes' => json_encode($item['salary_incomes']),
                    'deductions' => json_encode($item['salary_deductions']),
                ]
            ];
        });


        try {
            $activeCareer = Career::where('employee_id', $request->employee_id)->where('is_active', 1)->first();
            if ($activeCareer !== null) {
                $activeCareer->is_active = 0;
                $activeCareer->save();
            }

            $career->save();
            $career->payslips()->attach($payslips);

            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $career,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $career = Career::findOrFail($id);
        $employee_id = $career->employee_id;
        $employee = Employee::findOrFail($employee_id);
        $departments = CompanyDepartment::all();
        $designations = CompanyDesignation::all();
        $jobTitles = JobTitle::all();
        $minimumWages = ProvinceMinimumWage::all();
        $payslips = PaySlip::with(['salaryIncomes', 'salaryDeductions'])->get();

        // return $payslips;
        // $lastCareer = Career::find(DB::table('careers')->where('employee_id', $employee_id)->max('id'));
        $activeCareer = Career::where('employee_id', $employee_id)->where('is_active', 1)->first();

        $activeCareerEffectiveDate = null;

        if ($activeCareer !== null) {
            $activeCareerEffectiveDate = Carbon::createFromFormat('Y-m-d', $activeCareer->effective_date)->addDays()->toDateString();
        }

        $selectedPayslips = $career->payslips->each(function ($item, $key) {
            $item['salary_incomes'] = json_decode($item->pivot->incomes);
            $item['salary_deductions'] = json_decode($item->pivot->deductions);
        })->map(function ($item, $key) {
            return collect($item)->except(['pivot']);
        });

        $checkedPayslips = collect($selectedPayslips)->map(function ($item, $key) {
            return $item['id'];
        })->all();

        $payslips = $payslips->map(function ($item, $key) use ($checkedPayslips, $selectedPayslips) {
            $exist = array_search($item->id, $checkedPayslips);
            if ($exist !== false) {
                return $selectedPayslips[$exist];
            }

            return $item;
        })->all();

        // return $payslips;

        // return $checkedPayslips;

        // return $selectedPayslips;
        // return [
        //     'SP' => $selectedPayslips,
        //     'P' => $payslips,
        // ];

        // Carbon::createFromFormat('Y-m-d H', '1975-05-21 22')->toDateTimeString();
        // return $lastCareer;

        // return $activeCareer = Career::where('employee_id', 5)->where('is_active', 1)->first();

        // return $activeCareerEffectiveDate->addDays()->toDateString();


        return view('career.edit', [
            'career' => $career,
            'selected_payslips' => $selectedPayslips,
            'checked_payslips' => json_encode($checkedPayslips),
            'employee' => $employee,
            'departments' => $departments,
            'designations' => $designations,
            'job_titles' => $jobTitles,
            'minimum_wages' => $minimumWages,
            'payslips' => json_encode($payslips),
            'effective_date' => $activeCareerEffectiveDate,
            'last_career' => $activeCareer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $career = Career::find($id);
        $career->employee_id = $request->employee_id;
        $career->employee_status = $request->employee_status;
        $career->type = $request->type;
        $career->department_id = $request->department;
        $career->designation_id = $request->designation;
        $career->job_title_id = $request->job_title;
        $career->golongan = $request->golongan;
        $career->effective_date = $request->effective_date;
        $career->end_of_employement_date = $request->end_of_employement_date;
        $career->end_of_employee_status_reminder = $request->end_of_employee_status_reminder;
        $career->province_minimum_wage_id = $request->minimum_wage;
        $career->tax_calculation_method = $request->tax_calculation_method;
        // pay_slips
        // pay_slips_id
        $payslips = collect($request->pay_slips)->mapWithKeys(function ($item, $key) {
            return [
                $item['id'] => [
                    'incomes' => json_encode($item['salary_incomes']),
                    'deductions' => json_encode($item['salary_deductions']),
                ]
            ];
        });

        try {
            $career->payslips()->detach();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => 'Error when detaching payslips'
            ], 500);
        }


        try {
            // $activeCareer = Career::where('employee_id', $request->employee_id)->where('is_active', 1)->first();
            // if ($activeCareer !== null) {
            //     $activeCareer->is_active = 0;
            //     $activeCareer->save();
            // }

            $career->save();
            $career->payslips()->attach($payslips);

            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
                'data' => $career,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $career = Career::find($id);
        try {
            $career->payslips()->detach();
            $career->delete();
            return [
                'message' => 'data has been deleted',
                'error' => false,
                'code' => 200,
            ];
        } catch (Exception $e) {
            return [
                'message' => 'internal error',
                'error' => true,
                'code' => 500,
                'errors' => $e,
            ];
        }
    }
}
