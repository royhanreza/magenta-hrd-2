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

@section('pagestyle')
<style>
    .pills-regular .nav.nav-pills .nav-item .nav-link {
        font-size: 13px;
    }

    .col-form-label,
    .form-group.row label {
        font-size: 13px;
        white-space: normal;
    }

    .input-group-text {
        line-height: 0.5;
    }

    .form-group.row label {
        white-space: normal;
    }

    .input-date-bs:read-only {
        background-color: #fff;
    }
</style>
@endsection

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
                        <h2 class="pageheader-title">Employee </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item">Employee</li>
                                    <li class="breadcrumb-item active" aria-current="page">Career</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end pageheader  -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- ============================================================== -->
                <!-- basic table  -->
                <!-- ============================================================== -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    @include('employee.menu')
                    @include('employee.profile')
                    <!-- <div class="d-flex justify-content-end">
            <button class="btn btn-primary btn-sm mb-3" type="button" data-toggle="collapse" data-target="#collapsePayslip" aria-expanded="false" aria-controls="collapseExample">
              <i class="fas fa-plus"></i> Add New
            </button>
          </div> -->

                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title">Daftar Kasbon</h5>
                            <div class="toolbar ml-auto">

                            </div>
                        </div>
                        <div class="card-body">
                            <h3>Sisa pinjaman: Rp {{ number_format(($total_loan - $total_payment),0,",",".") }}</h3>
                            <span>Total pinjaman: Rp {{ number_format(($total_loan),0,",",".") }}</span><br>
                            <span>Total dibayarkan: Rp {{ number_format(($total_payment),0,",",".") }}</span>
                            <hr>



                            <div class="collapse" id="loan-form">
                                <form @submit.prevent="addLoan">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-6">
                                            <input type="date" v-model="loan.add.date" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Periode Payslip</label>
                                        <div class="col-sm-6">
                                            <!-- <input type="date" v-model="payment.add.payslipDate" class="form-control form-control-sm"> -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select v-model="loan.add.month" class="form-control form-control-sm">
                                                        <?php
                                                        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                        $monthIndex = 1;
                                                        ?>
                                                        @foreach($months as $month)
                                                        <option value="{{ sprintf('%02d', $monthIndex) }}" <?= $monthIndex == (int) date("m") ? 'selected' : '' ?>>{{ $month }}</option>
                                                        @php $monthIndex++ @endphp
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select v-model="loan.add.year" class="form-control form-control-sm">
                                                        @for($i = 2020; $i <= 2040; $i++) <option value="{{ $i }}" <?= $i == date("Y") ? 'selected' : '' ?>>{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <small>Kasbon akan dicantumkan pada payslip dengan periode yang dipilih</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Jumlah</label>
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" v-model="loan.add.amount" class="form-control form-control-sm text-right">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Angsuran</label>
                                        <div class="col-sm-3">
                                            <div class="input-group mb-3">
                                                <input type="number" v-model="loan.add.term" class="form-control form-control-sm text-right">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Bulan</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control form-control-sm text-right" :value="Math.round(loan.add.amount / loan.add.term)" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-6">
                                            <textarea v-model="loan.add.description" class="form-control form-control-sm"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-6 text-right">
                                            <button type="button" class="btn btn-light btn-sm" data-toggle="collapse" data-target="#loan-form" aria-expanded="false" aria-controls="loan-form">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loan.add.loading"><span v-if="loan.add.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                            </div>
                            <div class="collapse" id="payment-form">
                                <form @submit.prevent="addPayment">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-6">
                                            <input type="date" v-model="payment.add.date" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Periode Payslip</label>
                                        <div class="col-sm-6">
                                            <!-- <input type="date" v-model="payment.add.payslipDate" class="form-control form-control-sm"> -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select v-model="payment.add.month" class="form-control form-control-sm">
                                                        <?php
                                                        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                        $monthIndex = 1;
                                                        ?>
                                                        @foreach($months as $month)
                                                        <option value="{{ sprintf('%02d', $monthIndex) }}" <?= $monthIndex == (int) date("m") ? 'selected' : '' ?>>{{ $month }}</option>
                                                        @php $monthIndex++ @endphp
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select v-model="payment.add.year" class="form-control form-control-sm">
                                                        @for($i = 2020; $i <= 2040; $i++) <option value="{{ $i }}" <?= $i == date("Y") ? 'selected' : '' ?>>{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <small>Kasbon akan dicantumkan pada payslip dengan periode yang dipilih</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Jumlah</label>
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" v-model="payment.add.amount" class="form-control form-control-sm text-right">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-6">
                                            <textarea v-model="payment.add.description" class="form-control form-control-sm"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-6 text-right">
                                            <button type="button" class="btn btn-light btn-sm" data-toggle="collapse" data-target="#payment-form" aria-expanded="false" aria-controls="payment-form">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="payment.add.loading"><span v-if="payment.add.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                            </div>
                            @if(in_array("addEmployeeLoan", $userLoginPermissions))
                            <div id="tool-buttons">
                                <div class="d-flex justify-content-end mb-3">
                                    <button class="btn btn-primary btn-sm mr-3" data-toggle="collapse" data-target="#loan-form" aria-expanded="false" aria-controls="loan-form"><i class="fas fa-plus fa-xs"></i> Tambah Kasbon</button>
                                    <button class="btn btn-outline-primary btn-sm" data-toggle="collapse" data-target="#payment-form" aria-expanded="false" aria-controls="payment-form"><i class="fas fa-hand-holding-usd"></i> Bayar Kasbon</button>
                                </div>
                            </div>
                            @endif
                            <table class="table table-bordered use-datatable">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Periode Payslip</th>
                                        <th>Jenis</th>
                                        <th>Keterangan</th>
                                        <th>Tambah Kasbon</th>
                                        <th>Pembayaran</th>
                                        <!-- <th>Sisa Pinjaman</th> -->
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php $totalLoan = 0; ?>
                                    <?php $totalPayment = 0; ?>
                                    @if( count($loans) > 0 )
                                    @foreach($loans as $loan)
                                    <tr>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($loan->date)->isoFormat('LL') }}</td>
                                        <td class="text-center">{{ ($loan->payslip_date !== null) ?  $months[(int) date_format(date_create($loan->payslip_date), "m") - 1] . ' ' . date_format(date_create($loan->payslip_date), "Y") : '' }}</td>
                                        @if($loan->type == 'loan')
                                        <td class="text-center"><span class="badge badge-warning">Kasbon</span></td>
                                        @else
                                        <td class="text-center"><span class="badge badge-success">Potong</span></td>
                                        @endif
                                        <td>{{ $loan->description }}</td>
                                        @if($loan->type == 'loan')
                                        <td class="text-right">Rp {{ number_format($loan->amount,0,",",".") }}</td>
                                        @php
                                        $totalLoan += $loan->amount
                                        @endphp
                                        <td></td>
                                        @else
                                        <td></td>
                                        <td class="text-right">Rp {{ number_format($loan->amount,0,",",".") }}</td>
                                        @php
                                        $totalPayment += $loan->amount
                                        @endphp
                                        @endif
                                        <!-- <td class="text-right">
                      Rp {{ number_format(($totalLoan - $totalPayment),0,",",".") }}
                    </td> -->
                                        <td class="text-center">
                                            <button class="btn btn-light btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg" @click="showModal(<?php echo $loan->id ?>)"><i class="fas fa-pencil-alt"></i></button>
                                            @if($loan->type == 'payment')
                                            <button class="btn btn-light btn-sm" @click="holdPayment({{$loan->id}})">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            @endif
                                        </td>

                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada kasbon</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
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

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin: 35px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="editLoan">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-6">
                            <input type="date" v-model="payment.edit.date" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Periode Payslip</label>
                        <div class="col-sm-6">
                            <!-- <input type="date" v-model="payment.add.payslipDate" class="form-control form-control-sm"> -->
                            <div class="row">
                                <div class="col-md-6">
                                    <select v-model="payment.edit.month" class="form-control form-control-sm">
                                        <?php
                                        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                        $monthIndex = 1;
                                        ?>
                                        @foreach($months as $month)
                                        <option value="{{ sprintf('%02d', $monthIndex) }}" <?= $monthIndex == (int) date("m") ? 'selected' : '' ?>>{{ $month }}</option>
                                        @php $monthIndex++ @endphp
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select v-model="payment.edit.year" class="form-control form-control-sm">
                                        @for($i = 2020; $i <= 2040; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                            <small>Kasbon akan dicantumkan pada payslip dengan periode yang dipilih</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah</label>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                </div>
                                <input type="number" v-model="payment.edit.amount" class="form-control form-control-sm text-right">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-6">
                            <textarea v-model="payment.edit.description" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>
                    <input hidden type="text" v-model="payment.edit.id" class="form-control form-control-sm text-right">
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-6 text-right">
                            <!--<button type="button" class="btn btn-light btn-sm" data-toggle="collapse" data-target="#payment-form" aria-expanded="false" aria-controls="payment-form">Batal</button>-->
                            <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="payment.edit.loading"><span v-if="payment.edit.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>

                        </div>
                    </div>
                </form>
            </div>



        </div>
    </div>
</div>


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
            employeeId: '{{ $employee->id }}',
            loan: {
                add: {
                    date: '',
                    payslipDate: '',
                    amount: 0,
                    description: '',
                    month: '{{ date("m") }}',
                    year: '{{ date("Y") }}',
                    term: 1,
                    paymentPerMonth: 0,
                    loading: false,
                },
                edit: {
                    date: '',
                    amount: '',
                    description: '',
                    loading: false,
                },
            },
            payment: {
                add: {
                    date: '',
                    amount: '',
                    description: '',
                    month: '{{ date("m") }}',
                    year: '{{ date("Y") }}',
                    loading: false,
                },
                edit: {
                    date: '',
                    amount: '',
                    description: '',
                    loading: false,
                },
            }
        },
        methods: {
            addLoan: function() {
                let vm = this;
                vm.loan.add.loading = true;
                // const payslipDate = `${vm.loan.add.year}-${vm.loan.add.month}-${vm.loan.add.date.split('-')[2]}`;
                const payslipDate = `${vm.loan.add.year}-${vm.loan.add.month}-10`;
                axios.post('/loan-v2', {
                        employee_id: vm.employeeId,
                        date: vm.loan.add.date,
                        payslip_date: payslipDate,
                        amount: vm.loan.add.amount,
                        term: vm.loan.add.term,
                        description: vm.loan.add.description,
                        type: 'loan',
                    })
                    .then(function(response) {
                        vm.loan.add.loading = false;
                        // window.location.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data berhasil disimpan',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                        // console.log(response)
                    })
                    .catch(function(error) {
                        vm.loan.add.loading = false;
                        Swal.fire({
                            title: 'Error',
                            text: "Data gagal disimpan",
                            icon: 'error'
                        })
                        console.log(error);
                    });
            },
            addPayment: function() {
                let vm = this;
                vm.payment.add.loading = true;
                const payslipDate = `${vm.payment.add.year}-${vm.payment.add.month}-10`;
                axios.post('/loan', {
                        employee_id: vm.employeeId,
                        date: vm.payment.add.date,
                        payslip_date: payslipDate,
                        amount: vm.payment.add.amount,
                        description: vm.payment.add.description,
                        type: 'payment',
                    })
                    .then(function(response) {
                        vm.payment.add.loading = false;
                        // window.location.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data berhasil disimpan',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                        // console.log(response)
                    })
                    .catch(function(error) {
                        vm.payment.add.loading = false;
                        Swal.fire({
                            title: 'Error',
                            text: "Data gagal disimpan",
                            icon: 'error'
                        })
                        console.log(error);
                    });
            },
            showModal: function(id) {
                let vm = this;

                axios.get(`/loan/data-loan/${id}`)
                    .then(function(response) {
                        console.log(response.data.data)
                        vm.payment.edit.description = `${response.data.data[0].description}`;
                        vm.payment.edit.amount = `${response.data.data[0].amount}`;
                        vm.payment.edit.date = `${response.data.data[0].date}`;
                        vm.payment.edit.id = `${response.data.data[0].id}`;
                        console.log(response.data.data[0].payslip_date);
                        const payslipMonth = response.data.data[0].payslip_date;
                        const month = payslipMonth.split('-')[1];
                        const year = payslipMonth.split('-')[0];
                        vm.payment.edit.year = year;
                        vm.payment.edit.month = month;


                    })

            },
            editLoan: function() {
                let vm = this;
                console.log(vm.payment.edit.id)
                console.log(vm.payment.edit.amount)
                console.log(vm.payment.edit.description)


                vm.payment.edit.loading = true;
                const payslipDate = `${vm.payment.edit.year}-${vm.payment.edit.month}-10`;
                axios.patch(`/loan/${vm.payment.edit.id}`, {

                        date: vm.payment.edit.date,
                        payslip_date: payslipDate,
                        amount: vm.payment.edit.amount,
                        description: vm.payment.edit.description,

                    })
                    .then(function(response) {
                        vm.payment.edit.loading = false;
                        // window.location.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data berhasil disimpan',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                        // console.log(response)
                    })
                    .catch(function(error) {
                        vm.payment.edit.loading = false;
                        Swal.fire({
                            title: 'Error',
                            text: "Data gagal disimpan",
                            icon: 'error'
                        })
                        console.log(error);
                    });
            },
            holdPayment: function(id) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Pembayaran akan dialihkan ke bulan terakhir pembayaran",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Alihkan',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.post('/loan-v2/action/hold/' + id)
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
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            // text: 'Data has been deleted',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            }
        },
    })
</script>
<script>
    $(function() {
        // $('table.use-datatable').DataTable({
        //   "order": [
        //     [0, "desc"]
        //   ],
        //   "searching": false,
        //   "paging": false,
        // });

        $('#loan-form').on('show.bs.collapse', function() {
            $('#tool-buttons').hide();
        });
        $('#loan-form').on('hide.bs.collapse', function() {
            $('#tool-buttons').show();
        });

        $('#payment-form').on('show.bs.collapse', function() {
            $('#tool-buttons').hide();
        });
        $('#payment-form').on('hide.bs.collapse', function() {
            $('#tool-buttons').show();
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
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batalkan',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios.delete('/employee/' + id)
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
                allowOutsideClick: () => !Swal.isLoading(),
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
    })
</script>
@endsection