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
            <h2 class="pageheader-title">Pengajuan Sakit </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pengajuan Sakit</li>
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
              <h5 class="card-header-title">Daftar Pengajuan Sakit</h5>
              @if(in_array("addSickSubmission", $userLoginPermissions))
              <div class="toolbar ml-auto">
                <a href="{{ url('sick/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
              </div>
              @endif
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
                      <th>Tanggal Sakit</th>
                      <th>Status</th>
                      <th>Approval</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($sick_submissions as $sick)
                    <tr>
                      <td>
                        <div class="row">
                          <div class="col-md-3">
                            <img src="{{ ($sick->employee->photo !== null) ? Storage::disk('s3')->url($sick->employee->photo) : 'https://cabdindikwil1.com/wp-content/uploads/2020/12/male.png' }}" alt="" width="45" class="rounded">
                          </div>
                          <div class="col-md-9">
                            <span>{{ $sick->employee->first_name }}<br><small>{{ $sick->employee->employee_id }}</small></span>
                          </div>
                        </div>
                      </td>

                      <td class="text-center">{{ date_format(date_create($sick->date_of_filing), "d-m-Y") }}</td>
                      <td class="text-center">{{ count(explode(",", $sick->sick_dates)) }} Hari</td>
                      <td>{{ implode(", ", explode(",", $sick->sick_dates)) }}</td>
                      <td class="text-center">
                        @if($sick->status == 'approved')
                        <span class="badge badge-success">Approved</span>
                        @elseif($sick->status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                        @else
                        <span class="badge badge-danger">Rejected</span>
                        @endif
                      </td>
                      <td class="text-center">
                        @if($sick->status == 'pending')
                        @if(in_array("approvalSickSubmission", $userLoginPermissions))
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <button type="button" @click="rejectSickSubmission({{$sick->id}})" class="btn btn-sm btn-light"><i class="fas fa-fw fa-times"></i></a>
                            <button type="button" @click="approveSickSubmission({{$sick->id}})" class="btn btn-sm btn-light btn-delete"><i class="fas fa-fw fa-check"></i></button>
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
                          @if($sick->status == 'pending')
                          @if(in_array("editSickSubmission", $userLoginPermissions))
                          <a href="/sick/edit/{{ $sick->id }}" class="btn btn-sm btn-light"><i class="fas fa-fw fa-pencil-alt"></i></a>
                          @endif
                          @if(in_array("deleteSickSubmission", $userLoginPermissions))
                          <button type="button" @click="deleteSickSubmission({{$sick->id}})" class="btn btn-sm btn-light btn-delete"><i class="fas fa-fw fa-trash"></i></button>
                          @endif
                          @endif
                          @if($sick->attachment !== null)
                          <a href="{{ Storage::disk('s3')->url($sick->attachment) }}" target="_blank" class="btn btn-sm btn-light"><i class="fas fa-fw fa-file"></i></a>
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
      deleteSickSubmission: function(id) {
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
            return axios.delete('/api/sick-submissions/' + id)
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
            })
          }
        })
      },
      approveSickSubmission: function(id) {
        console.log(id)
        // const id = $(this).attr('data-id');
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Pengajuan sakit akan disetujui",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#2ec551',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Approve',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.post('/api/sick-submissions/action/approve/' + id)
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
            })
          }
        })
      },
      rejectSickSubmission: function(id) {
        console.log(id)
        // const id = $(this).attr('data-id');
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Pengajuan sakit akan ditolak",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          // confirmButtonColor: '#2ec551',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Reject',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.post('/api/sick-submissions/action/reject/' + id)
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