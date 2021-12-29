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
            <h2 class="pageheader-title">Penggajian (Gaji & THR) </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item">Penggajian</li>
                  <li class="breadcrumb-item active" aria-current="page">Gaji & THR</li>
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
              <h5 class="card-header-title">Grup Gaji Tetap</h5>
              <!-- <div class="toolbar ml-auto">
                <a href="{{ url('company/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Generate Slip</a>
              </div> -->
            </div>
            <div class="card-body">
              <!-- <form>
                <div class="form-row">
                  <div class="col-3">
                    <select class="form-control" name="" id="">
                      <option value="">Semua</option>
                      @foreach($payslips as $payslip)
                      <option value="{{ $payslip->id }}">{{ $payslip->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-2">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <button class="btn btn-outline-light btn-sm" type="button"><i class="fas fa-angle-left"></i></button>
                      </div>
                      <select class="form-control">
                        <option value="">Januari</option>
                        <option value="">Februari</option>
                        <option value="">Maret</option>
                        <option value="">April</option>
                        <option value="">Mei</option>
                        <option value="">Juni</option>
                        <option value="">Juli</option>
                        <option value="">Agustus</option>
                        <option value="">September</option>
                        <option value="">Oktober</option>
                        <option value="">November</option>
                        <option value="">Desember</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="input-group mb-3">
                      <select class="form-control" name="" id="">
                        <option value="">2021</option>
                      </select>
                      <div class="input-group-append">
                        <button class="btn btn-outline-light btn-sm" type="button"><i class="fas fa-angle-right"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </form> -->
              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Grup Gaji</th>
                      <th>Lama Periode</th>
                      <th>Awal Periode</th>
                      <!-- <th>Jumlah Pegawai</th> -->
                      <!-- <th>Status Kelengkapan</th> -->
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($payslips as $payslip)
                    <tr>
                      <td>{{ $payslip->name }}</td>
                      <td class="text-center">
                        @if($payslip->long_period == 'monthly')
                        1 Bulanan
                        @elseif($payslip->long_period == 'weekly')
                        1 Mingguan
                        @else
                        {{$payslip->daily_number_of_days}} Harian
                        @endif
                      </td>
                      <td class="text-center">
                        @if($payslip->long_period == 'monthly')
                        Tanggal {{ $payslip->monthly_first_day }}
                        @elseif($payslip->long_period == 'weekly')
                        Hari {{ $payslip->weekly_first_day }}
                        @endif
                      </td>
                      <!-- <td class="text-center">5 Orang</td> -->
                      <!-- <td class="text-center"><span class="badge badge-warning">Belum Lengkap</span></td> -->
                      <td class="text-center" style="width: 15%;">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <a href="/payroll/{{ $payslip->id }}?month={{ date('m') }}&year={{ date('Y') }}" class="btn btn-sm btn-light"><i class="fas fa-th"></i> Detail</a>
                          <!-- <button type="button" class="btn btn-sm btn-light btn-delete" data-id=""><i class="fas fa-fw fa-trash"></i></button> -->
                          <!-- <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Other">
                              <i class="fas fa-fw fa-cog"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="#">Slip Gaji Massal</a>
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
  $(function() {
    $('table.use-datatable').DataTable({
      "searching": false,
    });

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