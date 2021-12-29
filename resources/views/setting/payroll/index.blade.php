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
            <h2 class="pageheader-title">Setting </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item">Setting</li>
                  <li class="breadcrumb-item active" aria-current="page">Payroll</li>
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
              <h5 class="card-header-title">List All Payslip</h5>
              @if(in_array("viewPayrollSetting", $userLoginPermissions))
              <div class="toolbar ml-auto">
                <a href="/setting/payroll/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
              </div>
              @endif
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Nama</th>
                      <th>Periode</th>
                      <th>Lama Periode</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($payslips as $payslip)
                    <tr>
                      <td>{{ $payslip->name }}</td>
                      <td class="text-capitalize text-center">{{ $payslip->period_type }}</td>
                      <td class="text-center">
                        @if($payslip->period_type == 'tetap')
                        @if($payslip->long_period == 'monthly')
                        1 Bulanan
                        @elseif($payslip->long_period == 'weekly')
                        1 Mingguan
                        @elseif($payslip->long_period == 'daily')
                        N Harian
                        @endif
                        @else
                        -
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          @if(in_array("editPayrollSetting", $userLoginPermissions))
                          <a href="/setting/payroll/edit/{{ $payslip->id }}" class="btn btn-light btn-sm"><i class="fas fa-fw fa-pencil-alt"></i></a>
                          @endif
                          @if(in_array("deletePayrollSetting", $userLoginPermissions))
                          <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-trash"></i></button>
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
                  <tfoot class="text-center">
                    <tr>
                      <th>Nama</th>
                      <th>Periode</th>
                      <th>Lama Periode</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end basic table  -->
        <!-- ============================================================== -->
        <!-- scrollspy  -->
        <!-- ============================================================== -->
        <!-- <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
          <div class="sidebar-nav-fixed">
            <ul class="list-unstyled">
              <li><a href="#overview" class="active">Overview</a></li>
              <li><a href="#cards">Cards</a></li>
              <li><a href="#image-card">Card Images</a></li>
              <li><a href="#headerfooter">Header &amp; Footer</a></li>
              <li><a href="#cardaction">Card Action<span class="badge badge-secondary ml-1">Fresh</span></a></li>
              <li><a href="#cardfooterlink">Card With Footer Link <span class="badge badge-secondary ml-1">New</span></a></li>
              <li><a href="#c-nav">Card Navigation</a></li>
              <li><a href="#masonary">Card Masonary</a></li>
              <li><a href="#card-list">Card List Group</a></li>
              <li><a href="#card-variance">Card Variance</a></li>
              <li><a href="#top">Back to Top</a></li>
            </ul>
          </div>
        </div> -->
        <!-- scrollspy  -->
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
  $(function() {
    $('table.use-datatable').DataTable();

    $('.btn-delete').on('click', function() {
      const id = $(this).attr('data-id');
      Swal.fire({
        title: 'Are you sure?',
        text: "The data will be deleted",
        icon: 'warning',
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return axios.delete('/company/' + id)
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
    })
  })
</script>
@endsection