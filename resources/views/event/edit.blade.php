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
                    <!-- <div class="form-row">
                                      <div class="form-group col-md-3">
                                        
                                      </div>
                                      <div class="form-group col-md-3">
                                        
                                      </div>
                                      <div class="form-group col-md-3">
                                        
                                      </div>
                                      <div class="form-group col-md-3">
                                        <label for="quotation-number">Status</label>
                                        <select name="" class="form-control form-control-sm">
                                          <option value="pending">Pending</option>
                                          <option value="approve">Approve</option>
                                          <option value="reject">Reject</option>
                                        </select>
                                      </div>
                                    </div> -->
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <!-- <label for="quotation-number">Quotation Number</label>
                        <select v-model="quotation" name="" id="quotation-number" class="form-control form-control-sm">
                          <option value="1">QT-31232 | Event Nutricia</option>
                        </select> -->
                        <label for="quotation-number">Quotation Number</label>
                        <input v-model="quotationNumber" type="text" class="form-control form-control-sm" id="quotation-number" readonly>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="event-date">Event Date</label>
                        <input v-model="quotationEventDate" type="text" class="form-control form-control-sm" id="event-date" readonly>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="title-event">Title Event</label>
                        <input v-model="title" type="text" class="form-control form-control-sm" id="title-event" readonly required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="customer">Customer</label>
                        <input v-model="customer" type="text" class="form-control form-control-sm" id="customer" readonly required>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <input v-model="quotationStatus" type="text" class="form-control form-control-sm" id="status" readonly required>
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
                      <button type="button" class="btn btn-primary btn-sm" @click="addTask(taskModel)" v-bind:disabled="loadingTask"><span v-if="loadingTask" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Add</button>
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
                        <div>
                          <button type="button" @click="onModalOpen(task.id, task.task, index)" class="btn btn-sm btn-light" data-toggle="modal" data-target="#editTaskModal"><i class="fas fa-fw fa-pencil-alt"></i></button>
                          <button @click="deleteTask(index, task.id)" type="button" class="btn btn-sm btn-light btn-delete-task"><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Edit Task</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label>Task</label>
                        <input v-model="taskModelEdit" type="text" class="form-control form-control-sm" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" @click="editTask" v-bind:disabled="loadingEditTask"><span v-if="loadingEditTask" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <button @click="showToast" type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button> -->
              <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; top: 70px;">
                <div ref="toast" id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                  <div class="toast-header">
                    <strong class="mr-5"><i class="fa fa-fw" v-bind:class="toastType == 'success' ? 'fa-check text-success' : 'fa-times text-danger'"></i> @{{ toastTitle }}</strong>
                    <button type="button" class="ml-5 mb-1 close" data-dismiss="toast" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="toast-body pr-5">
                    @{{toastText}}
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
      taskModelEdit: '',
      taskId: '',
      taskIndex: '',
      tasks: JSON.parse('{!! $event->tasks !!}'),
      quotationNumber: '{{ $event->quotation_number }}',
      quotationEventDate: `{{ $event->quotation_event_date }}`,
      title: `{{ $event->title }}`,
      customer: `{{ $event->customer }}`,
      quotationStatus: `{{ $event->quotation_status }}`,
      quotationNote: `{{ $event->quotation_note }}`,
      quotationPicEvent: `{{ $event->quotation_pic_event }}`,
      provinces: JSON.parse('{!! $provinces !!}'),
      province: '{{ $event->city->province->id }}',
      provinceSelected: true,
      cities: JSON.parse('{!! $cities !!}'),
      city: '{{ $event->city_id }}',
      cityLoading: false,
      // budget: '{{ $event->budget }}',
      poNumber: '{{ $event->po_number }}',
      poDate: '{{ $event->po_date }}',
      number: '{{ $event->number }}',
      startDate: '{{ $event->start_date }}',
      endDate: '{{ $event->end_date }}',
      description: '{{ $event->description }}',
      loading: false,
      loadingTask: false,
      loadingEditTask: false,
      toast: false,
      toastType: 'success',
      toastTitle: 'Success',
      toastText: 'Task has been saved',
      eventId: '{{ $event->id }}',
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
        axios.patch('/event/{{ $event->id }}', {
            // quotation_id: this.quotation,
            city_id: this.city,
            budget: this.budget,
            start_date: this.startDate,
            end_date: this.endDate,
            description: this.description,
            tasks: this.tasks,
            po_number: this.poNumber,
            po_date: this.poDate,
          })
          .then(function(response) {
            // console.log(response);
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
          // this.tasks.push({ task, status: 'in progress' })
          // this.taskModel = '';
          let vm = this;
          vm.loadingTask = true;
          axios.post('/event-task', {
              task: this.taskModel,
              status: 'in progress',
              event_id: this.eventId,
            })
            .then(function(response) {
              // console.log(response)
              vm.loadingTask = false;
              vm.showToast('Success', 'Task has been saved', 'success');
              vm.tasks.push(response.data.data);
              vm.taskModel = '';
            })
            .catch(function(error) {
              vm.showToast('Error', 'Internal server error', 'error');
              vm.loadingTask = false;
              console.log(error);
            });
        }
      },
      deleteTask: function(index, id) {
        let vm = this;
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
            return axios.delete('/event-task/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Internal server error', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Task has been deleted', 'success');
            vm.tasks.splice(index, 1);
          }
        })
      },
      editTask: function() {
        let vm = this;
        vm.loadingEditTask = true;
        axios.patch('/event-task/' + this.taskId, {
            task: this.taskModelEdit,
          })
          .then(function(response) {
            vm.loadingEditTask = false;
            $('#editTaskModal').modal('hide')
            vm.tasks[vm.taskIndex].task = vm.taskModelEdit;
            vm.taskId = '';
            vm.taskIndex = '';
            vm.showToast('Success', 'Changes has been saved', 'success');
          })
          .catch(function(error) {
            vm.loadingEditTask = false;
            console.log(error);
            vm.showToast('Error', 'Internal server error', 'error');
          });
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
      },
      showToast: function(title, text, type = 'success') {
        this.toastTitle = title;
        this.toastText = text;
        this.toastType = type;
        $('#liveToast').toast('show');
      },
      hideToast: function() {
        // this.toast = false;
      },
      onModalOpen: function(id, task, index) {
        this.taskId = id;
        this.taskIndex = index;
        this.taskModelEdit = task;
      }
    }
  })
</script>
@endsection