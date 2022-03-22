<!-- ============================================================== -->
<!-- left sidebar -->
<!-- ============================================================== -->
@php $permissions = json_decode(Auth::user()->role->role_permissions); @endphp
<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">
                        Menu
                    </li>
                    <li class="nav-item">
                        <?php
                        $dashboardRoute = request()->is('/');
                        $isDashboardGroup = $dashboardRoute;
                        ?>
                        <a class="nav-link {{ $isDashboardGroup ? 'active' : '' }}" href="{{ url('/') }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
                    </li>
                    <!-- <li class="nav-ite ">
                        <a class="nav-link active" href="/"><i class="fas fa-fw fa-chart-pie"></i>Dashboard</a>
                    </li> -->
                    @if(in_array("viewEmployee", $permissions) || in_array("viewRole", $permissions) || in_array("viewShift", $permissions))
                    <li class="nav-item">
                        <?php $isEmployeeGroup = request()->is('employee*') || request()->is('role*') || request()->is('office-shift*') ?>
                        <a class="nav-link {{ $isEmployeeGroup ? 'active' : '' }}" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-user-circle"></i>Pegawai</a>
                        <div id="submenu-2" class="collapse {{ $isEmployeeGroup ? 'show' : '' }} submenu">
                            <ul class="nav flex-column">
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="#">Staff Dashboard</a>
                                </li> -->
                                @if(in_array("viewEmployee", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->is('employee*')) ? 'active' : '' }}" href="{{ url('/employee') }}">Pegawai</a>
                                </li>
                                @endif
                                @if(in_array("viewRole", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->is('role*')) ? 'active' : '' }}" href="{{ url('/role') }}">Hak Akses</a>
                                </li>
                                @endif
                                @if(in_array("viewShift", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->is('office-shift*')) ? 'active' : '' }}" href="{{ url('/office-shift') }}">Shift Kerja</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(in_array("viewLocation", $permissions) || in_array("viewDepartment", $permissions) || in_array("viewDesignation", $permissions) || in_array("viewJobTitle", $permissions))
                    <li class="nav-item">
                        <?php
                        $officeLocationRoute = request()->is('company-location*');
                        $structureRoute = request()->is('structure*');
                        $isCompanyGroup = $officeLocationRoute || $structureRoute;
                        ?>
                        <a class="nav-link {{ $isCompanyGroup ? 'active' : '' }}" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fa fa-fw fa-building"></i>Perusahaan</a>
                        <div id="submenu-3" class="collapse {{ $isCompanyGroup ? 'show' : '' }} submenu">
                            <ul class="nav flex-column">
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/company') }}">Perusahaan</a>
                                </li> -->
                                @if(in_array("viewLocation", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $officeLocationRoute ? 'active' : '' }}" href="{{ url('/company-location') }}">Lokasi Kantor</a>
                                </li>
                                @endif
                                @if(in_array("viewDepartment", $permissions) || in_array("viewDesignation", $permissions) || in_array("viewJobTitle", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $structureRoute ? 'active' : '' }}" href="{{ url('/structure') }}">Bagian & Pekerjaan</a>
                                </li>
                                @endif
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/company-department') }}">Bagian & Pekerjaan</a>
                                </li> -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/company-designation') }}">Bagian</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/company-designation') }}">Job Title</a>
                                </li> -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/company-announcement') }}">Pengumuman</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/company-policy') }}">Kebijakan</a>
                                </li> -->
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(in_array("viewAttendance", $permissions))
                    <li class="nav-item">
                        <?php
                        $attendanceRoute = request()->is('attendance') || request()->is('attendance/date*');
                        $overtimeSubmissionRoute = request()->is('overtime-submission*');
                        $isAttendanceGroup = $attendanceRoute || $overtimeSubmissionRoute || request()->is('attendance/upload') || request()->is('attendance/upload-from-machine') || request()->is('attendance/upload-from-machine-app');
                        ?>
                        <a class="nav-link {{ $isAttendanceGroup ? 'active' : '' }}" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4"><i class="fa fa-fw fa-clock"></i>Timesheet</a>
                        <div id="submenu-4" class="collapse {{ $isAttendanceGroup  ? 'show' : '' }} submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ $attendanceRoute ? 'active' : '' }}" href="{{ url('/attendance') }}">Absensi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $overtimeSubmissionRoute ? 'active' : '' }}" href="{{ url('/overtime-submission') }}">Pengajuan Lembur</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->is('attendance/upload')) ? 'active' : '' }}" href="{{ url('/attendance/upload') }}">Impor Absensi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->is('attendance/upload-from-machine')) ? 'active' : '' }}" href="{{ url('/attendance/upload-from-machine') }}">Impor Absensi Dari Mesin</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->is('attendance/upload-from-machine-app')) ? 'active' : '' }}" href="{{ url('/attendance/upload-from-machine-app') }}">Impor Absensi Dari Aplikasi Mesin</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(in_array("viewMonthlySalary", $permissions) || in_array("viewDailySalary", $permissions) || in_array("viewThr", $permissions) || in_array("viewLeaveSalary", $permissions))
                    <li class="nav-item">
                        <?php
                        $monthlyPayrollRoute = request()->is('payroll*');
                        $dailyPayrollRoute = request()->is('daily-payroll*');
                        $thrPayrollRoute = request()->is('thr*');
                        $leavePayrollRoute = request()->is('leave-payroll*');
                        $isPayrollGroup = $monthlyPayrollRoute || $dailyPayrollRoute || $thrPayrollRoute || $leavePayrollRoute;
                        ?>
                        <a class="nav-link {{ $isPayrollGroup ? 'active' : '' }}" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-payroll" aria-controls="submenu-4"><i class="fa fa-fw fa-dollar-sign"></i>Penggajian</a>
                        <div id="submenu-payroll" class="collapse {{ $isPayrollGroup ? 'show' : '' }} submenu">
                            <ul class="nav flex-column">
                                @if(in_array("viewMonthlySalary", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $monthlyPayrollRoute ? 'active' : '' }}" href="{{ url('/payroll') }}">Gaji Bulanan</a>
                                </li>
                                @endif
                                @if(in_array("viewDailySalary", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $dailyPayrollRoute ? 'active' : '' }}" href="{{ url('/daily-payroll') }}">Gaji Harian</a>
                                </li>
                                @endif
                                @if(in_array("viewThr", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $thrPayrollRoute ? 'active' : '' }}" href="{{ url('/thr') . '?year=' . date('Y') }}">THR</a>
                                </li>
                                @endif
                                @if(in_array("viewLeaveSalary", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $leavePayrollRoute ? 'active' : '' }}" href="{{ url('/leave-payroll') . '?year=' . date('Y') }}">Cuti</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(in_array("viewSickSubmission", $permissions) || in_array("viewPermissionSubmission", $permissions))
                    <li class="nav-item">
                        <?php
                        $sickSubmissionRoute = request()->is('sick*');
                        $permissionSubmissionRoute = request()->is('permission*');
                        $isSubmissionGroup = $sickSubmissionRoute || $permissionSubmissionRoute;
                        ?>
                        <a class="nav-link {{ $isSubmissionGroup ? 'active' : '' }}" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-sick" aria-controls="submenu-sick"><i class="fa fa-fw fa-notes-medical"></i>Sakit & Izin</a>
                        <div id="submenu-sick" class="collapse {{ $isSubmissionGroup ? 'show' : '' }} submenu">
                            <ul class="nav flex-column">
                                @if(in_array("viewSickSubmission", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $sickSubmissionRoute ? 'active' : '' }}" href="{{ url('/sick') }}">Pengajuan Sakit</a>
                                </li>
                                @endif
                                @if(in_array("viewPermissionSubmission", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $permissionSubmissionRoute ? 'active' : '' }}" href="{{ url('/permission') }}">Pengajuan Izin</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(in_array("viewSickSubmission", $permissions) || in_array("viewPermissionSubmission", $permissions))
                    <li class="nav-item">
                        <?php
                        $leaveRoute = request()->is('leave');
                        $leaveSubmissionRoute = request()->is('leave/submission*');
                        $isLeaveGroup = $leaveRoute || $leaveSubmissionRoute;
                        ?>
                        <a class="nav-link {{ $isLeaveGroup ? 'active' : '' }}" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-leave" aria-controls="submenu-leave"><i class="fa fa-fw fa-paste"></i>Kelola Cuti</a>
                        <div id="submenu-leave" class="collapse {{ $isLeaveGroup ? 'show' : '' }} submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ $leaveRoute ? 'active' : '' }}" href="{{ url('/leave') }}">Data Cuti</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $leaveSubmissionRoute ? 'active' : '' }}" href="{{ url('/leave/submission') }}">Pengajuan Cuti</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item">
                        <?php
                        $reportRoute = request()->is('report');
                        $isReportGroup = $reportRoute;
                        ?>
                        <a class="nav-link {{ $isReportGroup ? 'active' : '' }}" href="{{ url('/report') }}"><i class="fas fa-file-alt"></i> Laporan</a>
                    </li>
                    @if(in_array("viewCalendarSetting", $permissions) || in_array("viewSalarySetting", $permissions) || in_array("viewPayrollSetting", $permissions) || in_array("viewPphSetting", $permissions) || in_array("viewBpjsSetting", $permissions) || in_array("viewLeaveSetting", $permissions) || in_array("viewPermissionSetting", $permissions))
                    <li class="nav-item">
                        <?php
                        $settingCalendarRoute = request()->is('setting/calendar');
                        $settingSalaryRoute = request()->is('setting/salary');
                        $settingPayrollRoute = request()->is('setting/payroll');
                        $settingPphRoute = request()->is('setting/pph');
                        $settingBpjsRoute = request()->is('setting/bpjs');
                        $settingLeaveRoute = request()->is('setting/leave');
                        $settingPermissionRoute = request()->is('setting/permission');
                        $isSettingGroup = $settingCalendarRoute || $settingSalaryRoute || $settingPayrollRoute || $settingPphRoute || $settingBpjsRoute || $settingLeaveRoute || $settingPermissionRoute;
                        ?>
                        <a class="nav-link {{ $isSettingGroup ? 'active' : '' }}" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-6" aria-controls="submenu-6"><i class="fa fa-fw fa-cogs"></i>Pengaturan</a>
                        <div id="submenu-6" class="collapse {{ $isSettingGroup ? 'show' : '' }} submenu">
                            <ul class="nav flex-column">
                                @if(in_array("viewCalendarSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $settingCalendarRoute ? 'active' : '' }}" href="{{ url('/setting/calendar') }}">Kalender</a>
                                </li>
                                @endif
                                @if(in_array("viewSalarySetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $settingSalaryRoute ? 'active' : '' }}" href="{{ url('/setting/salary') }}">Gaji & THR</a>
                                </li>
                                @endif
                                @if(in_array("viewPayrollSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $settingPayrollRoute ? 'active' : '' }}" href="{{ url('/setting/payroll') }}">Penggajian</a>
                                </li>
                                @endif
                                @if(in_array("viewPphSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $settingPphRoute ? 'active' : '' }}" href="{{ url('/setting/pph') }}">PPh21/26</a>
                                </li>
                                @endif
                                @if(in_array("viewBpjsSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $settingBpjsRoute ? 'active' : '' }}" href="{{ url('/setting/bpjs') }}">BPJS</a>
                                </li>
                                @endif
                                @if(in_array("viewLeaveSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $settingLeaveRoute ? 'active' : '' }}" href="{{ url('/setting/leave') }}">Cuti</a>
                                </li>
                                @endif
                                @if(in_array("viewPermissionSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link {{ $settingPermissionRoute ? 'active' : '' }}" href="{{ url('/setting/permission') }}">Izin</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
<!-- ============================================================== -->
<!-- end left sidebar -->
<!-- ============================================================== -->