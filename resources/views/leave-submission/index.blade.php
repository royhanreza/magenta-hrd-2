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
            <h2 class="pageheader-title">Pengajuan Cuti </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pengajuan Cuti</li>
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
              <h5 class="card-header-title">Daftar Pengajuan Cuti</h5>
              <div class="toolbar ml-auto">
                <a href="{{ url('leave/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Nama Pegawai</th>
                      <!-- <th>Jenis Izin</th> -->
                      <th>Tanggal Pengajuan</th>
                      <th>Jumlah Hari</th>
                      <th>Tanggal Cuti</th>
                      <th>Status</th>
                      <th>Approval</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($leave_submissions as $leave)
                    <tr>
                      <td>
                        <div class="row">
                          <div class="col-md-3">
                            <img src="{{ ($leave->employee->photo !== null) ? Storage::disk('s3')->url($leave->employee->photo) : 'https://cabdindikwil1.com/wp-content/uploads/2020/12/male.png' }}" alt="" width="45" class="rounded">
                          </div>
                          <div class="col-md-9">
                            <span>{{ $leave->employee->first_name }}<br><small>{{ $leave->employee->employee_id }}</small></span>
                          </div>
                        </div>
                      </td>

                      <td class="text-center">{{ date_format(date_create($leave->date_of_filing), "d-m-Y") }}</td>
                      <td class="text-center">{{ count(explode(",", $leave->leave_dates)) }} Hari</td>
                      <td>{{ implode(", ", explode(",", $leave->leave_dates)) }}</td>
                      <td class="text-center">
                        @if($leave->status == 'approved')
                        <span class="badge badge-success">Approved</span>
                        @elseif($leave->status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                        @else
                        <span class="badge badge-danger">Rejected</span>
                        @endif
                      </td>
                      <td class="text-center">
                        @if($leave->status == 'pending')
                        @if(in_array("approvalLeaveSubmission", $userLoginPermissions))
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <button type="button" @click="rejectLeaveSubmission({{$leave->id}})" class="btn btn-sm btn-light"><i class="fas fa-fw fa-times"></i></a>
                            <button type="button" @click="approveLeaveSubmission({{$leave->id}})" class="btn btn-sm btn-light btn-delete"><i class="fas fa-fw fa-check"></i></button>
                            <!-- <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Other">
                              <i class="fas fa-fw fa-cog"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="#">Print</a>
                            </div>
                          </div> -->
                        </div>
                        @endif
                        @endif
                      </td>
                      <td class="text-center">
                        @if($leave->status == 'pending')
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          @if(in_array("editLeaveSubmission", $userLoginPermissions))
                          <a href="/leave/submission/edit/{{ $leave->id }}" class="btn btn-sm btn-light"><i class="fas fa-fw fa-pencil-alt"></i></a>
                          @endif
                          @if(in_array("deleteLeaveSubmission", $userLoginPermissions))
                          <button type="button" @click="deleteLeaveSubmission({{$leave->id}})" class="btn btn-sm btn-light btn-delete"><i class="fas fa-fw fa-trash"></i></button>
                          @endif
                        </div>
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

    },
    methods: {
      deleteLeaveSubmission: function(id) {
        console.log(id)
        // const id = $(this).attr('data-id');
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Data absensi di tanggal cuti akan dihapus",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Delete',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.delete('/api/leave-submissions/' + id)
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
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.reload();
              }
            })
          }
        })
      },
      approveLeaveSubmission: function(id) {
        console.log(id)
        // const id = $(this).attr('data-id');
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Pengajuan cuti akan disetujui",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#2ec551',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Approve',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.post('/api/leave-submissions/action/approve/' + id)
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
              text: 'Data has been approved',
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.reload();
              }
            })
          }
        })
      },
      rejectLeaveSubmission: function(id) {
        console.log(id)
        // const id = $(this).attr('data-id');
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Pengajuan cuti akan ditolak",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          // confirmButtonColor: '#2ec551',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Reject',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.post('/api/leave-submissions/action/reject/' + id)
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
              text: 'Data has been rejected',
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.reload();
              }
            })
          }
        })
      },
    }
  })
</script>

<script>
  $(function() {
    $('table.use-datatable').DataTable({
      "order": [
        [1, "desc"]
      ],
    });
  })
</script>
@endsection