@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link href="{{ asset('vendor/select2/css/select2.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
<!-- <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" /> -->
@endsection

@section('title', 'Magenta HRD')

@section('pagestyle')
<style>
  .col-form-label,
  .form-group.row label {
    font-size: 13px;
    white-space: normal;
  }

  .input-date-bs:read-only {
    background-color: #fff;
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
            <h2 class="pageheader-title">Izin </h2>
            <!-- <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p> -->
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="/employee" class="breadcrumb-link">Izin</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Add</li>
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
      <!-- ============================================================== -->
      <!-- basic form  -->
      <!-- ============================================================== -->
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

          <form autocomplete="off" @submit.prevent="submitForm" enctype="multipart/form-data">
            <div class="card">
              <div class="card-header bg-light mb-0">
                <h5 class="card-title mb-0">Pengajuan Izin</h5>
              </div>
              <!-- Basic Information -->
              <div class="card-body">
                <div class="row justify-content-between">
                  <div class="col-md-9">
                    <div class="form-group row justify-content-between align-items-center">
                      <label for="first-name" class="col-sm-3 col-form-label">Tanggal Pengajuan<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <!-- <input v-model="firstName" type="text" class="form-control form-control-sm" required> -->
                        <span>{{ date("d-m-Y") }}</span>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="first-name" class="col-sm-3 col-form-label">Nama Pegawai<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <!-- <input v-model="firstName" type="text" class="form-control form-control-sm" required> -->
                        <select class="form-control form-control-sm" id="employee-name" v-model="employee" required>
                          <option value="">-- Pilih Pegawai --</option>
                          @foreach($employees as $employee)
                          <option value="{{ $employee->id }}">{{ $employee->employee_id }} - {{ $employee->first_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="first-name" class="col-sm-3 col-form-label">Jenis Izin<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <!-- <input v-model="firstName" type="text" class="form-control form-control-sm" required> -->
                        <select class="form-control form-control-sm" id="category" v-model="category" required>
                          <option value="">-- Pilih Jenis Izin --</option>
                          @foreach($categories as $category)
                          <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                        </select>
                        <em v-if="category !== ''">Maksimal: @{{ maxDays }} Hari</em>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="first-name" class="col-sm-3 col-form-label">Tanggal Izin<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <!-- <input v-model="firstName" type="text" class="form-control form-control-sm" required> -->
                        <div class="input-group">
                          <input type="text" id="permission-dates" class="form-control form-control-sm input-date-bs effective-date" aria-describedby="basic-addon2" readonly required>
                          <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar" style="line-height: 0;"></i></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Jumlah Hari Izin</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="selectedDatesNumber" class="form-control form-control-sm" readonly required>
                        <span v-if="category !== '' && selectedDatesNumber > maxDays"><i class="fas fa-exclamation-triangle fa-xs text-warning"></i> Jumlah hari telah melewati batas maksimal</span>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="first-name" class="col-sm-3 col-form-label">Surat Keterangan Izin</label>
                      <div class="col-sm-9">
                        <!-- <input v-model="firstName" type="text" class="form-control form-control-sm" required> -->
                        <div class="input-group">
                          <input type="file" v-on:change="handleFile($event)" class="form-control form-control-sm" aria-describedby="basic-addon2">
                          <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-file" style="line-height: 0;"></i></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Keterangan</label>
                      <div class="col-sm-9">
                        <textarea v-model="description" class="form-control form-control-sm"></textarea>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-2"></div>
                </div>
              </div>
              <!-- END::Basic Information -->

              <div class="card-footer d-flex justify-content-end align-items-center">
                <div class="form-group mr-4" style="margin-bottom: 0;">
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-model="approve"><span class="custom-control-label" style="margin-top: 3px"><strong>Setujui</strong></span>
                  </label>
                </div>
                <button type="submit" class="btn btn-primary px-5" v-bind:disabled="loading || selectedDatesNumber > maxDays"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- end basic form  -->
      <!-- ============================================================== -->
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- footer -->
  <!-- ============================================================== -->
  @include('layouts.footer')
  <!-- ============================================================== -->
  <!-- end footer -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- end wrapper  -->
<!-- ============================================================== -->
@endsection

@section('script')
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<!-- slimscroll js -->
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
<!-- additional script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
<script src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
<!-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> -->
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

  let app = new Vue({
    el: '#app',
    data: {
      dateOfFiling: '{{ date("Y-m-d") }}',
      employee: '',
      category: '',
      categories: JSON.parse('{!! $categories !!}'),
      permissionDates: [],
      numberOfDays: '',
      attachment: '',
      description: '',
      status: 'pending',
      approve: false,
      loading: false,
    },
    methods: {
      handleFile(event) {
        this.attachment = event.target.files[0];
      },
      submitForm: function() {
        this.sendData();
      },
      sendData: function() {
        let vm = this;

        if (vm.permissionDates.length < 1 || vm.permissionDates == null) {
          return Swal.fire(
            'Warning',
            'Pilih Tanggal Sakit',
            'warning'
          )
        }

        vm.loading = true;
        let data = {
          date_of_filing: vm.dateOfFiling,
          employee_id: vm.employee,
          permission_category_id: vm.category,
          permission_dates: vm.permissionDatesString,
          number_of_days: vm.selectedDatesNumber,
          attachment: vm.attachment,
          description: vm.description,
          status: vm.status,
        }

        if (vm.approve) {
          data.status = 'approved';
        }

        let formData = new FormData();
        for (var key in data) {
          formData.append(key, data[key]);
        }

        axios.post('/permission', formData)
          .then(function(response) {
            vm.loading = false;
            Swal.fire(
              'Success',
              'Your data has been saved',
              'success'
            ).then((result) => {
              if (result.isConfirmed) {
                window.location.href = '/permission';
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

    },
    computed: {
      maxDays: function() {
        const vm = this;
        if (this.category !== '' && this.category !== null) {
          return this.categories.filter(cat => cat.id == this.category)[0]?.max_day;
        }

        return 0;
      },
      selectedDatesNumber: function() {
        return this.permissionDates.length;
      },
      permissionDatesString: function() {
        return this.permissionDates.map(date => moment(date).format("YYYY-MM-DD")).join();
      }
    }
  })
</script>
<script>
  $(function() {
    function formatState(state) {
      if (!state.id) {
        return state.text;
      }

      var $state = $(
        '<span><img src="data:image/png;base64,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" width="20" /> ' + state.text + '</span>'
      );
      return $state;
    };

    $("#employee-name").select2({
      templateResult: formatState,
      templateSelection: formatState
    }).on('select2:select', function(e) {
      var data = e.params.data;
      // console.log(data);
      app.$data.employee = data.id
    });

    $("#category").select2().on('select2:select', function(e) {
      var data = e.params.data;
      // console.log(data);
      app.$data.category = data.id
    });

    $('#permission-dates').datepicker({
      format: 'yyyy-mm-dd',
      multidate: true,
    }).on('changeDate', function(e) {
      // `e` here contains the extra attributes
      app.$data.permissionDates = e.dates;
    });
  })
</script>
@endsection