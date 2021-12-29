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
            <h2 class="pageheader-title">Laporan BPJS</h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Laporan</li>
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
          <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title"><i class="fas fa-th"></i> Laporan PPh 21</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="">Tahun</label>
                    <select class="form-control" v-model="year" @change="onSelectYear">
                      @for($i = 2020; $i <= date("Y"); $i++) <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                  </div>
                </div>
              </div>
              <div v-if="loading" class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
              </div>
              <table class="table">
                <thead>
                  <tr class="text-center">
                    <th>Bulan / Tahun</th>
                    <th>Laporan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($reports as $report)
                  <tr class="text-center">
                    <td>{{ $months[(int) $report['month'] - 1] }} {{ $year }}</td>
                    @if($report['file'] == null)
                    @if(((int) $report['month'] < (int) date('m')) && (int) $report['year']>= (int) date('Y'))
                      <td><button @click="createReport({{ $report['month'] }}, {{ $report['year'] }})" class="btn btn-light btn-sm" :disabled="loading">Generate</button></td>
                      @else
                      <td><em>Belum Tersedia</em></td>
                      @endif
                      @else
                      <td><a href="{{ Storage::disk('s3')->url($report['file'] ) }}" target="_blank" class="text-primary"><i class="fas fa-file-alt"></i> Laporan PPh 21 {{ $months[(int) $report['month'] - 1] }} {{ $year }}</a></td>
                      @endif
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
      year: '{{ $year }}',
      loading: false,
    },
    methods: {
      onSelectYear: function() {
        window.location.href = '/report/pph/?year=' + this.year;
      },
      createReport: function(month, year) {
        let vm = this;
        vm.loading = true;
        let data = {
          month,
          year
        };

        axios.post('/report/pph', data)
          .then(function(response) {
            vm.loading = false;
            Swal.fire(
              'Success',
              'Your data has been saved',
              'success'
            ).then((result) => {
              if (result.isConfirmed) {
                window.location.reload();
              }
            })
            console.log(response);
          })
          .catch(function(error) {
            vm.loading = false;
            // console.log(error.response);
            Swal.fire(
              'Oops!',
              'Something wrong',
              'error'
            )
          });
      },
    }
  })
</script>

@endsection