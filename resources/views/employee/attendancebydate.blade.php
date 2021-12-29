@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('libs/css/timeline.css') }}">
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

  .notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    /* padding: 5px 10px; */
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ff407b;

  }

  .notification-badge .number {
    display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 12px;
    color: white;
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
            <h2 class="pageheader-title bg-white mb-0 px-3 py-2 rounded-top">Absensi </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb bg-light px-3 py-2 rounded-bottom">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item">Timesheet</li>
                  <li class="breadcrumb-item active" aria-current="page">Attendance</li>
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
      <!-- <div class="card">
        <div class="card-body">
          <nav class="nav flex-column flex-sm-row">
            <a class="flex-sm-fill text-sm-center nav-link active" href="/attendance">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-calendar-alt fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Attendance</span><br>
                  <small class="text-muted">List All Attendances</small>
                </div>
              </div>
            </a>
          </nav>
        </div>
      </div> -->
      <!-- ============================================================== -->
      <!-- end page nav  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- summary  -->
      <!-- ============================================================== -->

      <div class="row">
        <!-- ============================================================== -->
        <!-- basic table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          @include('employee.menu')
          @include('employee.profile')
          <div class="row">
            <div class="col-md-6 xol-xs-12">
              <h2 style="color: #a6a6b7;"><i class="fas fa-calendar-alt"></i> {{ (int) explode("-", $date1)[0] }} {{ Helper::prettyMonth((int) explode("-", $date1)[1] - 1, "id") }} {{ explode("-", $date1)[2] }} - {{ (int) explode("-", $date2)[0] }} {{ Helper::prettyMonth((int) explode("-", $date2)[1] - 1, "id") }} {{ explode("-", $date2)[2] }}</h2>
            </div>
            <div class="col-md-6 xol-xs-12 text-right">
              <!-- <button href="#" class="btn btn-primary btn-sm mr-1" disabled><i class="fas fa-calendar-alt"></i> Attendance Calendar</button>
              <button href="#" class="btn btn-primary btn-sm mr-1" disabled><i class="fas fa-download"></i> Download</button>
              <button href="#" class="btn btn-primary btn-sm" disabled><i class="fas fa-upload"></i> Upload</button> -->
            </div>
          </div>

          <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title mb-0">Ringkasan Absensi</h5>
              <div class="toolbar ml-auto">

              </div>

            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <canvas id="chartjs_bar"></canvas>
                </div>
                <div class="col-md-6 col-xs-12 row pr-0">
                  <ul class="list-group list-group-flush col-md-6 col-xs-12">
                    <li class="list-group-item">
                      <div class="d-flex justify-content-between">
                        <div>
                          <span class="badge badge-success">Hadir</span>
                        </div>
                        <div>
                          <span>{{ $summary['present_count'] }}</span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item list-group-item-light">
                      <div class="d-flex justify-content-between">
                        <div>
                          <span class="badge badge-warning">Sakit</span>
                        </div>
                        <div>
                          <span>{{ $summary['sick_count'] }}</span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="d-flex justify-content-between">
                        <div>
                          <span class="badge badge-primary">Izin</span>
                        </div>
                        <div>
                          <span>{{ $summary['permission_count'] }}</span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item list-group-item-light">
                      <div class="d-flex justify-content-between">
                        <div>
                          <span class="badge badge-info">Cuti</span>
                        </div>
                        <div>
                          <span>{{ $summary['leave_count'] }}</span>
                        </div>
                      </div>
                    </li>
                  </ul>
                  <ul class="list-group list-group-flush col-md-6 col-xs-12 pr-0">
                    <li class="list-group-item">
                      <div class="d-flex justify-content-between">
                        <div>
                          <span class="badge badge-warning text-white">Pending</span>
                        </div>
                        <div>
                          <span>{{ $summary['pending_count'] }}</span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="d-flex justify-content-between">
                        <div>
                          <span class="badge badge-danger">Rejected</span>
                        </div>
                        <div>
                          <span>{{ $summary['rejected_count'] }}</span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item list-group-item-light">
                      <div class="d-flex justify-content-between">
                        <div>
                          <span class="badge badge-light">N/A</span> (Belum ada status)
                        </div>
                        <div>
                          <span>{{ count($period) - ($summary['present_count'] - $summary['sick_count'] - $summary['permission_count'] - $summary['leave_count'] - $summary['rejected_count']) }}</span>
                        </div>
                      </div>
                    </li>

                  </ul>
                </div>

              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title">Data Absensi Pegawai</h5>
              <div class="toolbar ml-auto">
                <a href="/attendance/export/sheet?employee_id={{ $employee->id }}&start_date={{ implode('-', array_reverse(explode('-', $date1))) }}&end_date={{ implode('-', array_reverse(explode('-', $date2))) }}" target="_blank" class="btn btn-primary btn-sm mr-1"><i class="fas fa-download"></i> Download</a>
                <!-- <button type="button" class="btn btn-primary btn-sm position-relative px-4" data-toggle="modal" data-target="#approvalModal">
                  Approval
                  <div class="notification-badge" v-if="pendingAttendances.length">
                    <span class="number">@{{ pendingAttendances.length }}</span>
                  </div>
                </button> -->
              </div>
            </div>
            <div class="card-body">
              <div class="row align-items-center mb-3">
                <div class="col-md-3 col-xs-12">
                  <div class="input-group">
                    <input type="text" id="date1" class="form-control input-date-bs" value="{{ $date1 }}" aria-describedby="basic-addon2" readonly required>
                    <div class="input-group-append">
                      <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar" style="line-height: 0;"></i></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-xs-12">
                  <div class="input-group">
                    <input type="text" id="date2" class="form-control input-date-bs" value="{{ $date2 }}" aria-describedby="basic-addon2" readonly required>
                    <div class="input-group-append">
                      <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar" style="line-height: 0;"></i></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-xs-12">
                  <button class="btn btn-primary btn-sm" id="btn-select-date" :disabled="date1 > date2">Tampilkan</button>
                </div>
              </div>
              <div class="mb-2" v-if="date1 > date2"><i class="fas fa-exclamation-triangle text-warning fa-xs"></i> Tanggal tidak valid</div>
              <div class="table-responsive">
                <table class="table table-striped use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Tanggal</th>
                      <th>Status</th>
                      <th>Jam Masuk</th>
                      <th>Jam Keluar</th>
                      <th>Lampiran</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($period as $att)
                    <tr>
                      @php
                      $explodedDate = explode("-", $att['date']);
                      $day = $explodedDate[2];
                      $month = $explodedDate[1];
                      $year = $explodedDate[0];
                      @endphp
                      <td class="text-center">{{ (int) $day }} {{ Helper::prettyMonth((int) $month - 1, "id") }} {{ $year }}</td>
                      @if($att['attendance'] !== null)
                      <td class="text-center">
                        @if($att['attendance']['status'] == 'present')
                        <span class="badge badge-success">Hadir</span>
                        @elseif($att['attendance']['status'] == 'sick')
                        <span class="badge badge-warning">Sakit</span>
                        @elseif($att['attendance']['status'] == 'permission')
                        <span class="badge badge-primary">Izin</span>
                        @elseif($att['attendance']['status'] == 'leave')
                        <span class="badge badge-info">Cuti</span>
                        @elseif($att['attendance']['status'] == 'pending')
                        <span class="badge badge-warning text-white">Pending ({{ $att['attendance']['pending_category'] }})</span>
                        @elseif($att['attendance']['status'] == 'rejected')
                        <span class="badge badge-danger">Rejected</span>
                        @else
                        <span class="badge badge-light">N/A</span>
                        @endif
                      </td>
                      <td class="text-center">{{ $att['attendance']['clock_in'] }}</td>
                      <td class="text-center">{{ $att['attendance']['clock_out'] }}</td>
                      <td class="text-center">
                        @if(count($att['attendance']['images']) > 0)
                        @foreach($att['attendance']['images'] as $image)
                        <a href="{{ Storage::disk("s3")->url($image) }}" target="_blank" class="mr-3"><i class="fas fa-paperclip"></i> {{ $image }}</a>
                        <br>
                        @endforeach
                        @else
                        -
                        @endif
                      </td>
                      @else
                      <td class="text-center"><span class="badge badge-light">N/A</span></td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      @endif
                    </tr>
                    @endforeach
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
  <!-- Modal -->
  <div class="modal fade" id="approvalModal" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-center modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="approvalModalLabel">Approval@{{ ` (${pendingAttendances.length})` }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="background-color: #efeff6;">
          <pending-attendance v-for="(attendance, index) in pendingAttendances" :key="attendance.id" :id="attendance.id" :index="index" :employee="attendance.employee" :note="attendance.note" :attachment="attendance.image" :onapprove="approveAttendance" :onreject="rejectAttendance" :date="attendance.created_at" :moment="moment"></pending-attendance>
          <div v-if="pendingAttendances.length < 1" colspan="6" class="text-center py-5">
            <i class="fa fa-fw fa-folder-open fa-3x"></i>
            <h4>No data yet</h4>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="historyModal" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-center modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="historyModalLabel">Attendance History</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
          <div class="container">
            <div class="row">
              <div class="col-md-10">
                <ul class="cbp_tmtimeline">
                  <li>
                    <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span class="hidden">25/12/2017</span> <span class="large">Now</span></time>
                    <div class="cbp_tmicon"><i class="zmdi zmdi-account"></i></div>
                    <div class="cbp_tmlabel empty"> <span>No Activity</span> </div>
                  </li>
                  <li>
                    <time class="cbp_tmtime" datetime="2017-11-04T03:45"><span>03:45 AM</span> <span>Today</span></time>
                    <div class="cbp_tmicon bg-info"><i class="zmdi zmdi-label"></i></div>
                    <div class="cbp_tmlabel">
                      <h2><a href="javascript:void(0);">Art Ramadani</a> <span>posted a status update</span></h2>
                      <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
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
<script src="{{ asset('vendor/charts/charts-bundle/Chart.bundle.js') }}"></script>
<script src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
<!-- <script src="{{ asset('vendor/charts/charts-bundle/chartjs.js') }}"></script> -->
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  function test() {
    return 'test'
  }

  moment.locale('id');

  Vue.component('pending-attendance', {
    props: ['id', 'index', 'employee', 'note', 'date', 'attachment', 'onapprove', 'onreject', 'moment'],
    template: `
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-end">
          <a v-if="note" class="mr-3" data-toggle="collapse" :href="'#collapseNote' + index" role="button" aria-expanded="false" :aria-controls="'#collapseNote' + index"><i class="fas fa-sticky-note"></i> Note</a>
          <a v-if="attachment" :href="'/images/' + attachment" target="_blank" class="mr-3"><i class="fas fa-paperclip"></i> Attachment</a>
          <span class="badge badge-warning">Sakit</span>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-2">
            <img src="https://api.unikom.ac.id/dashboard/public/api/foto/mahasiswa/rJq4esJmnRi3wQ7BN9Wx5Pep1S3kPgyg" alt="" width="80" class="rounded">
          </div>
          <div class="col-md-10">
            <h3 class="mb-0">@{{ employee.first_name }} @{{ employee.last_name }} (@{{ employee.employee_id }})</h3>
            <span class="mr-3 align-middle"><i class="fas fa-building fa-xs"></i> <span>@{{ employee.designation.department.company.name }}</span></span>
            <span class="mr-3 align-middle"><i class="fas fa-star fa-xs"></i> <span>@{{ employee.designation.department.name }}</span></span>
            <span class=" align-middle"><i class="fas fa-check-circle fa-xs"></i> <span>@{{ employee.designation.name }}</span></span>
            <div class="mt-2">
              <table>
                <tr>
                  <td><small>Gender</small></td>
                  <td><small>:</small></td>
                  <td class="pl-2"><small>@{{ employee.gender }}</small></td>
                </tr>
                <tr>
                  <td><small>Phone</small></td>
                  <td><small>:</small></td>
                  <td class="pl-2"><small>@{{ employee.phone }}</small></td>
                </tr>
                <tr>
                  <td><small>Email</small></td>
                  <td><small>:</small></td>
                  <td class="pl-2"><small>@{{ employee.email }}</small></td>
                </tr>
                <tr>
                  <td style="vertical-align: top;"><small>Address</small></td>
                  <td><small>:</small></td>
                  <td class="pl-2"><small>@{{ employee.address }}</small></td>
                </tr>
              </table>
            </div>
            <div class="collapse" :id="'collapseNote' + index">
              <div class="alert alert-info mt-3">
                @{{ note }}
              </div>
            </div>
            <!-- <div class="d-flex justify-content-end">
              <div>
                
              </div>
            </div> -->
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <i class="fas fa-clock"></i>
            <span>@{{ moment(date).format('LLLL') }}</span>
          </div>
          <div class="text-center">
            <button @click="onreject(id, index)" class="btn btn-light btn-sm mr-3"><i class="fas fa-times"></i> Reject</button>
            <button @click="onapprove(id, index)" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Approve</button>
          </div>
        </div>
      </div>
    </div>
    `,
  })

  function jsonEscape(str) {
    return str.replace(/\n/g, "\\\\n").replace(/\r/g, "\\\\r").replace(/\t/g, "\\\\t");
  }

  let app = new Vue({
    el: '#app',
    data: {
      pendingAttendances: (JSON.parse(jsonEscape('{!! json_encode($pending_attendances) !!}'))),
      date1: '{{ implode("-", array_reverse(explode("-", $date1))) }}',
      date2: '{{ implode("-", array_reverse(explode("-", $date2))) }}',
    },
    methods: {
      approveAttendance: function(id, index) {
        // const id = $(this).attr('data-id');
        const vm = this;
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
            return axios.post('/attendance/' + id + '/approve', {
                approval_note: note,
                approved_by: 1,
              })
              .then(function(response) {
                console.log(response.data);
                vm.pendingAttendances.splice(index, 1);
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
                // window.location.reload();
              }
            })
          }
        })
      },
      rejectAttendance: function(id, index) {
        const vm = this;
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
            return axios.post('/attendance/' + id + '/reject', {
                rejection_note: note,
                rejected_by: 1,
              })
              .then(function(response) {
                console.log(response.data);
                vm.pendingAttendances.splice(index, 1);
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
                // window.location.reload();
              }
            })
          }
        })
      },
      moment: function() {
        return moment();
      }
    }
  })
</script>

<script>
  $(function() {

    if ($('#chartjs_bar').length) {
      var ctx = document.getElementById("chartjs_bar").getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'bar',

        data: {
          // labels: ["Hadir", "Sakit", "Izin", "Cuti", "Rejected", "N/A"],
          datasets: [{
              label: 'Hadir',
              backgroundColor: "#d4edda",
              borderColor: "#c3e6cb",
              borderWidth: 2,
              data: ["{{ $summary['present_count'] }}"]
            },
            {
              label: 'Sakit',
              backgroundColor: "#fff3cd",
              borderColor: "#856404",
              borderWidth: 2,
              data: ["{{ $summary['sick_count'] }}"]
            },
            {
              label: 'Izin',
              backgroundColor: "rgba(89, 105, 255,0.5)",
              borderColor: "rgba(89, 105, 255,0.7)",
              borderWidth: 2,
              data: ["{{ $summary['permission_count'] }}"]
            },
            {
              label: 'Cuti',
              backgroundColor: "#d1ecf1",
              borderColor: "#bee5eb",
              borderWidth: 2,
              data: ["{{ $summary['leave_count'] }}"]
            },
            {
              label: 'Rejected',
              backgroundColor: "#f8d7da",
              borderColor: "#f5c6cb",
              borderWidth: 2,
              data: ["{{ $summary['rejected_count'] }}"]
            },
            {
              label: 'N/A',
              backgroundColor: "#e2e3e5",
              borderColor: "#d6d8db",
              borderWidth: 2,
              data: ["{{ count($period) - ($summary['present_count'] - $summary['sick_count']) - $summary['permission_count'] - $summary['leave_count'] - $summary['rejected_count'] }}"]
            },
          ]
        },
        options: {
          scales: {
            yAxes: [{

            }]
          },
          legend: {
            display: true,
            position: 'bottom',

            labels: {
              fontColor: '#71748d',
              fontFamily: 'Circular Std Book',
              fontSize: 14,
            }
          },

          scales: {
            xAxes: [{
              ticks: {
                fontSize: 14,
                fontFamily: 'Circular Std Book',
                fontColor: '#71748d',
              }
            }],
            yAxes: [{
              ticks: {
                fontSize: 14,
                fontFamily: 'Circular Std Book',
                fontColor: '#71748d',
              }
            }]
          }
        }


      });
    }

    $('table.use-datatable').DataTable({
      "paging": false,
      "searching": false,
      "sorting": false,
    });

    $('#date1').datepicker({
      format: 'dd-mm-yyyy'
    }).on('changeDate', function(e) {
      app.$data.date1 = e.format(0, 'yyyy-mm-dd');
      // window.location.href = '/attendance/date/' + e.format(0, 'dd-mm-yyyy')
    })
    $('#date2').datepicker({
      format: 'dd-mm-yyyy'
    }).on('changeDate', function(e) {
      app.$data.date2 = e.format(0, 'yyyy-mm-dd');
      // window.location.href = '/attendance/date/' + e.format(0, 'dd-mm-yyyy')
    })

    $('#btn-select-date').on('click', function() {
      window.location.href = '/employee/attendance/{{ $employee->id }}/' + $('#date1').val() + '/' + $('#date2').val()
    })

  })
</script>
@endsection