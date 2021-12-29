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
      <alert-status :icon="alertStatus.icon" :text="alertStatus.text" :type="alertStatus.type"></alert-status>
      <!-- ============================================================== -->
      <!-- basic form  -->
      <!-- ============================================================== -->

      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="card">
            <confirmation-button v-if="status == 'pending'" :approve="approveEvent" :reject="rejectEvent"></confirmation-button>
            <div class="card-header tab-regular">
              <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" id="card-tab-1" href="/mapping-event/{{ $event->id }}/view" role="tab" aria-controls="card-1" aria-selected="false"><i class="fa fa-fw fa-cogs"></i> Basic Information</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active show" id="card-tab-2" data-toggle="tab" role="tab" aria-controls="card-2" aria-selected="true"><i class="fa fa-fw fa-tasks"></i> Tasks<span v-if="tasks.length > 0"> (@{{ tasks.length }})</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="card-tab-3" href="/mapping-event/{{ $event->id }}/budget"><i class="fas fa-fw fa-dollar-sign"></i> Budget</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="card-tab-4" href="/mapping-event/{{ $event->id }}/member"><i class="fa fa-fw fa-users"></i> Members</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade active show" id="card-2" role="tabpanel" aria-labelledby="card-tab-2">
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
  Vue.component('alert-status', {
    props: ['icon', 'text', 'type'],
    template: `
      <div class="alert" :class="type" role="alert">
        <i class="fas fa-fw" :class="icon"></i> @{{ text }}
      </div>
    `,
  })

  Vue.component('confirmation-button', {
    props: ['approve', 'reject'],
    template: `
      <div class="d-flex justify-content-end p-3 border-bottom">
        <button @click="reject" type="button" class="btn btn-danger btn-sm mr-2"><i class="fa fa-fw fa-times"></i> Reject</button>
        <button @click="approve" type="button" class="btn btn-success btn-sm "><i class="fa fa-fw fa-check"></i> Approve</button>
      </div>
    `,
  })

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
      loadingTask: false,
      loadingEditTask: false,
      toast: false,
      toastType: 'success',
      toastTitle: 'Success',
      toastText: 'Task has been saved',
      eventId: '{{ $event->id }}',
      status: '{{ $event->status }}',
    },
    computed: {
      alertStatus: function() {
        switch (this.status) {
          case 'pending':
            return {
              icon: 'fas fa-clock', text: 'This event waiting for confirmation', type: 'alert-warning'
            };
            break;
          case 'approved':
            return {
              icon: 'fas fa-check', text: 'This event has been approved', type: 'alert-success'
            };
            break;
          case 'rejected':
            return {
              icon: 'fas fa-times', text: 'This event has been rejected', type: 'alert-danger'
            };
            break;
          case 'closed':
            return {
              icon: 'fas fa-lock', text: 'This event has been closed', type: 'alert-secondary'
            };
            break;
          default:
            return {
              icon: 'fas fa-clock', text: 'This event waiting for confirmation', type: 'alert-warning'
            };
            break;
        }
      }
    },
    methods: {
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
      },
      approveEvent: function() {
        let url = '/event/{{ $event->id }}/approve';
        let status = 'approved';
        this.changeStatusEvent(url, status);
      },
      rejectEvent: function() {
        let url = '/event/{{ $event->id }}/reject';
        let status = 'rejected';
        this.changeStatusEvent(url, status);
      },
      changeStatusEvent: function(url, status) {
        const vm = this;
        Swal.fire({
          title: 'Are you sure?',
          text: "This event status will be changed to " + status,
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: (status == 'approved') ? 'Approve' : 'Reject',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.patch(url)
              .then(function(response) {
                // console.log(response.data);
                vm.status = status;
              })
              .catch(function(error) {
                // console.log(error.response.data);
                if (error.response.data.code == 400) {
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: error.response.data.message,
                  })
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: 'Something wrong',
                  })
                }
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: 'Event has been ' + status,
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = '/mapping-event';
              }
            })
          }
        })
      },
    }
  })
</script>
@endsection