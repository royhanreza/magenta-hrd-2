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

  .col-form-label,
  .form-group.row label {
    /* font-size: 13px; */
    white-space: normal;
  }


  .input-group-text {
    line-height: 0.5;
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

          <div class="simple-card">
            @include('employee.settings.menu')
            <div class="tab-content" id="myTabContent5">
              <div class="tab-pane fade active show" id="home-simple" role="tabpanel" aria-labelledby="home-tab-simple">
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <form @submit.prevent="saveEmployeeSalary">
                      <div class="form-group row">
                        <label for="dailySalary" class="col-sm-4 col-form-label">Uang Harian (Hari Kerja)</label>
                        <div class="col-sm-8">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" v-model="dailyMoneyRegular" class="form-control text-right" aria-describedby="basic-addon1">
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="dailySalaryHoliday" class="col-sm-4 col-form-label">Uang Harian (Hari Libur)</label>
                        <div class="col-sm-8">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" v-model="dailyMoneyHoliday" class="form-control text-right" aria-describedby="basic-addon1">
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="dailySalaryHoliday" class="col-sm-4 col-form-label">Upah Lembur / Jam (Hari Kerja)</label>
                        <div class="col-sm-8">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" v-model="overtimePayRegular" class="form-control text-right" aria-describedby="basic-addon1">
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="dailySalaryHoliday" class="col-sm-4 col-form-label">Upah Lembur / Jam (Hari Libur)</label>
                        <div class="col-sm-8">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" v-model="overtimePayHoliday" class="form-control text-right" aria-describedby="basic-addon1">
                          </div>
                        </div>
                      </div>
                      <div class="d-flex justify-content-end">
                        <!-- <button class="btn btn-outline-primary btn-sm mr-2"><i class="fas fa-pencil-alt"></i> Edit</button>
                      <button class="btn btn-outline-danger btn-sm mr-2"><i class="fas fa-times"></i> Batal</button> -->
                        <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
                      </div>

                    </form>
                  </div>
                </div>

              </div>
              <!-- <div class="tab-pane fade" id="profile-simple" role="tabpanel" aria-labelledby="profile-tab-simple">
                <p>Nullam et tellus ac ligula condimentum sodales. Aenean tincidunt viverra suscipit. Maecenas id molestie est, a commodo nisi. Quisque fringilla turpis nec elit eleifend vestibulum. Aliquam sed purus in odio ullamcorper congue consectetur in neque. Aenean sem ex, tempor et auctor sed, congue id neque. </p>
              </div>
              <div class="tab-pane fade" id="contact-simple" role="tabpanel" aria-labelledby="contact-tab-simple">
                <p>Vivamus pellentesque vestibulum lectus vitae auctor. Maecenas eu sodales arcu. Fusce lobortis, libero ac cursus feugiat, nibh ex ultricies tortor, id dictum massa nisl ac nisi. Fusce a eros pellentesque, ultricies urna nec, consectetur dolor. Nam dapibus scelerisque risus, a commodo mi tempus eu.</p>
              </div> -->
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
      dailyMoneyRegular: '{{ $employee->daily_money_regular }}',
      dailyMoneyHoliday: '{{ $employee->daily_money_holiday }}',
      overtimePayRegular: '{{ $employee->overtime_pay_regular }}',
      overtimePayHoliday: '{{ $employee->overtime_pay_holiday }}',
      loading: false,
    },
    methods: {
      saveEmployeeSalary: function() {
        // console.log('submitted');
        let vm = this;
        vm.loading = true;
        axios.patch('/employee/{{ $employee->id }}/edit-salary-setting', {
            daily_money_regular: this.dailyMoneyRegular,
            daily_money_holiday: this.dailyMoneyHoliday,
            overtime_pay_regular: this.overtimePayRegular,
            overtime_pay_holiday: this.overtimePayHoliday,
          })
          .then(function(response) {
            vm.loading = false;
            Swal.fire({
              title: 'Success',
              text: 'Your data has been saved',
              icon: 'success',
              allowOutsideClick: false,
            }).then((result) => {
              if (result.isConfirmed) {
                // window.location.;
              }
            })
            // console.log(response);
          })
          .catch(function(error) {
            vm.loading = false;
            console.log(error);
            Swal.fire(
              'Oops!',
              'Something wrong',
              'error'
            )

          });
      }
    }
  })
</script>
@endsection