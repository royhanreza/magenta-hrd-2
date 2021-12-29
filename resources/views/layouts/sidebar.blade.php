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
                    <!-- <li class="nav-ite ">
                        <a class="nav-link active" href="/"><i class="fas fa-fw fa-chart-pie"></i>Dashboard</a>
                    </li> -->
                    @if(in_array("viewEmployee", $permissions) || in_array("viewRole", $permissions) || in_array("viewShift", $permissions))
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-user-circle"></i>Pegawai</a>
                        <div id="submenu-2" class="collapse submenu">
                            <ul class="nav flex-column">
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="#">Staff Dashboard</a>
                                </li> -->
                                @if(in_array("viewEmployee", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/employee') }}">Pegawai</a>
                                </li>
                                @endif
                                @if(in_array("viewRole", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/role') }}">Hak Akses</a>
                                </li>
                                @endif
                                @if(in_array("viewShift", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/office-shift') }}">Shift Kerja</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(in_array("viewLocation", $permissions) || in_array("viewDepartment", $permissions) || in_array("viewDesignation", $permissions) || in_array("viewJobTitle", $permissions))
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fa fa-fw fa-building"></i>Perusahaan</a>
                        <div id="submenu-3" class="collapse submenu">
                            <ul class="nav flex-column">
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/company') }}">Perusahaan</a>
                                </li> -->
                                @if(in_array("viewLocation", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/company-location') }}">Lokasi Kantor</a>
                                </li>
                                @endif
                                @if(in_array("viewDepartment", $permissions) || in_array("viewDesignation", $permissions) || in_array("viewJobTitle", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/structure') }}">Bagian & Pekerjaan</a>
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
                        <?php $isAttendanceGroup = request()->is('attendance') || request()->is('attendance/upload') || request()->is('attendance/upload-from-machine') || request()->is('attendance/upload-from-machine-app'); ?>
                        <a class="nav-link {{ $isAttendanceGroup ? 'active' : '' }}" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4"><i class="fa fa-fw fa-clock"></i>Timesheet</a>
                        <div id="submenu-4" class="collapse {{ $isAttendanceGroup  ? 'show' : '' }} submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ (request()->is('attendance')) ? 'active' : '' }}" href="{{ url('/attendance') }}">Absensi</a>
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
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-payroll" aria-controls="submenu-4"><i class="fa fa-fw fa-dollar-sign"></i>Penggajian</a>
                        <div id="submenu-payroll" class="collapse submenu">
                            <ul class="nav flex-column">
                                @if(in_array("viewMonthlySalary", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/payroll') }}">Gaji Bulanan</a>
                                </li>
                                @endif
                                @if(in_array("viewDailySalary", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/daily-payroll') }}">Gaji Harian</a>
                                </li>
                                @endif
                                @if(in_array("viewThr", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/thr') . '?year=' . date('Y') }}">THR</a>
                                </li>
                                @endif
                                @if(in_array("viewLeaveSalary", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/leave-payroll') . '?year=' . date('Y') }}">Cuti</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(in_array("viewSickSubmission", $permissions) || in_array("viewPermissionSubmission", $permissions))
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-sick" aria-controls="submenu-sick"><i class="fa fa-fw fa-notes-medical"></i>Sakit & Izin</a>
                        <div id="submenu-sick" class="collapse submenu">
                            <ul class="nav flex-column">
                                @if(in_array("viewSickSubmission", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/sick') }}">Pengajuan Sakit</a>
                                </li>
                                @endif
                                @if(in_array("viewPermissionSubmission", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/permission') }}">Pengajuan Izin</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(in_array("viewSickSubmission", $permissions) || in_array("viewPermissionSubmission", $permissions))
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-leave" aria-controls="submenu-leave"><i class="fa fa-fw fa-paste"></i>Kelola Cuti</a>
                        <div id="submenu-leave" class="collapse submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/leave') }}">Data Cuti</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/leave/submission') }}">Pengajuan Cuti</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-5" aria-controls="submenu-5"><i class="fa fa-fw fa-calendar-check"></i>Event</a>
                        <div id="submenu-5" class="collapse submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/quotation-event') }}">Quotation</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/event') }}">Manage Event</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/mapping-event') }}">Mapping Event</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/freelancer') }}">Freelancer</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/report') }}"><i class="fas fa-file-alt"></i> Laporan</a>
                    </li>
                    @if(in_array("viewCalendarSetting", $permissions) || in_array("viewSalarySetting", $permissions) || in_array("viewPayrollSetting", $permissions) || in_array("viewPphSetting", $permissions) || in_array("viewBpjsSetting", $permissions) || in_array("viewLeaveSetting", $permissions) || in_array("viewPermissionSetting", $permissions))
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-6" aria-controls="submenu-6"><i class="fa fa-fw fa-cogs"></i>Pengaturan</a>
                        <div id="submenu-6" class="collapse submenu">
                            <ul class="nav flex-column">
                                @if(in_array("viewCalendarSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/setting/calendar') }}">Kalender</a>
                                </li>
                                @endif
                                @if(in_array("viewSalarySetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/setting/salary') }}">Gaji & THR</a>
                                </li>
                                @endif
                                @if(in_array("viewPayrollSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/setting/payroll') }}">Penggajian</a>
                                </li>
                                @endif
                                @if(in_array("viewPphSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/setting/pph') }}">PPh21/26</a>
                                </li>
                                @endif
                                @if(in_array("viewBpjsSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/setting/bpjs') }}">BPJS</a>
                                </li>
                                @endif
                                @if(in_array("viewLeaveSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/setting/leave') }}">Cuti</a>
                                </li>
                                @endif
                                @if(in_array("viewPermissionSetting", $permissions))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/setting/permission') }}">Izin</a>
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