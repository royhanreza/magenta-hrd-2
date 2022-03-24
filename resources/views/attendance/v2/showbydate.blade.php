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

@section('title', 'Absensi | Magenta HRD')

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
                                            <th>ID Pegawai</th>
                                            <th>Pegawai</th>
                                            <th>Job Title</th>
                                            <th>Status</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Keluar</th>
                                            <th>Jumlah Jam Lembur</th>
                                            <th>Pengajuan Lembur</th>
                                            <th>Jumlah Jam Lembur Pengajuan</th>
                                            <th><i class="fas fa-paperclip"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(attendance, index) in attendances">
                                            <td>@{{ attendance.employee_id }}</td>
                                            <td>
                                                <span><a :href="'/employee/detail/' + attendance.id">@{{ attendance.first_name }}</a></span>
                                            </td>
                                            <!-- Career -->
                                            <td v-if="attendance.careers.length && attendance.careers[0].job_title">@{{attendance.careers[0].job_title.name}}</td>
                                            <td v-else></td>
                                            <!-- End:Career -->
                                            <!-- Status -->
                                            <td class="text-center">
                                                <span v-if="attendance.status == 'present'" class="badge badge-success">Hadir</span>
                                                <span v-else-if="attendance.status == 'sick'" class="badge badge-warning">Sakit</span>
                                                <span v-else-if="attendance.status == 'permission'" class="badge badge-primary">Izin</span>
                                                <span v-else-if="attendance.status == 'leave'" class="badge badge-info">Cuti</span>
                                                <span v-else-if="attendance.status == 'pending'" class="badge badge-warning text-white">Pending (@{{ attendance.pending_category }})</span>
                                                <span v-else-if="attendance.status == 'rejected'" class="badge badge-danger">Rejected</span>
                                                <span v-else class="badge badge-light">N/A</span>
                                            </td>
                                            <!-- End:Status -->
                                            <!-- Jam Masuk & Keluar -->
                                            <td class="text-center">
                                                <div class="input-group">
                                                    <input type="time" data-date="{{ date('Y-m-d', strtotime($date)) }}" :data-employee="attendance.id" :data-id="attendance.checkin_id" @change="updateClockIn($event, index)" class="form-control form-control-sm attendance-time" :value="attendance.clock_in" style="width: 120px;">
                                                    <div class="input-group-append">
                                                        <button v-if="attendance.clock_in" class="btn btn-sm btn-dark" @click="resetClockIn($event, index)" :data-id="attendance.checkin_id" type="button" style="line-height: 0.5;">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group">
                                                    <input type="time" data-date="{{ date('Y-m-d', strtotime($date)) }}" :data-employee="attendance.id" :data-id="attendance.checkout_id" @change="updateClockOut($event, index)" class="form-control form-control-sm attendance-time" :value="attendance.clock_out" style="width: 120px;">
                                                    <div class="input-group-append">
                                                        <button v-if="attendance.clock_out" class="btn btn-sm btn-dark" @click="resetClockOut($event, index)" :data-id="attendance.checkout_id" type="button" style="line-height: 0.5;">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- End:Jam Masuk & Keluar  -->
                                            <!-- Lembur -->
                                            <td class="text-center">
                                                <input v-if="attendance.status == 'present' && attendance.checkout_id !== null" type="number" @change="updateOvertime($event, attendance.checkout_id)" class="form-control form-control-sm mx-auto" :value="attendance.overtime_duration" style="width: 70px;">
                                                <i v-else class="fas fa-exclamation-circle" style="color: #3d405c;" data-toggle="tooltip" data-placement="top" title="Isi jam keluar untuk mengisi lembur"></i>
                                            </td>
                                            <!-- End:Lembur -->
                                            <td class="text-center">
                                                <div v-html="getOvertimeSubmissions(attendance.id)"></div>
                                            </td>
                                            <td class="text-center">
                                                <input v-if="attendance.status == 'present' && attendance.checkout_id !== null" type="number" @change="updateOvertimeSubmission($event, attendance.checkout_id)" class="form-control form-control-sm mx-auto" :value="attendance.overtime_submission_duration" style="width: 70px;">
                                                <i v-else class="fas fa-exclamation-circle" style="color: #3d405c;" data-toggle="tooltip" data-placement="top" title="Isi jam keluar untuk mengisi lembur"></i>
                                            </td>
                                            <!-- Attachment -->
                                            <td class="text-center">
                                                <div v-if="attendance.images.length">
                                                    <a v-for="image in attendance.images" :href="'https://arenzha.s3.ap-southeast-1.amazonaws.com/' + image" target="_blank" class="mr-3"><i class="fas fa-file-image"></i></a>
                                                </div>
                                                <span v-else>-</span>
                                            </td>
                                            <!-- End:Attachment -->
                                        </tr>
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

    function jsonEscape(str) {
        return str.replace(/\n/g, "\\\\n").replace(/\r/g, "\\\\r").replace(/\t/g, "\\\\t");
    }

    let app = new Vue({
        el: '#app',
        data: {
            attendances: JSON.parse(String.raw `{!! json_encode($attendances) !!}`),
            pendingAttendances: (JSON.parse(jsonEscape('{!! json_encode($pending_attendances) !!}'))),
            overtimeSubmissions: JSON.parse(String.raw `{!! json_encode($overtime_submissions) !!}`),
        },
        methods: {
            getOvertimeSubmissions(employeeId) {
                let vm = this;

                const getStatusColor = (status) => {
                    switch (status) {
                        case 'pending':
                            return 'warning';
                        case 'approved':
                            return 'success';
                        case 'rejected':
                            return 'danger';
                        default:
                            return 'dark';
                    }
                }

                const overtimeSubmissions = vm.overtimeSubmissions.filter(submission => {
                    return submission.employee_id == employeeId;
                });

                if (overtimeSubmissions.length > 0) {
                    let submissionsList = '<div><ul>';
                    overtimeSubmissions.forEach(submission => {
                        // submissionsList += '<li>';
                        submissionsList += `<li><a href="/overtime-submission/detail/${submission.id}" target="_blank" class="text-${getStatusColor(submission.status)}" data-toggle="tooltip" data-placement="top" title="${submission.work}">${ submission.overtime_start } - ${submission.overtime_end} (${submission.duration} Jam Lembur)</a></li>`;
                        // submissionsList += '</li>';
                    });
                    submissionsList += '</ul></div>';
                    return submissionsList;
                }

                return '<span>-</span>';
            },
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
                            text: "Failed to update overtime",
                        })
                    });
            },
            updateOvertimeSubmission: function(event, id) {
                axios.patch('/attendance/' + id + '/update-overtime-submission', {
                        overtime_submission_duration: event.target.value,
                    })
                    .then(function(response) {
                        console.log('overtime updated');
                    })
                    .catch(function(error) {
                        console.log(error.data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops',
                            text: "Failed to update overtime",
                        })
                    });
            },
            updateClockIn: function(event, index) {
                let vm = this;

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

                        const clock = new Date(data.clock_in);
                        const newClock = vm.pad(clock.getHours(), 2) + ':' + vm.pad(clock.getMinutes(), 2) + ':00';

                        vm.attendances[index].checkin_id = data.id;
                        vm.attendances[index].clock_in = newClock;
                        if (!vm.attendances[index].status) {
                            vm.attendances[index].status = 'present';
                        }
                    })
                    .catch(function(error) {
                        console.log(error.data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops',
                            text: "Failed to update clock in",
                        })
                    });
            },
            updateClockOut: function(event, index) {
                let vm = this;

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

                // if (!id) {
                //     $('.attendance-time').prop('disabled', true);
                // }

                axios.post('/attendance/' + id + '/update-clockout', {
                        date: date,
                        clock_out: event.target.value,
                        employee_id: employeeId,
                    })
                    .then(function(response) {
                        // $('.attendance-time').prop('disabled', false);
                        console.log('clock out updated');
                        // if (!id) {
                        //     window.location.reload();
                        // }
                        // console.log(response.data);
                        const {
                            data
                        } = response.data;
                        event.target.setAttribute('data-employee', data.employee_id);
                        event.target.setAttribute('data-id', data.id);

                        const clock = new Date(data.clock_out);
                        const newClock = vm.pad(clock.getHours(), 2) + ':' + vm.pad(clock.getMinutes(), 2) + ':00';

                        vm.attendances[index].checkout_id = data.id;
                        vm.attendances[index].clock_out = newClock;
                        if (!vm.attendances[index].status) {
                            vm.attendances[index].status = 'present';
                        }
                    })
                    .catch(function(error) {
                        $('.attendance-time').prop('disabled', false);
                        console.log(error.data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops',
                            text: "Failed to update clock out",
                        })
                    });
            },
            resetClockIn: function(event, index) {
                let vm = this;

                // let id = event.target.getAttribute('data-id');
                let id = vm.attendances[index].checkin_id;
                if (!id) {
                    id = null;
                }

                axios.delete('/attendance/' + id + '/reset-clock')
                    .then(function(response) {
                        console.log('clock in updated');
                        // console.log(response.data);
                        vm.attendances[index].checkin_id = null;
                        vm.attendances[index].clock_in = null;
                        if (!vm.attendances[index].clock_out && !vm.attendances[index].checkout_id) {
                            vm.attendances[index].status = null;
                        }
                    })
                    .catch(function(error) {
                        console.log(error.data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops',
                            text: "Failed to reset clock in",
                        })
                    });
            },
            resetClockOut: function(event, index) {
                let vm = this;

                // let id = event.target.getAttribute('data-id');
                let id = vm.attendances[index].checkout_id;
                if (!id) {
                    id = null;
                }

                axios.delete('/attendance/' + id + '/reset-clock')
                    .then(function(response) {
                        console.log('clock out updated');
                        // console.log(response.data);
                        vm.attendances[index].checkout_id = null;
                        vm.attendances[index].clock_out = null;
                        if (!vm.attendances[index].clock_in && !vm.attendances[index].checkin_id) {
                            vm.attendances[index].status = null;
                        }
                    })
                    .catch(function(error) {
                        console.log(error.data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops',
                            text: "Failed to reset clock out",
                        })
                    });
            },
            pad: function(num, size) {
                var s = "000000000" + num;
                return s.substr(s.length - size);
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