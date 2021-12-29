@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/buttons.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/select.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/fixedHeader.bootstrap4.css') }}">
@endsection

@section('title', 'Magenta HRD')

@section('content')
@php
$userLoginPermissions = [];
if (request()->session()->has('userLoginPermissions')) {
$userLoginPermissions = request()->session()->get('userLoginPermissions');
}
@endphp
 <!--============================================================== -->
 <!--wrapper  -->
 <!--============================================================== -->
<div class="dashboard-wrapper">
  <div class="dashboard-ecommerce">
    <div class="container-fluid dashboard-content ">
       <!--============================================================== -->
       <!--pageheader  -->
       <!--============================================================== -->
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="page-header">
            <h2 class="pageheader-title">Penggajian</h2>
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
       <!--============================================================== -->
       <!--end pageheader  -->
       <!--============================================================== -->
       <!--============================================================== -->
       <!--page nav  -->
       <!--============================================================== -->

       <!--============================================================== -->
       <!--end page nav  -->
       <!--============================================================== -->
      <div class="row">
         <!--============================================================== -->
         <!--basic table  -->
         <!--============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title">List Slip Gaji Harian</h5>
              @if(in_array("addDailySalary", $userLoginPermissions))
              <div class="toolbar ml-auto">
                <div class="d-flex">
                    <div class="dropdown mr-3">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Download Laporan
                      </button>
                      <?php 
                      $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                      ?>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @foreach($months as $key => $month)
                        <a href="/daily-payroll/report/sheet?month={{ ($key + 1) > 9 ? ($key + 1) : '0' . ($key + 1)  }}&year={{ date('Y') }}" class="dropdown-item" target="_blank"><i class="fas fa-download"></i> Laporan Bulan {{ $month }}</a>
                        @endforeach
                        <!--<a class="dropdown-item" href="#">Action</a>-->
                        <!--<a class="dropdown-item" href="#">Another action</a>-->
                        <!--<a class="dropdown-item" href="#">Something else here</a>-->
                      </div>
                    </div>
                    <a href="{{ url('daily-payroll/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Generate Slip</a>
                </div>
              </div>
              @endif
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Pegawai</th>
                      <th>Job Title</th>
                      <th>Periode</th>
                      <th>Jumlah Hari</th>
                      <th>Tanggal Pembuatan</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($payslips as $payslip)
                    <tr>
                      <td><span>{{ $payslip->employee->first_name }} {{ $payslip->employee->last_name }} ({{ $payslip->employee->employee_id }})</span></td>
                      @if(count($payslip->employee->careers) > 0)
                      @if($payslip->employee->careers[0]->jobTitle !== null)
                      <td>{{$payslip->employee->careers[0]->jobTitle->name}}</td>
                      @else
                    <td></td>
                      @endif
                      @else
                      <td></td>
                      @endif
                      <td class="text-center">{{ date_format(date_create($payslip->start_date_period), "d/m/Y") }} - {{ date_format(date_create($payslip->end_date_period), "d/m/Y") }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::parse($payslip->start_date_period)->diffInDays($payslip->end_date_period) + 1 }} Hari</td>
                      <td class="text-center">{{ date_format(date_create($payslip->created_at), "d/m/Y") }}</td>
                      <td class="text-center" style="width: 15%;">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <a href="/daily-payroll/print/{{ $payslip->id }}" target="_blank" class="btn btn-sm btn-light"><i class="fas fa-print"></i></a>
                          @if(in_array("deleteDailySalary", $userLoginPermissions))
                          <button type="button" @click="deletePayslip({{ $payslip->id }})" class="btn btn-sm btn-light"><i class="fas fa-fw fa-trash"></i></button>
                          @endif
                           <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Other">
                              <i class="fas fa-fw fa-cog"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="#">Slip Gaji Massal</a>
                            </div>
                          </div> 
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
         <!--============================================================== -->
         <!--end basic table  -->
         <!--============================================================== -->
      </div>
    </div>
  </div>
   <!--============================================================== -->
   <!--footer -->
   <!--============================================================== -->

   <!--============================================================== -->
   <!--end footer -->
   <!--============================================================== -->
</div>
 <!--============================================================== -->
 <!--end wrapper  -->
 <!--============================================================== -->
@endsection

@section('script')
 slimscroll js 
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
 additional script 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
 main js 
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  let app = new Vue({
    el: '#app',
    data: {

    },
    methods: {
      deletePayslip: function(id) {
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Data akan dihapus",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.delete('/daily-payroll/' + id)
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
              text: 'Data berhasil dihapus',
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
      "order": [
        [4, "asc"]
      ]
      // "searching": false,
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