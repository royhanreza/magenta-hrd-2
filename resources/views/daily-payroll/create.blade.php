@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/buttons.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/select.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/fixedHeader.bootstrap4.css') }}"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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

  .table-employee-info tr td {
    padding: 3px;
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
              <h5 class="card-header-title">Buat Slip Gaji Harian</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="{{ url('company/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Generate Slip</a> -->
              </div>
            </div>
            <div class="card-body">
              <div class="p-3 border bg-light mb-3">
                <div class="row">
                  <div class="col-lg-8 col-md-12">
                    <div class="form-row">
                      <div class="col">
                        <input type="text" class="form-control" id="datepicker">
                      </div>
                      <div class="col">
                        <button type="button" @click="generate" class="btn btn-primary btn-sm"><i class="fas fa-database"></i> Generate</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="checkedGeneratedPayslips.length > 0" class="p-3 border mb-3 d-flex justify-content-end">
                <button class="btn btn-light btn-sm" @click="createFinalPayslip" v-bind:disabled="loadingCreatePayslip"><span v-if="loadingCreatePayslip" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;<i class="fas fa-plus-circle"></i> Buat @{{ checkedGeneratedPayslips.length }} Payslip</button>
              </div>
              <!-- <button type="button" @click="generate" class="btn btn-primary btn-sm"><i class="fas fa-database"></i> Generate</button> -->
              <div v-if="loading" class="d-flex justify-content-center mb-3">
                <div class="spinner-grow text-primary" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-grow text-primary" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-grow text-primary" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th class="text-center">
                        <label class="custom-control custom-checkbox" style="display: inline-block;">
                          <input type="checkbox" v-model="checkedAll" @change="toggleCheckAll" class="custom-control-input"><span class="custom-control-label"></span>
                        </label>
                      </th>
                      <th>Pegawai</th>
                      <th>Job Title</th>
                      <th>Periode</th>
                      <th>Jumlah Hari</th>
                      <th>Take Home Pay</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="generatedPayslips.length < 1 && isGenerated == false">
                      <td colspan="7">
                        <div>
                          <div class="text-center">
                            <i class="far fa-calendar-alt fa-4x text-light"></i><br>
                            <span class="d-inline-block mt-2">Pilih periode gaji</span><br>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr is="generated-payslip" v-for="generatedPayslip in generatedPayslips" :key="generatedPayslip.id" :id="generatedPayslip.id" :name="generatedPayslip.first_name" :jobtitle="generatedPayslip.careers[0]?.job_title?.name" :payslipperiod="generatedPayslipPeriod" :days="amountOfDays" :takehomepay="calculateTakeHomePay(generatedPayslip.payments)" :checkedpayslips.sync="checkedGeneratedPayslips" :onshowmodal="showModal" :shift="generatedPayslip.office_shifts"></tr>
                    <!-- <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-center" style="width: 15%;">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <a href="/payroll/" class="btn btn-sm btn-light"><i class="fas fa-th"></i> Detail</a>
                        </div>
                      </td>
                    </tr> -->
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
<!-- ============================================================== -->
<!-- Modal  -->
<!-- ============================================================== -->
<div class="modal fade" id="modalGeneratedPayslip" tabindex="-1" role="dialog" aria-labelledby="modalGeneratedPayslipCenterTitle" aria-hidden="true" ref="modal">
  <div class="modal-dialog mw-100 w-75 modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalGeneratedPayslipLongTitle">Detail Slip Gaji</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div v-if="generatedPayslipIsSelected">
          <div class="text-center">
            <h2>Slip Gaji</h2>
            <h4>Periode @{{ generatedPayslipPeriod }}</h4>
          </div>
          <div class="p-3 bg-light border mb-3">
            <div class="row">
              <div class="col-lg-6 col-md-12">
                <table class="table-employee-info">
                  <tr>
                    <td>ID Pegawai</td>
                    <td>:</td>
                    <td>@{{ generatedPayslips[selectedGeneratedPayslipIndex]?.employee_id }}</td>
                  </tr>
                  <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>@{{ generatedPayslips[selectedGeneratedPayslipIndex]?.first_name }}</td>
                  </tr>
                  <tr>
                    <td>Job Title</td>
                    <td>:</td>
                    <td>@{{ generatedPayslips[selectedGeneratedPayslipIndex]?.careers[0]?.job_title?.name }}</td>
                  </tr>
                  <tr>
                    <td>Bagian / Divisi</td>
                    <td>:</td>
                    <td>@{{ generatedPayslips[selectedGeneratedPayslipIndex]?.careers[0]?.designation?.name }}</td>
                  </tr>
                </table>
              </div>
              <div class="col-lg-6 col-md-12">
                <table class="table-employee-info">
                  <tr>
                    <td>Uang Harian (Hari Kerja)</td>
                    <td>:</td>
                    <td>Rp @{{ generatedPayslips[selectedGeneratedPayslipIndex]?.daily_money_regular }}</td>
                  </tr>
                  <tr>
                    <td>Uang Harian (Hari Libur)</td>
                    <td>:</td>
                    <td>Rp @{{ generatedPayslips[selectedGeneratedPayslipIndex]?.daily_money_holiday }}</td>
                  </tr>
                  <tr>
                    <td>Upah Lembur (Hari Kerja)</td>
                    <td>:</td>
                    <td>Rp @{{ generatedPayslips[selectedGeneratedPayslipIndex]?.overtime_pay_regular }}</td>
                  </tr>
                  <tr>
                    <td>Upah Lembur (Hari Libur)</td>
                    <td>:</td>
                    <td>Rp @{{ generatedPayslips[selectedGeneratedPayslipIndex]?.overtime_pay_holiday }}</td>
                  </tr>
                </table>
              </div>
              <div class="col-lg-6 col-md-12">
              </div>
            </div>
          </div>
          <!-- <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#alert-info"><i class="fas fa-exclamation-circle"></i>Info</button>
          <div id="alert-info" class="collapse alert alert-info">
            <ul>
              <li>WD: Hari Kerja</li>
              <li>HOL: Hari Libur</li>
              <li>LN: Libur Nasional</li>
              <li>CB: Cuti Bersama</li>
            </ul>
          </div> -->
          <div class="row mb-4 p-3" style="max-height: 500px; overflow: scroll;">
            <table class="table">
              <thead>
                <tr class="text-center">
                  <th>Tanggal</th>
                  <th>Hari</th>
                  <th>Kal</th>
                  <th>Masuk</th>
                  <th>Pulang</th>
                  <th>Lembur</th>
                  <th>Gaji Harian</th>
                  <th>Uang Lembur</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="payment in generatedPayslips[selectedGeneratedPayslipIndex]?.payments" :key="payment.date">
                  <td class="text-center">@{{ payment.date.split('-')[2] }}/@{{ payment.date.split('-')[1] }}/@{{ payment.date.split('-')[0] }}</td>
                  <td>@{{ moment(payment.date).format('dddd') }}</td>
                  <td class="text-capitalize text-center">@{{ determineDayStatus(payment.day_status) }}</td>
                  <td v-if="payment.attendance !== null">
                    <span v-if="payment.attendance.category !== 'present'">@{{ determineCategory(payment.attendance.category) }}</span>
                    <span v-else>@{{ payment.attendance.clock_in }}</span>
                  </td>
                  <td v-else>-</td>
                  <td v-if="payment.attendance !== null">@{{ payment.attendance.clock_out }}</td>
                  <td v-else>-</td>
                  <td v-if="payment.attendance !== null">@{{ String(payment.attendance.overtime).padStart(2, "0") }}:00:00</td>
                  <td v-else>-</td>
                  <!-- <td v-if="payment.attendance !== null" class="text-right">@{{ Intl.NumberFormat('de-DE').format(payment.attendance.daily_money) }}</td> -->
                  <td v-if="payment.attendance !== null">
                    <div class="d-flex justify-content-end">
                      <div class="input-group" style="width: 150px;">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" v-model="payment.attendance.daily_money" class="form-control text-right" aria-describedby="basic-addon1">
                      </div>
                    </div>
                  </td>
                  <td v-else class="text-right">-</td>
                  <!-- <td v-if="payment.attendance !== null" class="text-right">@{{ Intl.NumberFormat('de-DE').format(payment.attendance.overtime_pay) }}</td> -->
                  <td v-if="payment.attendance !== null">
                    <div class="d-flex justify-content-end">
                      <div class="input-group" style="width: 150px;">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" v-model="payment.attendance.overtime_pay" class="form-control text-right" aria-describedby="basic-addon1">
                      </div>
                    </div>
                  </td>
                  <td v-else class="text-right">-</td>
                  <td v-if="payment.attendance !== null" class="text-right">
                    <div class="d-flex justify-content-end">
                      <div class="input-group" style="width: 150px;">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" :value="Number(payment.attendance.daily_money) + Number(payment.attendance.overtime_pay)" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                      </div>
                    </div>
                  </td>
                  <td v-else class="text-right">-</td>
                </tr>
              </tbody>
              <!-- <tfoot>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="text-right"><strong>Rp @{{ calculateTotalDaily(generatedPayslips[selectedGeneratedPayslipIndex]?.payments) }}</strong></td>
                  <td class="text-right"><strong>Rp @{{ calculateTotalOvertime(generatedPayslips[selectedGeneratedPayslipIndex]?.payments) }}</strong></td>
                  <td class="text-right"><strong>Rp @{{ calculateTakeHomePay(generatedPayslips[selectedGeneratedPayslipIndex]?.payments) }}</strong></td>
                </tr>
              </tfoot> -->
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Simpan</button>
        <!-- <button type="button" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button> -->
        <!-- <button type="button" class="btn btn-light btn-sm"><i class="fas fa-plus"></i> Buat Slip</button> -->
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
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<script type="text/javascript" src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  moment.locale('id');
  $(function() {
    $('#datepicker').daterangepicker();
    // $('#datepicker').on('apply.daterangepicker', function(ev, picker) {
    //   console.log(picker.startDate.format('YYYY-MM-DD'));
    //   console.log(picker.endDate.format('YYYY-MM-DD'));
    // });
  })
</script>
<script>
  Vue.component('generated-payslip', {
    props: ['id', 'name', 'jobtitle', 'payslipperiod', 'days', 'takehomepay', 'shift', 'checkedpayslips', 'onshowmodal'],
    computed: {
      temp: {
        get: function() {
          return this.checkedpayslips;
        },
        set: function(newValue) {
          this.$emit("update:checkedpayslips", newValue);
        }
      }
    },
    template: `
      <tr>
        <td class="text-center" style="width: 60px;">
          <label class="custom-control custom-checkbox" style="display: inline-block;">
            <input type="checkbox" v-model="temp" :value="id" class="custom-control-input" :disabled="shift.length < 1"><span class="custom-control-label"></span>
          </label>
        </td>
        <td>@{{ name }}</td>
        <td v-if="jobtitle !== null && jobtitle !== undefined">@{{ jobtitle }}</td>
        <td v-else><a :href="'/employee/career/' + id" target="_blank">Atur Karir <i class="fas fa-cog fa-xs"></i></a></td>
        <td class="text-center">@{{ payslipperiod }}</td>
        <td class="text-center">@{{ days }}</td>
        <td class="text-center">Rp @{{ Intl.NumberFormat('de-DE').format(takehomepay)  }}</td>
        <td v-if="shift.length > 0" class="text-center" style="width: 15%;">
          <div class="btn-group" role="group" aria-label="Action Buttons">
            <a href="#" class="btn btn-sm btn-light" data-toggle="modal" data-target="#modalGeneratedPayslip" @click="onshowmodal(id)"><i class="fas fa-file-alt"></i> Detail</a>
          </div>
        </td>
        <td v-else class="text-center"><a :href="'/employee/office-shift/' + id" target="_blank">Atur Shift <i class="fas fa-cog fa-xs"></i></a></td>
      </tr>
    `
  })

  let app = new Vue({
    el: '#app',
    data: {
      generatedPayslips: [],
      finalPayslips: [],
      isGenerated: false,
      startDatePeriod: '',
      endDatePeriod: '',
      loading: false,
      loadingCreatePayslip: false,
      // generatedPayslipPeriod: '',
      amountOfDays: 1,
      checkedGeneratedPayslips: [],
      generatedPayslipIsSelected: true,
      selectedGeneratedPayslipIndex: null,
    },
    methods: {
      createFinalPayslip: function() {
        let vm = this;
        vm.loadingCreatePayslip = true;
        axios.post('/daily-payroll', {
            payslips: this.checkedFinal,
            start_date: this.startDatePeriod,
            end_date: this.endDatePeriod,
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
                window.location.href = '/daily-payroll';
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
      generate: function() {
        // console.log('submitted');
        let daterange = $('#datepicker').data('daterangepicker');
        let startDate = daterange.startDate.format('YYYY-MM-DD');
        let endDate = daterange.endDate.format('YYYY-MM-DD');
        // console.log(daterange.startDate.format('YYYY-MM-DD'), daterange.endDate.format('YYYY-MM-DD'));
        let vm = this;
        vm.loading = true;
        vm.isGenerated = true;

        // console.log(startDate, endDate);
        axios.get(`/daily-payroll/generate?startDate=${startDate}&endDate=${endDate}`)
          .then(function(response) {
            vm.loading = false;
            // console.log(response);
            vm.generatedPayslips = response.data.data.generated_payslips;
            vm.finalPayslips = response.data.data.final_payslips;
            // vm.generatedPayslipPeriod = moment(response.data.data.start_date).format('L') + ' - ' + moment(response.data.data.end_date).format('L');
            vm.startDatePeriod = response.data.data.start_date;
            vm.endDatePeriod = response.data.data.end_date;
            vm.amountOfDays = response.data.data.amount_of_days;
            vm.checkedGeneratedPayslips = [];
            // vm.period = 
            // console.log(response);
          })
          .catch(function(error) {

          });
      },
      calculateTakeHomePay: function(payments) {
        let takeHomePay = payments.map(payment => {
          if (payment.attendance !== null) {
            return Number(payment.attendance.daily_money) + Number(payment.attendance.overtime_pay);
          }

          return 0;
        }).reduce((acc, cur) => {
          return acc + cur;
        }, 0);

        return takeHomePay;
      },
      calculateTotalDaily: function(payments) {
        let total = payments.map(payment => {
          if (payment.attendance !== null) {
            return Number(payment.attendance.daily_money);
          }

          return 0;
        }).reduce((acc, cur) => {
          return acc + cur;
        }, 0);

        return total;
      },
      calculateTotalOvertime: function(payments) {
        let total = payments.map(payment => {
          if (payment.attendance !== null) {
            return Number(payment.attendance.overtime_pay);
          }
          return 0;
        }).reduce((acc, cur) => {
          return acc + cur;
        }, 0);

        return total;
      },
      toggleCheckAll: function() {
        if (this.checkedAll) {
          this.checkedGeneratedPayslips = [];
        } else {
          this.checkedGeneratedPayslips = this.generatedPayslips.filter(payslip => payslip.office_shifts.length > 0).map(paylsip => paylsip.id);
        }
      },
      showModal: function(id) {
        // let element = this.$refs.modal.$el
        // console.log('clicked id: ' + id)
        $('#modalGeneratedPayslip').modal('show');
        this.payslipIsSelected = true;
        let index = null;
        // if (this.generatedPayslips.length > 0) {
        //   index = this.generatedPayslips.map(payslip => payslip.id).findIndex(payslipId => payslipId == id)
        // }
        index = this.generatedPayslips.map(payslip => payslip.id).findIndex(payslipId => payslipId == id)
        // console.log(index);
        // console.log(this.careers[index]);
        this.selectedGeneratedPayslipIndex = index;
        // let career = this.careers.filter(c => c.id == id)[0];
        // if(career !== null || typeof career == 'undefined') {
        //   this.selectedCareer = career;
        // }
      },
      moment: function(date) {
        return moment(date);
      },
      determineDayStatus: function(status) {
        switch (status) {
          case 'holiday':
            return 'HOL';
            break;
          case 'workday':
            return 'WD';
            break;
          case 'cuti bersama':
            return 'CB';
            break;
          case 'libur nasional':
            return 'LN';
            break;
          default:
            return '-';
        }
      },
      determineCategory: function(category) {
        switch (category) {
          case 'sick':
            return 'Sakit';
            break;
          case 'permission':
            return 'Izin';
            break;
          case 'leave':
            return 'Cuti';
            break;
          default:
            return '-';
        }
      }
    },
    computed: {
      checkedAll: {
        get: function() {
          return this.checkedGeneratedPayslips.length == this.generatedPayslips.filter(payslip => payslip.office_shifts.length > 0).length;
          // return this.checkedGeneratedPayslips.length == this.generatedPayslips.length;

        },
        set: function(newValue) {
          return newValue;
        }
      },
      // Get data intersect between checked and generated payslip
      checkedFinal: function() {
        // let checkedGeneratedPayslipIds = this.checkedGeneratedPayslips.map(checked => checked.id);
        let vm = this;
        return this.generatedPayslips.filter(payslip => vm.checkedGeneratedPayslips.indexOf(payslip.id) > -1);
      },
      generatedPayslipPeriod: function() {
        return moment(this.startDatePeriod).format('L') + ' - ' + moment(this.endDatePeriod).format('L');
      },
    }
  })
</script>


@endsection