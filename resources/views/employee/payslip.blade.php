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
      <div class="row">
        <!-- ============================================================== -->
        <!-- basic table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          @include('employee.menu')
          @include('employee.profile')
          <!-- <div class="d-flex justify-content-end">
            <button class="btn btn-primary btn-sm mb-3" type="button" data-toggle="collapse" data-target="#collapsePayslip" aria-expanded="false" aria-controls="collapseExample">
              <i class="fas fa-plus"></i> Add New
            </button>
          </div> -->

          <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title">List All Payslips</h5>
              <div class="toolbar ml-auto">

              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered use-datatable">
                <thead class="bg-light text-center">
                  <tr>
                    <th>Tipe</th>
                    <th>Periode</th>
                    <!--<th>THP</th>-->
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($final_payslips as $payslip)
                  <tr>
                    <td class="text-center">
                      @if($payslip->type == 'custom_period')
                      Gaji Harian
                      @elseif($payslip->type == 'fix_period')
                      Gaji Bulanan
                      @endif
                    </td>
                    <td class="text-center">{{ date_format(date_create($payslip->start_date_period), "d/m/Y") }} - {{ date_format(date_create($payslip->end_date_period), "d/m/Y") }}</td>
                    <!--<td class="text-right">Rp {{ number_format($payslip->take_home_pay, 0, "" , ".") }}</td>-->
                    <td class="text-center">
                      <div class="btn-group" role="group" aria-label="Action Buttons">
                         @if($payslip->type == 'custom_period')
                      <a href="/daily-payroll/print/{{ $payslip->id }}" target="_blank" class="btn btn-sm btn-light"><i class="fas fa-fw fa-print"></i></a>
                      @elseif($payslip->type == 'fix_period')
                      <a href="/payroll/print/{{ $payslip->id }}" target="_blank" class="btn btn-sm btn-light"><i class="fas fa-fw fa-print"></i></a>
                      @endif
                        
                        <!-- <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Other">
                              <i class="fas fa-fw fa-cog"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="#">Print</a>
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
  let app = new Vue({
    el: '#app',
    data: {
      group: '',

    },
    methods: {
      sumIncomes: function(incomes) {
        const sum = incomes.map(income => isNaN(parseInt(income.value)) ? 0 : parseInt(income.value)).reduce((acc, cur) => {
          return acc + cur
        }, 0)

        if (isNaN(sum)) {
          return 0
        }

        return parseInt(sum);
      },
      sumDeductions: function(deductions) {
        const sum = deductions.map(deduction => isNaN(parseInt(deduction.value)) ? 0 : parseInt(deduction.value)).reduce((acc, cur) => {
          return acc + cur
        }, 0)

        if (isNaN(sum)) {
          return 0
        }

        return parseInt(sum);
      }
    },
    computed: {
      selectedPayslip: function() {
        const vm = this;

        if (this.group == '') {
          return null
        }

        return this.payslips.filter(payslip => payslip.id == vm.group)[0];
      }
    }
  })
</script>
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
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batalkan',
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return axios.delete('/employee/' + id)
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
          })
        }
      })
    })
  })
</script>
@endsection