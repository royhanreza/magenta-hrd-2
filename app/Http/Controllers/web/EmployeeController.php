<?php

namespace App\Http\Controllers\web;

use App\Exports\EmployeesExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Career;
use App\Models\Company;
use App\Models\CompanyDepartment;
use App\Models\CompanyDesignation;
use App\Models\CompanyLocation;
use App\Models\CompanyNpwp;
use App\Models\Employee;
use App\Models\EmployeeBpjs;
use App\Models\EmployeeFile;
use App\Models\EmployeeNpwp;
use App\Models\FinalPayslip;
use App\Models\JobTitle;
use App\Models\Leave;
use App\Models\LeaveSetting;
use App\Models\Loan;
use App\Models\OfficeShift;
use App\Models\Role;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::with(['careers' => function ($query) {
            $query->with(['designation', 'department', 'jobTitle'])->orderByDesc('effective_date');
        }])->paginate(10);
        // $active_employees = $employees->filter(function ($value, $key) {
        //     return $value > 2;
        // });
        $all_employees = Employee::all();
        $active_employees = $all_employees->filter(function ($item, $key) {
            return $item->is_active == 1;
        });
        $inactive_employees = $all_employees->filter(function ($item, $key) {
            return $item->is_active == 0;
        });
        // return $employees;

        return view('employee.index', [
            'employees' => $employees,
            'active_employees' => $active_employees,
            'inactive_employees' => $inactive_employees,
        ]);
    }

    public function search(Request $request)
    {

        // Get the search value from the request
        $search = $request->input('keyword');

        // Search in the title and body columns from the posts table
        $employees = Employee::query()
            ->with(['careers' => function ($query) {
                $query->with(['designation', 'department', 'jobTitle'])->orderByDesc('effective_date');
            }])
            ->where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('employee_id', 'LIKE', "%{$search}%")
            ->paginate(10);

        $all_employees = Employee::all();
        $active_employees = $all_employees->filter(function ($item, $key) {
            return $item->is_active == 1;
        });
        $inactive_employees = $all_employees->filter(function ($item, $key) {
            return $item->is_active == 0;
        });

        // Return the search view with the resluts compacted
        // return $employees;
        return view('employee.search', [
            'employees' => $employees,
            'active_employees' => $active_employees,
            'inactive_employees' => $inactive_employees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee = Employee::where('created_at', 'like', date('Y-m-d') . "%")->get();
        $counter = sprintf("%03d", count($employee) + 1);
        $employee_id = 'EP-' . date('d') . date('m') . date('Y') . '-' . $counter;

        $maxIdEmployee = Employee::find(DB::table('employees')->max('id'));

        $maxId = 0;
        if ($maxIdEmployee !== null) {
            $maxId = $maxIdEmployee->id;
        }

        // $companies = Company::all();
        // $companies_final = [['id' => '', 'text' => 'Choose Company']];
        // foreach ($companies as $company) {
        //     array_push($companies_final, ['id' => $company->id, 'text' => $company->name]);
        // }

        $locations = CompanyLocation::all();


        return view('employee.create', [
            'employee_id' => $employee_id,
            'locations' => $locations,
            'max_id' => str_pad(($maxId + 1), 4, '0', STR_PAD_LEFT),
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
        // return $request->all();
        $employee = new Employee;
        // $employee->company_id = $request->company_id;
        // $employee->department_id = $request->department_id;
        // $employee->last_name = $request->last_name;
        // $employee->designation_id = $request->designation_id;
        // $employee->company_location_id = $request->location_id;
        // $employee->office_shift_id = $request->office_shift_id;
        // $employee->report_to = $request->report_to;
        // $employee->leave_id = $request->leave_id;
        $employee->employee_id = $request->employee_id;
        $employee->first_name = $request->first_name;
        $employee->place_of_birth = $request->place_of_birth;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->gender = $request->gender;
        $employee->contact_number = $request->contact_number;
        $employee->work_placement = $request->work_placement;
        $employee->type = $request->type;
        $employee->payslip_permission = $request->payslip_permission;
        $employee->company_location_id = $request->office_location;
        $employee->address = $request->address;
        $employee->email = $request->email;
        $employee->start_work_date = $request->start_work_date;
        $employee->citizenship = $request->citizenship;
        $employee->citizenship_country = $request->citizenship_country;
        $employee->identity_type = $request->identity_type;
        $employee->identity_number = $request->identity_number;
        $employee->identity_expire_date = $request->identity_expire_date;
        $employee->marital_status = $request->marital_status;
        $employee->religion = $request->religion;
        $employee->blood_type = $request->blood_type;
        $employee->last_education = $request->last_education;
        $employee->last_education_name = $request->last_education_name;
        $employee->study_program = $request->study_program;
        $employee->emergency_contact_name = $request->emergency_contact_name;
        $employee->emergency_contact_relation = $request->emergency_contact_relation;
        $employee->emergency_contact_number = $request->emergency_contact_number;
        $employee->bank_account_name = $request->bank_account_name;
        $employee->bank_account_owner = $request->bank_account_owner;
        $employee->bank_account_number = $request->bank_account_number;
        $employee->bank_account_branch = $request->bank_account_branch;
        $employee->is_active = $request->is_active;
        $employee->is_active_account = $request->is_active_account;
        // $employee->username = $request->username;
        // $employee->password = !is_null($request->password) ? Hash::make($request->password) : null;
        // $employee->pin = $request->ppin;
        // $employee->role_id = $request->role_id;
        // $employee->has_mobile_access = $request->has_mobile_access;
        // $employee->mobile_access_type = $request->mobile_access_type;
        // $photo = $request->file('photo')->store('avatars');
        // $file_name = time() . "_" . $photo->getClientOriginalName();
        // $photo->move(public_path('images'), $file_name);
        $employee->photo = 'photos/default-photo.png';

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = 'photos/' . time() . '-' . $photo->getClientOriginalName();
            Storage::disk('s3')->put($photoPath, file_get_contents($photo));
            $employee->photo = $photoPath;
        }
        // $employee->photo = $uploadPath;

        // $usernameExist =  Employee::where('username', $request->username)->where('username', '!=', null)->first();

        $existCredentialErrors = [];

        if ($request->email !== '' || $request->email !== null) {
            $emailExist =  Employee::where('email', $request->email)->where('email', '!=', null)->first();

            if ($emailExist) {
                array_push($existCredentialErrors, ['field' => 'email', 'error_message' => 'Email already used']);
            }
        }


        // if ($usernameExist) {
        //     array_push($existCredentialErrors, ['field' => 'username', 'error_message' => 'Username already used']);
        // }

        if (count($existCredentialErrors) > 0) {
            return response()->json([
                'message' => 'Validation errors',
                'error' => true,
                'code' => 400,
                'error_type' => 'exist_credential',
                'errors' => $existCredentialErrors
            ], 400);
        } else {
            try {
                $employee->save();
                // return response()->json([
                //     'message' => 'Data has been saved',
                //     'error' => true,
                //     'code' => 200,
                // ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Internal Error',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e
                ], 500);
            }
        }

        $leaveSetting = LeaveSetting::all()->first();

        if ($leaveSetting == null) {
            $employee->delete();

            return response()->json([
                'message' => 'Internal Error, Setting not found',
                'error' => true,
                'code' => 500,
            ], 500);
        }

        try {
            $employeeId = $employee->id;
            $npwp = new EmployeeNpwp;
            $npwp->employee_id = $employeeId;
            $npwp->save();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        // try {
        //     $employeeId = $employee->id;
        //     $bpjs = new EmployeeBpjs;
        //     $bpjs->employee_id = $employeeId;
        //     $bpjs->save();
        // } catch (Exception $e) {

        //     $employee->delete();

        //     return response()->json([
        //         'message' => 'Internal Error',
        //         'error' => true,
        //         'code' => 500,
        //         'errors' => $e
        //     ], 500);
        // }

        try {
            $employeeId = $employee->id;
            $bpjs = new EmployeeBpjs;
            $bpjs->employee_id = $employeeId;
            $bpjs->save();
        } catch (Exception $e) {

            $employee->delete();

            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        $monthDiff = Carbon::parse($employee->start_work_date)->diffInMonths(date("Y-m-d"));

        try {

            $leave = new Leave;
            $employeeId = $employee->id;
            $leave->employee_id = $employeeId;

            $leave->employee_id = $employeeId;
            $leave->start_date = date("2021-01-01");
            $leave->end_date = date("2021-12-31");
            $leave->total_leave = 0;
            $leave->taken_leave = 0;
            $leave->total_carry_forward = 0;
            $leave->taken_carry_forward = 0;

            if ($monthDiff >= $leaveSetting->after_month_work_months_number) {
                $leave->total_leave = $leaveSetting->single_plafond_max_day;
            }

            $leave->save();
        } catch (Exception $e) {

            $employee->delete();

            return response()->json([
                'message' => 'Internal Error',
                'error' => true,
                'code' => 500,
                'errors' => $e
            ], 500);
        }

        return response()->json([
            'message' => 'Data has been saved',
            'error' => true,
            'code' => 200,
        ]);



        // try {
        //     $employee->save();
        //     return response()->json([
        //         'message' => 'Data has been saved',
        //         'error' => true,
        //         'code' => 200,
        //     ]);
        // } catch (Exception $e) {
        //     return response()->json([
        //         'message' => 'Internal Error',
        //         'error' => true,
        //         'code' => 500,
        //         'errors' => $e
        //     ], 500);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));
        $companyNpwps = CompanyNpwp::all();
        $employeeNpwp = EmployeeNpwp::firstOrCreate(
            ['employee_id' => $id]
        );
        $employeeBpjs = EmployeeBpjs::firstOrCreate(
            ['employee_id' => $id]
        );

        $employeeFiles = EmployeeFile::where('employee_id', $id)->get()->map(function ($file) {
            $file->url = Storage::disk("s3")->url($file->path);
            $explodedPath = explode('.', $file->path);
            $file->extension = $explodedPath[count($explodedPath) - 1];
            return $file;
        });

        // return $employeeNpwp;
        return view('employee.v2.show', [
            'employee' => $employee,
            'last_career' => $lastCareer,
            'company_npwps' => $companyNpwps,
            'employee_npwp' => $employeeNpwp,
            'employee_bpjs' => $employeeBpjs,
            'employee_files' => $employeeFiles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        // Company Select2 Options
        // $companies = Company::all();
        // $companies_final = [['id' => '', 'text' => 'Choose Company']];
        // foreach ($companies as $company) {
        //     array_push($companies_final, ['id' => $company->id, 'text' => $company->name]);
        // }

        // $company_id = $employee->designation->department->company->id;
        // $company = Company::find($company_id)->with(['departments', 'locations', 'roles', 'officeShifts'])->first();


        // // Department Select2 Options
        // $departments = $company->departments;
        // $departments_final = [['id' => '', 'text' => 'Choose Department']];
        // foreach ($departments as $department) {
        //     array_push($departments_final, ['id' => $department->id, 'text' => $department->name]);
        // }

        // // Department Select2 Options
        // $locations = $company->locations;
        // $locations_final = [['id' => '', 'text' => 'Choose Location']];
        // foreach ($locations as $location) {
        //     array_push($locations_final, ['id' => $location->id, 'text' => $location->location_name]);
        // }

        // // Role Select2 Options
        // $roles = $company->roles;
        // $roles_final = [['id' => '', 'text' => 'Choose Role']];
        // foreach ($roles as $role) {
        //     array_push($roles_final, ['id' => $role->id, 'text' => $role->name]);
        // }

        // // Office Shift Select2 Options
        // $shifts = $company->officeShifts;
        // $shifts_final = [['id' => '', 'text' => 'Choose Role']];
        // foreach ($shifts as $shift) {
        //     array_push($shifts_final, ['id' => $shift->id, 'text' => $shift->name]);
        // }

        // // Role Select2 Options
        // $department_id = $employee->designation->department->id;
        // $department = CompanyDepartment::find($department_id)->with(['designations'])->first();

        // $designations = $department->designations;
        // $designations_final = [['id' => '', 'text' => 'Choose Role']];
        // foreach ($designations as $designation) {
        //     array_push($designations_final, ['id' => $designation->id, 'text' => $designation->name]);
        // }
        $locations = CompanyLocation::all();

        return view('employee.edit', [
            'employee' => $employee,
            'locations' => $locations,
            // 'companies' => json_encode($companies_final),
            // 'departments' => json_encode($departments_final),
            // 'locations' => json_encode($locations_final),
            // 'roles' => json_encode($roles_final),
            // 'designations' => json_encode($designations_final), 'shifts' => json_encode($shifts_final)
        ]);

        // return $designations_final;
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
        $employee = Employee::find($id);
        // $employee->employee_id = $request->employee_id;
        // $employee->first_name = $request->first_name;
        // $employee->last_name = $request->last_name;
        // $employee->company_location_id = $request->location_id;
        // $employee->designation_id = $request->designation_id;
        // $employee->date_of_birth = $request->date_of_birth;
        // $employee->gender = $request->gender;
        // $employee->contact_number = $request->contact_number;
        // $employee->office_shift_id = $request->office_shift_id;
        // $employee->report_to = $request->report_to;
        // $employee->leave_id = $request->leave_id;
        // $employee->work_placement = $request->work_placement;
        // $employee->type = $request->type;
        // $employee->address = $request->address;
        // $employee->email = $request->email;
        // $employee->username = $request->username;
        // $employee->password = !is_null($request->password) ? Hash::make($request->password) : null;
        // $employee->pin = $request->pin;
        // $employee->role_id = $request->role_id;
        $employee->employee_id = $request->employee_id;
        $employee->first_name = $request->first_name;
        $employee->place_of_birth = $request->place_of_birth;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->gender = $request->gender;
        $employee->contact_number = $request->contact_number;
        $employee->work_placement = $request->work_placement;
        $employee->type = $request->type;
        $employee->payslip_permission = $request->payslip_permission;
        $employee->company_location_id = $request->office_location;
        $employee->address = $request->address;
        $employee->email = $request->email;
        $employee->start_work_date = $request->start_work_date;
        $employee->citizenship = $request->citizenship;
        $employee->citizenship_country = $request->citizenship_country;
        $employee->identity_type = $request->identity_type;
        $employee->identity_number = $request->identity_number;
        $employee->identity_expire_date = $request->identity_expire_date;
        $employee->marital_status = $request->marital_status;
        $employee->religion = $request->religion;
        $employee->blood_type = $request->blood_type;
        $employee->last_education = $request->last_education;
        $employee->last_education_name = $request->last_education_name;
        $employee->study_program = $request->study_program;
        $employee->emergency_contact_name = $request->emergency_contact_name;
        $employee->emergency_contact_relation = $request->emergency_contact_relation;
        $employee->emergency_contact_number = $request->emergency_contact_number;
        $employee->bank_account_name = $request->bank_account_name;
        $employee->bank_account_owner = $request->bank_account_owner;
        $employee->bank_account_number = $request->bank_account_number;
        $employee->bank_account_branch = $request->bank_account_branch;
        $employee->is_active = $request->is_active;
        $employee->is_active_account = $request->is_active_account;

        if ($request->hasFile('photo')) {
            Storage::disk('s3')->delete($employee->photo);
            $photo = $request->file('photo');
            $photoPath = 'photos/' . time() . '-' . $photo->getClientOriginalName();
            Storage::disk('s3')->put($photoPath, file_get_contents($photo));
            $employee->photo = $photoPath;
        }

        // $existCredentialErrors = [];


        // $emailExist =  Employee::where('email', $request->email)->whereNotIn('id', [$id])->first();

        // if ($emailExist) {
        //     array_push($existCredentialErrors, ['field' => 'email', 'error_message' => 'Email already used']);
        // }

        $existCredentialErrors = [];

        $usernameExist =  Employee::where('username', $request->username)->whereNotIn('id', [$id])->first();


        if ($request->email !== '' && $request->email !== null) {
            $emailExist =  Employee::where('email', $request->email)->whereNotIn('id', [$id])->first();

            if ($emailExist) {
                array_push($existCredentialErrors, ['field' => 'email', 'error_message' => 'Email already used']);
            }
        }

        // if ($usernameExist) {
        //     array_push($existCredentialErrors, ['field' => 'username', 'error_message' => 'Username already used']);
        // }

        if (count($existCredentialErrors) > 0) {
            return response()->json([
                'message' => 'Validation errors',
                'error' => true,
                'code' => 400,
                'error_type' => 'exist_credential',
                'errors' => $existCredentialErrors
            ], 400);
        } else {
            try {
                $employee->save();
                return response()->json([
                    'message' => 'Data has been saved',
                    'error' => true,
                    'code' => 200,
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        try {
            $employee->delete();
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

    public function career($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));
        $careers = Career::with(['payslips', 'department', 'designation', 'jobTitle'])->where('employee_id', $id)->orderByDesc('effective_date')->get();

        return view('employee.career', ['careers' => $careers, 'employee' => $employee, 'last_career' => $lastCareer]);
    }

    public function payslip($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));
        // $career = Career::with(['payslips'])->where('employee_id', $id)->first();

        // if ($career !== null) {
        //     collect($career->payslips)->each(function ($item, $key) {
        //         // return json_decode($item['pivot']['incomes']);
        //         $item['incomes'] = json_decode($item['pivot']['incomes']);
        //         $item['deductions'] = json_decode($item['pivot']['deductions']);
        //         $item['pivot']['incomes'] = '';
        //         $item['pivot']['deductions'] = '';
        //     });
        // } else {
        //     $career = (object) ['payslips' => []];
        //     // $career = collect($career)->all();
        // }

        // return $career;
        // if ($career !== null) {
        //     collect($career->payslips)->each(function ($item, $key) {
        //         // return json_decode($item['pivot']['incomes']);
        //         $item['incomes'] = json_decode($item['pivot']['incomes']);
        //         $item['deductions'] = json_decode($item['pivot']['deductions']);
        //         $item['pivot']['incomes'] = '';
        //         $item['pivot']['deductions'] = '';
        //     });
        // } else {
        //     return redirect('/career/create/' . $id)->with('message', 'Employee');
        // }

        // return $career;
        $finalPayslips = $employee->finalPayslips->each(function ($item, $key) {
            if ($item->type == 'custom_period') {
                $incomes = json_decode($item->income);
                $takeHomePay = collect($incomes)->where('attendance', '!==', null)->map(function ($income, $key) {
                    return $income->attendance->daily_money + $income->attendance->overtime_pay;
                    // return $income;
                })->sum();
                $item['take_home_pay'] = $takeHomePay;
            }
        });

        // return $finalPayslips;

        return view('employee.payslip', ['employee' => $employee,  'last_career' => $lastCareer, 'final_payslips' => $finalPayslips]);
    }

    public function loan($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));

        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id', 'photo'];

        $loans = Loan::with(['employee' => function ($query) use ($employeeColumns) {
            $query->select($employeeColumns);
        }, 'finalPayslip'])
            ->where('employee_id', $id)
            ->orderBy('payslip_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // $totalLoan = $loans->where('type', 'loan')->sum('amount');
        // $totalPayment = $loans->where('type', 'payment')->sum('amount');
        $finalPayslips = FinalPayslip::where('employee_id', $id)->where('type', 'fix_period')->get();

        $totalLoan = $finalPayslips->flatMap(function ($payslip) {
            $incomes = json_decode($payslip->income);
            // $deductions = json_decode($payslip->deduction);
            // return [
            //     'incomes' => $incomes,
            //     'deductions' => $deductions,
            // ];
            // return $payslip;
            // return array_merge($incomes, $deductions);
            return $incomes;
        })->where('is_loan', 1)->values()
            ->sum('value');

        $totalPayment = $finalPayslips->flatMap(function ($payslip) {
            $deductions = json_decode($payslip->deduction);
            return $deductions;
        })->where('is_loan', 1)->values()
            ->sum('value');

        // return $loans;

        return view('employee.v2.loan', [
            'loans' => $loans,
            'employee' => $employee,
            'last_career' => $lastCareer,
            'total_loan' => $totalLoan,
            'total_payment' => $totalPayment,
        ]);
    }

    public function account($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));
        $roles = Role::all()->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });

        $divisions = CompanyDesignation::all()->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });;

        // return $roles;

        return view('employee.account', [
            'employee' => $employee,
            'roles' => $roles,
            'last_career' => $lastCareer,
            'divisions' => $divisions,
        ]);
    }

    public function editAccount(Request $request, $id)
    {
        $employee = Employee::find($id);
        $employee->username = $request->username;
        if (!is_null($request->password)) {
            $employee->password = Hash::make($request->password);
        }
        // $employee->password = !is_null($request->password) ? Hash::make($request->password) : null;
        $employee->pin = $request->pin;
        $employee->role_id = $request->role_id;
        $employee->has_mobile_access = $request->has_mobile_access;
        $employee->mobile_access_type = $request->mobile_access_type;
        $employee->supervisor_access = $request->supervisor_access;
        $employee->accessible_divisions = $request->accessible_divisions;

        $emailExist =  Employee::where('email', $request->email)->whereNotIn('id', [$id])->first();
        $usernameExist =  Employee::where('username', $request->username)->whereNotIn('id', [$id])->first();

        $existCredentialErrors = [];
        // if ($emailExist) {
        //     array_push($existCredentialErrors, ['field' => 'email', 'error_message' => 'Email already used']);
        // }

        // if ($usernameExist) {
        //     array_push($existCredentialErrors, ['field' => 'username', 'error_message' => 'Username already used']);
        // }

        if (count($existCredentialErrors) > 0) {
            return response()->json([
                'message' => 'Validation errors',
                'error' => true,
                'code' => 400,
                'error_type' => 'exist_credential',
                'errors' => $existCredentialErrors
            ], 400);
        }

        try {
            $employee->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
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

    public function editAccountStatus(Request $request, $id)
    {
        $employee = Employee::find($id);
        $employee->is_active_account = $request->is_active_account;

        try {
            $employee->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
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

    public function editSalarySetting(Request $request, $id)
    {
        $employee = Employee::find($id);
        $employee->daily_money_regular = $request->daily_money_regular;
        $employee->daily_money_holiday = $request->daily_money_holiday;
        $employee->overtime_pay_regular = $request->overtime_pay_regular;
        $employee->overtime_pay_holiday = $request->overtime_pay_holiday;

        try {
            $employee->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
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

    public function officeShift($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));

        // return $shift = OfficeShift::query()
        //     ->where('employee_id', $id)
        //     ->where('is_active', 1)
        //     ->first();
        $officeShifts = OfficeShift::all();
        $shift = $employee->officeShifts()->where('is_active', 1)->first();

        // return $shift;

        return view('employee.shift', [
            'employee' => $employee,
            'last_career' => $lastCareer,
            'shift' => $shift,
            'office_shifts' => $officeShifts,
        ]);
    }

    public function sick($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));

        // return $shift = OfficeShift::query()
        //     ->where('employee_id', $id)
        //     ->where('is_active', 1)
        //     ->first();
        $sickSubmissions = $employee->sickSubmissions;
        // $shift = $employee->officeShifts()->where('is_active', 1)->first();

        // return $shift;

        return view('employee.sick', [
            'employee' => $employee,
            'last_career' => $lastCareer,
            // 'shift' => $shift,
            'sick_submissions' => $sickSubmissions,
        ]);
    }

    public function permission($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));

        // return $shift = OfficeShift::query()
        //     ->where('employee_id', $id)
        //     ->where('is_active', 1)
        //     ->first();
        $permissionSubmissions = $employee->permissionSubmissions;
        // $shift = $employee->officeShifts()->where('is_active', 1)->first();

        // return $shift;

        return view('employee.permission', [
            'employee' => $employee,
            'last_career' => $lastCareer,
            // 'shift' => $shift,
            'permissions' => $permissionSubmissions,
        ]);
    }

    public function leave($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));

        // return $shift = OfficeShift::query()
        //     ->where('employee_id', $id)
        //     ->where('is_active', 1)
        //     ->first();
        $leaveSubmissions = $employee->leaveSubmissions;
        // $shift = $employee->officeShifts()->where('is_active', 1)->first();

        // return $shift;

        return view('employee.leave', [
            'employee' => $employee,
            'last_career' => $lastCareer,
            // 'shift' => $shift,
            'leave_submissions' => $leaveSubmissions,
        ]);
    }

    public function setting($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));

        return view('employee.setting', ['employee' => $employee, 'last_career' => $lastCareer]);
    }

    public function settingBpjs($id)
    {
        $employee = Employee::findOrFail($id);
        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));

        $employeeBpjs = EmployeeBpjs::firstOrCreate(
            ['employee_id' => $id]
        );

        return view('employee.settings.bpjs', [
            'employee' => $employee,
            'last_career' => $lastCareer,
            'employee_bpjs' => $employeeBpjs
        ]);
    }

    public function attendance($id)
    {
        $employee = Employee::findOrFail($id);

        $date1 = date("Y-m-01");
        $date2 = date("Y-m-t");
        $attendances = $this->getAttendance($date1, $date2, $id)['attendances'];

        $attendancesKeys = collect($this->getAttendance($date1, $date2, $id)['attendances'])->map(function ($item, $key) {
            return $key;
        })->all();
        $attendanceSummary = $this->getAttendance($date1, $date2, $id)['summary'];
        $pendingAttendances = $this->getAttendance($date1, $date2, $id)['pending_attendances'];

        // return $attendances;

        $period = collect($this->getDatesFromRange($date1, $date2))->map(function ($item, $key) use ($attendancesKeys, $attendances) {
            $searchResult = array_search($item, $attendancesKeys);
            if ($searchResult !== false) {
                return [
                    'date' => $item,
                    'attendance' => $attendances[$searchResult],
                ];
            }

            return [
                'date' => $item,
                'attendance' => null
            ];
        });

        // return $period;

        // return [
        //     'period' => $period,
        //     'summary' => $attendanceSummary,
        //     'pending_attendances' => $pendingAttendances
        // ];

        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));


        return view('employee.attendance', [
            'period' => $period,
            'summary' => $attendanceSummary,
            'pending_attendances' => $pendingAttendances,
            'employee' => $employee,
            'last_career' => $lastCareer,
        ]);
    }

    public function attendanceByDate($id, $date_1, $date_2)
    {

        $employee = Employee::findOrFail($id);

        $date1 = implode("-", array_reverse(explode("-", $date_1)));
        $date2 = implode("-", array_reverse(explode("-", $date_2)));

        $attendances = $this->getAttendance($date1, $date2, $id)['attendances'];

        $attendancesKeys = collect($this->getAttendance($date1, $date2, $id)['attendances'])->map(function ($item, $key) {
            return $key;
        })->all();
        $attendanceSummary = $this->getAttendance($date1, $date2, $id)['summary'];
        $pendingAttendances = $this->getAttendance($date1, $date2, $id)['pending_attendances'];

        // return $attendances;

        $period = collect($this->getDatesFromRange($date1, $date2))->map(function ($item, $key) use ($attendancesKeys, $attendances) {
            $searchResult = array_search($item, $attendancesKeys);
            if ($searchResult !== false) {
                return [
                    'date' => $item,
                    'attendance' => $attendances[$searchResult],
                ];
            }

            return [
                'date' => $item,
                'attendance' => null
            ];
        });

        // return $date1;

        $lastCareer = Career::with(['designation', 'department', 'jobTitle'])->find(DB::table('careers')->where('employee_id', $id)->max('id'));

        return view('employee.attendancebydate', [
            'date1' => $date_1,
            'date2' => $date_2,
            'period' => $period,
            'summary' => $attendanceSummary,
            'pending_attendances' => $pendingAttendances,
            'employee' => $employee,
            'last_career' => $lastCareer
        ]);
    }


    private function getAttendance($date1, $date2, $employee_id = null)
    {
        $employeeColumns = ['id', 'employee_id', 'first_name', 'last_name', 'designation_id', 'photo'];

        $attendances = Attendance::query()
            ->where('employee_id', $employee_id)
            ->where('date', '>=', $date1)
            ->where('date', '<=', $date2)
            ->get()->sortBy('date')->groupBy('date')->map(function ($item, $key) {
                // $item['status'] = null;
                // $item['pending_category'] = null;
                // $item['clock_in'] = null;
                // $item['clock_out'] = null;
                // $item['note'] = null;
                // $item['images'] = [];
                $status = null;
                $pendingCategory = null;
                $clockIn = null;
                $clockOut = null;
                $note = null;
                $images = [];

                // foreach ($item as $att) {
                //     if ($att->clock_in !== null) {
                //         $item['checkin'] = $att;
                //     } else {
                //         $item['checkout'] = $att;
                //     }
                // }


                foreach ($item as $att) {
                    $note = $att->note;
                    if ($att->image || isset($att->image)) {
                        $images = array_merge($images, [$att->image]);
                    }
                    if ($att->category == 'present') {
                        if ($att->status == 'approved') {
                            $status = 'present';
                        } else if ($att->status == 'pending') {
                            $status = 'pending';
                            $pendingCategory = 'Hadir';
                        } else {
                            $status = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'sick') {
                        if ($att->status == 'approved') {
                            $status = 'sick';
                        } else if ($att->status == 'pending') {
                            $status = 'pending';
                            $pendingCategory = 'Sakit';
                        } else {
                            $status = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'permission') {
                        if ($att->status == 'approved') {
                            $status = 'permission';
                        } else if ($att->status == 'pending') {
                            $status = 'pending';
                            $pendingCategory = 'Izin';
                        } else {
                            $status = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    } else if ($att->category == 'leave') {
                        if ($att->status == 'approved') {
                            $status = 'leave';
                        } else if ($att->status == 'pending') {
                            $status = 'pending';
                            $pendingCategory = 'Cuti';
                        } else {
                            $status = 'rejected';
                        }
                        if ($att->type == 'check in') {
                            $clockIn = date_format(date_create($att->clock_in), "H:i:s");
                        } else if ($att->type == 'check out') {
                            $clockOut = date_format(date_create($att->clock_out), "H:i:s");
                        }
                    }
                }

                // return $item['checkin'];

                return [
                    'status' => $status,
                    'pending_category' => $pendingCategory,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'note' => $note,
                    'images' => $images,
                ];
            })->all();

        $attendanceSummary = [
            'sick_count' => DB::table('attendances')->where('category', 'sick')->where('date', '>=', $date1)->where('date', '<=', $date2)->where('employee_id', $employee_id)->where('status', 'approved')->count(),
            'present_count' => DB::table('attendances')->where('category', 'present')->where('date', '>=', $date1)->where('date', '<=', $date2)->where('employee_id', $employee_id)->where('type', 'check in')->where('status', 'approved')->count(),
            'permission_count' => DB::table('attendances')->where('category', 'permission')->where('date', '>=', $date1)->where('date', '<=', $date2)->where('employee_id', $employee_id)->where('status', 'approved')->count(),
            'leave_count' => DB::table('attendances')->where('category', 'leave')->where('date', '>=', $date1)->where('date', '<=', $date2)->where('employee_id', $employee_id)->where('status', 'approved')->count(),
            'rejected_count' => DB::table('attendances')->where('date', '>=', $date1)->where('date', '<=', $date2)->where('employee_id', $employee_id)->where('status', 'rejected')->groupBy('employee_id')->count(),
            'pending_count' => DB::table('attendances')->where('date', '>=', $date1)->where('date', '<=', $date2)->where('employee_id', $employee_id)->where('status', 'pending')->groupBy('employee_id')->count(),
        ];

        // $attendances->each(function ($item, $key) {
        //     $item['status'] = null;
        //     $item['pending_category'] = null;
        //     $item['clock_in'] = null;
        //     $item['clock_out'] = null;
        //     $item['note'] = null;
        //     $item['images'] = [];

        //     if (count($item->attendances) > 0) {
        //         foreach ($item->attendances as $att) {
        //             $item['note'] = $att->note;
        //             if ($att->image || isset($att->image)) {
        //                 $item['images'] = array_merge($item['images'], [$att->image]);
        //             }
        //             if ($att->category == 'present') {
        //                 if ($att->status == 'approved') {
        //                     $item['status'] = 'present';
        //                 } else if ($att->status == 'pending') {
        //                     $item['status'] = 'pending';
        //                     $item['pending_category'] = 'Hadir';
        //                 } else {
        //                     $item['status'] = 'rejected';
        //                 }
        //                 if ($att->type == 'check in') {
        //                     $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
        //                 } else if ($att->type == 'check out') {
        //                     $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
        //                 }
        //             } else if ($att->category == 'sick') {
        //                 if ($att->status == 'approved') {
        //                     $item['status'] = 'sick';
        //                 } else if ($att->status == 'pending') {
        //                     $item['status'] = 'pending';
        //                     $item['pending_category'] = 'Sakit';
        //                 } else {
        //                     $item['status'] = 'rejected';
        //                 }
        //                 if ($att->type == 'check in') {
        //                     $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
        //                 } else if ($att->type == 'check out') {
        //                     $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
        //                 }
        //             } else if ($att->category == 'permission') {
        //                 if ($att->status == 'approved') {
        //                     $item['status'] = 'permission';
        //                 } else if ($att->status == 'pending') {
        //                     $item['status'] = 'pending';
        //                     $item['pending_category'] = 'Izin';
        //                 } else {
        //                     $item['status'] = 'rejected';
        //                 }
        //                 if ($att->type == 'check in') {
        //                     $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
        //                 } else if ($att->type == 'check out') {
        //                     $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
        //                 }
        //             } else if ($att->category == 'leave') {
        //                 if ($att->status == 'approved') {
        //                     $item['status'] = 'leave';
        //                 } else if ($att->status == 'pending') {
        //                     $item['status'] = 'pending';
        //                     $item['pending_category'] = 'Cuti';
        //                 } else {
        //                     $item['status'] = 'rejected';
        //                 }
        //                 if ($att->type == 'check in') {
        //                     $item['clock_in'] = date_format(date_create($att->clock_in), "H:i:s");
        //                 } else if ($att->type == 'check out') {
        //                     $item['clock_out'] = date_format(date_create($att->clock_out), "H:i:s");
        //                 }
        //             }
        //         }
        //     }
        // });

        $pendingAttendances = Attendance::query()->where('employee_id', $employee_id)->where('date', '>=', $date1)->where('date', '<=', $date2)->where('employee_id', $employee_id)->where('status', 'pending')->get();

        return [
            'attendances' => $attendances,
            'summary' => $attendanceSummary,
            'pending_attendances' => $pendingAttendances
        ];
    }

    public function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach ($period as $date) {
            $array[] = $date->format($format);
        }

        return $array;
    }

    public function editShift(Request $request, $id)
    {
        $employee = Employee::find($id);

        $activeShift = $employee->officeShifts()->where('is_active', 1)->first();

        $shiftId = $request->office_shift;

        if ($activeShift !== null) {
            try {
                $employee->officeShifts()->updateExistingPivot($activeShift->id, [
                    'is_active' => 0,
                ]);
                // $company->save();
                // return response()->json([
                //     'message' => 'Data has been saved',
                //     'error' => true,
                //     'code' => 200,
                // ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => '[Internal Error] Error while inactiving current shift',
                    'error' => true,
                    'code' => 500,
                    'errors' => $e
                ], 500);
            }
        }

        try {
            $employee->officeShifts()->attach($shiftId);
            // $company->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => true,
                'code' => 200,
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

    public function editNpwp(Request $request, $id)
    {
        $npwp = EmployeeNpwp::find($id);
        $npwp->number = $request->number;
        $npwp->effective_date = $request->effective_date;
        $npwp->company_npwp_id = $request->company_npwp_id;
        $npwp->type = $request->type;

        try {
            $npwp->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
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

    public function editBpjs(Request $request, $id)
    {
        $bpjs = EmployeeBpjs::find($id);
        $bpjs->bpjs_ketenagakerjaan_number = $request->bpjs_ketenagakerjaan_number;
        $bpjs->bpjs_ketenagakerjaan_effective_date = $request->bpjs_ketenagakerjaan_effective_date;
        $bpjs->bpjs_kesehatan_number = $request->bpjs_kesehatan_number;
        $bpjs->bpjs_kesehatan_effective_date = $request->bpjs_kesehatan_effective_date;

        try {
            $bpjs->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
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

    public function editBpjsValue(Request $request, $id)
    {
        $bpjs = EmployeeBpjs::find($id);
        $bpjs->wage = $request->wage;
        $bpjs->jkk_company_percentage = $request->jkk_company_percentage;
        $bpjs->jkk_personal_percentage = $request->jkk_personal_percentage;
        $bpjs->jkm_company_percentage = $request->jkm_company_percentage;
        $bpjs->jkm_personal_percentage = $request->jkm_personal_percentage;
        $bpjs->jht_company_percentage = $request->jht_company_percentage;
        $bpjs->jht_personal_percentage = $request->jht_personal_percentage;
        $bpjs->jp_company_percentage = $request->jp_company_percentage;
        $bpjs->jp_personal_percentage = $request->jp_personal_percentage;
        $bpjs->kesehatan_company_percentage = $request->kesehatan_company_percentage;
        $bpjs->kesehatan_personal_percentage = $request->kesehatan_personal_percentage;


        try {
            $bpjs->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
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
    
    public function activateEmployee(Request $request, $id)
    {
        try {
            $employee = Employee::find($id);
            $employee->is_active = 1;
            $employee->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
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

    public function inactivateEmployee(Request $request, $id)
    {
        try {
            $employee = Employee::find($id);
            $employee->is_active = 0;
            $employee->save();
            return response()->json([
                'message' => 'Data has been saved',
                'error' => false,
                'code' => 200,
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

    // public function search(Request $request)
    // {
    // }
    public function export()
    {
        return Excel::download(new EmployeesExport, 'Data Pegawai ' . date('d-m-Y') . '.xlsx');
    }
}
