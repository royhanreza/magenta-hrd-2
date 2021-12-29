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

  table tr:first-child td {
    font-weight: bold;
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
          @include('employee.menu')
          @include('employee.profile')
          <!-- <div class="section-block">
            <h5 class="section-title">Histori Karir & Remunerasi</h5>

          </div> -->
          <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title">Histori Karir & Remunerasi</h5>
              @if(in_array("addEmployeeCareer", $userLoginPermissions))
              <div class="toolbar ml-auto">
                <a href="{{ url('career/create') . '/' . $employee->id }} " class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
              </div>
              @endif
            </div>
            <!-- NOTE -->
            <!-- <div class="alert alert-warning">Dev Note: Jika ada slip yang sudah dibayarkan, karir tidak bisa dihapus</div> -->
            <!-- NOTE -->
            <div class="card-body">
              <table class="table table-bordered use-datatable">
                <thead class="bg-light text-center">
                  <tr>
                    <th>Tanggal Efektif</th>
                    <th>Bagian</th>
                    <th>Job Title</th>
                    <th>Status Karyawan (Grade)</th>
                    <th>Slip Gaji</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($careers as $career)
                  <tr>
                    <td class="text-center">{{ explode('-', $career->effective_date)[2] }} {{ Helper::prettyMonth((int) explode('-', $career->effective_date)[1] - 1, "id") }} {{ explode('-', $career->effective_date)[0] }}
                      <br>
                      <span>({{ $career->type }})</span>
                    </td>
                    <td>{{ $career->designation->name }}</td>
                    @if($career->jobTitle !== null)
                    <td>{{ $career->jobTitle->name }}</td>
                    @else 
                    <td></td>
                    @endif
                    <td>{{ $career->employee_status }}</td>
                    <td>{{ $career->payslips->pluck('name')->implode(', ') }}</td>
                    <td class="text-center">
                      <div class="btn-group" role="group" aria-label="Action Buttons">
                        @if( $career->is_active == 1 )
                        @if(in_array("editEmployeeCareer", $userLoginPermissions))
                        <a href="{{ url('career/edit') . '/' . $career->id }}" class="btn btn-sm btn-light"><i class="fas fa-fw fa-pencil-alt"></i></a>
                        @endif
                        @if(in_array("deleteEmployeeCareer", $userLoginPermissions))
                        <button type="button" class="btn btn-sm btn-light btn-delete" @click="deleteCareer({{ $career->id }})"><i class="fas fa-fw fa-trash"></i></button>
                        @endif
                        @endif
                        <!-- <div class="dropdown">
                          <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Other">
                            <i class="fas fa-fw fa-cog"></i>
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Detail</a>
                          </div>
                        </div> -->
                      </div>
                    </td>
                  </tr>
                  @endforeach
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
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  let vue = new Vue({
    el: '#app',
    data: {

    },
    methods: {
      deleteCareer: function(id) {
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Data akan dihapus",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batalkan',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.delete('/career/' + id)
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
          allowOutsideClick: () => !Swal.isLoading(),
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
      }
    }
  })
</script>

<script>
  $(function() {
    $('table.use-datatable').DataTable({
      "searching": false,
      "order": [
        [0, "desc"]
      ]
    });

    // $('.btn-delete').on('click', function() {
    //   const id = $(this).attr('data-id');
    //   Swal.fire({
    //     title: 'Are you sure?',
    //     text: "The data will be deleted",
    //     icon: 'warning',
    //     reverseButtons: true,
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Hapus',
    //     cancelButtonText: 'Batalkan',
    //     showLoaderOnConfirm: true,
    //     preConfirm: () => {
    //       return axios.delete('/employee/' + id)
    //         .then(function(response) {
    //           console.log(response.data);
    //         })
    //         .catch(function(error) {
    //           console.log(error.data);
    //           Swal.fire({
    //             icon: 'error',
    //             title: 'Oops',
    //             text: 'Something wrong',
    //           })
    //         });
    //     },
    //     allowOutsideClick: () => !Swal.isLoading(),
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //       Swal.fire({
    //         icon: 'success',
    //         title: 'Success',
    //         text: 'Data has been deleted',
    //       })
    //     }
    //   })
    // })
  })
</script>
@endsection