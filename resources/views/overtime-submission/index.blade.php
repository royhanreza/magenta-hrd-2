@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/buttons.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/select.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/fixedHeader.bootstrap4.css') }}"> -->
@endsection

@section('title', 'Pengajuan Lembur | Magenta HRD')

@section('content')
@php
$userLoginPermissions = [];
if (request()->session()->has('userLoginPermissions')) {
$userLoginPermissions = request()->session()->get('userLoginPermissions');
}
@endphp
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
                        <h2 class="pageheader-title">Pengajuan Lembur</span></h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pengajuan Lembur</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end pageheader  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- page nav  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- end page nav  -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- ============================================================== -->
                <!-- basic table  -->
                <!-- ============================================================== -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title">Daftar Pengajuan Lembur <span class="text-capitalize">(@{{ listStatus ? listStatus : 'Semua' }}) </span> </h5>
                            <div class="toolbar ml-auto">
                                <a class="btn btn-dark btn-sm" data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse"><i class="fas fa-fw fa-filter"></i> Filter</a>
                                <a href="{{ url('overtime-submission/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="collapse" id="filterCollapse">
                                <div class="card card-body">
                                    <h4>Filter</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="statusFilter" class="col-sm-2 col-form-label">Status</label>
                                                <div class="col-sm-10">
                                                    <select v-model="filter.status" id="filter-status" class="form-control">
                                                        <option value="">Semua</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="approved">Approved (Disetujui)</option>
                                                        <option value="rejected">Rejected (Ditolak)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button class="btn btn-primary btn-sm btn-apply-filter">Terapkan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped use-datatable">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th>Nama Pegawai</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Tanggal Lembur</th>
                                            <th>Jam Mulai</th>
                                            <th>Jam Selesai</th>
                                            <th>Uraian Pekerjaan</th>
                                            <th>Catatan</th>
                                            <th>Status</th>
                                            <th>Approval</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end basic table  -->
                <!-- ============================================================== -->
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->

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
<!-- additional script -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
    let app = new Vue({
        el: '#app',
        data: {
            filter: {
                status: 'pending',
            },
            listStatus: 'pending',
        },
        methods: {
            applyFilter() {
                document.location.href = '/'
            }
        }
    })
</script>

<script>
    $(function() {

        const datatable = $('table.use-datatable').DataTable({
            "order": [
                [1, "desc"]
            ],
            processing: true,
            serverSide: true,
            ajax: '/datatables/overtime-submissions?status=pending',
            columns: [{
                    data: 'employee.first_name',
                    name: 'employee.first_name',
                },
                {
                    data: 'date_of_filing',
                    name: 'overtime_submissions.date_of_filing',
                    className: 'text-center'
                },
                {
                    data: 'date',
                    name: 'overtime_submissions.date',
                    className: 'text-center'
                },
                {
                    data: 'overtime_start',
                    name: 'overtime_submissions.overtime_start',
                    className: 'text-center'
                },
                {
                    data: 'overtime_end',
                    name: 'overtime_submissions.overtime_end',
                    className: 'text-center'
                },
                {
                    data: 'work',
                    name: 'overtime_submissions.work',
                    render: function(data, type, row) {
                        if (data) {
                            return `<button type="button" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="${ data }">
                        <i class="fas fa-sticky-note"></i>
                        </button>`
                        }
                        return null;
                    },
                    className: 'text-center'
                },
                {
                    data: 'note',
                    name: 'overtime_submissions.note',
                    render: function(data, type, row) {
                        if (data) {
                            return `<button type="button" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="${ data }">
                        <i class="fas fa-sticky-note"></i>
                        </button>`
                        }

                        return null;
                    },
                    className: 'text-center'
                },
                {
                    data: 'status',
                    name: 'overtime_submissions.status',
                    render: function(data, type, row) {
                        let badgeType = 'primary';
                        if (data == 'pending') {
                            badgeType = 'warning';
                        } else if (data == 'approved') {
                            badgeType = 'success';
                        } else if (data == 'rejected') {
                            badgeType = 'danger';
                        }

                        return `<span class="badge badge-${badgeType} text-capitalize"><small>${data}</small></span>`
                    },
                    className: 'text-center'
                },
                {
                    data: 'approval',
                    name: 'approval',
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    className: 'text-center'
                },
            ]
        });

        function deleteOvertimeSubmission(id) {
            console.log(id)
            // const id = $(this).attr('data-id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data absensi di tanggal lembur akan dihapus",
                icon: 'warning',
                reverseButtons: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios.delete('/overtime-submission/' + id)
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
                    datatable.ajax.reload();
                }
            })
        }

        function approveOvertimeSubmission(id) {
            console.log(id)
            // const id = $(this).attr('data-id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Pengajuan lembur akan disetujui",
                icon: 'warning',
                reverseButtons: true,
                showCancelButton: true,
                confirmButtonColor: '#2ec551',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios.post('/api/overtime-submissions/action/approve/' + id)
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
                        text: 'Data has been approved',
                    })
                    datatable.ajax.reload();
                }
            })
        };

        function rejectOvertimeSubmission(id) {
            console.log(id)
            // const id = $(this).attr('data-id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Pengajuan lembur akan ditolak",
                icon: 'warning',
                reverseButtons: true,
                showCancelButton: true,
                // confirmButtonColor: '#2ec551',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios.post('/api/overtime-submissions/action/reject/' + id)
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
                        text: 'Data has been rejected',
                    })
                    datatable.ajax.reload();
                }
            })
        }

        $('.use-datatable').on('click', 'tr td .btn-delete', function(e) {
            const id = $(this).attr('data-id');
            if (!id) {
                return alert('Missing ID')
            }
            deleteOvertimeSubmission(id);
        })

        $('.use-datatable').on('click', 'tr td .btn-approve', function(e) {
            const id = $(this).attr('data-id');
            if (!id) {
                return alert('Missing ID')
            }
            approveOvertimeSubmission(id);
        })

        $('.use-datatable').on('click', 'tr td .btn-reject', function(e) {
            const id = $(this).attr('data-id');
            if (!id) {
                return alert('Missing ID')
            }
            rejectOvertimeSubmission(id);
        })

        $('.btn-apply-filter').on('click', function(e) {
            // console.log(status);
            // alert('clicked', status);
            applyFitler();
        })

        const applyFitler = () => {
            const queries = [];
            const status = $('#filter-status').val();
            if (status) {
                queries.push('status=' + status);
            }
            const queryString = queries.join('&');

            const url = '/datatables/overtime-submissions?' + queryString;

            datatable.ajax.url(url).load();

            app.$data.listStatus = status;
        }
    })

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
</script>
@endsection