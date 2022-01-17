@php
$userLoginPermissions = [];
if (request()->session()->has('userLoginPermissions')) {
$userLoginPermissions = request()->session()->get('userLoginPermissions');
}
@endphp
<div class="pills-regular">
  <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
    @if(in_array("viewEmployee", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/detail/{{ $employee->id }}" class="nav-link {{ request()->is('employee/detail/*') ? 'active' : '' }}">Detail Pegawai</a>
    </li>
    @endif
    @if(in_array("viewEmployeeAccount", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/account/{{ $employee->id }}" class="nav-link {{ request()->is('employee/account/*') ? 'active' : '' }}">Akun</a>
    </li>
    @endif
    @if(in_array("viewEmployeeCareer", $userLoginPermissions))
    @if($employee->type == 'staff')
    @if(in_array("staffSalary", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/career/{{ $employee->id }}" class="nav-link {{ request()->is('employee/career/*') || request()->is('career/*') ? 'active' : '' }}">Karir & Remunerasi</a>
    </li>
    @endif
    @else
    <li class="nav-item">
      <a href="/employee/career/{{ $employee->id }}" class="nav-link {{ request()->is('employee/career/*') || request()->is('career/*') ? 'active' : '' }}">Karir & Remunerasi</a>
    </li>
    @endif
    @endif
    @if(in_array("viewEmployeeAttendance", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/attendance/{{ $employee->id }}" class="nav-link {{ request()->is('employee/attendance/*') ? 'active' : '' }}">Absensi</a>
    </li>
    @endif
    @if(in_array("viewEmployeeShift", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/office-shift/{{ $employee->id }}" class="nav-link {{ request()->is('employee/office-shift/*') ? 'active' : '' }}">Shift Kerja</a>
    </li>
    @endif
    @if(in_array("viewSickSubmission", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/sick/{{ $employee->id }}" class="nav-link {{ request()->is('employee/sick/*') ? 'active' : '' }}">Sakit</a>
    </li>
    @endif
    @if(in_array("viewPermissionSubmission", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/permission/{{ $employee->id }}" class="nav-link {{ request()->is('employee/permission/*') ? 'active' : '' }}">Izin</a>
    </li>
    @endif
    @if(in_array("viewLeaveSubmission", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/leave/{{ $employee->id }}" class="nav-link {{ request()->is('employee/leave/*') ? 'active' : '' }}">Cuti</a>
    </li>
    @endif
    @if(in_array("viewEmployeePayslip", $userLoginPermissions) && in_array("staffSalary", $userLoginPermissions))
    @if($employee->type == 'staff')
    @if(in_array("staffSalary", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/payslip/{{ $employee->id }}" class="nav-link {{ request()->is('employee/payslip/*') ? 'active' : '' }}">Slip Gaji</a>
    </li>
    @endif
    @else
    <li class="nav-item">
      <a href="/employee/payslip/{{ $employee->id }}" class="nav-link {{ request()->is('employee/payslip/*') ? 'active' : '' }}">Slip Gaji</a>
    </li>
    @endif
    @endif
    @if(in_array("viewEmployeeLoan", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/loan/{{ $employee->id }}" class="nav-link {{ request()->is('employee/loan/*') ? 'active' : '' }}">Kasbon</a>
    </li>
    @endif
    @if(in_array("viewEmployeeSetting", $userLoginPermissions))
    <li class="nav-item">
      <a href="/employee/setting/{{ $employee->id }}/salary" class="nav-link {{ request()->is('employee/setting/*') ? 'active' : '' }}"><i class="fas fa-cog"></i> Pengaturan</a>
    </li>
    @endif
  </ul>
</div>