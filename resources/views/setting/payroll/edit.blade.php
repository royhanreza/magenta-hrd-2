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
            <h2 class="pageheader-title">Setting </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item">Setting</li>
                  <li class="breadcrumb-item active" aria-current="page">Salary</li>
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
          <form @submit.prevent="editPayslip">
            <div class="card">
              <!-- 1 -->
              <div class="card-header d-flex bg-light">
                <h5 class="card-header-title">Basic Information</h5>
                <div class="toolbar ml-auto">
                  <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
              <div class="card-body">

                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Nama Slip Gaji <sup class="text-danger">*</sup></label>
                  <div class="col-sm-9">
                    <input v-model="name" type="text" class="form-control" id="name" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="period" class="col-sm-3 col-form-label">Periode<sup class="text-danger">*</sup></label>
                  <div class="col-sm-9">
                    <select v-model="periodType" class="form-control">
                      <option value="tetap">Tetap (Gaji Bulanan)</option>
                      <!-- <option value="tidak tetap">Tidak Tetap (THR atau bonus lebih dari 31 hari)</option> -->
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="period" class="col-sm-3 col-form-label">Lama Periode<sup class="text-danger">*</sup></label>
                  <div class="col-sm-9">
                    <select v-model="longPeriod" class="form-control">
                      <option value="monthly">1 Bulanan</option>
                      <!-- <option value="weekly">1 Mingguan</option>
                      <option value="daily">N Harian</option> -->
                    </select>
                  </div>
                </div>
                <div v-if="longPeriod == 'monthly'" class="form-group row">
                  <label for="period" class="col-sm-3 col-form-label">Tanggal Awal Periode (1-28)<sup class="text-danger">*</sup></label>
                  <div class="col-sm-9">
                    <input v-model="monthlyFirstDay" type="number" class="form-control" min="1" max="28" required>
                  </div>
                </div>
                <div v-if="longPeriod == 'weekly'" class="form-group row">
                  <label for="period" class="col-sm-3 col-form-label">Hari Awal Periode<sup class="text-danger">*</sup></label>
                  <div class="col-sm-9">
                    <select v-model="weeklyFirstDay" class="form-control">
                      <option value="monday">Senin</option>
                      <option value="tuesday">Selasa</option>
                      <option value="wednesday">Rabu</option>
                      <option value="thursday">Kamis</option>
                      <option value="friday">Jumat</option>
                      <option value="saturday">Sabtu</option>
                      <option value="sunday">Minggu</option>
                    </select>
                  </div>
                </div>
                <div v-if="longPeriod == 'daily'" class="form-group row">
                  <label for="period" class="col-sm-3 col-form-label">Jumlah Hari dalam Periode<sup class="text-danger">*</sup></label>
                  <div class="col-sm-9">
                    <input v-model="dailyNumberOfDays" type="number" class="form-control" min="31" placeholder="Harus lebih dari 30 hari" required>
                  </div>
                </div>
                <div v-if="longPeriod == 'daily'" class="form-group row">
                  <label for="period" class="col-sm-3 col-form-label">Tanggal Slip Sebelumnya<sup class="text-danger">*</sup></label>
                  <div class="col-sm-9">
                    <input v-model="dailyPreviousPaySlipDate" type="date" class="form-control" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="period" class="col-sm-3 col-form-label" style="white-space: normal;">Untuk komponen pendapatan tergantung kehadiran, tentukan hari absensi terakhir yang masuk hitungan gaji:<sup class="text-danger">*</sup></label>
                  <div class="col-sm-9">
                    <select v-model="incomeLastDayAttendance" class="form-control">
                      @for($i = 0; $i <= 10; $i++) <option value="{{ $i }}">{{ $i }} hari sebelum akhir periode</option>
                        @endfor
                    </select>
                  </div>
                </div>
                <!-- <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-sm btn-primary px-5">
                    Save
                  </button>
                </div> -->

              </div>
              <!-- End: 1 -->
              <!-- Begin: 2 -->
              <div class="card-header d-flex border-top bg-light mt-5">
                <h5 class="card-header-title">Komponen pendapatan dalam Slip Gaji (selain tunjangan PPh 21 dan BPJS)</h5>
                <div class="toolbar ml-auto">
                  <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                  <div class="input-group mb-3">
                    <select v-model="salaryIncomeModel" class="form-control">
                      <option value="">-Pilih Komponen Pendapatan-</option>
                      <option v-for="(income, index) in unselectedSalaryIncomes" :value="income.id" :key="income.id">@{{ income.name }}</option>
                    </select>
                    <div class="input-group-append">
                      <button @click="selectSalaryIncome" type="button" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add</button>
                    </div>
                  </div>
                </div>
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col">Nama Pendapatan</th>
                      <th scope="col">Tipe</th>
                      <th scope="col" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(selectedSalaryIncome, index) in selectedSalaryIncomes" :key="selectedSalaryIncome.id">
                      <td>@{{ selectedSalaryIncome.name }}</td>
                      <td class="text-capitalize">@{{ selectedSalaryIncome.type }}</td>
                      <td class="text-center">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <button @click="unselectSalaryIncome(index, {{ $payslip->id }}, selectedSalaryIncome.id)" type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- End: 2 -->
              <!-- Begin: 3 -->
              <div class="card-header d-flex border-top bg-light mt-5">
                <h5 class="card-header-title">Komponen potongan dalam Slip Gaji (selain tunjangan PPh 21 dan BPJS)</h5>
                <div class="toolbar ml-auto">
                  <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                  <div class="input-group mb-3">
                    <select v-model="salaryDeductionModel" class="form-control">
                      <option value="">-Pilih Komponen Potongan-</option>
                      <option v-for="(deduction, index) in unselectedSalaryDeductions" :value="deduction.id" :key="deduction.id">@{{ deduction.name }}</option>
                    </select>
                    <div class="input-group-append">
                      <button @click="selectSalaryDeduction" type="button" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add</button>
                    </div>
                  </div>
                </div>
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col">Nama Potongan</th>
                      <th scope="col">Tipe</th>
                      <th scope="col" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(selectedSalaryDeduction, index) in selectedSalaryDeductions" :key="selectedSalaryDeduction.id">
                      <td>@{{ selectedSalaryDeduction.name }}</td>
                      <td class="text-capitalize">@{{ selectedSalaryDeduction.type }}</td>
                      <td class="text-center">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <button type="button" @click="unselectSalaryDeduction(index)" class="btn btn-sm btn-light "><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- End: 3 -->
              <div class="card-footer">
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-sm btn-primary px-5" v-bind:disabled="loadingPaySlip"><span v-if="loadingPaySlip" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <!-- ============================================================== -->
        <!-- end basic table  -->
        <!-- ============================================================== -->
        <!-- scrollspy  -->
        <!-- ============================================================== -->
        <!-- <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
          <div class="sidebar-nav-fixed">
            <ul class="list-unstyled">
              <li><a href="#overview" class="active">Basic Information</a></li>
              <li><a href="#cards">Komponen Pendapatan</a></li>
              <li><a href="#image-card">Komponen Potongan</a></li>
            </ul>
          </div>
        </div> -->
        <!-- scrollspy  -->
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
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')

<script>
  let app = new Vue({
    el: '#app',
    data: {
      name: '{{ $payslip->name }}',
      periodType: '{{ $payslip->period_type }}',
      longPeriod: '{{ $payslip->long_period }}',
      monthlyFirstDay: '{{ $payslip->monthly_first_day }}',
      weeklyFirstDay: '{{ $payslip->weekly_first_day }}',
      dailyNumberOfDays: '{{ $payslip->daily_number_of_days }}',
      dailyPreviousPaySlipDate: '{{ $payslip->daily_previous_pay_slip_date }}',
      incomeLastDayAttendance: '{{ $payslip->income_last_day_attendance }}',
      loadingPaySlip: false,
      loadingSalaryIncome: true,
      loadingSalaryDeduction: true,
      salaryIncomes: JSON.parse('{!! $incomes !!}'),
      salaryDeductions: JSON.parse('{!! $deductions !!}'),
      salaryIncomeModel: '',
      salaryDeductionModel: '',
      selectedSalaryIncomes: JSON.parse('{!! $payslip->salaryIncomes !!}'),
      selectedSalaryDeductions: JSON.parse('{!! $payslip->salaryDeductions !!}'),

    },
    methods: {
      editPayslip: function() {
        // console.log('submitted');
        let vm = this;
        vm.loadingPaySlip = true;
        axios.patch('/payslip/{{ $payslip->id }}', {
            name: this.name,
            period_type: this.periodType,
            long_period: this.longPeriod,
            monthly_first_day: this.monthlyFirstDay,
            weekly_first_day: this.weeklyFirstDay,
            daily_number_of_days: this.dailyNumberOfDays,
            daily_previous_payslip_date: this.dailyPreviousPaySlipDate,
            income_last_day_attendance: this.incomeLastDayAttendance,
            salary_incomes: this.selectedSalaryIncomes,
            salary_deductions: this.selectedSalaryDeductions,
          })
          .then(function(response) {
            vm.loadingPaySlip = false;
            Swal.fire({
              title: 'Success',
              text: 'Your data has been saved',
              icon: 'success',
              allowOutsideClick: false,
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = '/setting/payroll';
              }
            })
            console.log(response);
          })
          .catch(function(error) {
            vm.loadingPaySlip = false;
            console.log(error);
            Swal.fire(
              'Oops!',
              'Something wrong',
              'error'
            )
          });
      },
      selectSalaryIncome: function() {
        if (this.salaryIncomeModel !== '') {
          const vm = this;
          const index = this.salaryIncomes.findIndex(income => income.id == vm.salaryIncomeModel);
          this.selectedSalaryIncomes.push(this.salaryIncomes[index]);
          this.salaryIncomeModel = '';
        }
      },
      selectSalaryDeduction: function() {
        if (this.salaryDeductionModel !== '') {
          const vm = this;
          const index = this.salaryDeductions.findIndex(deduction => deduction.id == vm.salaryDeductionModel);
          this.selectedSalaryDeductions.push(this.salaryDeductions[index]);
          this.salaryDeductionModel = '';
        }
      },
      unselectSalaryIncome: function(index, id, incomeId) {
        let vm = this;
        vm.selectedSalaryIncomes.splice(index, 1);
        // Swal.fire({
        //   title: 'Apakah anda yakin?',
        //   text: "Komponen ini akan dihapus dari payslip",
        //   icon: 'warning',
        //   reverseButtons: true,
        //   showCancelButton: true,
        //   confirmButtonColor: '#3085d6',
        //   cancelButtonColor: '#d33',
        //   confirmButtonText: 'Delete',
        //   cancelButtonText: 'Cancel',
        //   showLoaderOnConfirm: true,
        //   preConfirm: () => {
        //     return axios.delete(`/payslip/${id}/incomes/${incomeId}`)
        //       .then(function(response) {
        //         console.log(response.data);

        //       })
        //       .catch(function(error) {
        //         console.log(error.data);
        //         Swal.fire({
        //           icon: 'error',
        //           title: 'Oops',
        //           text: 'Something wrong',
        //         })
        //       });
        //   },
        //   allowOutsideClick: () => !Swal.isLoading()
        // }).then((result) => {
        //   if (result.isConfirmed) {
        //     Swal.fire({
        //       icon: 'success',
        //       title: 'Success',
        //       text: 'Komponen berhasil dihapus',
        //     })
        //   }
        // })
      },
      unselectSalaryDeduction: function(index) {
        this.selectedSalaryDeductions.splice(index, 1);
      },
      navigate: function() {
        window.location.href = `/payroll/${id}?month=${month}&year=${year}`;
      }
    },
    computed: {
      unselectedSalaryIncomes: function() {
        return _.differenceBy(this.salaryIncomes, this.selectedSalaryIncomes, 'id');
      },
      unselectedSalaryDeductions: function() {
        return _.differenceBy(this.salaryDeductions, this.selectedSalaryDeductions, 'id');
      }
    }
  })
</script>

<script>
  $(function() {
    $('table.use-datatable').DataTable();

    //   $('.btn-delete').on('click', function() {
    //     const id = $(this).attr('data-id');
    //     Swal.fire({
    //       title: 'Are you sure?',
    //       text: "The data will be deleted",
    //       icon: 'warning',
    //       reverseButtons: true,
    //       showCancelButton: true,
    //       confirmButtonColor: '#3085d6',
    //       cancelButtonColor: '#d33',
    //       confirmButtonText: 'Delete',
    //       cancelButtonText: 'Cancel',
    //       showLoaderOnConfirm: true,
    //       preConfirm: () => {
    //         return axios.delete('/company/' + id)
    //           .then(function(response) {
    //             console.log(response.data);
    //           })
    //           .catch(function(error) {
    //             console.log(error.data);
    //             Swal.fire({
    //               icon: 'error',
    //               title: 'Oops',
    //               text: 'Something wrong',
    //             })
    //           });
    //       },
    //       allowOutsideClick: () => !Swal.isLoading()
    //     }).then((result) => {
    //       if (result.isConfirmed) {
    //         Swal.fire({
    //           icon: 'success',
    //           title: 'Success',
    //           text: 'Data has been deleted',
    //         })
    //       }
    //     })
    //   })
  })
</script>
@endsection