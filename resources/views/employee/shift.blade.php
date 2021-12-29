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

  .holiday {
    background-color: #f8d7da;
    color: #721c24;
    padding: 5px 0;
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

          <!-- NOTE -->
          <!-- <div class="alert alert-warning">Dev Note:
            <ul>
              <li>Tambah keterangan kategori staff, non staff, atau freelancer</li>
              <li>Tab: Shift</li>
              <li>Tab: Cuti</li>
              <li>Tab: Sakit</li>
              <li>Tab: Izin</li>
              <li>Tab: Kasbon</li>
            </ul>
          </div> -->
          <!-- NOTE -->
          <div class="card">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h3 class="m-0 mb-4">Shift Kerja Pegawai</h3>
                </div>
              </div>

              @if($shift == null)
              <div class="alert alert-warning">
                <p><i class="fas fa-exclamation-triangle fa-xs"></i> Pegawai ini belum memiliki shift</p>
              </div>
              @endif

              <form @submit.prevent="saveShift">
                <div class="row">
                  <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                      <label for="currentShift">Shift Kerja</label>
                      <select v-model="officeShift" class="form-control" required>
                        <option value="">-Pilih Shift-</option>
                        @foreach($office_shifts as $office_shift)
                        <option value="{{ $office_shift->id }}">{{ $office_shift->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    @if(in_array("editEmployeeShift", $userLoginPermissions))
                    <div>
                      <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
                    </div>
                    @endif
                  </div>
                </div>
              </form>

              <hr>

              <table class="table table-bordered">
                <thead>
                  <tr class="text-center">
                    <th>Hari</th>
                    <th>Status Hari</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <!-- <th>Jam Kerja</th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Senin</td>
                    <td v-if="selectedShift.monday_status !== ''" class="text-center">@{{ (selectedShift.monday_status == 'workday') ? 'Hari Kerja' : 'Hari Libur' }}</td>
                    <td v-else></td>
                    <td class="text-center">@{{ selectedShift.monday_in_time }}</td>
                    <td class="text-center">@{{ selectedShift.monday_out_time }}</td>
                    <!-- <td class="text-center"></td> -->
                  </tr>
                  <tr>
                    <td>Selasa</td>
                    <td v-if="selectedShift.tuesday_status !== ''" class="text-center">@{{ (selectedShift.tuesday_status == 'workday') ? 'Hari Kerja' : 'Hari Libur' }}</td>
                    <td v-else></td>
                    <td class="text-center">@{{ selectedShift.tuesday_in_time }}</td>
                    <td class="text-center">@{{ selectedShift.tuesday_out_time }}</td>
                    <!-- <td class="text-center"></td> -->
                  </tr>
                  <tr>
                    <td>Rabu</td>
                    <td v-if="selectedShift.wednesday_status !== ''" class="text-center">@{{ (selectedShift.wednesday_status == 'workday') ? 'Hari Kerja' : 'Hari Libur' }}</td>
                    <td v-else></td>
                    <td class="text-center">@{{ selectedShift.wednesday_in_time }}</td>
                    <td class="text-center">@{{ selectedShift.wednesday_out_time }}</td>
                    <!-- <td class="text-center"></td> -->
                  </tr>
                  <tr>
                    <td>Kamis</td>
                    <td v-if="selectedShift.thursday_status !== ''" class="text-center">@{{ (selectedShift.thursday_status == 'workday') ? 'Hari Kerja' : 'Hari Libur' }}</td>
                    <td v-else></td>
                    <td class="text-center">@{{ selectedShift.thursday_in_time }}</td>
                    <td class="text-center">@{{ selectedShift.thursday_out_time }}</td>
                    <!-- <td class="text-center"></td> -->
                  </tr>
                  <tr>
                    <td>Jumat</td>
                    <td v-if="selectedShift.friday_status !== ''" class="text-center">@{{ (selectedShift.friday_status == 'workday') ? 'Hari Kerja' : 'Hari Libur' }}</td>
                    <td v-else></td>
                    <td class="text-center">@{{ selectedShift.friday_in_time }}</td>
                    <td class="text-center">@{{ selectedShift.friday_out_time }}</td>
                    <!-- <td class="text-center"></td> -->
                  </tr>
                  <tr>
                    <td>Sabtu</td>
                    <td v-if="selectedShift.saturday_status !== ''" class="text-center">@{{ (selectedShift.saturday_status == 'workday') ? 'Hari Kerja' : 'Hari Libur' }}</td>
                    <td v-else></td>
                    <td class="text-center">@{{ selectedShift.saturday_in_time }}</td>
                    <td class="text-center">@{{ selectedShift.saturday_out_time }}</td>
                    <!-- <td class="text-center"></td> -->
                  </tr>
                  <tr>
                    <td>Minggu</td>
                    <td v-if="selectedShift.sunday_status !== ''" class="text-center">@{{ (selectedShift.sunday_status == 'workday') ? 'Hari Kerja' : 'Hari Libur' }}</td>
                    <td v-else></td>
                    <td class="text-center">@{{ selectedShift.sunday_in_time }}</td>
                    <td class="text-center">@{{ selectedShift.sunday_out_time }}</td>
                    <!-- <td class="text-center"></td> -->
                  </tr>
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
@endsection

@section('script')
<!-- slimscroll js -->
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
<!-- additional script -->
<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script> -->
<!-- <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script> -->
<script src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  let app = new Vue({
    el: '#app',
    data: {
      officeShifts: JSON.parse('{!! $office_shifts  !!}'),
      officeShift: '{{ ($shift !== null) ? $shift->id : "" }}',
      loading: false,
      // name: '',
      // mondayInTime: '',
      // // mondayInTimeDisabled: false,
      // mondayOutTime: '',
      // mondayStatus: 'workday',

      // tuesdayInTime: '',
      // tuesdayOutTime: '',
      // tuesdayStatus: 'workday',

      // wednesdayInTime: '',
      // wednesdayOutTime: '',
      // wednesdayStatus: 'workday',

      // thursdayInTime: '',
      // thursdayOutTime: '',
      // thursdayStatus: 'workday',

      // fridayInTime: '',
      // fridayOutTime: '',
      // fridayStatus: 'workday',

      // saturdayInTime: '',
      // saturdayOutTime: '',
      // saturdayStatus: 'workday',

      // sundayInTime: '',
      // sundayOutTime: '',
      // sundayStatus: 'workday',
      loading: false,
    },
    methods: {
      saveShift: function() {
        // console.log('submitted');
        let vm = this;
        vm.loading = true;
        axios.post('/employee/{{ $employee->id }}/edit-shift', {
            // company: this.company,
            office_shift: this.officeShift,
          })
          .then(function(response) {
            vm.loading = false;
            Swal.fire(
              'Success',
              'Shift has been changed',
              'success'
            ).then((result) => {
              if (result.isConfirmed) {
                window.location.reload();
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
      moment: function() {
        return moment();
      },
    },
    computed: {
      selectedShift: function() {

        let initial = {
          monday_status: '',
          monday_in_time: '',
          monday_out_time: '',
          tuesday_status: '',
          tuesday_in_time: '',
          tuesday_out_time: '',
          wednesday_status: '',
          wednesday_in_time: '',
          wednesday_out_time: '',
          thursday_status: '',
          thursday_in_time: '',
          thursday_out_time: '',
          friday_status: '',
          friday_in_time: '',
          friday_out_time: '',
          saturday_status: '',
          saturday_in_time: '',
          saturday_out_time: '',
          sunday_status: '',
          sunday_in_time: '',
          sunday_out_time: '',
        }

        let vm = this;
        if (vm.officeShift !== null || vm.officeShift !== '') {
          let shift = vm.officeShifts.filter(shift => shift.id == vm.officeShift)[0];

          if (shift == null) {
            return initial
          }

          return shift;
        }

        return initial;
      }
    }
  })
</script>
@endsection