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
            <h2 class="pageheader-title">Pengajuan Izin </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pengajuan Izin</li>
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
              <h5 class="card-header-title">Daftar Pengajuan Izin</h5>
              @if(in_array("addPermissionSubmission", $userLoginPermissions))
              <div class="toolbar ml-auto">
                <a href="{{ url('permission/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
              </div>
              @endif
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Nama Pegawai</th>
                      <th>Jenis Izin</th>
                      <th>Tanggal Pengajuan</th>
                      <th>Jumlah Hari</th>
                      <th>Tanggal Izin</th>
                      <th>Status</th>
                      <th>Keterangan</th>
                      <th>Approval</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($permissions as $permission)
                    <tr>
                      <td>
                        <div class="row">
                          <div class="col-md-3">
                            <img src="{{ ($permission->employee->photo !== null) ? Storage::disk('s3')->url($permission->employee->photo) : 'https://cabdindikwil1.com/wp-content/uploads/2020/12/male.png' }}" alt="" width="45" class="rounded">
                          </div>
                          <div class="col-md-9">
                            <span>{{ $permission->employee->first_name }}<br><small>{{ $permission->employee->employee_id }}</small></span>
                          </div>
                        </div>
                      </td>
                      <td>{{ ($permission->permissionCategory == null) ? '' : $permission->permissionCategory->name }}</td>
                      <td class="text-center">{{ $permission->date_of_filing }}</td>
                      <td class="text-center">{{ $permission->number_of_days }} Hari</td>
                      <td>{{ $permission->permission_dates }}</td>
                      <td class="text-center">
                        @if($permission->status == 'approved')
                        <span class="badge badge-success">Approved</span>
                        @elseif($permission->status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                        @else
                        <span class="badge badge-danger">Rejected</span>
                        @endif
                      </td>
                      <td>{{ $permission->description }}</td>
                      <td class="text-center">
                        @if($permission->status == 'pending')
                        @if(in_array("approvalPermisionSubmission", $userLoginPermissions))
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <button type="button" @click="rejectPermissionSubmission({{$permission->id}})" class="btn btn-sm btn-light"><i class="fas fa-fw fa-times"></i></a>
                            <button type="button" @click="approvePermissionSubmission({{$permission->id}})" class="btn btn-sm btn-light btn-delete"><i class="fas fa-fw fa-check"></i></button>
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
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          @if($permission->status == 'pending')
                          @if(in_array("editPermisionSubmission", $userLoginPermissions))
                          <a href="/permission/edit/{{ $permission->id }}" class="btn btn-sm btn-light"><i class="fas fa-fw fa-pencil-alt"></i></a>
                          @endif
                          @if(in_array("deletePermisionSubmission", $userLoginPermissions))
                          <button type="button" @click="deletePermissionSubmission({{$permission->id}})" class="btn btn-sm btn-light btn-delete"><i class="fas fa-fw fa-trash"></i></button>
                          @endif
                          @endif
                          @if($permission->attachment !== null)
                          <a href="{{ Storage::disk('s3')->url($permission->attachment) }}" target="_blank" class="btn btn-sm btn-light"><i class="fas fa-fw fa-file"></i></a>
                          @endif
                        </div>
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
      deletePermissionSubmission: function(id) {
        console.log(id)
        // const id = $(this).attr('data-id');
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Data absensi di tanggal sakit akan dihapus",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Delete',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.delete('/api/permission-submissions/' + id)
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
      approvePermissionSubmission: function(id) {
        console.log(id)
        // const id = $(this).attr('data-id');
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Pengajuan izin akan disetujui",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#2ec551',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Approve',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.post('/api/permission-submissions/action/approve/' + id)
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
      rejectPermissionSubmission: function(id) {
        console.log(id)
        // const id = $(this).attr('data-id');
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Pengajuan izin akan ditolak",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          // confirmButtonColor: '#2ec551',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Reject',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.post('/api/permission-submissions/action/reject/' + id)
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
@endsection