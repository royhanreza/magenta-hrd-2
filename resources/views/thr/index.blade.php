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
            <h2 class="pageheader-title">Penggajian (THR) </h2>
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
              <h5 class="card-header-title">Tunjangan Hari Raya (THR)</h5>
              <div class="toolbar ml-auto">
                <a href="/thr/export/report/monthly?start_date_period={{ $start_date_period }}&end_date_period={{ $end_date_period }}&staffonly={{ request()->query('staffonly') !== null ? request()->query('staffonly') : 'false' }}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-file-alt"></i> Download Laporan(.xlsx)</a>
              </div>
            </div>
            <div class="card-body">
              <form>
                <div class="form-row">
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Tahun</span>
                      </div>
                      <select @change="navigate" v-model="year" class="form-control pr-3" dir="rtl">
                        @for($i=2020; $i <= date("Y"); $i++ ) <option value="{{ $i }}">{{ $i }}</option>
                          @endfor
                      </select>
                    </div>
                  </div>
                </div>
              </form>
              @if(in_array("addThr", $userLoginPermissions))
              <div v-if="checkedCareers.length > 0" class="my-3">
                <div class="bg-light p-3 d-flex justify-content-end">
                  <div>
                    <button class="btn btn-primary btn-sm" :disabled="checkedCareers.length < 1" v-bind:disabled="loadingCreatePayslip" @click="createFinalPayslip"><span v-if="loadingCreatePayslip" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;<i class="fas fa-copy"></i> Buat Payslip</button>
                    <p class="mt-2" v-if="checkedCareers.length > 0"><i class="fas fa-user"></i> <strong>@{{ checkedCareers.length }} </strong>Pegawai Terpilih</p>
                  </div>
                </div>
              </div>
              @endif
              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead>
                    <tr>
                      <th class="text-center">
                        <label class="custom-control custom-checkbox" style="display: inline-block;">
                          <input type="checkbox" v-model="checkedAll" @change="toggleCheckAll" class="custom-control-input"><span class="custom-control-label"></span>
                        </label>
                      </th>
                      <th class="text-center">Pegawai</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Take Home Pay</th>
                      <th class="text-center">Slip</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr is="final-payslip" v-for="finalPayslip in finalPayslips" :key="finalPayslip.id" :id="finalPayslip.id" :name="finalPayslip.employee.first_name" :thp="finalPayslip?.income[0]?.value" :ondelete="deletePayslip"></tr>
                    <tr is="payslip" v-for="career in careers" :key="career.id" :id="career.id" :name="career.employee.first_name" :thp="calculateTakeHomePay(calculateTotalSub(career.incomes), 0)" :checkedcareers.sync="checkedCareers" :onshowmodal="showModal"></tr>
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
<div class="modal fade" id="modalPayslip" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" ref="modal">
  <div class="modal-dialog mw-100 w-75 modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Slip Gaji THR Tahun {{ date("Y") }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div v-if="payslipIsSelected">
          <div class="d-flex justify-content-end">
          </div>
          <div class="row mb-4">
            <div class="col-md-6">
              <table class="w-100">
                <tr>
                  <td>Nama</td>
                  <td>:</td>
                  <td>@{{ careers[selectedCareerIndex]?.employee?.first_name }}</td>
                </tr>
                <tr>
                  <td>ID Pegawai</td>
                  <td>:</td>
                  <td>@{{ careers[selectedCareerIndex]?.employee?.employee_id }}</td>
                </tr>
                <tr>
                  <td>Bagian</td>
                  <td>:</td>
                  <td>@{{ careers[selectedCareerIndex]?.designation?.name }}</td>
                </tr>
                <tr>
                  <td>Job Title</td>
                  <td>:</td>
                  <td>@{{ careers[selectedCareerIndex]?.job_title?.name }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="w-100">
                <tr>
                  <td>Status Karyawan</td>
                  <td>:</td>
                  <td>@{{ careers[selectedCareerIndex]?.employee_status }}</td>
                </tr>
                <tr>
                  <td>Status PTKP</td>
                  <td>:</td>
                  <td>@{{ careers[selectedCareerIndex]?.employee?.npwp?.type }}</td>
                </tr>
                <tr>
                  <td>Tanggal Bergabung</td>
                  <td>:</td>
                  <td>@{{ moment(careers[selectedCareerIndex]?.employee?.start_work_date).format('LL') }}</td>
                </tr>
                <tr>
                  <td>Lama Kerja</td>
                  <td>:</td>
                  <td>@{{ getDuration(careers[selectedCareerIndex]?.employee?.start_work_date) }}</td>
                </tr>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <table class="table">
                <tr class="bg-info">
                  <td>Income</td>
                  <td style="width: 200px;">Amount</td>
                </tr>
                <!-- <tr>
                  <td>Tunjangan Hari Raya</td>
                  <td class="d-flex justify-content-end">
                    <div class="input-group" style="width: 200px;">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" :value="Intl.NumberFormat('de-DE').format(careers[selectedCareerIndex]?.thr_amount)" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                    </div>
                  </td>
                </tr> -->
                <tr v-for="income in careers[selectedCareerIndex]?.incomes">
                  <td>@{{ income.name }}</td>
                  <td class="d-flex justify-content-end">
                    <div class="input-group" style="width: 200px;">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" v-model="income.value" class="form-control text-right" aria-describedby="basic-addon1" :readonly="income.type !== 'Manual'">
                    </div>
                  </td>
                </tr>
              </table>
              <div class="d-flex justify-content-end px-2 my-3">

              </div>
            </div>
            <div class="col-md-6">
              <table class="table">
                <tr class="bg-info">
                  <td>Deduction</td>
                  <td style="width: 200px;">Amount</td>
                </tr>
                <!-- <tr v-for="deduction in careers[selectedCareerIndex]?.payslips[0]?.pivot?.deductions">
                  <td>@{{ deduction.name }}</td>
                  <td class="d-flex justify-content-end">
                    <div class="input-group" style="width: 200px;">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" v-model="deduction.value" class="form-control text-right" aria-describedby="basic-addon1" :readonly="deduction.type !== 'Manual'">
                    </div>
                  </td>
                </tr> -->

              </table>
              <div class="d-flex justify-content-end px-2 my-3">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <table class="table">
                <tr class="bg-light">
                  <td>Total</td>
                  <td class="d-flex justify-content-end">
                    <div class="input-group" style="width: 200px;">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" :value="calculateTotalSub(careers[selectedCareerIndex]?.incomes)" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                    </div>
                  </td>
                </tr>

              </table>
            </div>
            <div class="col-md-6">
              <table class="table">
                <tr class="bg-light">
                  <td>Total</td>
                  <td class="d-flex justify-content-end">
                    <div class="input-group" style="width: 200px;">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" :value="0" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                    </div>
                  </td>
                </tr>

              </table>
              <table class="table">
                <tr class="bg-light">
                  <td>Take Home Pay</td>
                  <td class="d-flex justify-content-end">
                    <div class="input-group" style="width: 200px;">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" :value="calculateTakeHomePay(calculateTotalSub(careers[selectedCareerIndex]?.incomes), 0)" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                    </div>
                  </td>
                </tr>

              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Buat Slip</button> -->
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
<script type="text/javascript" src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')

<script>
  moment.locale('id');

  Vue.component('payslip', {
    props: ['id', 'name', 'thp', 'checkedcareers', 'onshowmodal'],
    computed: {
      temp: {
        get: function() {
          return this.checkedcareers;
        },
        set: function(newValue) {
          this.$emit("update:checkedcareers", newValue);
        }
      }
    },
    template: `
    <tr>
      <td class="text-center" style="width: 60px;">
        <label class="custom-control custom-checkbox" style="display: inline-block;">
          <input type="checkbox" v-model="temp" :value="id" class="custom-control-input"><span class="custom-control-label"></span>
        </label>
      </td>
      <td>@{{ name }}</td>
      <td class="text-center"><i class="fas fa-times-circle text-warning"></i> Belum Dibuat</td>
      <td class="text-center">Rp @{{ thp }}</td>
      <td class="text-center">
        <a href="#" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalPayslip" @click="onshowmodal(id)"><i class="fas fa-file-alt"></i> Slip Gaji</a>
      </td>
    </tr>
    `,
  })

  Vue.component('final-payslip', {
    props: ['id', 'name', 'thp', 'ondelete'],
    template: `
    <tr>
      <td class="text-center" style="width: 60px;">
      </td>
      <td>@{{ name }}</td>
      <td class="text-center"><i class="fas fa-check-circle text-success"></i> Sudah Dibuat</td>
      <td class="text-center">Rp @{{ Intl.NumberFormat('de-DE').format(thp) }}</td>
      <td class="text-center">
        <div class="btn-group" role="group" aria-label="Basic example">
          <a :href="'/payroll/print/' + id" target="_blank" class="btn btn-light btn-sm"><i class="fas fa-print"></i></a>
          @if(in_array("deleteThr", $userLoginPermissions))
          <a href="#" @click.prevent="ondelete(id)" class="btn btn-light btn-sm"><i class="fas fa-trash-alt"></i></a>
          @endif
          @if(in_array("editThr", $userLoginPermissions))
          <a :href="'/final-payslip/setting/' + id" class="btn btn-light btn-sm"><i class="fas fa-cog"></i></a>
          @endif
        </div>
      </td>
    </tr>
    `,
  })

  let app = new Vue({
    el: '#app',
    data: {
      checkedCareers: [],
      careers: JSON.parse(`{!! $preview_payslips !!}`),
      finalPayslips: JSON.parse(`{!! $final_payslips !!}`),
      payslipIsSelected: false,
      selectedCareerIndex: null,
      modalSkeleton: false,
      month: '{{ request()->query("month") }}',
      year: '{{ request()->query("year") }}',

      loadingCreatePayslip: false,
      startDatePeriod: `{{ $start_date_period }}`,
      endDatePeriod: `{{ $end_date_period }}`
    },
    methods: {
      createFinalPayslip: function() {
        let vm = this;
        vm.loadingCreatePayslip = true;
        axios.post('/thr', {
            payslips: this.checkedFinal,
            start_date: this.startDatePeriod,
            end_date: this.endDatePeriod,
            payslip_id: this.payslipId,
          })
          .then(function(response) {
            console.log(response);
            vm.loadingCreatePayslip = false;
            Swal.fire({
              title: 'Success',
              text: 'Your data has been saved',
              icon: 'success',
              allowOutsideClick: false,
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.reload();
              }
            })
            // console.log(response);
          })
          .catch(function(error) {
            vm.loadingCreatePayslip = false;
            // console.log(error);
            Swal.fire(
              'Oops!',
              'Something wrong',
              'error'
            )

          });
      },
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
            return axios.delete('/final-payslip/' + id)
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
      },
      toggleCheckAll: function() {
        if (this.checkedAll) {
          this.checkedCareers = [];
        } else {
          this.checkedCareers = this.careers.map(career => career.id);
        }
      },
      showModal: function(id) {
        // let element = this.$refs.modal.$el
        console.log('clicked id: ' + id)
        $('#modalPayslip').modal('show');
        this.payslipIsSelected = true;
        let index = this.careers.map(career => career.id).findIndex(careerId => careerId == id);
        // console.log(index);
        // console.log(this.careers[index]);
        this.selectedCareerIndex = index;
        // let career = this.careers.filter(c => c.id == id)[0];
        // if(career !== null || typeof career == 'undefined') {
        //   this.selectedCareer = career;
        // }
      },
      calculateTotalSub: function(arr) {
        return arr.map(item => Number(item.value)).reduce((acc, cur) => {
          return acc + cur
        }, 0);
      },
      calculateTakeHomePay: function(totalIncome = 0, totalDeduction = 0) {
        const thp = Number(totalIncome) - totalDeduction;
        return Intl.NumberFormat('de-DE').format(thp);


      },
      navigate: function() {
        window.location.href = `/thr?year=${this.year}`;
      },
      moment: function(date) {
        return moment(date);
      },
      getDuration: function(date) {
        var x = new moment()
        var y = new moment(date)
        var duration = moment.duration(x.diff(y));
        return (duration.years() !== 0 ? duration.years() + ' Tahun ' : '') + (duration.months() !== 0 ? duration.months() + ' Bulan ' : '') + (duration.days() !== 0 ? duration.days() + ' Hari' : '0 Hari');
      }
    },
    computed: {
      checkedAll: {
        get: function() {
          return this.checkedCareers.length == this.careers.length
        },
        set: function(newValue) {
          return newValue;
        }
      },
      checkedFinal: function() {
        // let checkedGeneratedPayslipIds = this.checkedGeneratedPayslips.map(checked => checked.id);
        let vm = this;
        return this.careers.filter(payslip => vm.checkedCareers.indexOf(payslip.id) > -1);
      },
      // fullName: {
      //   // getter
      //   get: function() {
      //     return this.firstName + ' ' + this.lastName
      //   },
      //   // setter
      //   set: function(newValue) {
      //     var names = newValue.split(' ')
      //     this.firstName = names[0]
      //     this.lastName = names[names.length - 1]
      //   }
      // }
    }
  })
</script>
<script>
  $('.use-datatable').DataTable();
</script>

@endsection