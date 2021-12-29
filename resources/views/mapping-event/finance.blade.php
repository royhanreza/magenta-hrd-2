@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/buttons.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/select.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/fixedHeader.bootstrap4.css') }}"> -->
@endsection

@section('title', 'Magenta HRD')

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
                        <h2 class="pageheader-title">Mapping Event </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/mapping-event" class="breadcrumb-link">Mapping Event</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Finance</li>
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
            <!-- ============================================================== -->
            <!-- summary  -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- ============================================================== -->
                <!-- sales  -->
                <!-- ============================================================== -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="card border-3 border-top border-top-primary">
                        <div class="card-body">
                            <h5 class="text-muted">Total Cash In <span class="icon-circle-small icon-box-xs text-success bg-success-light"><i class="fa fa-fw fa-arrow-down"></i></span></h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">Rp. {{ number_format($total_income, 0, "", ".") }}</h1>
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
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="card border-3 border-top border-top-primary">
                        <div class="card-body">
                            <h5 class="text-muted">Total Cash Out <span class="icon-circle-small icon-box-xs text-danger bg-danger-light"><i class="fa fa-fw fa-arrow-up"></i></span></h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">Rp. {{ number_format($total_expense, 0, "", ".") }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end new customer  -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- total orders  -->
                <!-- ============================================================== -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="card border-3 border-top border-top-primary">
                        <div class="card-body">
                            <h5 class="text-muted">Balance <span class="icon-circle-small icon-box-xs text-success bg-success-light"><i class="fa fa-fw fa-dollar-sign"></i></span></h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1">Rp. {{ number_format(($total_income - $total_expense), 0, "", ".") }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end total orders  -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- end summary  -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- ============================================================== -->
                <!-- basic table  -->
                <!-- ============================================================== -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title">List All Budgets</h5>
                            <div class="toolbar ml-auto">
                                <a href="/mapping-event/{{ $event->id }}/budget" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add Balance</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered use-datatable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Cash In</th>
                                            <th>Cash Out</th>
                                            <th>Balance</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($budgets as $budget)
                                        <tr>
                                            <td>{{ date_format(date_create($budget->date), "d-m-Y  H:i:s") }}</td>
                                            <td class="text-capitalize">{{ $budget->type }}</td>
                                            <td>{{ $budget->note }}</td>
                                            <td class="text-right">@if($budget->type == 'income') Rp. {{ number_format($budget->amount, 0, "", ".") }} @endif</td>
                                            <td class="text-right">@if($budget->type == 'expense') Rp. {{ $budget->amount }} @endif</td>
                                            <td class="text-right">Rp. {{ number_format($budget->balance, 0, "", ".") }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-image"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Cash In</th>
                                            <th>Cash Out</th>
                                            <th>Balance</th>
                                            <th>Image</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title">Pending Budgets</h5>
                            <!-- <div class="toolbar ml-auto">
                                <a href="/mapping-event/{{ $event->id }}/budget" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-share-square"></i> See Rejected Budgets</a>
                            </div> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered use-datatable">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pending_budgets as $budget)
                                        <tr>
                                            <td>{{ date_format(date_create($budget->date), "d-m-Y H:i:s") }}</td>
                                            <td class="text-capitalize">{{ $budget->type }}</td>
                                            <td>{{ $budget->note }}</td>
                                            <td class="text-right">Rp {{ number_format($budget->amount, 0, "", ".") }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group" aria-label="Action Buttons">
                                                    <button type="button" class="btn btn-sm btn-light btn-reject" data-id="{{ $budget->id }}"><i class="fas fa-fw fa-times"></i></button>
                                                    <button type="button" class="btn btn-sm btn-light btn-approve" data-id="{{ $budget->id }}"><i class="fas fa-fw fa-check"></i></button>
                                                    <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-image"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title">Rejected Budgets</h5>
                            <!-- <div class="toolbar ml-auto">
                                <a href="/mapping-event/{{ $event->id }}/budget" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-share-square"></i> See Rejected Budgets</a>
                            </div> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered use-datatable">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Amount</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rejected_budgets as $budget)
                                        <tr>
                                            <td>{{ date_format(date_create($budget->date), "d-m-Y H:i:s") }}</td>
                                            <td class="text-capitalize">{{ $budget->type }}</td>
                                            <td>{{ $budget->note }}</td>
                                            <td class="text-right">Rp {{ number_format($budget->amount, 0, "", ".") }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group" aria-label="Action Buttons">
                                                    <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-image"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Note</th>
                                            <th>Amount</th>
                                            <th>Image</th>
                                        </tr>
                                    </tfoot>
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
    $(function() {
        $('table.use-datatable').DataTable({
            "order": [[ 0, "desc" ]]
        });

        $('.btn-approve').on('click', function() {
            const id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                },
                inputLabel: 'Approval Note (Optional)',
                text: "Data will be approved",
                icon: 'warning',
                reverseButtons: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: (note) => {
                    return axios.post('/event-budget/' + id + '/approve', {
                            approval_note: note,
                            approved_by: 1,
                        })
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
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    })
                }
            })
        })

        $('.btn-reject').on('click', function() {
            const id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                },
                inputLabel: 'Rejection Note (Optional)',
                text: "Data will be rejected",
                icon: 'warning',
                reverseButtons: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: (note) => {
                    return axios.post('/event-budget/' + id + '/reject', {
                            rejection_note: note,
                            rejected_by: 1,
                        })
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
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    })
                }
            })
        })
    })
</script>
@endsection