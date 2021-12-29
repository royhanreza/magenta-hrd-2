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
            <h2 class="pageheader-title">Employee </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Employee</li>
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
      <!-- <div class="card">
        <div class="card-body">
          <nav class="nav flex-column flex-sm-row">
            <a class="flex-sm-fill text-sm-center nav-link" href="/staff-dashboard">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-chart-bar fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Staff Dashboard</span><br>
                  <small class="text-muted">Staff Dashboard</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link active" href="/employee">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-user fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Employees</span><br>
                  <small class="text-muted">Manage Employees</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/role">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-flag fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Set Role</span><br>
                  <small class="text-muted">Manage Employee's Role</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/office-shift">
              <div class="d-flex justify-content-start align-items-center">
                <div>
                  <i class="far fa-fw fa-clock fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Office Shifts</span><br>
                  <small class="text-muted">Manage Office Shifts</small>
                </div>
              </div>
            </a>
          </nav>
        </div>
      </div> -->
      <!-- ============================================================== -->
      <!-- end page nav  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- summary  -->
      <!-- ============================================================== -->
      <div class="row">
        <!-- ============================================================== -->
        <!-- sales  -->
        <!-- ============================================================== -->
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
          <div class="card border-3 border-top border-top-primary">
            <div class="card-body">
              <h5 class="text-muted">Total Pegawai <span class="icon-circle-small icon-box-xs text-primary bg-primary-light"><i class="fa fa-fw fa-users"></i></span></h5>
              <div class="metric-value">
                <h1 class="mb-1 text-right">{{ number_format($employees->total(), 0, "", ".") }}</h1>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end sales  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- new customer  -->
        <!-- ============================================================== -->
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
          <div class="card border-3 border-top border-top-primary">
            <div class="card-body">
              <h5 class="text-muted">Pegawai Aktif <span class="icon-circle-small icon-box-xs text-success bg-success-light"><i class="fa fa-fw fa-user"></i></span></h5>
              <div class="metric-value">
                <h1 class="mb-1 text-right">{{ number_format(count($active_employees), 0, "", ".") }}</h1>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end new customer  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- total orders  -->
        <!-- ============================================================== -->
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
          <div class="card border-3 border-top border-top-primary">
            <div class="card-body">
              <h5 class="text-muted">Pegawai Nonaktif <span class="icon-circle-small icon-box-xs text-danger bg-danger-light"><i class="fa fa-fw fa-user-times"></i></span></h5>
              <div class="metric-value">
                <h1 class="mb-1 text-right">{{ number_format(count($inactive_employees), 0, "", ".") }}</h1>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end total orders  -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- end summary  -->
      <!-- ============================================================== -->
      <div class="row">
        <!-- ============================================================== -->
        <!-- basic table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="row mb-3">
            <div class="col-md-6 col-xs-12">
              <a href="{{ url('employee/create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus fa-xs"></i> Tambah Pegawai
              </a>
              <a href="{{ url('employee') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-users fa-xs"></i> Tampilkan Semua Pegawai
              </a>
            </div>
            <div class="col-md-6 col-xs-12">
              <form action="/employee/search" method="GET" autocomplete="off">
                <div class="input-group mb-3">
                  <div class="input-group mb-3">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari Nama atau ID Pegawai" aria-label="Cari Nama atau ID Pegawai" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-primary btn-sm" type="button"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- SKELETON -->
          <!-- <div class="row">
            @for($i = 0; $i < 2; $i++) <div class="col-md-6 col-xs-12">
              <div class="card">
                <div class="card-header bg-light">
                  <div class="d-flex justify-content-end">
                    <div style="width: 120px; height: 20px; background-color: rgba(0, 0, 0, .1);"></div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="d-flex justify-space-between">
                    <div style="width: 28%;">
                      <div style="width: 100px; height: 120px; background-color: rgba(0, 0, 0, .1);">
                      </div>
                    </div>
                    <div style="width: 70%;">
                      <div class="d-flex justify-content-between border-bottom pb-2">
                        <div>
                          <div style="width: 120px; height: 20px; background-color: rgba(0, 0, 0, .1); "></div>
                          <div class="mt-2" style="width: 70px; height: 15px; background-color: rgba(0, 0, 0, .1); "></div>
                        </div>
                        <div>
                          <div style="width: 70px; height: 20px; background-color: rgba(0, 0, 0, .1); "></div>
                        </div>
                      </div>
                      <div class="mt-2">
                        <div class="mt-1" style="width: 200px; height: 15px; background-color: rgba(0, 0, 0, .1); "></div>
                        <div class="mt-1" style="width: 200px; height: 15px; background-color: rgba(0, 0, 0, .1); "></div>
                        <div class="mt-1" style="width: 200px; height: 15px; background-color: rgba(0, 0, 0, .1); "></div>
                        <div class="mt-1" style="width: 200px; height: 15px; background-color: rgba(0, 0, 0, .1); "></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          @endfor
        </div> -->
          <!-- END:KELETON -->
          <p class="text-muted text-center">Hasil pencarian untuk <strong>{{ request()->query('keyword') }}</strong></p>
          <div class="row">
            @foreach($employees as $employee) <div class="col-md-6 col-xs-12">
              <div class="card">
                <div class="card-header bg-light">
                  <div>
                    <ul class="d-flex flex-row-reverse align-items-center m-0 list-unstyled text-right">
                      <li class="ml-3">
                        <div class="dropdown dropleft">
                          <a href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                          </a>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="/employee/detail/{{ $employee->id }}">Detail</a>
                            <a class="dropdown-item" href="/employee/account/{{ $employee->id }}">Akun</a>
                            <a class="dropdown-item" href="/employee/career/{{ $employee->id }}">Karir & Remunerasi</a>
                            <a class="dropdown-item" href="/employee/attendance/{{ $employee->id }}">Absensi</a>
                            <a class="dropdown-item" href="/employee/office-shift/{{ $employee->id }}">Shift Kerja</a>
                            <a class="dropdown-item" href="/employee/setting/{{ $employee->id }}">Pengaturan</a>
                          </div>
                        </div>

                      </li>
                      <!-- <li class="ml-3"><i class="fas fa-download"></i></li> -->
                      <li class="ml-3"><a href="/employee/edit/{{ $employee->id }}"><i class="fas fa-pencil-alt"></i></a></li>
                      <li class="ml-3"><a href="#" @click.prevent="deleteEmployee({{ $employee->id }})"><i class="fas fa-trash-alt"></i></a></li>
                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <!-- <div style="background-color: #e5e5f7; opacity: 0.1; background-image: radial-gradient(#000000 1px, transparent 1px), radial-gradient(#000000 1px, #e5e5f7 1px); background-size: 40px 40px; background-position: 0 0,20px 20px; position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div> -->
                  <div class="d-flex justify-space-between">
                    <div style="width: 115px;">
                      <div style="width: 100px; height: 120px;" class="p-1 border">
                        <a href="/employee/detail/{{ $employee->id }}">
                          <!--<img src="data:image/png;base64,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" alt="" style="height: 100%; width: 100%; object-fit: cover">-->
                           <img src="{{ ($employee->photo !== null) ? Storage::disk('s3')->url($employee->photo) : '' }}" alt="" class="lozad" style="height: 100%; width: 100%; object-fit: cover">
                        </a>
                      </div>
                    </div>
                    <div style="width: calc(100% - 120px);">
                      <div class="d-flex justify-content-between border-bottom">
                        <div>
                          <a href="/employee/detail/{{ $employee->id }}">
                            <h4 class="card-title">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                          </a>
                          <h6 class="card-subtitle mb-2 text-muted">{{ $employee->employee_id }}</h6>
                          <h6 class="card-subtitle mb-2 text-muted">USER ID: {{ $employee->id }}</h6>
                        </div>
                        <div>
                          @if($employee->is_active == 1)
                          <span class="badge-dot badge-success"></span>Aktif
                          @else
                          <span class="badge-dot badge-danger"></span>Nonaktif
                          @endif
                        </div>
                      </div>
                      <div class="mt-2">
                        @if($employee->start_work_date !== null)
                        <span>Mulai bekerja sejak {{ date_format(date_create($employee->start_work_date), "d/m/Y") }}</span>
                        <span class="start-work-date d-block mb-2">{{ $employee->start_work_date }}</span>
                        @endif
                        @if(count($employee->careers) > 0)
                        @if($employee->careers[0]->jobTitle !== null)
                        <span><i class="fas fa-star fa-xs text-primary mr-2"></i> {{ $employee->careers[0]->jobTitle->name }}</span><br>

                        @endif
                        @else
                        <i class="fas fa-star fa-xs text-primary mr-2"></i> <a href="/career/create/{{ $employee->id }}"><span style="text-decoration: underline; text-decoration-style: dotted;">Atur Karir <i class="far fa-edit fa-xs"></i></span></a><br>
                        @endif
                        <span><i class="fas fa-phone fa-xs text-primary mr-2"></i> {{ ($employee->contact_number) ? $employee->contact_number : '-' }}</span><br>
                        <span><i class="fas fa-envelope fa-xs text-primary mr-2"></i> {{ $employee->email }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <span>Menampilkan <strong>{{ $employees->count() }}</strong> dari <strong>{{ $employees->total() }}</strong> Pegawai</span>
          <div class="d-flex justify-content-center">
            {{ $employees->appends(['keyword' => request()->query('keyword')])->links() }}
          </div>
          <!-- <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">«</span><span class="sr-only">Previous</span> </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span>
                <span class="sr-only">Next</span></a>
            </li>
          </ul>
        </nav> -->

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
<script src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
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
      deleteEmployee: function(id) {
        Swal.fire({
          title: 'Are you sure?',
          text: "The data will be deleted",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal',
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
    moment.locale('id');

    // $('table.use-datatable').DataTable();
    
    const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();

    $('.start-work-date').each((index, el) => {
      if (el.textContent !== null && el.textContent !== '') {
        var x = new moment()
        var y = new moment(el.textContent)
        var duration = moment.duration(x.diff(y));
        el.textContent = '(' + (duration.years() !== 0 ? duration.years() + ' Tahun ' : '') + (duration.months() !== 0 ? duration.months() + ' Bulan ' : '') + (duration.days() !== 0 ? duration.days() + ' Hari' : '0 Hari') + ')';
      }
      // console.log(el.textContent)
    })

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