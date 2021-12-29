@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}"> -->
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/buttons.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/select.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/fixedHeader.bootstrap4.css') }}"> -->
<link href="{{ asset('vendor/select2/css/select2.css') }}" rel="stylesheet" />
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
            <h2 class="pageheader-title">Event </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="/event" class="breadcrumb-link">Event</a></li>
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
          <!-- <div class="section-block" id="select">
                        <h3 class="section-title">General Information</h3>
                        <p>Add general information for the event</p>
                    </div> -->

          <div class="card">
            <div class="card-header tab-regular">
              <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active show" id="card-tab-1" data-toggle="tab" href="#card-1" role="tab" aria-controls="card-1" aria-selected="true"><i class="fa fa-fw fa-cogs"></i> Basic Information</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="card-tab-2" data-toggle="tab" href="#card-2" role="tab" aria-controls="card-2" aria-selected="false"><i class="fa fa-fw fa-tasks"></i> Tasks<span v-if="tasks.length > 0"> (@{{ tasks.length }})</span></a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="card-1" role="tabpanel" aria-labelledby="card-tab-1">
                  <form autocomplete="off" @submit.enter.prevent="submitForm">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="quotation-number">Quotation Number</label>
                        <input v-model="quotationNumber" type="text" class="form-control form-control-sm" id="quotation-number" readonly>
                        <!-- <select v-model="quotationId" name="" id="quotation-number" class="form-control form-control-sm">
                                          <option value="1"></option>
                                        </select> -->
                      </div>
                      <div class="form-group col-md-6">
                        <label for="event-date">Event Date</label>
                        <input v-model="quotationEventDate" type="text" class="form-control form-control-sm" id="event-date" value="26-06-2020" readonly>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="title-event">Title Event</label>
                        <input v-model="title" type="text" class="form-control form-control-sm" id="title-event" value="Event" readonly required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="customer">Customer</label>
                        <input v-model="customer" type="text" class="form-control form-control-sm" id="customer" value="AQUA" readonly required>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <input v-model="quotationStatus" type="text" class="form-control form-control-sm" id="status" value="Final" readonly required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="note">Note</label>
                        <input v-model="quotationNote" type="text" class="form-control form-control-sm" id="note" value="Note" readonly required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="pic-event">PIC Event</label>
                        <input v-model="quotationPicEvent" type="text" class="form-control form-control-sm" name="pic-event" id="pic-event" readonly required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="project-number">Project Number</label>
                        <input v-model="number" type="text" class="form-control form-control-sm" id="project-number" readonly required>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="po-number">PO Number<sup class="text-danger">*</sup></label>
                        <input v-model="poNumber" type="text" class="form-control form-control-sm" id="po-number" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="po-date">PO Date<sup class="text-danger">*</sup></label>
                        <input v-model="poDate" type="date" class="form-control form-control-sm" id="po-date" required>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="project-location">Project Location (Province)<sup class="text-danger">*</sup></label>
                        <select2 v-model="province" class="form-control form-control-sm" :options="provinces" @selected="onChangeProvince" required></select2>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="project-location">Project Location (City)<sup class="text-danger">*</sup></label>
                        <select2 v-model="city" class="form-control form-control-sm" :options="cities" v-bind:disabled="!provinceSelected || cityLoading" required></select2>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="start-date">Start Date<sup class="text-danger">*</sup></label>
                        <input v-model="startDate" @input="swapDate" type="date" class="form-control form-control-sm" id="start-date" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="end-date">End Date<sup class="text-danger">*</sup></label>
                        <input v-model="endDate" @input="swapDate" type="date" class="form-control form-control-sm" id="end-date" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="description">Description</label>
                      <textarea v-model="description" class="form-control form-control-sm" name="description" id="description"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                      <button type="submit" class="btn btn-primary px-5" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    </div>
                  </form>
                </div>
                <div class="tab-pane fade" id="card-2" role="tabpanel" aria-labelledby="card-tab-2">
                  <!-- <form @submit.prevent=""> -->
                  <div class="input-group mb-3">
                    <input v-model="taskModel" v-on:keyup.enter="addTask(taskModel)" type="text" class="form-control" required>
                    <div class="input-group-append">
                      <button type="button" class="btn btn-primary btn-sm" @click="addTask(taskModel)">Add</button>
                    </div>
                  </div>

                  <!-- </form> -->
                  <div>
                    <div class="text-center py-3" v-if="tasks.length < 1">
                      <i class="fa fa-fw fa-folder-open fa-5x"></i>
                      <p class="h6">No task yet</p>
                    </div>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(task, index) in tasks" v-bind:key="index">
                        <div>
                          <span>@{{task.task}}</span>
                          <span class="badge badge-secondary badge-pill">In Progress</span>
                        </div>
                        <button @click="deleteTask(index)" type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-trash"></i></button>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
      <!-- ============================================================== -->
      <!-- end basic form  -->
      <!-- ============================================================== -->
      <!-- <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-5 btn-save">Save</button>
            </div> -->

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
<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script> -->
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
      taskModel: '',
      tasks: [],
      quotationId: `{{ $quotation[0]['id'] }}`,
      quotationNumber: `{{ $quotation[0]['quotation_number'] }}`,
      quotationEventDate: `{{ $quotation[0]['event_date'] }}`,
      title: `{{ $quotation[0]['title_event'] }}`,
      customer: `{{ $quotation[0]['customer'] }}`,
      quotationStatus: `{{ $quotation[0]['status'] }}`,
      quotationNote: '',
      quotationPicEvent: `{{ $quotation[0]['pic_event'] }}`,
      number: '{{ $project_number }}',
      provinces: JSON.parse('{!! $provinces !!}'),
      province: '',
      provinceSelected: false,
      cities: [],
      city: '',
      cityLoading: false,
      // budget: '',
      startDate: '',
      endDate: '',
      poNumber: '',
      poDate: '',
      description: '',
      loading: false,
      url: '/event'
    },
    methods: {
      submitForm: function() {
        let vm = this;
        if (this.tasks.length < 1) {
          Swal.fire({
            title: 'No task yet, save anyway?',
            text: "You still be able to add task later in mapping event",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save anyway',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
              // Swal.fire(
              //   'Deleted!',
              //   'Your file has been deleted.',
              //   'success'
              // )
              vm.sendData();
            }
          })
        } else {
          this.sendData();
        }
      },
      sendData: function() {
        let vm = this;
        vm.loading = true;
        axios.post('/event', {
            quotation_id: this.quotationId,
            quotation_number: this.quotationNumber,
            city_id: this.city,
            number: this.number,
            start_date: this.startDate,
            end_date: this.endDate,
            description: this.description,
            tasks: this.tasks,
            po_number: this.poNumber,
            po_date: this.poDate,
            quotation_event_date: this.quotationEventDate,
            title: this.title,
            customer: this.customer,
            quotation_status: this.quotationStatus,
            quotation_note: this.quotationNote,
            quotation_pic_event: this.quotationPicEvent,
          })
          .then(function(response) {
            console.log(response);
            vm.loading = false;
            Swal.fire(
              'Success',
              'Your data has been saved',
              'success'
            ).then((result) => {
              if (result.isConfirmed) {
                window.location.href = vm.url;
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
      },
      addTask: function(task) {
        if (task.trim() !== '') {
          this.tasks.push({
            task,
            status: 'in progress'
          })
          this.taskModel = '';
        }
      },
      deleteTask: function(index) {
        this.tasks.splice(index, 1);
      },
      onChangeProvince: function(id) {
        this.cityLoading = true;
        this.cities = [{
          id: '',
          text: 'Choose City'
        }];
        let vm = this;
        // let id = event.target.value;
        if (this.provinces.length > 0 && this.provinces !== null) {
          axios.get('/api/provinces/' + id + '/cities').then((res) => {
            // console.log(res);
            res.data.data.forEach(city => {
              vm.cities.push({
                id: city.id,
                text: city.name,
              })
            })
            vm.cityLoading = false;
            vm.provinceSelected = true;
          }).catch(err => {
            vm.cityLoading = false;
            console.log(err);
          });

        }
      },
      swapDate: function() {
        if (this.startDate !== '' || this.endDate !== '') {
          let startDate = new Date(this.startDate);
          let endDate = new Date(this.endDate);

          if (startDate > endDate) {
            [this.startDate, this.endDate] = [this.endDate, this.startDate];
          }
        }
      }
    }
  })
</script>
@endsection