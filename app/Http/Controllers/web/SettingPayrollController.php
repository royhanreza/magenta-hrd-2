<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\PaySlip;
use App\Models\SalaryDeduction;
use App\Models\SalaryIncome;
use Illuminate\Http\Request;

class SettingPayrollController extends Controller
{
    public function index()
    {
        $paySlips = PaySlip::all();
        return view('setting.payroll.index', ['payslips' => $paySlips]);
    }

    public function create()
    {
        $salaryIncomes = SalaryIncome::all();
        $salaryDeductions = SalaryDeduction::all();
        return view('setting.payroll.create', ['incomes' => $salaryIncomes, 'deductions' => $salaryDeductions]);
    }

    public function edit($id)
    {
        $payslip = PaySlip::with(['salaryIncomes', 'salaryDeductions'])->findOrFail($id);
        // $selectedSalaryIncomes = $payslip->salaryIncomes;
        // $selectedSalaryDeductions = $payslip->salaryDeductions;
        // return $payslip;
        $salaryIncomes = SalaryIncome::all();
        $salaryDeductions = SalaryDeduction::all();
        return view('setting.payroll.edit', [
            'incomes' => $salaryIncomes,
            'deductions' => $salaryDeductions,
            // 'selected_incomes' => $selectedSalaryIncomes,
            // 'selected_deductions' => $selectedSalaryDeductions,
            'payslip' => $payslip,
        ]);
    }
}
