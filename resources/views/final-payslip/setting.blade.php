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
            <h2 class="pageheader-title">Slip Gaji</h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item">Penggajian</li>
                  <li class="breadcrumb-item active" aria-current="page">Slip Gaji</li>
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
              <h5 class="card-header-title"><i class="fas fa-cog"></i> Atur Slip Gaji</h5>
            </div>
            <div class="card-body">
              <h4><i class="fas fa-user"></i> Informasi Pegawai</h4>
              <div class="mb-5">
                <table class="table table-bordered ">
                  <thead class="bg-light">
                    <tr class="text-center">
                      <td>ID Pegawai</td>
                      <td>Nama</td>
                      <td>Departemen</td>
                      <td>Bagian</td>
                      <td>Job Title</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="text-center">
                      <td>{{ $payslip->employee->employee_id }}</td>
                      <td>{{ $payslip->employee->first_name }}</td>
                      @if($payslip->employee->activeCareer !== null)
                      <td>{{ $payslip->employee->careers[0]->department->name }}</td>
                      <td>{{ $payslip->employee->careers[0]->designation->name }}</td>
                      @if(count($payslip->employee->careers) > 0)
                        @if($payslip->employee->careers[0]->jobTitle !== null)
                      <td>{{ $payslip->employee->careers[0]->jobTitle->name }}</td>
                        @endif
                      @endif
                      @else
                      <td></td>
                      <td></td>
                      <td></td>
                      @endif
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- <div class="text-right mb-3">
                <button class="btn btn-success btn-sm"><i class="fas fa-briefcase-medical"></i> Atur BPJS</button>
              </div> -->

              <h4><i class="fas fa-file-alt"></i> Slip Gaji</h4>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <table class="table">
                    <thead class="bg-light">
                      <tr>
                        <td>Income</td>
                        <td class="text-right">Amount</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(income, index) in incomes" :key="income.id">
                        <td>
                          <div v-if="income.is_added == '1'" class="row align-items-center">
                            <div class="col-sm-2 text-center">
                              <a v-if="income.is_loan == '1'" href="#" @click.prevent="deleteLoan(index)"><i class="fas fa-trash-alt text-danger"></i></a>
                              <a v-else href="#" @click.prevent="deleteIncome(index)"><i class="fas fa-trash-alt text-danger"></i></a>
                            </div>
                            <div class="col-sm-10">
                              <span>@{{ income.name }}</span><br>
                              <div v-if="!income.is_loan">
                                <small class="text-muted">Penambah THP: @{{ (income.adder == '1') ? 'Ya' : 'Tidak' }}</small><br>
                                <small class="text-muted">Dipotong Pajak: @{{ (income.tax == '1') ? 'Ya' : 'Tidak' }}</small>
                              </div>
                            </div>
                          </div>
                          <span v-else>@{{ income.name }}</span>
                        </td>
                        <td class="text-right">Rp @{{ Intl.NumberFormat('de-DE').format(income.value) }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="mt-2 text-right">
                    <!-- <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addIncomeModal"><i class="fas fa-plus-circle"></i> Tambah Income</button> -->
                    <div class="dropdown">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-plus-circle"></i> Tambah
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addIncomeModal">Tambah Income</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addLoanModal">Tambah Kasbon</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <table class="table">
                    <thead class="bg-light">
                      <tr>
                        <td>Deduction</td>
                        <td class="text-right">Amount</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(deduction, index) in deductions" :key="deduction.id">
                        <!-- <td>
                          <span>@{{ deduction.name }}</span>
                        </td> -->
                        <td>
                          <div v-if="deduction.is_added == '1'" class="row align-items-center">
                            <div class="col-sm-2 text-center">
                              <a v-if="deduction.is_loan == '1'" href="#" @click.prevent="deletePayLoan(index)"><i class="fas fa-trash-alt text-danger"></i></a>
                              <a v-else href="#" @click.prevent="deleteDeduction(index)"><i class="fas fa-trash-alt text-danger"></i></a>
                            </div>
                            <div class="col-sm-10">
                              <span>@{{ deduction.name }}</span><br>
                              <small v-if="!deduction.is_loan" class="text-muted">Pengurang Pajak: @{{ (deduction.tax_deduction == '1') ? 'Ya' : 'Tidak' }}</small>
                            </div>
                          </div>
                          <span v-else>@{{ deduction.name }}</span>
                        </td>
                        <td class="text-right">Rp @{{ Intl.NumberFormat('de-DE').format(deduction.value) }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="mt-2 text-right">
                    <!-- <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addDeductionModal"><i class="fas fa-plus-circle"></i> Tambah Deduction</button> -->
                    <div class="dropdown">
                      <button class="btn btn-danger btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-plus-circle"></i> Tambah
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addDeductionModal">Tambah Deduction</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addPayLoanModal">Bayar Kasbon</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-lg-6 col-md-12">
                  <table class="table">
                    <tr class="bg-light">
                      <th>Total Income</th>
                      <th class="text-right">Rp @{{ Intl.NumberFormat('de-DE').format(totalIncome) }}</th>
                    </tr>
                  </table>
                </div>
                <div class="col-lg-6 col-md-12">
                  <table class="table">
                    <tr class="bg-light">
                      <th>Total Deduction</th>
                      <th class="text-right">Rp @{{ Intl.NumberFormat('de-DE').format(totalDeduction) }}</th>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-lg-6 col-md-12">
                </div>
                <div class="col-lg-6 col-md-12">
                  <table class="table">
                    <tr class="bg-light">
                      <th>Take Home Pay</th>
                      <th class="text-right">Rp @{{ Intl.NumberFormat('de-DE').format(totalIncome - totalDeduction) }}</th>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="text-right mt-3">
                <button class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Kembali</button>
                <a href="/payroll/print/{{ $payslip->id }}" target="_blank" class="btn btn-light btn-sm"><i class="fas fa-print"></i> Cetak</a>
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
<!-- Modal -->
<div class="modal fade" id="addIncomeModal" tabindex="-1" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addIncomeModalLabel">Tambah Pendapatan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addIncome">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Nama Pendapatan</label>
            <div class="col-sm-8">
              <input type="text" v-model="incomeModel.name" class="form-control form-control-sm" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Jumlah</label>
            <div class="col-sm-8">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp</span>
                </div>
                <input type="text" v-model="incomeModel.value" class="form-control form-control-sm text-right" required>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Penambah THP (Take Home Pay)</label>
            <div class="col-sm-8">
              <select v-model="incomeModel.adder" class="form-control form-control-sm" required>
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Dipotong Pajak</label>
            <div class="col-sm-8">
              <select v-model="incomeModel.tax" class="form-control form-control-sm" required>
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" v-bind:disabled="incomeModel.loading"><span v-if="incomeModel.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Deduction -->
<div class="modal fade" id="addDeductionModal" tabindex="-1" aria-labelledby="addDeductionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDeductionModalLabel">Tambah Pendapatan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addDeduction">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Nama Potongan</label>
            <div class="col-sm-8">
              <input type="text" v-model="deductionModel.name" class="form-control form-control-sm" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Jumlah</label>
            <div class="col-sm-8">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp</span>
                </div>
                <input type="text" v-model="deductionModel.value" class="form-control form-control-sm text-right" required>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Pengurang Pajak</label>
            <div class="col-sm-8">
              <select v-model="deductionModel.taxDeduction" class="form-control form-control-sm" required>
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" v-bind:disabled="deductionModel.loading"><span v-if="deductionModel.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Add Loan -->
<div class="modal fade" id="addLoanModal" tabindex="-1" aria-labelledby="addLoanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addLoanModalLabel">Tambah Kasbon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addLoan">
        <div class="modal-body">
          <p class="text-right">Kasbon saat ini: <strong>Rp {{ $remaining_loan }}</strong></p>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Jumlah</label>
            <div class="col-sm-8">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp</span>
                </div>
                <input type="text" v-model="loanModel.value" class="form-control form-control-sm text-right" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" v-bind:disabled="loanModel.loading"><span v-if="loanModel.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Add Pay Loan -->
<div class="modal fade" id="addPayLoanModal" tabindex="-1" aria-labelledby="addPayLoanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPayLoanModalLabel">Tambah Kasbon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addPayLoan">
        <div class="modal-body">
          <p class="text-right">Kasbon saat ini: <strong>Rp {{ $remaining_loan }}</strong></p>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Jumlah</label>
            <div class="col-sm-8">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp</span>
                </div>
                <input type="text" v-model="payLoanModel.value" class="form-control form-control-sm text-right" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" v-bind:disabled="payLoanModel.loading"><span v-if="payLoanModel.loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
        </div>
      </form>
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
      employeeId: '{{ $payslip->employee_id }}',
      incomes: JSON.parse('{!! $payslip->income !!}'),
      deductions: JSON.parse('{!! $payslip->deduction !!}'),
      incomeModel: {
        name: '',
        value: '',
        adder: 1,
        tax: 0,
        loading: false,
      },
      deductionModel: {
        name: '',
        value: '',
        taxDeduction: 0,
        loading: false,
      },
      loanModel: {
        value: '',
        loading: false,
      },
      payLoanModel: {
        value: '',
        loading: false,
      },
    },
    methods: {
      addIncome: function() {
        this.incomeModel.loading = true;
        // const newIncome = this.incomes.push({
        //   name: this.incomeModel.name,
        //   value: this.incomeModel.value,
        //   adder: this.incomeModel.adder,
        //   text: this.incomeModel.text,
        //   is_added: 1,
        // })
        const newIncome = [...this.incomes, {
          name: this.incomeModel.name,
          value: this.incomeModel.value,
          adder: this.incomeModel.adder,
          tax: this.incomeModel.tax,
          is_added: 1,
        }]
        // this.incomeModel.loading = false;
        // console.log(newIncome);
        let vm = this;
        axios.patch('/final-payslip/{{$payslip->id}}/add-income', {
            income: newIncome,
          })
          .then(function(response) {
            vm.incomeModel.loading = false;
            vm.incomes = response.data.data.income;
            $('#addIncomeModal').modal('hide');
            vm.resetAddIncomeForm();
            // console.log(response)
          })
          .catch(function(error) {
            vm.incomeModel.loading = false;
            Swal.fire({
              title: 'Error',
              text: "Data gagal disimpan",
              icon: 'error'
            })
            console.log(error);
          });
      },
      resetAddIncomeForm: function() {
        this.incomeModel = {
          name: '',
          value: '',
          adder: 1,
          tax: 0,
          loading: false,
        }
      },
      deleteIncome: function(index) {
        // this.incomes.splice(index, 1);
        let incomesClone = [...this.incomes]
        incomesClone.splice(index, 1);
        const newIncome = incomesClone;
        let vm = this;
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
            return axios.patch('/final-payslip/{{$payslip->id}}/add-income', {
                income: newIncome,
              })
              .then(function(response) {
                // console.log(response.data);
                vm.incomes = response.data.data.income;
              })
              .catch(function(error) {
                console.log(error.data);
                Swal.fire({
                  icon: 'error',
                  title: 'Oops',
                  text: 'Something wrong',
                })
              });
            // console.log(newIncome);
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            // Swal.fire({
            //   icon: 'success',
            //   title: 'Success',
            //   text: 'Data has been deleted',
            // })
            // $('#addIncomeModal').modal('hide');
          }
        })
      },
      // DEDUCTION
      addDeduction: function() {
        this.deductionModel.loading = true;
        const newDeduction = [...this.deductions, {
          name: this.deductionModel.name,
          value: this.deductionModel.value,
          tax_deduction: this.deductionModel.taxDeduction,
          is_added: 1,
        }]

        let vm = this;
        axios.patch('/final-payslip/{{$payslip->id}}/delete-deduction', {
            deduction: newDeduction,
          })
          .then(function(response) {
            vm.deductionModel.loading = false;
            vm.deductions = response.data.data.deduction;
            $('#addDeductionModal').modal('hide');
            vm.resetAddDeductionForm();
            // console.log(response)
          })
          .catch(function(error) {
            vm.deductionModel.loading = false;
            Swal.fire({
              title: 'Error',
              text: "Data gagal disimpan",
              icon: 'error'
            })
            console.log(error);
          });
      },
      resetAddDeductionForm: function() {
        this.deductionModel = {
          name: '',
          value: '',
          taxDeduction: 0,
          loading: false,
        }
      },
      deleteDeduction: function(index) {
        let deductionsClone = [...this.deductions]
        deductionsClone.splice(index, 1);
        const newDeduction = deductionsClone;
        let vm = this;
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
            return axios.patch('/final-payslip/{{$payslip->id}}/delete-deduction', {
                deduction: newDeduction,
              })
              .then(function(response) {
                // console.log(response.data);
                vm.deductions = response.data.data.deduction;
              })
              .catch(function(error) {
                console.log(error.data);
                Swal.fire({
                  icon: 'error',
                  title: 'Oops',
                  text: 'Something wrong',
                })
              });
            // console.log(newIncome);
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            // Swal.fire({
            //   icon: 'success',
            //   title: 'Success',
            //   text: 'Data has been deleted',
            // })
            // $('#addIncomeModal').modal('hide');
          }
        })
      },
      addLoan: function() {
        this.loanModel.loading = true;
        // const newIncome = [...this.incomes, {
        //   name: 'Kasbon',
        //   value: this.loanModel.value,
        //   // adder: this.incomeModel.adder,
        //   // tax: this.incomeModel.tax,
        //   is_added: 1,
        // }]
        // this.incomeModel.loading = false;
        // console.log(newIncome);
        let vm = this;
        axios.patch('/final-payslip/{{$payslip->id}}/add-loan', {
            income: vm.incomes,
            amount: vm.loanModel.value,
            employee_id: vm.employeeId,
          })
          .then(function(response) {
            vm.loanModel.loading = false;
            vm.incomes = response.data.data.income;
            $('#addLoanModal').modal('hide');
            vm.resetAddLoanForm();
            // console.log(response)
          })
          .catch(function(error) {
            vm.loanModel.loading = false;
            Swal.fire({
              title: 'Error',
              text: "Data gagal disimpan",
              icon: 'error'
            })
            console.log(error);
          });
      },
      resetAddLoanForm: function() {
        this.loanModel = {
          value: '',
          loading: false,
        }
      },
      deleteLoan: function(index) {
        // this.incomes.splice(index, 1);
        let incomesClone = [...this.incomes];
        const loanId = incomesClone[index].loan_id;
        incomesClone.splice(index, 1);
        const newIncome = incomesClone;
        let vm = this;
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Kasbon akan dihapus",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.patch('/final-payslip/{{$payslip->id}}/delete-loan', {
                income: newIncome,
                loan_id: loanId,
              })
              .then(function(response) {
                // console.log(response.data);
                vm.incomes = response.data.data.income;
              })
              .catch(function(error) {
                console.log(error.data);
                Swal.fire({
                  icon: 'error',
                  title: 'Oops',
                  text: 'Something wrong',
                })
              });
            // console.log(newIncome);
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            // Swal.fire({
            //   icon: 'success',
            //   title: 'Success',
            //   text: 'Data has been deleted',
            // })
            // $('#addIncomeModal').modal('hide');
          }
        })
      },
      addPayLoan: function() {
        this.payLoanModel.loading = true;
        // const newIncome = [...this.incomes, {
        //   name: 'Kasbon',
        //   value: this.payLoanModel.value,
        //   // adder: this.incomeModel.adder,
        //   // tax: this.incomeModel.tax,
        //   is_added: 1,
        // }]
        // this.incomeModel.loading = false;
        // console.log(newIncome);
        let vm = this;
        axios.patch('/final-payslip/{{$payslip->id}}/add-payment', {
            deduction: vm.deductions,
            amount: vm.payLoanModel.value,
            employee_id: vm.employeeId,
          })
          .then(function(response) {
            vm.payLoanModel.loading = false;
            vm.deductions = response.data.data.deduction;
            $('#addPayLoanModal').modal('hide');
            vm.resetAddPayLoanForm();
            // console.log(response)
          })
          .catch(function(error) {
            vm.payLoanModel.loading = false;
            Swal.fire({
              title: 'Error',
              text: "Data gagal disimpan",
              icon: 'error'
            })
            console.log(error);
          });
      },
      resetAddPayLoanForm: function() {
        this.payLoanModel = {
          value: '',
          loading: false,
        }
      },
      deletePayLoan: function(index) {
        // this.deductions.splice(index, 1);
        let deductionsClone = [...this.deductions];
        const loanId = deductionsClone[index].loan_id;
        deductionsClone.splice(index, 1);
        const newDeduction = deductionsClone;
        let vm = this;
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Bayar kasbon akan dihapus",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.patch('/final-payslip/{{$payslip->id}}/delete-payment', {
                deduction: newDeduction,
                loan_id: loanId,
              })
              .then(function(response) {
                // console.log(response.data);
                vm.deductions = response.data.data.deduction;
              })
              .catch(function(error) {
                console.log(error.data);
                Swal.fire({
                  icon: 'error',
                  title: 'Oops',
                  text: 'Something wrong',
                })
              });
            // console.log(newIncome);
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            // Swal.fire({
            //   icon: 'success',
            //   title: 'Success',
            //   text: 'Data has been deleted',
            // })
            // $('#addIncomeModal').modal('hide');
          }
        })
      },
    },
    computed: {
      totalIncome: function() {
        return this.incomes.filter(income => {
          return income.adder == null || income.adder == 1 || income.adder == '1'
        }).map(income => Number(income.value)).reduce((acc, cur) => {
          return acc + cur;
        }, 0);
      },
      totalDeduction: function() {
        return this.deductions.map(deduction => Number(deduction.value)).reduce((acc, cur) => {
          return acc + cur;
        }, 0);
      },
    }
  })
</script>

<script>
  $(function() {
    $('table.use-datatable').DataTable({
      "searching": false,
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
  })
</script>
@endsection