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
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form @submit.prevent="saveBpjsValue">
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Upah</label>
                        <div class="col-sm-4">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" v-model="wage" class="form-control text-right" aria-describedby="basic-addon1">
                          </div>
                        </div>

                      </div>

                      <hr>
                      <h3>BPJS Ketenagakerjaan</h3>
                      <h4>JKK</h4>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Perusahaan</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.ketenagakerjaan.jkk.company.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control text-right" aria-describedby="basic-addon1" :value="jkkCompany" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.ketenagakerjaan.jkk.company.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pribadi</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.ketenagakerjaan.jkk.personal.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="jkkPersonal" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.ketenagakerjaan.jkk.personal.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>

                      <hr>
                      <h4>JKM</h4>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Perusahaan</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.ketenagakerjaan.jkm.company.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="jkmCompany" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.ketenagakerjaan.jkm.company.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pribadi</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.ketenagakerjaan.jkm.personal.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="jkmPersonal" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.ketenagakerjaan.jkm.personal.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>

                      <hr>
                      <h4>JHT</h4>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Perusahaan</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.ketenagakerjaan.jht.company.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="jhtCompany" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.ketenagakerjaan.jht.company.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pribadi</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.ketenagakerjaan.jht.personal.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="jhtPersonal" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.ketenagakerjaan.jht.personal.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>

                      <hr>
                      <h4>JP</h4>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Perusahaan</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.ketenagakerjaan.jp.company.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="jpCompany" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.ketenagakerjaan.jp.company.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pribadi</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.ketenagakerjaan.jp.personal.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="jpPersonal" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.ketenagakerjaan.jp.personal.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>

                      <hr>
                      <h3>BPJS Kesehatan</h3>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Perusahaan</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.kesehatan.company.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="kesehatanCompany" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.kesehatan.company.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pribadi</label>
                        <div class="col-sm-2">
                          <div class="input-group mb-3">
                            <input type="text" v-model="bpjs.kesehatan.personal.value" class="form-control" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" :value="kesehatanPersonal" class="form-control text-right" aria-describedby="basic-addon1" readonly>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="bpjs.kesehatan.personal.description" class="form-control" aria-describedby="basic-addon1" placeholder="Keterangan">
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
      wage: '{{ $employee_bpjs->wage }}',
      bpjs: {
        ketenagakerjaan: {
          jkk: {
            company: {
              value: '{{ $employee_bpjs->jkk_company_percentage }}',
              description: '',
            },
            personal: {
              value: '{{ $employee_bpjs->jkk_personal_percentage }}',
              description: '',
            },
          },
          jkm: {
            company: {
              value: '{{ $employee_bpjs->jkm_company_percentage }}',
              description: '',
            },
            personal: {
              value: '{{ $employee_bpjs->jkm_personal_percentage }}',
              description: '',
            },
          },
          jht: {
            company: {
              value: '{{ $employee_bpjs->jht_company_percentage }}',
              description: '',
            },
            personal: {
              value: '{{ $employee_bpjs->jht_personal_percentage }}',
              description: '',
            },
          },
          jp: {
            company: {
              value: '{{ $employee_bpjs->jp_company_percentage }}',
              description: '',
            },
            personal: {
              value: '{{ $employee_bpjs->jp_personal_percentage }}',
              description: '',
            },
          },
        },
        kesehatan: {
          company: {
            value: '{{ $employee_bpjs->kesehatan_company_percentage }}',
            description: '',
          },
          personal: {
            value: '{{ $employee_bpjs->kesehatan_personal_percentage }}',
            description: '',
          },
        }
      },
      dailyMoneyRegular: '{{ $employee->daily_money_regular }}',
      dailyMoneyHoliday: '{{ $employee->daily_money_holiday }}',
      overtimePayRegular: '{{ $employee->overtime_pay_regular }}',
      overtimePayHoliday: '{{ $employee->overtime_pay_holiday }}',
      loading: false,
    },
    methods: {
      saveBpjsValue: function() {
        // console.log('submitted');
        let vm = this;
        vm.loading = true;
        axios.patch('/employee/{{ ($employee_bpjs !== null) ? $employee_bpjs->id : ""  }}/edit-bpjs-value', {
            wage: this.wage,
            jkk_company_percentage: this.bpjs.ketenagakerjaan.jkk.company.value,
            jkk_personal_percentage: this.bpjs.ketenagakerjaan.jkm.personal.value,
            jkm_company_percentage: this.bpjs.ketenagakerjaan.jkm.company.value,
            jkm_personal_percentage: this.bpjs.ketenagakerjaan.jkm.personal.value,
            jht_company_percentage: this.bpjs.ketenagakerjaan.jht.company.value,
            jht_personal_percentage: this.bpjs.ketenagakerjaan.jht.personal.value,
            jp_company_percentage: this.bpjs.ketenagakerjaan.jp.company.value,
            jp_personal_percentage: this.bpjs.ketenagakerjaan.jp.personal.value,
            kesehatan_company_percentage: this.bpjs.kesehatan.company.value,
            kesehatan_personal_percentage: this.bpjs.kesehatan.personal.value,
          })
          .then(function(response) {
            vm.loading = false;
            Swal.fire({
              title: 'Success',
              text: 'Setting telah disimpan',
              icon: 'success',
              allowOutsideClick: false,
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
      },
      calculate: function(wage, item) {
        return wage * item;
      }
    },
    computed: {
      jkkCompany: function() {
        const jkkCompany = this.wage * (this.bpjs.ketenagakerjaan.jkk.company.value / 100);
        return jkkCompany;
      },
      jkkPersonal: function() {
        const jkkPersonal = this.wage * (this.bpjs.ketenagakerjaan.jkk.personal.value / 100);
        return jkkPersonal;
      },
      jkmCompany: function() {
        const jkmCompany = this.wage * (this.bpjs.ketenagakerjaan.jkm.company.value / 100);
        return jkmCompany;
      },
      jkmPersonal: function() {
        const jkmPersonal = this.wage * (this.bpjs.ketenagakerjaan.jkm.personal.value / 100);
        return jkmPersonal;
      },
      jhtCompany: function() {
        const jhtCompany = (this.wage * this.bpjs.ketenagakerjaan.jht.company.value / 100);
        return jhtCompany;
      },
      jhtPersonal: function() {
        const jhtPersonal = this.wage * (this.bpjs.ketenagakerjaan.jht.personal.value / 100);
        return jhtPersonal;
      },
      jpCompany: function() {
        const jpCompany = this.wage * (this.bpjs.ketenagakerjaan.jp.company.value / 100);
        return jpCompany;
      },
      jpPersonal: function() {
        const jpPersonal = this.wage * (this.bpjs.ketenagakerjaan.jp.personal.value / 100);
        return jpPersonal;
      },
      kesehatanCompany: function() {
        const kesehatanCompany = this.wage * (this.bpjs.kesehatan.company.value / 100);
        return kesehatanCompany;
      },
      kesehatanPersonal: function() {
        const kesehatanPersonal = this.wage * (this.bpjs.kesehatan.personal.value / 100);
        return kesehatanPersonal;
      },
    }

  })
</script>
@endsection