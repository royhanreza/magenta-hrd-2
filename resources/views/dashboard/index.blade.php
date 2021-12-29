@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/charts/chartist-bundle/chartist.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/charts/morris-bundle/morris.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/charts/c3charts/c3.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icon-css/flag-icon.min.css') }}">
@endsection

@section('title', 'Magenta HRD')

<?php
function rupiahFormat($number)
{
    return number_format($number, 0, ',', '.');
}
?>

@section('content')
<!-- ============================================================== -->
<!-- wrapper  -->
<!-- ============================================================== -->
<div class="dashboard-wrapper">
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- ============================================================== -->
            <!-- pageheader  -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <h2 class="pageheader-title">Dashboard </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Main Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end pageheader  -->
            <!-- ============================================================== -->
            <div>
                <div class="card  border-3 border-top border-top-primary">
                    <div class="card-body">
                        <?php
                        $greeting = 'pagi';
                        $currentTime = date('H');
                        if ($currentTime >= '19' && $currentTime < '03') {
                            $greeting = 'malam';
                        } else if ($currentTime >= '03' && $currentTime < '11') {
                            $greeting = 'pagi';
                        } else if ($currentTime >= '11' && $currentTime < '15') {
                            $greeting = 'siang';
                        } else {
                            $greeting = 'sore';
                        }

                        ?>
                        <h3>Selamat {{ $greeting }}, {{ Auth::user()->first_name }}</h3>
                        <p>Hari ini {{ \Carbon\Carbon::now()->isoFormat('LLLL') }}</p>
                        <h4>Shortcut</h4>
                        <div>
                            <a href="/employee" class="btn btn-rounded btn-light">Pegawai</a>
                            <a href="/attendance" class="btn btn-rounded btn-light">Absensi</a>
                            <a href="/payroll" class="btn btn-rounded btn-light">Gaji Bulanan</a>
                            <a href="/daily-payroll" class="btn btn-rounded btn-light">Gaji Harian</a>
                            <a href="/attendance/upload-from-machine-app" class="btn btn-rounded btn-light">Impor Absensi Dari Aplikasi Mesin</a>
                        </div>
                    </div>
                </div>
            </div>
            @if(date("Y-m-d") < date("2022-01-01")) <div class="alert alert-primary" role="alert">
                <h4 class="alert-heading">Magenta HRD v1.4 Beta (29 Desember 2021)</h4>
                <hr>
                <strong>New features:</strong>
                <ul>
                    <li>Dashboard (Shortcut, Summary Pegawai, Summary Absensi, Pengajuan sakit, cuti, izin pending)</li>
                    <li>Reset / Hapus Absensi</li>
                    <li>Highlight sidebar menu</li>
                </ul>
        </div>
        @endif
        <div class="ecommerce-widget">

            <div class="row">
                <!-- ============================================================== -->
                <!-- sales  -->
                <!-- ============================================================== -->
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Total Pegawai</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">{{ rupiahFormat($total_employees) }}</h1>
                            </div>
                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                <span class="icon-circle-small icon-box-xs text-primary bg-primary-light"><i class="fa fa-fw fa-users"></i></span>
                                <!-- <span class="ml-1">5.86%</span> -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end sales  -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- new customer  -->
                <!-- ============================================================== -->
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Pegawai Aktif</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">{{ rupiahFormat($active_employees) }}</h1>
                            </div>
                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                <span class="icon-circle-small icon-box-xs text-success bg-success-light"><i class="fa fa-fw fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end new customer  -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- visitor  -->
                <!-- ============================================================== -->
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted">Pegawai Nonaktif</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">{{ rupiahFormat($inactive_employees) }}</h1>
                            </div>
                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                <span class="icon-circle-small icon-box-xs text-danger bg-danger-light"><i class="fa fa-fw fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end visitor  -->
                <!-- ============================================================== -->
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title mb-0">Ringkasan Absensi Hari Ini</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartjs_pie"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title mb-0">Pengajuan Absensi (Pending)</h5>
                        </div>
                        <div class="card-body">
                            <div class="p-3">
                                <div class="row">
                                    <?php
                                    $pendingSickCount = collect($pending_submissions)->where('type', 'sakit')->count();
                                    $pendingPermissionCount = collect($pending_submissions)->where('type', 'izin')->count();
                                    $pendingLeaveCount = collect($pending_submissions)->where('type', 'cuti')->count();
                                    ?>
                                    <div class="col-md-4 col-sm-12">
                                        <span class="badge-dot badge-warning"></span>
                                        <a href="/sick">Sakit: <strong>{{ $pendingSickCount }}</strong></a>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <span class="badge-dot badge-primary"></span>
                                        <a href="/permission">Izin: <strong>{{ $pendingPermissionCount }}</strong></a>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <span class="badge-dot badge-info"></span>
                                        <a href="/leave/submission">Cuti: <strong>{{ $pendingLeaveCount }}</strong></a>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php
                                $take = 5;
                                $cut_pending_submissions = collect($pending_submissions)->take($take)->all();
                                ?>
                                @foreach($cut_pending_submissions as $submission)
                                <?php
                                $typeBadgeColor = 'light';
                                $employeeLink = 'detail';
                                if ($submission->type == 'izin') {
                                    $typeBadgeColor = 'primary';
                                    $employeeLink = 'permission';
                                } else if ($submission->type == 'sakit') {
                                    $typeBadgeColor = 'warning';
                                    $employeeLink = 'sick';
                                } else if ($submission->type == 'cuti') {
                                    $typeBadgeColor = 'info';
                                    $employeeLink = 'leave';
                                }
                                ?>
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-sm-4">
                                            <span>
                                                <i class="fas fa-calendar-alt"></i>
                                                <small>Tanggal Pengajuan</small> <br>
                                                {{ $submission->date_of_filing }}
                                            </span>
                                        </div>
                                        <div class="col-sm-5">
                                            @if($submission->employee !== null)
                                            <a href="/employee/{{ $employeeLink }}/{{ $submission->employee->id }}"> {{ $submission->employee->first_name }}</a>
                                            @endif
                                        </div>
                                        <div class="col-sm-3 text-right">

                                            <span class="badge badge-pill badge-{{ $typeBadgeColor }} text-capitalize">{{ $submission->type }}</span>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- footer -->
<!-- ============================================================== -->
@include('layouts.footer')
<!-- ============================================================== -->
<!-- end footer -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- end wrapper  -->
<!-- ============================================================== -->
@endsection

@section('script')
<!-- slimscroll js -->
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
<!-- chart js -->
<script src="{{ asset('vendor/charts/charts-bundle/Chart.bundle.js') }}"></script>
<!-- moment js -->
<script src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
@endsection

@section('pagescript')

<script>
    $(function() {

        if ($('#chartjs_pie').length) {
            var ctx = document.getElementById("chartjs_pie").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ["Hadir", "Sakit", "Izin", "Cuti", "Rejected", "N/A (Belum ada status)"],
                    datasets: [{
                        backgroundColor: [
                            "#21ae41",
                            "#f3b600",
                            "#5969ff",
                            "#0998b0",
                            "#da0419",
                            "#efeff6",
                        ],
                        data: ["{{ $summary['present_count'] }}", "{{ $summary['sick_count'] }}", "{{ $summary['permission_count'] }}", "{{ $summary['leave_count'] }}", "{{ $summary['rejected_count'] }}", "{{ count($attendances) - ($summary['present_count'] - $summary['sick_count']) - $summary['permission_count'] - $summary['leave_count'] - $summary['rejected_count'] }}"]
                    }]
                },
                options: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            fontColor: '#71748d',
                            fontFamily: 'Circular Std Book',
                            fontSize: 14,
                        }
                    },


                }
            });
        }
    })
</script>
@endsection