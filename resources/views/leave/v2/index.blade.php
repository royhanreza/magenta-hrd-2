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
                        <h2 class="pageheader-title">Data Cuti </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Cuti</li>
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
                            <h5 class="card-header-title">Data Cuti Pegawai</h5>
                            <!-- <div class="toolbar ml-auto">
                <a href="{{ url('leave/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
              </div> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped use-datatable">
                                    <thead class="bg-light text-center">
                                        <?php $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] ?>
                                        <?php $alliasMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] ?>
                                        <tr>
                                            <th rowspan="2">Nama Pegawai</th>
                                            <!-- <th>Jenis Izin</th> -->
                                            <th rowspan="2">Jatah Cuti</th>
                                            <th colspan="{{ count($months) }}">Cuti Diambil</th>
                                            <th rowspan="2">Total Cuti Diambil</th>
                                            <!--<th>Jatah Carry Forward</th>-->
                                            <!--<th>Carry Forward Diambil</th>-->
                                            <!--<th>Total Diambil</th>-->
                                            <th rowspan="2">Sisa</th>
                                            <th rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            @foreach($alliasMonths as $month)
                                            <td>{{ $month }}</td>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $employee)
                                        <tr>
                                            <td>
                                                <span>{{ $employee->first_name }}<br><small>{{ $employee->employee_id }}</small></span>
                                            </td>
                                            <td class="text-center">{{ $employee->activeLeave->total_leave }}</td>
                                            @foreach($months as $index => $month)
                                            <td class="text-center">
                                                <?php
                                                if (isset($employee->leave_monthly)) {
                                                    if (isset($employee->leave_monthly[$index + 1])) {
                                                        echo $employee->leave_monthly[$index + 1];
                                                    } else {
                                                        echo 0;
                                                    }
                                                } else {
                                                    echo '';
                                                }
                                                ?>
                                            </td>
                                            @endforeach
                                            <td class="text-center">{{ $employee->activeLeave->taken_leave }}</td>
                                            <td class="text-center">{{ $employee->activeLeave->total_leave - $employee->activeLeave->taken_leave }}</td>
                                            <td class="text-center">
                                                @if(in_array("editLeaveData", $userLoginPermissions))
                                                <button class="btn btn-light btn-sm" @click="openEditLeaveModal({{ $employee->activeLeave->id }}, {{ $employee->activeLeave->total_leave }},{{ $employee->activeLeave->taken_leave }}, {{ $employee->activeLeave->total_carry_forward }})"><i class="fas fa-pencil-alt"></i></button>
                                                @endif
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
<div class="modal fade" id="editLeaveModal" tabindex="-1" role="dialog" aria-labelledby="editLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLeaveModalLabel">Edit Jatah Cuti Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="editLeave(editLeaveId)">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Jatah Cuti</label>
                            <input type="number" v-model="leaveModel.edit.totalLeave" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Cuti Diambil</label>
                            <input type="number" v-model="leaveModel.edit.takenLeave" class="form-control form-control-sm">
                        </div>
                        <!--<div class="form-group col-md-6">-->
                        <!--  <label>Jatah Carry Forward</label>-->
                        <!--  <input type="number" v-model="leaveModel.edit.totalCarryForward" class="form-control form-control-sm">-->
                        <!--</div>-->
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingEditLeave"><span v-if="loadingEditLeave" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
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
            loadingEditLeave: false,
            editLeaveId: null,
            leaveModel: {
                edit: {
                    totalLeave: 0,
                    takenLeave: 0,
                    totalCarryForward: 0
                }
            }
        },
        methods: {
            openEditLeaveModal: function(id, totalLeave, takenLeave, totalCF) {
                this.editLeaveId = id;
                this.leaveModel.edit.totalLeave = totalLeave;
                this.leaveModel.edit.takenLeave = takenLeave;
                this.leaveModel.edit.totalCarryForward = totalCF;
                $('#editLeaveModal').modal('show');
            },
            editLeave: function(id) {
                let vm = this;
                vm.loadingEditLeave = true;
                axios.patch('/leave/' + id, {
                        total_leave: this.leaveModel.edit.totalLeave,
                        taken_leave: this.leaveModel.edit.takenLeave,
                        total_carry_forward: this.leaveModel.edit.totalCarryForward,
                    })
                    .then(function(response) {
                        vm.loadingEditLeave = false;
                        vm.editLeaveId = null;
                        Swal.fire({
                            title: 'Success',
                            text: 'Cuti berhasil diubah',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    })
                    .catch(function(error) {
                        vm.loadingEditLeave = false;
                        Swal.fire({
                            title: 'Error',
                            text: 'Internal Error',
                            icon: 'error',
                        })
                    });
            },
            resetEditLeave: function() {
                this.leaveModel.edit.totalLeave = 0;
                this.leaveModel.edit.totalCarryForward = 0;
            },
        }
    })
</script>

<script>
    $(function() {
        $('table.use-datatable').DataTable({
            // "order": [
            //   [0, "desc"]
            // ],
        });
    })
</script>
@endsection