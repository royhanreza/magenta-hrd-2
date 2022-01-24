<?php

use App\Http\Controllers\api\AttendanceApiController;
use App\Http\Controllers\api\BankAccountApiController;
use App\Http\Controllers\api\BudgetCategoryApiController;
use App\Http\Controllers\api\CompanyApiController;
use App\Http\Controllers\api\CompanyDepartmentApiController;
use App\Http\Controllers\api\DivisionApiController;
use App\Http\Controllers\api\EmployeeApiController;
use App\Http\Controllers\api\EventApiController;
use App\Http\Controllers\api\EventBudgetApiController;
use App\Http\Controllers\api\EventTaskApiController;
use App\Http\Controllers\api\FreelancerApiController;
use App\Http\Controllers\api\JobTitleApiController;
use App\Http\Controllers\api\LeaveApiController;
use App\Http\Controllers\api\LoginApiController;
use App\Http\Controllers\api\PermissionApiController;
use App\Http\Controllers\api\PermissionCategoryApiController;
use App\Http\Controllers\api\ProvinceApiController;
use App\Http\Controllers\api\SickApiController;
use App\Http\Controllers\api\TestApiController;
use App\Http\Controllers\api\TransactionAccountApiController;
use App\Http\Controllers\web\CompanyController;
use App\Http\Controllers\web\EventBudgetController;
use App\Models\BudgetCategory;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Company
// Route::post('/company/store', [CompanyController::class, 'store']);
Route::prefix('companies')->group(function () {
    Route::get('/{id}/office-shifts', [CompanyApiController::class, 'getOfficeShifts']);
    Route::get('/{id}/roles', [CompanyApiController::class, 'getRoles']);
    Route::get('/{id}/locations', [CompanyApiController::class, 'getLocations']);
    Route::get('/{id}/departments', [CompanyApiController::class, 'getDepartments']);
});
// Route::get('/companies/{id}/locations', function($id) {
//     $locations = Company::find($id)->locations;
//     return response()->json(['locations' => $locations]);
// });

// Route::get('/companies/{id}/departments', function($id) {
//     $departments = Company::find($id)->departments;
//     return response()->json(['departments' => $departments]);
// });

// Department
Route::prefix('departments')->group(function () {
    Route::get('/{id}/designations', [CompanyDepartmentApiController::class, 'getDesignations']);
});
// Province
Route::prefix('provinces')->group(function () {
    Route::get('/{id}/cities', [ProvinceApiController::class, 'getCities']);
});
// Employee
Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeApiController::class, 'index']);
    Route::get('/{id}', [EmployeeApiController::class, 'show']);
    Route::get('/{id}/loans', [EmployeeApiController::class, 'loans']);
    Route::get('/{id}/events', [EmployeeApiController::class, 'getAllEvents']);
    Route::get('/{id}/companies', [EmployeeApiController::class, 'getCompany']);
    Route::get('/{id}/attendances', [EmployeeApiController::class, 'getAllAttendances']);
    Route::get('/{id}/sick-submissions', [EmployeeApiController::class, 'getAllSickSubmissions']);
    Route::get('/{id}/permission-submissions', [EmployeeApiController::class, 'getAllPermissionSubmissions']);
    Route::get('/{id}/leave-submissions', [EmployeeApiController::class, 'getAllLeaveSubmissions']);
    Route::get('/{id}/remaining-leaves', [EmployeeApiController::class, 'getRemainingLeaves']);
    Route::get('/{id}/active-leaves', [EmployeeApiController::class, 'getActiveLeave']);
    Route::get('/{id}/payslips', [EmployeeApiController::class, 'payslips']);
    Route::patch('/{id}/edit-account', [EmployeeApiController::class, 'editAccount']);
});
// Freelancer
Route::prefix('freelancers')->group(function () {
    Route::get('/', [FreelancerApiController::class, 'index']);
    Route::get('/{id}/events', [FreelancerApiController::class, 'getAllEvents']);
});
// Event
Route::prefix('events')->group(function () {
    Route::get('/', [EventApiController::class, 'index']);
    Route::get('/{id}/budgets', [EventApiController::class, 'getAllBudgets']);
});
// Event Task
Route::prefix('event-tasks')->group(function () {
    Route::post('/{id}/finish', [EventTaskApiController::class, 'finish']);
});
// Event Budget
Route::prefix('event-budgets')->group(function () {
    Route::get('/', [EventBudgetApiController::class, 'index']);
    Route::post('/', [EventBudgetApiController::class, 'store']);
    Route::post('/{id}/approve', [EventBudgetApiController::class, 'approve']);
    Route::post('/{id}/reject', [EventBudgetApiController::class, 'reject']);
    Route::get('/{id}', [EventBudgetApiController::class, 'show']);
    Route::patch('/{id}', [EventBudgetApiController::class, 'update']);
    Route::delete('/{id}', [EventBudgetApiController::class, 'destroy']);
});
// Budget Category
Route::prefix('budget-categories')->group(function () {
    Route::get('/', [BudgetCategoryApiController::class, 'index']);
    // Route::get('/{id}', [EventApiController::class, 'getAllBudgets']);
});
// Login
Route::prefix('login')->group(function () {
    Route::post('/mobile/employee', [LoginApiController::class, 'loginEmployee']);
    Route::post('/mobile/admin', [LoginApiController::class, 'loginAdmin']);
    Route::post('/web/dashboard-employee', [LoginApiController::class, 'loginDashboardEmployee']);
    // Route::get('/{id}', [EventApiController::class, 'getAllBudgets']);
});
// Logout
Route::prefix('logout')->group(function () {
    Route::post('/mobile/employee', [LoginApiController::class, 'logoutEmployee']);
    Route::post('/mobile/admin', [LoginApiController::class, 'logoutAdmin']);
    // Route::get('/{id}', [EventApiController::class, 'getAllBudgets']);
});

// Attendance
Route::prefix('attendances')->group(function () {
    Route::get('/', [AttendanceApiController::class, 'index']);
    Route::get('/{id}', [AttendanceApiController::class, 'show']);
    Route::post('/{id}/approve', [AttendanceApiController::class, 'approve']);
    Route::post('/{id}/reject', [AttendanceApiController::class, 'reject']);
    Route::post('/action/check-in', [AttendanceApiController::class, 'checkIn']);
    Route::post('/action/check-out', [AttendanceApiController::class, 'checkOut']);
    Route::post('/action/check-out', [AttendanceApiController::class, 'checkOut']);
    Route::post('/action/hardware', [AttendanceApiController::class, 'hardwareExperiment']);
    Route::patch('/{id}/update-overtime', [AttendanceApiController::class, 'updateOvertime']);
    Route::patch('/{id}/update-overtime-note', [AttendanceApiController::class, 'updateOvertimeNote']);
    // Route::get('/{id}', [EventApiController::class, 'getAllBudgets']);
});

// Sick Submission
Route::prefix('sick-submissions')->group(function () {
    Route::get('/', [SickApiController::class, 'index']);
    Route::get('/{id}', [SickApiController::class, 'show']);
    Route::post('/', [SickApiController::class, 'store']);
    Route::post('/{id}', [SickApiController::class, 'update']);
    Route::post('/action/approve/{id}', [SickApiController::class, 'approve']);
    Route::post('/action/reject/{id}', [SickApiController::class, 'reject']);
    Route::delete('/{id}', [SickApiController::class, 'destroy']);
    // Route::get('/{id}', [EventApiController::class, 'getAllBudgets']);
});

// Permission Submission
Route::prefix('permission-submissions')->group(function () {
    Route::get('/', [PermissionApiController::class, 'index']);
    Route::get('/{id}', [PermissionApiController::class, 'show']);
    Route::post('/', [PermissionApiController::class, 'store']);
    Route::post('/action/approve/{id}', [PermissionApiController::class, 'approve']);
    Route::post('/action/reject/{id}', [PermissionApiController::class, 'reject']);
    Route::delete('/{id}', [PermissionApiController::class, 'destroy']);
    Route::post('/{id}', [PermissionApiController::class, 'update']);
    // Route::get('/{id}', [EventApiController::class, 'getAllBudgets']);
});

// Leave SUbmision
Route::prefix('leave-submissions')->group(function () {
    Route::get('/', [LeaveApiController::class, 'leaveSubmissions']);
    Route::get('/{id}', [LeaveApiController::class, 'showLeaveSubmission']);
    Route::post('/', [LeaveApiController::class, 'store']);
    Route::post('/action/approve/{id}', [LeaveApiController::class, 'approve']);
    Route::post('/action/reject/{id}', [LeaveApiController::class, 'reject']);
    Route::delete('/{id}', [LeaveApiController::class, 'deleteLeaveSubmission']);
    Route::patch('/{id}', [LeaveApiController::class, 'updateLeaveSubmission']);
    // Route::get('/{id}', [EventApiController::class, 'getAllBudgets']);
});

// Leaves
Route::prefix('leaves')->group(function () {
    Route::get('/', [LeaveApiController::class, 'index']);
    Route::get('/{id}', [LeaveApiController::class, 'show']);
    Route::post('/', [LeaveApiController::class, 'store']);
    // Route::get('/{id}', [EventApiController::class, 'getAllBudgets']);
});

Route::prefix('test')->group(function () {
    Route::post('/upload-image', [TestApiController::class, 'uploadImage']);
    Route::get('/helper', [TestApiController::class, 'helper']);
    Route::post('/notification', [TestApiController::class, 'notification']);
});

// Job Title
Route::prefix('job-titles')->group(function () {
    // Route::get('/', [AttendanceApiController::class, 'index']);
    // Route::get('/{id}', [AttendanceApiController::class, 'show']);
    Route::get('/{id}/attendances', [JobTitleApiController::class, 'attendances']);
});
// Division
Route::prefix('divisions')->group(function () {
    // Route::get('/', [AttendanceApiController::class, 'index']);
    // Route::get('/{id}', [AttendanceApiController::class, 'show']);
    Route::get('/{id}/attendances', [DivisionApiController::class, 'attendances']);
    Route::get('/data/get-attendances', [DivisionApiController::class, 'attendanceByDivisions']);
});

// Permission Category
Route::prefix('permission-categories')->group(function () {
    // Route::get('/', [AttendanceApiController::class, 'index']);
    // Route::get('/{id}', [AttendanceApiController::class, 'show']);
    Route::get('/', [PermissionCategoryApiController::class, 'index']);
});

// Bank Account
Route::prefix('bank-accounts')->group(function () {
    Route::get('/', [BankAccountApiController::class, 'index']);
    Route::post('/', [BankAccountApiController::class, 'store']);
    Route::get('/{id}', [BankAccountApiController::class, 'show']);
    Route::get('/{id}/transactions', [BankAccountApiController::class, 'transactions']);
    Route::patch('/{id}', [BankAccountApiController::class, 'update']);
    Route::delete('/{id}', [BankAccountApiController::class, 'destroy']);
});

// Transaction Account
Route::prefix('transaction-accounts')->group(function () {
    Route::get('/', [TransactionAccountApiController::class, 'index']);
    Route::post('/', [TransactionAccountApiController::class, 'store']);
    Route::get('/{id}', [TransactionAccountApiController::class, 'show']);
    Route::patch('/{id}', [TransactionAccountApiController::class, 'update']);
    Route::delete('/{id}', [TransactionAccountApiController::class, 'destroy']);
});
