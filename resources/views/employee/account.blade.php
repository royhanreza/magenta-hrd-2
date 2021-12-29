@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link href="{{ asset('vendor/select2/css/select2.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
@endsection

@section('title', 'Magenta HRD')

@section('pagestyle')
<style>
  .pills-regular .nav.nav-pills .nav-item .nav-link {
    font-size: 13px;
  }

  table,
  .table td {
    border: none;
  }

  .col-form-label,
  .form-group.row label {
    font-size: 13px;
    white-space: normal;
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
      <div class="row">
        <!-- ============================================================== -->
        <!-- basic table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          @include('employee.menu')
          @include('employee.profile')
          <div class="card">
            <!-- Account Information -->

            <div style="padding: 1.25rem;">
              <div class="px-3 pt-3">
                <div class="section-block m-0">
                  <div class="row justify-content-between">
                    <div class="col-md-9">
                      <h3 class="section-title">Informasi Akun</h3>
                    </div>
                    <div class="col-md-3">
                      <!-- <div class="form-group">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" v-model="withoutAccount" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px"><strong>Nonaktifkan Akun</strong></span>
                        </label>
                      </div> -->
                      <select v-model="isActiveAccount" @change="changeAccountStatus" class="form-control form-control-sm">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <form @submit.prevent="submitForm">
              <div class="card-body">
                <div class="px-3 pb-3">
                  <div class="row" v-if="!withoutAccount">
                    <div class="col-md-9">
                      <div class="form-group row">
                        <label for="username" class="col-sm-3 col-form-label">Username<sup class="text-danger">*</sup> (3 Karakter atau lebih)</label>
                        <div class="col-sm-9">
                          <input v-model="username" ref="usernameInput" type="text" class="form-control form-control-sm" minlength="3" v-bind:class="{ 'is-invalid': usernameExist }" v-on:input="onInputUsername" required>
                          <div class="invalid-feedback">
                            Username sudah digunakan
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Password (8 Karakter atau lebih)</label>
                        <div class="col-sm-9">
                          <input v-model="password" type="text" class="form-control form-control-sm" minlength="8">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="confirm-password" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                        <div class="col-sm-9">
                          <input v-model="confirmPassword" ref="confirmPasswordInput" type="text" class="form-control form-control-sm" v-on:input="onInputConfirmPassword" v-bind:class="{ 'is-invalid': confirmPasswordIsInvalid }">
                          <div class="invalid-feedback">
                            Konfirmasi password tidak sesuai
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="pin-code" class="col-sm-3 col-form-label">PIN (6 Digit)</label>
                        <div class="col-sm-9">
                          <input v-model="pinCode" type="number" class="form-control form-control-sm" minlength="6" maxlength="6">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="role" class="col-sm-3 col-form-label">Hak Akses<sup class="text-danger">*</sup></label>
                        <div class="col-sm-9">
                          <select2 v-model="role" :options="roles" class="form-control form-control-sm" required></select2>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" v-model="supervisorAccess"><span class="custom-control-label" style="margin-top: 3px"><strong>Akses sebagai supervisor</strong></span>
                        </label>
                      </div>

                      <div v-if="supervisorAccess" class="form-group row">
                        <label for="role" class="col-sm-3 col-form-label">Akses Divisi<sup class="text-danger">*</sup></label>
                        <div class="col-sm-9">
                          <vue-multiselect v-model="selectedDivisions" placeholder="Tambah divisi" label="text" track-by="id" :options="divisions" :multiple="true" :taggable="true"></vue-multiselect>
                          <small>Supervisor dapat melihat data absen pegawai berdasarkan divisi yang dipilih</small>
                        </div>
                      </div>

                      <hr>

                      <div class="form-group">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" v-model="mobileAccess"><span class="custom-control-label" style="margin-top: 3px"><strong>Izinkan akses ke aplikasi mobile</strong></span>
                        </label>
                      </div>
                      <div class="form-group row" v-if="mobileAccess">
                        <label for="" class="col-sm-3 col-form-label">Tipe akses<sup class="text-danger">*</sup></label>
                        <div class="col-sm-9">
                          <select v-model="mobileAccessType" class="form-control form-control-sm" required>
                            <option value="">Pilih tipe akses</option>
                            <option value="employee">Regular</option>
                            <option value="admin">Admin</option>
                          </select>
                          <span v-if="mobileAccessType == 'admin'" class="d-block mt-1"><em>Pegawai dapat mengkakses aplikasi mobile sebagai admin</em></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END::Account Information -->
              @if(Auth::id() !== $employee->id)
              @if(in_array("editEmployeeAccount", $userLoginPermissions))
              <div class="card-footer d-flex justify-content-end">
                <!-- <button type="submit" class="btn btn-primary px-5 mr-3" v-bind:disabled="loadingInactiveAccount"><span v-if="loadingInactiveAccount" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Nonaktifkan Akun</button> -->
                <button type="submit" class="btn btn-primary px-5" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
              </div>
              @endif
              @else
              <div class="card-footer d-flex justify-content-end">
                <!-- <button type="submit" class="btn btn-primary px-5 mr-3" v-bind:disabled="loadingInactiveAccount"><span v-if="loadingInactiveAccount" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Nonaktifkan Akun</button> -->
                <button type="submit" class="btn btn-primary px-5" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
              </div>
              @endif
            </form>
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
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script type="text/x-template" id="select2-template">
  <select>
        <slot></slot>
    </select>
</script>

<script>
  Vue.component("select2", {
    props: ["options", "value"],
    template: "#select2-template",
    mounted: function() {
      var vm = this;
      $(this.$el)
        // init select2
        .select2({
          data: this.options
        })
        .val(this.value)
        .trigger("change")
        // emit event on change.
        .on("change", function() {
          vm.$emit('selected', this.value);
          vm.$emit("input", this.value);
        });
    },
    watch: {
      // value: function(value) {
      // // update value
      // $(this.$el)
      //     .val(value)
      //     .trigger("change");
      // },
      options: function(options) {
        // update options
        $(this.$el)
          .empty()
          .select2({
            data: options
          });
      }
    },
    destroyed: function() {
      $(this.$el)
        .off()
        .select2("destroy");
    }
  });

  Vue.component('vue-multiselect', window.VueMultiselect.default)


  let app = new Vue({
    el: '#app',
    data: {
      loading: false,
      loadingInactiveAccount: false,
      username: '{{ $employee->username }}',
      usernameExist: false,
      password: '',
      confirmPassword: '',
      pinCode: '{{ $employee->pin }}',
      roles: JSON.parse('{!! $roles !!}'),
      role: '{{ $employee->role_id }}',
      confirmPasswordIsInvalid: false,
      withoutAccount: false,
      supervisorAccess: parseInt('{{ $employee->supervisor_access }}'),
      divisions: JSON.parse('{!! $divisions !!}'),
      // jobTitles: [{
      //     id: 1,
      //     name: 'Reza',
      //   },
      //   {
      //     id: 2,
      //     name: 'Eza',
      //   },
      // ],
      selectedDivisions: JSON.parse(`{!! ($employee->accessible_divisions !== null && $employee->accessible_divisions !== '') ? $employee->accessible_divisions : '[]' !!}`),
      mobileAccess: parseInt('{{ $employee->has_mobile_access }}'),
      mobileAccessType: '{{ $employee->mobile_access_type }}',
      isActiveAccount: parseInt('{{ $employee->is_active_account }}'),

    },
    methods: {
      addTag(newTag) {
        const tag = {
          name: newTag,
          code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
        }
        this.options.push(tag)
        this.value.push(tag)
      },
      onInputConfirmPassword: function() {
        if (this.confirmPasswordIsInvalid) {
          this.confirmPasswordIsInvalid = false;
        }
      },
      onInputEmail: function() {
        if (this.emailExist) {
          this.emailExist = false;
        }
      },
      onInputUsername: function() {
        if (this.usernameExist) {
          this.usernameExist = false;
        }
      },
      submitForm: function() {
        // console.log('submitted');
        if (this.confirmPassword !== this.password) {
          this.confirmPasswordIsInvalid = true;
          this.$refs.confirmPasswordInput.focus();
        } else {
          this.sendData();
        }

      },
      sendData: function() {
        let vm = this;
        vm.loading = true;

        let data = {
          username: this.username,
          password: this.password,
          pin: this.pinCode,
          role_id: this.role,
          has_mobile_access: this.mobileAccess ? 1 : 0,
          mobile_access_type: this.mobileAccessType,
          supervisor_access: this.supervisorAccess,
          accessible_divisions: this.selectedDivisions,
        }
        axios.patch('/employee/{{ $employee->id }}/edit-account', data)
          .then(function(response) {
            vm.loading = false;
            Swal.fire(
              'Success',
              'Your data has been saved',
              'success'
            ).then((result) => {
              if (result.isConfirmed) {
                // window.location.href = vm.url;
              }
            })
            console.log(response);
          })
          .catch(function(error) {
            vm.loading = false;
            // console.log(error.response);
            if (error.response.status == 400) {
              if (error.response.data.error_type == 'exist_credential') {
                error.response.data.errors.forEach(error => {
                  if (error.field == 'username') {
                    vm.usernameExist = true;
                    vm.$refs.usernameInput.focus();
                  }
                  if (error.field == 'email') {
                    vm.emailExist = true;
                    vm.$refs.emailInput.focus();
                  }
                })
              }
            } else {
              Swal.fire(
                'Oops!',
                'Something wrong',
                'error'
              )
            }
          });
      },
      changeAccountStatus: function() {
        let vm = this;
        let data = {
          is_active_account: this.isActiveAccount,
        }
        axios.patch('/employee/{{ $employee->id }}/edit-account-status', data)
          .then(function(response) {
            console.log(response);
          })
          .catch(function(error) {
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