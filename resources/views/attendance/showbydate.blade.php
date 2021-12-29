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
          <div class="row">
            <div class="col-md-6 xol-xs-12">
              <h2 style="color: #a6a6b7;"><i class="fas fa-calendar-alt"></i> {{ (int) date_format(date_create($date), 'd') }} {{ Helper::prettyMonth((int) date_format(date_create($date), 'm') - 1, "id") }} {{ date_format(date_create($date), 'Y') }}</h2>
            </div>
            <div class="col-md-6 xol-xs-12 text-right">
              <button href="#" class="btn btn-primary btn-sm mr-1" disabled><i class="fas fa-calendar-alt"></i> Attendance Calendar</button>
              <button href="#" class="btn btn-primary btn-sm mr-1" disabled><i class="fas fa-download"></i> Download</button>
              <button href="#" class="btn btn-primary btn-sm" disabled><i class="fas fa-upload"></i> Upload</button>
            </div>
          </div>

          <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title mb-0">Ringkasan Absensi</h5>
              <div class="toolbar ml-auto">
                <div class="input-group">
                  <input type="text" class="form-control input-date-bs" value="{{ date_format(date_create($date), 'd/m/Y') }}" aria-describedby="basic-addon2" readonly required>
                  <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar" style="line-height: 0;"></i></span>
                  </div>
                </div>
              </div>

            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <canvas id="chartjs_pie"></canvas>
                </div>
                <div class="col-md-6 col-xs-12">
                  <ul class="list-group list-group-flush">
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
                          <span>{{ count($attendances) - ($summary['present_count'] - $summary['sick_count']) - $summary['permission_count'] - $summary['leave_count'] - $summary['rejected_count'] }}</span>
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
              <h5 class="card-header-title">List Absensi Pegawai</h5>
              <div class="toolbar ml-auto">
                <!-- <button type="button" class="btn btn-primary btn-sm position-relative px-4" data-toggle="modal" data-target="#approvalModal">
                  Approval
                  <div class="notification-badge" v-if="pendingAttendances.length">
                    <span class="number">@{{ pendingAttendances.length }}</span>
                  </div>
                </button> -->
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Pegawai</th>
                      <th>Job Title</th>
                      <th>Status</th>
                      <th>Jam Masuk</th>
                      <th>Jam Keluar</th>
                      <th>Durasi Lembur</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                      <td>
                        <!--<div class="d-flex align-items-center">-->
                        <!--    <a class="d-inline-block mr-1" href="/employee/detail/{{ $attendance->id }}">-->
                        <!--        <div style="width: 40px; height: 40px;">-->
                        <!--            <img src="{{ ($attendance->photo !== null) ? Storage::disk('s3')->url($attendance->photo) : 'https://cabdindikwil1.com/wp-content/uploads/2020/12/male.png' }}" alt="" class="rounded lozad" alt="" style="height: 100%; width: 100%; object-fit: cover">-->
                        <!--        </div>-->
                        <!--    </a>-->
                        <!--    <span><a href="/employee/detail/{{ $attendance->id }}">{{ $attendance->first_name . ' ' . $attendance->last_name }}</a></span>-->
                        <!--</div>-->
                        <span><a href="/employee/detail/{{ $attendance->id }}">{{ $attendance->first_name . ' ' . $attendance->last_name }}</a></span>
                      </td>
                      @if(count($attendance->careers) > 0)
                      @if($attendance->careers[0]->jobTitle !== null)
                      <td>{{ $attendance->careers[0]->jobTitle->name }}</td>
                      @else
                      <td></td>
                      @endif
                      @else
                      <td><a href="/career/create/{{ $attendance->id }}"><span style="text-decoration: underline; text-decoration-style: dotted;">Atur Karir <i class="far fa-edit fa-xs"></i></span></a></td>
                      @endif
                      <td class="text-center">
                        @if($attendance->status == 'present')
                        <span class="badge badge-success">Hadir</span>
                        @elseif($attendance->status == 'sick')
                        <span class="badge badge-warning">Sakit</span>
                        @elseif($attendance->status == 'permission')
                        <span class="badge badge-primary">Izin</span>
                        @elseif($attendance->status == 'leave')
                        <span class="badge badge-info">Cuti</span>
                        @elseif($attendance->status == 'pending')
                        <span class="badge badge-warning text-white">Pending ({{ $attendance->pending_category }})</span>
                        @elseif($attendance->status == 'rejected')
                        <span class="badge badge-danger">Rejected</span>
                        @else
                        <span class="badge badge-light">N/A</span>
                        @endif
                      </td>
                      <!-- <td class="text-center"><input type="time" @change="updateClockIn($event, {{ $attendance->checkin_id }})" class="form-control form-control-sm" value="{{ $attendance->clock_in }}"></td>
                      <td class="text-center"><input type="time" @change="updateClockOut($event, {{ $attendance->checkout_id }})" class="form-control form-control-sm" value="{{ $attendance->clock_out }}"></td> -->
                      <td class="text-center"><input type="time" data-date="{{ date('Y-m-d', strtotime($date)) }}" data-employee="{{ $attendance->id }}" data-id="{{ $attendance->checkin_id }}" @change="updateClockIn($event)" class="form-control form-control-sm attendance-time" value="{{ $attendance->clock_in }}"></td>
                      <td class="text-center"><input type="time" data-date="{{ date('Y-m-d', strtotime($date)) }}" data-employee="{{ $attendance->id }}" data-id="{{ $attendance->checkout_id }}" @change="updateClockOut($event)" class="form-control form-control-sm attendance-time" value="{{ $attendance->clock_out }}"></td>
                      <td class="text-center">
                        @if($attendance->status == 'present' && $attendance->checkout_id !== null)
                        <input type="number" @change="updateOvertime($event, {{ $attendance->checkout_id }})" class="form-control form-control-sm mx-auto" value="{{ $attendance->overtime_duration }}" style="width: 70px;">
                        @endif
                      </td>
                      <td class="text-center">
                        @if(count($attendance->images) > 0)
                        @foreach($attendance->images as $image)
                        <a href="/images/{{ $image }}" target="_blank" class="mr-3"><i class="fas fa-paperclip"></i> {{ $image }}</a>
                        <br>
                        @endforeach
                        @else
                        -
                        @endif
                        <!-- <a href="#" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="{{ $attendance->note }}"><i class="fas fa-sticky-note"></i></a>
                        <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#historyModal"><i class="fas fa-clock"></i></button> -->
                      </td>
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
          <pending-attendance v-for="(attendance, index) in pendingAttendances" :key="attendance.id" :id="attendance.id" :index="index" :employee="attendance.employee" :note="attendance.note" :attachment="attendance.image" :onapprove="approveAttendance" :onreject="rejectAttendance" :date="attendance.date" :category="categoryToLocale(attendance.category)" :datecreated="attendance.created_at" :moment="moment"></pending-attendance>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
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
    props: ['id', 'index', 'employee', 'note', 'date', 'category', 'datecreated', 'attachment', 'onapprove', 'onreject', 'moment'],
    template: `
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div>
            <i class="fas fa-calendar"></i>
            <span>@{{ moment(date).format('L') }}</span>
          </div>
          <div>
            <a v-if="note" class="mr-3" data-toggle="collapse" :href="'#collapseNote' + index" role="button" aria-expanded="false" :aria-controls="'#collapseNote' + index"><i class="fas fa-sticky-note"></i> Note</a>
            <a v-if="attachment" :href="'/images/' + attachment" target="_blank" class="mr-3"><i class="fas fa-paperclip"></i> Attachment</a>
            <span class="badge badge-warning">@{{ category }}</span>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-2">
            <img src="https://api.unikom.ac.id/dashboard/public/api/foto/mahasiswa/rJq4esJmnRi3wQ7BN9Wx5Pep1S3kPgyg" alt="" width="80" class="rounded">
          </div>
          <div class="col-md-10">
            <h3 class="mb-0">@{{ employee.first_name }} @{{ employee.last_name }} (@{{ employee.employee_id }})</h3>
            <span class="mr-3 align-middle" v-if="employee.careers.length"><i class="fas fa-check-circle fa-xs"></i> <span>@{{ employee.careers[0].job_title.name }}</span></span>
            <span class="align-middle" v-if="employee.careers.length"><i class="fas fa-star fa-xs"></i> <span>@{{ employee.careers[0].designation.name }}</span></span>
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
            <span>Diajukan pada @{{ moment(datecreated).format('LLLL') }}</span>
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
      moment: function(date) {
        return moment(date);
      },
      categoryToLocale: function(category) {
        switch (category) {
          case 'sick':
            return 'Sakit';
            break;
          case 'present':
            return 'Hadir';
            break;
          case 'permission':
            return 'Izin';
            break;
          default:
            return '';
        }
      },
      updateOvertime: function(event, id) {
        axios.patch('/attendance/' + id + '/update-overtime', {
            overtime_duration: event.target.value,
          })
          .then(function(response) {
            console.log('overtime updated');
          })
          .catch(function(error) {
            console.log(error.data);
            Swal.fire({
              icon: 'error',
              title: 'Oops',
              text: "Can't update overtime",
            })
          });
      },
      updateClockIn: function(event) {
        let date = event.target.getAttribute('data-date');
        let employeeId = event.target.getAttribute('data-employee');

        let id = event.target.getAttribute('data-id');
        if (!id || typeof id == "undefined") {
          id = null;
        } else {
          if (!employeeId || typeof employeeId == "undefined") {
            return Swal.fire({
              icon: 'error',
              title: 'Oops',
              text: "[Error] ID pegawai tidak ditemukan",
            })
          }
        }

        axios.post('/attendance/' + id + '/update-clockin', {
            date: date,
            clock_in: event.target.value,
            employee_id: employeeId,
          })
          .then(function(response) {
            console.log('clock in updated');
            // console.log(response.data);
            const {
              data
            } = response.data;
            event.target.setAttribute('data-employee', data.employee_id);
            event.target.setAttribute('data-id', data.id);
          })
          .catch(function(error) {
            console.log(error.data);
            Swal.fire({
              icon: 'error',
              title: 'Oops',
              text: "Can't update clock in",
            })
          });
      },
      updateClockOut: function(event) {
        let date = event.target.getAttribute('data-date');
        let employeeId = event.target.getAttribute('data-employee');

        let id = event.target.getAttribute('data-id');
        if (!id || typeof id == "undefined") {
          id = null;
        } else {
          if (!employeeId || typeof employeeId == "undefined") {
            return Swal.fire({
              icon: 'error',
              title: 'Oops',
              text: "[Error] ID pegawai tidak ditemukan",
            })
          }
        }

        if (!id) {
          $('.attendance-time').prop('disabled', true);
        }


        axios.post('/attendance/' + id + '/update-clockout', {
            date: date,
            clock_out: event.target.value,
            employee_id: employeeId,
          })
          .then(function(response) {
            // $('.attendance-time').prop('disabled', false);
            console.log('clock out updated');
            if (!id) {
              window.location.reload();
            }
            // console.log(response.data);
            // const {
            //   data
            // } = response.data;
            // event.target.setAttribute('data-employee', data.employee_id);
            // event.target.setAttribute('data-id', data.id);
          })
          .catch(function(error) {
            $('.attendance-time').prop('disabled', false);
            console.log(error.data);
            Swal.fire({
              icon: 'error',
              title: 'Oops',
              text: "Can't update clock out",
            })
          });
      },
    }
  })
</script>

<script>
  $(function() {
    const observer = lozad(); // lazy loads elements with default selector as '.lozad'
    observer.observe();

    if ($('#chartjs_pie').length) {
      var ctx = document.getElementById("chartjs_pie").getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ["Hadir", "Sakit", "Izin", "Cuti", "Rejected", "N/A (Belum ada status)"],
          datasets: [{
            backgroundColor: [
              "#21ae41",
              "#f3b600",
              "#5969ff",
              "#0998b0",
              "#da0419",
              "#efeff6",
            ],
            data: ["{{ $summary['present_count'] }}", "{{ $summary['sick_count'] }}", "{{ $summary['permission_count'] }}", "{{ $summary['leave_count'] }}", "{{ $summary['rejected_count'] }}", "{{ count($attendances) - ($summary['present_count'] - $summary['sick_count']) - $summary['permission_count'] - $summary['leave_count'] - $summary['rejected_count'] }}"]
          }]
        },
        options: {
          legend: {
            display: true,
            position: 'bottom',
            labels: {
              fontColor: '#71748d',
              fontFamily: 'Circular Std Book',
              fontSize: 14,
            }
          },


        }
      });
    }

    $('table.use-datatable').DataTable();

    $('.input-date-bs').datepicker({
      format: 'dd/mm/yyyy'
    }).on('changeDate', function(e) {
      window.location.href = '/attendance/date/' + e.format(0, 'dd-mm-yyyy')
    })


  })
</script>
@endsection