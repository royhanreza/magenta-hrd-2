@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/buttons.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/select.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/fixedHeader.bootstrap4.css') }}">
@endsection

@section('title', 'Magenta HRD')

@section('content')
@php
$userLoginPermissions = [];
if (request()->session()->has('userLoginPermissions')) {
$userLoginPermissions = request()->session()->get('userLoginPermissions');
}
@endphp
<!--============================================================== -->
<!--wrapper  -->
<!--============================================================== -->
<div class="dashboard-wrapper">
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!--============================================================== -->
            <!--pageheader  -->
            <!--============================================================== -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <h2 class="pageheader-title">Penggajian</h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item">Penggajian</li>
                                    <li class="breadcrumb-item active" aria-current="page">Gaji & THR</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!--============================================================== -->
            <!--end pageheader  -->
            <!--============================================================== -->
            <!--============================================================== -->
            <!--page nav  -->
            <!--============================================================== -->

            <!--============================================================== -->
            <!--end page nav  -->
            <!--============================================================== -->
            <div class="row">
                <!--============================================================== -->
                <!--basic table  -->
                <!--============================================================== -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title">List Slip Gaji Harian</h5>
                            @if(in_array("addDailySalary", $userLoginPermissions))
                            <div class="toolbar ml-auto">
                                <div class="d-flex">
                                    <div class="dropdown mr-3">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Download Laporan
                                        </button>
                                        <?php
                                        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                        ?>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @foreach($months as $key => $month)
                                            <a href="/daily-payroll/report/sheet?month={{ ($key + 1) > 9 ? ($key + 1) : '0' . ($key + 1)  }}&year={{ date('Y') }}" class="dropdown-item" target="_blank"><i class="fas fa-download"></i> Laporan Bulan {{ $month }}</a>
                                            @endforeach
                                            <!--<a class="dropdown-item" href="#">Action</a>-->
                                            <!--<a class="dropdown-item" href="#">Another action</a>-->
                                            <!--<a class="dropdown-item" href="#">Something else here</a>-->
                                        </div>
                                    </div>
                                    <a href="{{ url('daily-payroll/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Generate Slip</a>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select class="form-control select-year">
                                        @for($i = 2020; $i <= 2050; $i++) <option value="{{ $i }}" {{ ($i == $year) ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                            <ul>
                                @foreach($months as $index => $month)
                                <li><strong>{{ $month }}</strong></li>
                                @if(array_key_exists($index, $payslips))
                                <ul>
                                    @foreach($payslips[$index] as $period => $payslip)
                                    <?php
                                    $startDate = explode('/', $period)[0];
                                    $endDate = explode('/', $period)[1];
                                    ?>
                                    <li class="my-2">Periode <a href="/daily-payroll/show-by-date?start_date={{$startDate}}&end_date={{ $endDate }}" class="text-primary">{{ \Carbon\Carbon::parse($startDate)->isoFormat('ll') }} - {{ \Carbon\Carbon::parse($endDate)->isoFormat('ll') }}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!--============================================================== -->
                <!--end basic table  -->
                <!--============================================================== -->
            </div>
        </div>
    </div>
    <!--============================================================== -->
    <!--footer -->
    <!--============================================================== -->

    <!--============================================================== -->
    <!--end footer -->
    <!--============================================================== -->
</div>
<!--============================================================== -->
<!--end wrapper  -->
<!--============================================================== -->
@endsection

@section('script')
slimscroll js
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
additional script
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
main js
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
    let app = new Vue({
        el: '#app',
        data: {

        },
        methods: {
            deletePayslip: function(id) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan dihapus",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.delete('/daily-payroll/' + id)
                            .then(function(response) {
                                console.log(response.data);
                            })
                            .catch(function(error) {
                                console.log(error.data);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops',
                                    text: 'Something wrong',
                                })
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data berhasil dihapus',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            }
        }
    })
</script>
<script>
    $(function() {
        $('table.use-datatable').DataTable({
            "order": [
                [4, "asc"]
            ]
            // "searching": false,
        });

        $('.btn-delete').on('click', function() {
            const id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "The data will be deleted",
                icon: 'warning',
                reverseButtons: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios.delete('/company/' + id)
                        .then(function(response) {
                            console.log(response.data);
                        })
                        .catch(function(error) {
                            console.log(error.data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops',
                                text: 'Something wrong',
                            })
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data has been deleted',
                    })
                }
            })
        })

        $('.select-year').on('change', function() {
            const year = $(this).val();
            window.location.href = "/daily-payroll?year=" + year;
        })
    })
</script>
@endsection