<?php

use App\Http\Controllers\web\AttendanceController;
use App\Http\Controllers\web\BankAccountController;
use App\Http\Controllers\web\BudgetCategoryController;
use App\Http\Controllers\web\CalendarController;
use App\Http\Controllers\web\CareerController;
use App\Http\Controllers\web\CompanyAnnouncementController;
use App\Http\Controllers\web\CompanyController;
use App\Http\Controllers\web\CompanyDepartmentController;
use App\Http\Controllers\web\CompanyDesignationController;
use App\Http\Controllers\web\CompanyLocationController;
use App\Http\Controllers\web\CompanyNpwpController;
use App\Http\Controllers\web\CompanyPolicyController;
use App\Http\Controllers\web\DailyPayrollController;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\EmployeeController;
use App\Http\Controllers\web\EmployeeFileController;
use App\Http\Controllers\web\ErrorController;
use App\Http\Controllers\web\EventBudgetController;
use App\Http\Controllers\web\EventController;
use App\Http\Controllers\web\EventMemberController;
use App\Http\Controllers\web\EventTaskController;
use App\Http\Controllers\web\FinalPayslipController;
use App\Http\Controllers\web\FreelancerController;
use App\Http\Controllers\web\JobTitleController;
use App\Http\Controllers\web\LeaveController;
use App\Http\Controllers\web\LeavePayroll;
use App\Http\Controllers\web\LoanController;
use App\Http\Controllers\web\LoginController;
use App\Http\Controllers\web\MappingEventController;
use App\Http\Controllers\web\OfficeShiftController;
use App\Http\Controllers\web\PayrollController;
use App\Http\Controllers\web\PaySlipController;
use App\Http\Controllers\web\PermissionCategoryController;
use App\Http\Controllers\web\PermissionController;
use App\Http\Controllers\web\ProvinceMinimumWageController;
use App\Http\Controllers\web\QuotationEventController;
use App\Http\Controllers\web\ReportController;
use App\Http\Controllers\web\RoleController;
use App\Http\Controllers\web\SalaryDeductionController;
use App\Http\Controllers\web\SalaryIncomeController;
use App\Http\Controllers\web\SettingBpjsController;
use App\Http\Controllers\web\SettingLeave;
use App\Http\Controllers\web\SettingLeaveController;
use App\Http\Controllers\web\SettingPayrollController;
use App\Http\Controllers\web\SettingPermissionController;
use App\Http\Controllers\web\SettingPphController;
use App\Http\Controllers\web\SettingSalaryController;
use App\Http\Controllers\web\SickController;
use App\Http\Controllers\web\StructureController;
use App\Http\Controllers\web\ThrController;
use App\Http\Controllers\web\v2\LoanController as V2LoanController;
use App\Models\CompanyNpwp;
use App\Models\JobTitle;
use App\Models\PermissionCategory;
use App\Models\ProvinceMinimumWage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    // Route::get('/', function () {
    //     return redirect('/employee');
    // });
    Route::get('/', [DashboardController::class, 'index']);

    // Freelancer
    Route::prefix('freelancer')->group(function () {
        Route::get('/', [FreelancerController::class, 'index']);
        Route::get('/create', [FreelancerController::class, 'create']);
        Route::get('/edit/{id}', [FreelancerController::class, 'edit']);
        Route::patch('/{id}', [FreelancerController::class, 'update']);
        Route::post('', [FreelancerController::class, 'store']);
        Route::delete('/{id}', [FreelancerController::class, 'destroy']);
    });
    // Event
    Route::prefix('quotation-event')->group(function () {
        Route::get('/', [QuotationEventController::class, 'index']);
    });
    // Event
    Route::prefix('event')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::get('/create', [EventController::class, 'create']);
        Route::get('/edit/{id}', [EventController::class, 'edit']);
        Route::patch('/{id}', [EventController::class, 'update']);
        Route::post('', [EventController::class, 'store']);
        Route::delete('/{id}', [EventController::class, 'destroy']);
        Route::patch('/{id}/approve', [EventController::class, 'approve']);
        Route::patch('/{id}/reject', [EventController::class, 'reject']);
        Route::patch('/{id}/close', [EventController::class, 'close']);
        Route::patch('/{id}/update-budget-date', [EventController::class, 'updateBudgetDate']);
    });
    // Event Task
    Route::prefix('event-task')->group(function () {
        Route::get('/', [EventTaskController::class, 'index']);
        Route::get('/create', [EventTaskController::class, 'create']);
        Route::get('/edit/{id}', [EventTaskController::class, 'edit']);
        Route::patch('/{id}', [EventTaskController::class, 'update']);
        Route::post('', [EventTaskController::class, 'store']);
        Route::delete('/{id}', [EventTaskController::class, 'destroy']);
    });
    // Event Task
    Route::prefix('event-budget')->group(function () {
        Route::patch('/{id}', [EventBudgetController::class, 'update']);
        Route::post('', [EventBudgetController::class, 'store']);
        Route::delete('/{id}', [EventBudgetController::class, 'destroy']);
        Route::post('/{id}/approve', [EventBudgetController::class, 'approve']);
        Route::post('/{id}/reject', [EventBudgetController::class, 'reject']);
    });
    // Event Task
    Route::prefix('event-member')->group(function () {
        Route::patch('/{id}', [EventMemberController::class, 'update']);
        Route::post('', [EventMemberController::class, 'store']);
        Route::delete('/{id}', [EventMemberController::class, 'destroy']);
    });
    // Mapping Event
    Route::prefix('mapping-event')->group(function () {
        Route::get('', [MappingEventController::class, 'index']);
        Route::get('/create', [EventTaskController::class, 'create']);
        Route::get('/edit/{id}', [EventTaskController::class, 'edit']);
        Route::patch('/{id}', [EventTaskController::class, 'update']);
        Route::post('', [EventTaskController::class, 'store']);
        Route::delete('/{id}', [EventTaskController::class, 'destroy']);
        Route::get('/{id}/view', [MappingEventController::class, 'show']);
        Route::get('/{id}/task', [MappingEventController::class, 'task']);
        Route::get('/{id}/budget', [MappingEventController::class, 'budget']);
        Route::get('/{id}/member', [MappingEventController::class, 'member']);
        Route::get('/{id}/finance', [MappingEventController::class, 'finance']);
    });
    // Employee
    // Route::middleware('role:viewEmployee')->prefix('employee')->group(function () {
    Route::prefix('employee')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('home');
        Route::get('/create', [EmployeeController::class, 'create']);
        Route::get('/search', [EmployeeController::class, 'search']);
        Route::get('/export', [EmployeeController::class, 'export']);
        Route::get('/edit/{id}', [EmployeeController::class, 'edit']);
        Route::get('/attendance/{id}', [EmployeeController::class, 'attendance']);
        Route::get('/attendance/{id}/{date_1}/{date_2}', [EmployeeController::class, 'attendanceByDate']);
        Route::get('/detail/{id}', [EmployeeController::class, 'show']);
        Route::get('/payslip/{id}', [EmployeeController::class, 'payslip']);
        Route::get('/loan/{id}', [EmployeeController::class, 'loan']);
        Route::get('/career/{id}', [EmployeeController::class, 'career']);
        Route::get('/account/{id}', [EmployeeController::class, 'account']);
        Route::get('/sick/{id}', [EmployeeController::class, 'sick']);
        Route::get('/permission/{id}', [EmployeeController::class, 'permission']);
        Route::get('/leave/{id}', [EmployeeController::class, 'leave']);
        Route::get('/office-shift/{id}', [EmployeeController::class, 'officeShift']);
        Route::get('/setting/{id}/salary', [EmployeeController::class, 'setting']);
        Route::get('/setting/{id}/bpjs', [EmployeeController::class, 'settingBpjs']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::post('/{id}', [EmployeeController::class, 'update']);
        Route::post('/{id}/edit-shift', [EmployeeController::class, 'editShift']);
        Route::post('/{id}/activate-employee', [EmployeeController::class, 'activateEmployee']);
        Route::post('/{id}/inactivate-employee', [EmployeeController::class, 'inactivateEmployee']);
        // Route::post('/{id}/edit-shift', [EmployeeController::class, 'editShift']);
        Route::patch('/{id}/edit-account', [EmployeeController::class, 'editAccount']);
        Route::patch('/{id}/edit-account-status', [EmployeeController::class, 'editAccountStatus']);
        Route::patch('/{id}/edit-salary-setting', [EmployeeController::class, 'editSalarySetting']);
        Route::patch('/{id}/edit-npwp', [EmployeeController::class, 'editNpwp']);
        Route::patch('/{id}/edit-bpjs', [EmployeeController::class, 'editBpjs']);
        Route::patch('/{id}/edit-bpjs-value', [EmployeeController::class, 'editBpjsValue']);
        Route::delete('/{id}', [EmployeeController::class, 'destroy']);
    });
    // Career
    Route::prefix('career')->group(function () {
        Route::get('/', [CareerController::class, 'index']);
        Route::post('/', [CareerController::class, 'store']);
        Route::patch('/{id}', [CareerController::class, 'update']);
        Route::delete('/{id}', [CareerController::class, 'destroy']);
        Route::get('/create/{employee_id}', [CareerController::class, 'create']);
        Route::get('/edit/{id}', [CareerController::class, 'edit']);
    });
    // Role
    Route::prefix('role')->group(function () {
        Route::get('', [RoleController::class, 'index']);
        Route::get('/create', [RoleController::class, 'create']);
        Route::get('/edit/{id}', [RoleController::class, 'edit']);
        Route::patch('/{id}', [RoleController::class, 'update']);
        Route::post('', [RoleController::class, 'store']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });
    // Office Shift
    // Route::get('/office-shift', [OfficeShiftController::class, 'index']);
    Route::prefix('office-shift')->group(function () {
        Route::get('', [OfficeShiftController::class, 'index']);
        Route::get('/create', [OfficeShiftController::class, 'create']);
        Route::get('/edit/{id}', [OfficeShiftController::class, 'edit']);
        Route::patch('/{id}', [OfficeShiftController::class, 'update']);
        Route::post('', [OfficeShiftController::class, 'store']);
        Route::delete('/{id}', [OfficeShiftController::class, 'destroy']);
    });
    // Company
    Route::prefix('company')->group(function () {
        Route::get('', [CompanyController::class, 'index']);
        Route::get('/create', [CompanyController::class, 'create']);
        Route::get('/edit/{id}', [CompanyController::class, 'edit']);
        Route::patch('/{id}', [CompanyController::class, 'update']);
        Route::post('', [CompanyController::class, 'store']);
        Route::delete('/{id}', [CompanyController::class, 'destroy']);
    });


    // Company Location
    Route::prefix('company-location')->group(function () {
        Route::get('/', [CompanyLocationController::class, 'index']);
        Route::get('/create', [CompanyLocationController::class, 'create']);
        Route::post('/', [CompanyLocationController::class, 'store']);
        Route::get('/edit/{id}', [CompanyLocationController::class, 'edit']);
        Route::patch('/{id}', [CompanyLocationController::class, 'update']);
        Route::delete('/{id}', [CompanyLocationController::class, 'destroy']);
    });
    // Company Department
    Route::prefix('company-department')->group(function () {
        Route::get('/', [CompanyDepartmentController::class, 'index']);
        Route::get('/create', [CompanyDepartmentController::class, 'create']);
        Route::post('/', [CompanyDepartmentController::class, 'store']);
        Route::get('/edit/{id}', [CompanyDepartmentController::class, 'edit']);
        Route::patch('/{id}', [CompanyDepartmentController::class, 'update']);
        Route::delete('/{id}', [CompanyDepartmentController::class, 'destroy']);
    });
    // Company Designation
    Route::prefix('company-designation')->group(function () {
        Route::get('/', [CompanyDesignationController::class, 'index']);
        Route::get('/create', [CompanyDesignationController::class, 'create']);
        Route::post('/', [CompanyDesignationController::class, 'store']);
        Route::get('/edit/{id}', [CompanyDesignationController::class, 'edit']);
        Route::patch('/{id}', [CompanyDesignationController::class, 'update']);
        Route::delete('/{id}', [CompanyDesignationController::class, 'destroy']);
    });
    // Company Designation
    Route::prefix('job-title')->group(function () {
        Route::post('/', [JobTitleController::class, 'store']);
        Route::patch('/{id}', [JobTitleController::class, 'update']);
        Route::delete('/{id}', [JobTitleController::class, 'destroy']);
    });
    // Company Announcement
    Route::prefix('company-announcement')->group(function () {
        Route::get('/', [CompanyAnnouncementController::class, 'index']);
        Route::get('/create', [CompanyAnnouncementController::class, 'create']);
        Route::post('/', [CompanyAnnouncementController::class, 'store']);
        Route::get('/edit/{id}', [CompanyAnnouncementController::class, 'edit']);
        Route::patch('/{id}', [CompanyAnnouncementController::class, 'update']);
        Route::delete('/{id}', [CompanyAnnouncementController::class, 'destroy']);
    });
    // Company Policy
    Route::get('/company-policy', [CompanyPolicyController::class, 'index']);

    //Attendance
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index']);
        Route::get('/date/{date}', [AttendanceController::class, 'showByDate']);
        Route::get('/export/sheet', [AttendanceController::class, 'sheetAttendanceByEmployee']);
        Route::get('/export/sheet/all', [AttendanceController::class, 'sheetAttendanceAll']);
        Route::get('/upload', [AttendanceController::class, 'upload']);
        Route::get('/upload-from-machine', [AttendanceController::class, 'uploadFromMachine']);
        Route::get('/upload-from-machine-app', [AttendanceController::class, 'uploadFromMachineApp']);
        Route::post('/action/do-upload', [AttendanceController::class, 'doUpload']);
        Route::post('/action/do-upload-from-machine', [AttendanceController::class, 'doUploadFromMachine3']);
        Route::post('/action/do-upload-from-machine-app', [AttendanceController::class, 'doUploadFromMachineApp']);
        Route::post('/{id}/approve', [AttendanceController::class, 'approve']);
        Route::post('/{id}/reject', [AttendanceController::class, 'reject']);
        Route::patch('/{id}/update-overtime', [AttendanceController::class, 'updateOvertime']);
        Route::post('/{id}/update-clockin', [AttendanceController::class, 'updateClockIn']);
        Route::post('/{id}/update-clockout', [AttendanceController::class, 'updateClockOut']);
        Route::delete('/{id}/reset-clock', [AttendanceController::class, 'resetClock']);
    });

    //Bank Account
    Route::prefix('bank-account')->group(function () {
        Route::get('/', [BankAccountController::class, 'index']);
        Route::get('/create', [BankAccountController::class, 'create']);
        Route::post('/', [BankAccountController::class, 'store']);
        Route::get('/edit/{id}', [BankAccountController::class, 'edit']);
        Route::patch('/{id}', [BankAccountController::class, 'update']);
        Route::delete('/{id}', [BankAccountController::class, 'destroy']);
    });

    //Bank Account
    Route::prefix('budget-category')->group(function () {
        Route::get('/', [BudgetCategoryController::class, 'index']);
        Route::get('/create', [BudgetCategoryController::class, 'create']);
        Route::post('/', [BudgetCategoryController::class, 'store']);
        Route::get('/edit/{id}', [BudgetCategoryController::class, 'edit']);
        Route::patch('/{id}', [BudgetCategoryController::class, 'update']);
        Route::delete('/{id}', [BudgetCategoryController::class, 'destroy']);
    });
    //Setting
    Route::prefix('setting')->group(function () {
        Route::get('/salary', [SettingSalaryController::class, 'index']);
        // ->middleware('role:viewSalarySetting');
        Route::post('/salary', [SettingSalaryController::class, 'update']);
        // Route::post('/salary', [SettingSalaryController::class, 'index']);
        Route::get('/payroll', [SettingPayrollController::class, 'index']);
        Route::get('/payroll/create', [SettingPayrollController::class, 'create']);
        Route::get('/payroll/edit/{id}', [SettingPayrollController::class, 'edit']);
        Route::get('/pph', [SettingPphController::class, 'index']);
        Route::post('/pph', [SettingPphController::class, 'update']);
        Route::get('/bpjs', [SettingBpjsController::class, 'index']);
        Route::patch('/bpjs/{id}/ketenagakerjaan', [SettingBpjsController::class, 'updateBpjsKetenagakerjaan']);
        Route::patch('/bpjs/{id}/kesehatan', [SettingBpjsController::class, 'updateBpjsKesehatan']);
        Route::get('/leave', [SettingLeaveController::class, 'index']);
        Route::post('/leave', [SettingLeaveController::class, 'update']);
        Route::get('/permission', [SettingPermissionController::class, 'index']);
        Route::get('/calendar', [CalendarController::class, 'index']);
    });
    // Salary Income
    Route::prefix('salary-income')->group(function () {
        Route::post('/', [SalaryIncomeController::class, 'store']);
        Route::patch('/{id}', [SalaryIncomeController::class, 'update']);
        Route::delete('/{id}', [SalaryIncomeController::class, 'destroy']);
    });
    // Salary Deduction
    Route::prefix('salary-deduction')->group(function () {
        Route::post('/', [SalaryDeductionController::class, 'store']);
        Route::patch('/{id}', [SalaryDeductionController::class, 'update']);
        Route::delete('/{id}', [SalaryDeductionController::class, 'destroy']);
    });
    // Pay Slip
    Route::prefix('payslip')->group(function () {
        Route::post('/', [PaySlipController::class, 'store']);
        Route::patch('/{id}', [PaySlipController::class, 'update']);
        Route::delete('/{id}', [PaySlipController::class, 'destroy']);
        Route::delete('/{id}/incomes/{incomeId}', [PaySlipController::class, 'deleteIncome']);
        Route::delete('/{id}/deductions/{deductionId}', [PaySlipController::class, 'deleteDeduction']);
    });
    // BPJS
    Route::prefix('province-wage')->group(function () {
        Route::post('/', [ProvinceMinimumWageController::class, 'store']);
        Route::patch('/{id}', [ProvinceMinimumWageController::class, 'update']);
        Route::delete('/{id}', [ProvinceMinimumWageController::class, 'destroy']);
    });
    // Division & Job
    Route::prefix('structure')->group(function () {
        Route::get('/', [StructureController::class, 'index']);
    });
    // Company NPWP
    Route::prefix('company-npwp')->group(function () {
        Route::post('/', [CompanyNpwpController::class, 'store']);
        Route::patch('/{id}', [CompanyNpwpController::class, 'update']);
        Route::delete('/{id}', [CompanyNpwpController::class, 'destroy']);
    });
    // Permission Category
    Route::prefix('permission-category')->group(function () {
        Route::post('/', [PermissionCategoryController::class, 'store']);
        Route::patch('/{id}', [PermissionCategoryController::class, 'update']);
        Route::delete('/{id}', [PermissionCategoryController::class, 'destroy']);
    });

    // Permission
    Route::prefix('permission')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/create', [PermissionController::class, 'create']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::get('/edit/{id}', [PermissionController::class, 'edit']);
        Route::patch('/{id}', [PermissionController::class, 'update']);
        Route::delete('/{id}', [PermissionController::class, 'destroy']);
    });

    // Leave
    Route::prefix('leave')->group(function () {
        Route::get('/', [LeaveController::class, 'indexV2']);
        Route::get('/submission', [LeaveController::class, 'submission']);
        Route::get('/submission/edit/{id}', [LeaveController::class, 'editSubmission']);
        Route::get('/create', [LeaveController::class, 'create']);
        Route::post('/', [LeaveController::class, 'store']);
        Route::get('/edit/{id}', [LeaveController::class, 'edit']);
        Route::patch('/{id}', [LeaveController::class, 'update']);
        Route::delete('/{id}', [LeaveController::class, 'destroy']);
    });

    // Sick
    Route::prefix('sick')->group(function () {
        Route::get('/', [SickController::class, 'index']);
        Route::get('/create', [SickController::class, 'create']);
        Route::post('/', [SickController::class, 'store']);
        Route::get('/edit/{id}', [SickController::class, 'edit']);
        Route::patch('/{id}', [SickController::class, 'update']);
        Route::delete('/{id}', [SickController::class, 'destroy']);
    });

    // Payroll
    Route::prefix('payroll')->group(function () {
        Route::get('/', [PayrollController::class, 'index']);
        Route::get('/{id}', [PayrollController::class, 'show']);
        Route::get('/create', [PayrollController::class, 'create']);
        Route::post('/', [PayrollController::class, 'store']);
        Route::get('/edit/{id}', [PayrollController::class, 'edit']);
        Route::patch('/{id}', [PayrollController::class, 'update']);
        Route::delete('/{id}', [PayrollController::class, 'destroy']);
        Route::get('/print/{id}', [PayrollController::class, 'print']);
        Route::get('/export/report/monthly', [PayrollController::class, 'exportMonthlyReport']);
    });

    // Payroll
    Route::prefix('daily-payroll')->group(function () {
        Route::get('/', [DailyPayrollController::class, 'index']);
        Route::post('/', [DailyPayrollController::class, 'store']);
        Route::get('/create', [DailyPayrollController::class, 'create']);
        Route::get('/generate', [DailyPayrollController::class, 'generate']);
        Route::get('/show-by-date', [DailyPayrollController::class, 'showByDate']);
        Route::get('/print/{id}', [DailyPayrollController::class, 'print']);
        Route::get('/report/sheet', [DailyPayrollController::class, 'report']);
        Route::delete('/{id}', [DailyPayrollController::class, 'destroy']);
    });

    // Final Payslip
    Route::prefix('final-payslip')->group(function () {
        Route::get('/setting/{id}', [FinalPayslipController::class, 'setting']);
        Route::patch('/{id}/add-income', [FinalPayslipController::class, 'addIncome']);
        Route::patch('/{id}/delete-income', [FinalPayslipController::class, 'deleteIncome']);
        Route::patch('/{id}/add-deduction', [FinalPayslipController::class, 'addDeduction']);
        Route::patch('/{id}/delete-deduction', [FinalPayslipController::class, 'deleteDeduction']);
        Route::patch('/{id}/add-loan', [FinalPayslipController::class, 'addLoan']);
        Route::patch('/{id}/delete-loan', [FinalPayslipController::class, 'deleteLoan']);
        Route::patch('/{id}/add-payment', [FinalPayslipController::class, 'addPayment']);
        Route::patch('/{id}/delete-payment', [FinalPayslipController::class, 'deletePayment']);
        Route::delete('/{id}', [FinalPayslipController::class, 'destroy']);
    });

    // Loan
    Route::prefix('loan')->group(function () {
        Route::get('/', [LoanController::class, 'index']);
        Route::post('/', [LoanController::class, 'store']);
        Route::get('data-loan/{id}', [LoanController::class, 'data']);
        Route::delete('/{id}', [LoanController::class, 'destroy']);
        Route::patch('/{id}', [LoanController::class, 'update']);
    });

    // Loan v2
    Route::prefix('loan-v2')->group(function () {
        Route::get('/', [V2LoanController::class, 'index']);
        Route::post('/', [V2LoanController::class, 'store']);
        Route::post('/action/hold/{id}', [V2LoanController::class, 'hold']);
        Route::delete('/{id}', [V2LoanController::class, 'destroy']);
        Route::patch('/{id}', [V2LoanController::class, 'update']);
    });

    // Calendar
    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'index']);
        Route::post('/', [CalendarController::class, 'store']);
        Route::delete('/{id}', [CalendarController::class, 'destroy']);
        Route::patch('/{id}', [CalendarController::class, 'update']);
    });

    // THR
    Route::prefix('thr')->group(function () {
        Route::get('/', [ThrController::class, 'index']);
        Route::post('/', [ThrController::class, 'store']);
    });

    // THR
    Route::prefix('leave-payroll')->group(function () {
        Route::get('/', [LeavePayroll::class, 'index']);
        Route::post('/', [LeavePayroll::class, 'store']);
    });

    // Employee File
    Route::prefix('employee-file')->group(function () {
        Route::post('/', [EmployeeFileController::class, 'store']);
        Route::delete('/{id}', [EmployeeFileController::class, 'destroy']);
    });

    // THR
    Route::prefix('report')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/bpjs', [ReportController::class, 'bpjs']);
        Route::get('/pph', [ReportController::class, 'pph']);
        Route::get('/generate/bpjs', [ReportController::class, 'generateBpjs']);
        Route::get('/generate/pph', [ReportController::class, 'generatePph']);
        Route::get('/export/bpjs/excel', [ReportController::class, 'exportBpjsExcel']);
        Route::get('/export/pph/excel', [ReportController::class, 'exportPphExcel']);
        Route::post('/bpjs', [ReportController::class, 'storeReportBpjs']);
        Route::post('/pph', [ReportController::class, 'storeReportPph']);
    });
});

Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->middleware('guest')->name('login');
    Route::post('/action/authenticate', [LoginController::class, 'authenticate']);
    Route::get('/action/logout', [LoginController::class, 'logout']);
});

Route::prefix('error')->group(function () {
    Route::get('/forbidden', [ErrorController::class, 'forbidden'])->name('error.forbidden');
});
