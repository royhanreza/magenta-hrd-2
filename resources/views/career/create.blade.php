@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
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

  .col-form-label {
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
            <h2 class="pageheader-title">Employee</h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item">Employee</li>
                  <li class="breadcrumb-item">Career</li>
                  <li class="breadcrumb-item active" aria-current="page">Create</li>
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

          <!-- <div class="section-block">
            <h5 class="section-title">Career History & Remuneration</h5>
            <p>Employee status is PKWT, date of start of work 12 May 2020</p>
          </div> -->
          <div class="simple-card">
            <ul class="nav nav-tabs" id="myTab5" role="tablist">
              <li class="nav-item">
                <a class="nav-link border-left-0 active show" id="home-tab-simple" data-toggle="tab" href="#home-simple" role="tab" aria-controls="home" aria-selected="false">Non Remunerasi</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab-simple" data-toggle="tab" href="#profile-simple" role="tab" aria-controls="profile" aria-selected="false">Remunerasi</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent5">
              <div class="tab-pane fade active show" id="home-simple" role="tabpanel" aria-labelledby="home-tab-simple">
                <form @submit.prevent="addCareer">
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status Pegawai<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <select v-model="nonRemuneration.employeeStatus" class="form-control" required>
                        <option value=""></option>
                        <option value="Karyawan PKWTT">Karyawan PKWTT</option>
                        <option value="Karyawan Tetap Percobaan">Karyawan Tetap Percobaan</option>
                        <option value="Karyawan Tetap Permanen">Karyawan Tetap Permanen</option>
                        <option value="Karyawan PKWT">Karyawan PKWT</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tipe<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <select v-model="nonRemuneration.type" class="form-control" required>
                        <option value=""></option>
                        <option v-for="(type, index) in statusTypeOptions" :value="type.value">@{{ type.text }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Departemen<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <select v-model="nonRemuneration.department" class="form-control" required>
                        <option value=""></option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                        <!-- <option value="Karyawan Tetap Percobaan">Karyawan Tetap Percobaan</option>
                        <option value="Karyawan Tetap Permanen">Karyawan Tetap Permanen</option> -->
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Bagian<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <select v-model="nonRemuneration.designation" class="form-control" required>
                        <option value=""></option>
                        @foreach($designations as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Job Title</label>
                    <div class="col-sm-10">
                      <select v-model="nonRemuneration.jobTitle" class="form-control">
                        <option value=""></option>
                        @foreach($job_titles as $job_title)
                        <option value="{{ $job_title->id }}">{{ $job_title->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <!-- <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Golongan</label>
                    <div class="col-sm-10">
                      <input type="text" v-model="nonRemuneration.golongan" class="form-control">
                    </div>
                  </div> -->
                  <!-- <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tanggal Efektif<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <input type="date" v-model="nonRemuneration.effectiveDate" class="form-control" required>
                    </div>
                  </div> -->
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tanggal Efektif Jabatan<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <div class="input-group mb-3">
                        <input type="text" v-model="nonRemuneration.effectiveDate" class="form-control input-date-bs effective-date" aria-describedby="basic-addon2" readonly required>
                        <div class="input-group-append">
                          <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar" style="line-height: 0;"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row" v-if="nonRemuneration.employeeStatus !== 'Karyawan Tetap Permanen' && nonRemuneration.employeeStatus !== 'Karyawan PKWTT' ">
                    <label class="col-sm-2 col-form-label">Tanggal Masa Akhir Kerja<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <!-- <input type="date" v-model="nonRemuneration.endOfEmployementDate" class="form-control" required>
                     -->
                      <div class="input-group mb-3">
                        <input type="text" v-model="nonRemuneration.endOfEmployementDate" id="end-employement-date" class="form-control input-date-bs " aria-describedby="basic-addon2" readonly required>
                        <div class="input-group-append">
                          <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar" style="line-height: 0;"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="form-group row align-items-center">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-6">
                      <select class="form-control">
                        <option value="" disabled>Choose Period</option>
                        <option value="">3 Months</option>
                        <option value="">6 Months</option>
                        <option value="">1 Year</option>
                        <option value="">1 Year 6 Months</option>
                        <option value="">2 Years</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <span>After the effective date</span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                      <input type="date" class="form-control" readonly>
                    </div>
                  </div> -->
                  <!-- Pengingat Akhir Masa Status Karyawan -->
                  <div class="form-group row" v-if="nonRemuneration.employeeStatus !== 'Karyawan Tetap Permanen' && nonRemuneration.employeeStatus !== 'Karyawan PKWTT'">
                    <label class="col-sm-2 col-form-label" style="white-space: normal;">Pengingat Akhir Masa Status Karyawan<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <!-- <input type="date" v-model="nonRemuneration.endOfEmployeeStatusReminder" class="form-control" required> -->
                      <div class="input-group mb-3">
                        <input type="text" v-model="nonRemuneration.endOfEmployeeStatusReminder" id="end-employement-reminder" class="form-control input-date-bs" aria-describedby="basic-addon2" readonly required>
                        <div class="input-group-append">
                          <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar" style="line-height: 0;"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="form-group row align-items-center">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-6">
                      <select class="form-control">
                        <option value="" disabled>Choose Period</option>
                        <option value="">1 Week</option>
                        <option value="">2 Weeks</option>
                        <option value="">3 Weeks</option>
                        <option value="">1 Month</option>
                        <option value="">2 Months</option>
                        <option value="">3 Months</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <span>Before the end date of work period</span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                      <input type="date" class="form-control" readonly>
                    </div>
                  </div> -->
                  <!-- UMP -->
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">UMP<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <select v-model="nonRemuneration.minimumWage" class="form-control" required>
                        @foreach($minimum_wages as $wage)
                        <option value="{{ $wage->id }}">{{ $wage->name }} - Rp {{ $wage->value }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Metode Perhitungan Pajak<sup class="text-danger">*</sup></label>
                    <div class="col-sm-10">
                      <select v-model="nonRemuneration.taxCalculationMethod" class="form-control" required>
                        <option value="gross">Gross</option>
                        <option value="gross up">Gross Up</option>
                        <option value="net">Nett</option>
                      </select>
                    </div>
                  </div>
                  <div class="mt-5">
                    <div class="d-flex justify-content-end">
                      <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loading || selectedPayslips.length < 1 || selectedPayslipWithBasicSalary == ''"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    </div>
                  </div>
                  <p v-if="selectedPayslips.length < 1 || selectedPayslipWithBasicSalary == ''" class="text-right mt-3"><i class="fas fa-exclamation-triangle text-warning fa-xs"></i> Pilih minimal 1 slip gaji dengan gaji pokok di tab Remunerasi</p>
                </form>
              </div>


              <div class="tab-pane fade" id="profile-simple" role="tabpanel" aria-labelledby="profile-tab-simple">
                <div class="row">
                  <div class="col-md-3">
                    <div class="alert alert-info">
                      <span><i class="fas fa-check-circle"></i> Mengandung Gaji Pokok</span>
                    </div>
                    <ul class="list-group">
                      <li v-for="(payslip, index) in payslips" :key="payslip.id" class="list-group-item d-flex justify-content-between align-items-center" :class="{'bg-light': setDisablePayslip(payslip.id)}">
                        <span><i v-if="hasBasicSalary(payslip)" class="fas fa-check-circle text-success"></i> @{{ payslip.name }}</span>
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" @change="selectPayslip(payslip.id)" class="custom-control-input" :disabled="setDisablePayslip(payslip.id)"><span class="custom-control-label"></span>
                        </label>
                      </li>
                    </ul>
                  </div>
                  <div class="col-md-9">
                    <h3>Grup Gaji</h3>
                    <p v-if="this.selectedPayslipWithBasicSalary == ''"><i class="fas fa-exclamation-triangle text-warning fa-xs"></i> Karyawan tetap harus memiliki 1 grup gaji yang mengandung Gaji Pokok</p>
                    <div v-for="(selectedPayslip, payslipIndex) in selectedPayslips" class="mb-5">
                      <h3>- @{{ selectedPayslip.name }}</h3>
                      <div class="row bg-light py-3">
                        <div class="col-md-6 col-xs-12">
                          <h4 class="border-bottom">Income</h4>
                          <p v-if="selectedPayslip.salary_incomes.length < 1" class="text-center"><em>Slip gaji ini tidak memiliki komponen pendapatan</em></p>
                          <div v-for="(income, incomeIndex) in selectedPayslip.salary_incomes" class="form-group row">
                            <label class="col-sm-6 col-form-label">@{{ income.name }}</label>
                            <div class="col-sm-6">
                              <div v-if="income.type == 'Manual'">
                                <p><em>Nilai dimasukkan pada setiap slip gaji</em></p>
                              </div>
                              <div class="input-group" v-if="income.type !== 'Manual'">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" v-model="income.value" @change="calculateTotalIncomes(selectedPayslip)" class="form-control text-right" aria-describedby="basic-addon1">
                              </div>
                            </div>
                          </div>
                          <hr>
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Sum Income (Gaji Pokok + Pendapatan tipe jumlah tetap)</label>
                            <div class="col-sm-6">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right" :value="toCurrencyFormat(selectedPayslip.total_incomes)" aria-describedby="basic-addon1" readonly>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <h4 class="border-bottom">Deductions</h4>
                          <p v-if="selectedPayslip.salary_deductions.length < 1" class="text-center"><em>Slip gaji ini tidak memiliki komponen potongan</em></p>
                          <div v-for="(deduction, index) in selectedPayslip.salary_deductions" class="form-group row">
                            <label class="col-sm-6 col-form-label">@{{ deduction.name }}</label>
                            <div class="col-sm-6">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" v-model="deduction.value" @change="calculateTotalDeductions(selectedPayslip)" class="form-control text-right" aria-describedby="basic-addon1">
                              </div>
                            </div>
                          </div>
                          <hr>
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Sum Deduction (Potongan tipe jumlah tetap)</label>
                            <div class="col-sm-6">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" :value="toCurrencyFormat(selectedPayslip.total_deductions)" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <!-- <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title">Add New Career</h5>
            </div>
            <div class="card-body">

            </div>
          </div> -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  let app = new Vue({
    el: '#app',
    data: {
      payslips: JSON.parse('{!! $payslips !!}'),
      // payslipsWithBasicSalary: JSON.parse('{!! $payslips !!}'),
      selectedPayslips: [],
      selectedPayslipWithBasicSalary: '',
      // payslipComponentModels: [],
      nonRemuneration: {
        employeeStatus: 'Karyawan PKWTT',
        type: '',
        department: '',
        designation: '',
        jobTitle: '',
        golongan: '',
        effectiveDate: '',
        endOfEmployementDate: '',
        endOfEmployeeStatusReminder: '',
        minimumWage: '',
        taxCalculationMethod: '',

      },
      loading: false,
    },
    methods: {
      // onInput: function(event) {
      //   console.log(event.target.value);
      // },
      addCareer: function() {
        let vm = this;
        vm.loading = true;
        axios.post('/career', {
            employee_id: '{{ $employee->id }}',
            employee_status: this.nonRemuneration.employeeStatus,
            type: this.nonRemuneration.type,
            department: this.nonRemuneration.department,
            designation: this.nonRemuneration.designation,
            job_title: this.nonRemuneration.jobTitle,
            golongan: this.nonRemuneration.golongan,
            effective_date: this.nonRemuneration.effectiveDate,
            end_of_employement_date: this.nonRemuneration.endOfEmployementDate,
            end_of_employee_status_reminder: this.nonRemuneration.endOfEmployeeStatusReminder,
            minimum_wage: this.nonRemuneration.minimumWage,
            tax_calculation_method: this.nonRemuneration.taxCalculationMethod,
            pay_slips: this.selectedPayslips,
            pay_slips_id: this.selectedPayslipsIdModel,
          })
          .then(function(response) {
            vm.loading = false;
            Swal.fire({
              title: 'Success',
              text: 'Your data has been saved',
              icon: 'success',
              allowOutsideClick: false,
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = '/employee/career/' + '{{ $employee->id }}';
              }
            })
            console.log(response);
          })
          .catch(function(error) {
            vm.loading = false;
            console.log(error);
            Swal.fire(
              'Oops!',
              'Something wrong',
              'error'
            )

          });
      },
      selectPayslip: function(id) {
        const payslip = this.payslips.filter(payslip => payslip.id == id)[0];
        const selectedPayslipsId = this.selectedPayslips.map(payslip => payslip.id);
        if (selectedPayslipsId.length > 0) {
          const existIndex = selectedPayslipsId.indexOf(id);
          if (existIndex >= 0) {
            // this.selectedPayslips.splice(existIndex, 1);
            this.unselectPayslip(existIndex);
          } else {
            // this.selectedPayslips.push(payslip);
            this.addSelectedPayslip(payslip)
          }
        } else {
          // this.selectedPayslips.push(payslip);
          this.addSelectedPayslip(payslip)
        }
      },
      addSelectedPayslip: function(payslip) {
        // let newPayslip = payslip.salary_incomes.map(income => {
        //   return {
        //     ...income,
        //     value: 0,
        //   }
        // })
        // console.log(payslip.salary_incomes.length)
        payslip.salary_incomes.forEach(income => {
          income.value = 0;
        })
        payslip.total_incomes = 0;
        payslip.salary_deductions.forEach(deduction => {
          deduction.value = 0;
        });
        payslip.total_deductions = 0;
        this.selectedPayslips.push({
          ...payslip
        });
        if (this.hasBasicSalary(payslip)) {
          this.selectedPayslipWithBasicSalary = payslip.id;
        }
        // const index = this.payslipsWithBasicSalary.findIndex(item => item == payslip.id);
        // if (index > -1) {
        //   this.payslipsWithBasicSalary.splice(index, 1);
        // }
        // this.payslipComponentModels.push({
        //   payslip_id: payslip.id,
        //   salary_incomes: payslip.salary_incomes.map(income => ({
        //     id: income.id,
        //     name: income.name,
        //     value: 0
        //   })),
        //   salary_deductions: payslip.salary_deductions.map(deduction => ({
        //     id: deduction.id,
        //     name: deduction.name,
        //     value: 0
        //   })),
        // })
      },
      unselectPayslip: function(index) {
        let payslip = this.selectedPayslips[index];

        if (this.hasBasicSalary(payslip)) {
          this.selectedPayslipWithBasicSalary = '';
        }
        this.selectedPayslips.splice(index, 1);
        // this.payslipComponentModels.splice(index, 1);
      },
      sumIncomes: function(incomes) {
        const sum = incomes.map(income => isNaN(Number(income.value)) ? 0 : Number(income.value)).reduce((acc, cur) => {
          return acc + cur
        }, 0)

        if (isNaN(sum)) {
          return 0
        }

        return Number(sum);
      },
      sumDeductions: function(deductions) {
        const sum = deductions.map(deduction => isNaN(parseInt(deduction.value)) ? 0 : parseInt(deduction.value)).reduce((acc, cur) => {
          return acc + cur
        }, 0)

        if (isNaN(sum)) {
          return 0
        }

        return Number(sum);
      },
      calculateTotalIncomes(payslip) {
        const sum = payslip.salary_incomes.map(income => isNaN(Number(income.value)) ? 0 : Number(income.value)).reduce((acc, cur) => {
          return acc + cur
        }, 0)

        if (isNaN(sum)) {
          return 0
        }

        payslip.total_incomes = sum;
      },
      calculateTotalDeductions(payslip) {
        const sum = payslip.salary_deductions.map(deduction => isNaN(parseInt(deduction.value)) ? 0 : parseInt(deduction.value)).reduce((acc, cur) => {
          return acc + cur
        }, 0)

        if (isNaN(sum)) {
          return 0
        }

        payslip.total_deductions = sum;
      },
      setDisablePayslip: function(id) {
        const payslip = this.payslips.filter(payslip => payslip.id == id)[0];

        if (payslip !== null && this.selectedPayslipWithBasicSalary !== '') {
          // if (payslip.salary_incomes.map(income => income.type).indexOf('gaji pokok') > -1) {
          //   return true;
          // }

          if (this.hasBasicSalary(payslip) && id !== this.selectedPayslipWithBasicSalary) {
            return true;
          }
          return false;
        }

        return false;
        // this.payslips.filter(payslip => payslip.salary_incomes.map(income => income.type).indexOf('gaji pokok') > -1).map(payslip => payslip.id);
        // if (this.payslipsWithBasicSalary.indexOf(id) > -1) {
        //   return false;
        // }
        // return true;
        // return false;
      },
      hasBasicSalary: function(payslip) {
        if (payslip.salary_incomes.map(income => income.type).indexOf('gaji pokok') > -1) {
          return true;
        }
        return false;
      },
      toCurrencyFormat(number) {
        return new Intl.NumberFormat('De-de').format(number);
      }
    },
    computed: {
      selectedPayslipsIdModel: function() {
        return this.selectedPayslips.map(payslip => payslip.id);
      },
      statusTypeOptions: function() {
        if (this.nonRemuneration.employeeStatus == 'Karyawan Tetap Percobaan') {
          return [{
            value: 'Baru Direkrut',
            text: 'Baru Direkrut'
          }]
        }

        if (this.nonRemuneration.employeeStatus == 'Karyawan Tetap Permanen' || this.nonRemuneration.employeeStatus == 'Karyawan PKWTT') {
          this.nonRemuneration.endOfEmployementDate = '';
          this.nonRemuneration.endOfEmployeeStatusReminder = '';
          return [{
              value: 'Baru Direkrut',
              text: 'Baru Direkrut'
            },
            {
              value: 'Demosi',
              text: 'Demosi'
            },
            {
              value: 'Diangkat Karyawan Tetap',
              text: 'Diangkat Karyawan Tetap'
            },
            {
              value: 'Mutasi',
              text: 'Mutasi'
            },
            {
              value: 'Promosi',
              text: 'Promosi'
            },
            {
              value: 'Rotasi',
              text: 'Rotasi'
            }
          ]
        }

        if (this.nonRemuneration.employeeStatus == 'Karyawan PKWT') {
          return [{
              value: 'Baru Direkrut',
              text: 'Baru Direkrut'
            },
            {
              value: 'Perpanjangan Kontrak',
              text: 'Perpanjangan Kontrak'
            },
          ]
        }

        return []
      },
      // payslipsWithBasicSalary: function() {
      //   return this.payslips.filter(payslip => payslip.salary_incomes.map(income => income.type).indexOf('gaji pokok') > -1).map(payslip => payslip.id);
      // },
      // payslipsWithoutBasicSalary: function() {
      //   return this.payslips.filter(payslip => payslip.salary_incomes.map(income => income.type).indexOf('gaji pokok') < 0);
      // }
    },
    watch: {
      // 'form.items': {
      selectedPayslips: {
        handler(value, oldValue) {
          const vm = this;
          // const sum = incomes.map(income => isNaN(Number(income.value)) ? 0 : Number(income.value)).reduce((acc, cur) => {
          //   return acc + cur
          // }, 0)

          // if (isNaN(sum)) {
          //   return 0
          // }
          // console.log(first);
          vm.selectedPayslips.forEach(payslip => {
            let sum = 0;
            if (payslip.salary_incomes && Array.isArray(payslip.salary_incomes)) {
              sum = payslip.salary_incomes.map(income => isNaN(Number(income.value)) ? 0 : Number(income.value)).reduce((acc, cur) => {
                return acc + cur
              }, 0)
            }

            payslip.total_incomes = Number(sum);

          })

        },
        deep: true,
      },
    },
  })
</script>
<script>
  $(function() {
    $('.effective-date').datepicker({
      format: 'yyyy-mm-dd',
      startDate: '{{ ($effective_date !== null) ? $effective_date : "" }}',
    }).on('changeDate', function(e) {

      app.$data.nonRemuneration.effectiveDate = e.format(0, 'yyyy-mm-dd');
      app.$data.nonRemuneration.endOfEmployementDate = '';
      app.$data.nonRemuneration.endOfEmployeeStatusReminder = '';

      // $('#end-employement-date').val('');
      $('#end-employement-date').removeClass('end-employement-date');
      let effectiveDate = new Date(e.date);
      $('#end-employement-date').addClass('end-employement-date');

      $('.end-employement-date').datepicker({
        format: 'yyyy-mm-dd',
      }).on('changeDate', function(e) {

        app.$data.nonRemuneration.endOfEmployementDate = e.format(0, 'yyyy-mm-dd');
        app.$data.nonRemuneration.endOfEmployeeStatusReminder = '';


        // $('#end-employement-reminder').val('');
        $('#end-employement-reminder').removeClass('end-employement-reminder');

        $('#end-employement-reminder').addClass('end-employement-reminder');
        $('.end-employement-reminder').datepicker({
          format: 'yyyy-mm-dd',
        }).on('changeDate', function(e) {
          app.$data.nonRemuneration.endOfEmployeeStatusReminder = e.format(0, 'yyyy-mm-dd');
        });

        $('.end-employement-reminder').datepicker('setStartDate', effectiveDate);
      });

      $('.end-employement-date').datepicker('setStartDate', effectiveDate);
    });

    // window.onbeforeunload = function() {
    //   return 'Are you sure you want to leave?';
    // };

  })
</script>
@endsection