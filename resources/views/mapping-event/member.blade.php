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
                  <a class="nav-link" id="card-tab-2" href="/mapping-event/{{ $event->id }}/task" role="tab" aria-controls="card-2" aria-selected="false"><i class="fa fa-fw fa-tasks"></i> Tasks<span v-if="tasks.length > 0"> (@{{ tasks.length }})</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="card-tab-3" href="/mapping-event/{{ $event->id }}/budget"><i class="fas fa-fw fa-dollar-sign"></i> Budget</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active show" id="card-tab-4" href="/mapping-event/{{ $event->id }}/member"><i class="fa fa-fw fa-users"></i> Members</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="card-4" role="tabpanel" aria-labelledby="card-tab-4">
                  <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-fw fa-plus"></i> Add Member</button>
                  </div>
                  <div class="collapse" id="collapseExample">
                    <form autocomplete="off" @submit.prevent="addMember">
                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label>Type<sup class="text-danger">*</sup></label>
                          <select v-model="type" @change="onChangeMember" class="form-control form-control-sm">
                            <option value="employees">In House Employee</option>
                            <option value="freelancers">Freelancer</option>
                          </select>
                        </div>
                        <div class="form-group col-md-3">
                          <label>Employee / Freelancer<sup class="text-danger">*</sup></label>
                          <select2 v-model="employee" class="form-control form-control-sm" :options="employees" v-bind:disabled="employeeLoading" required></select2>
                        </div>
                        <div class="form-group col-md-3">
                          <label>Daily Money<sup class="text-danger">*</sup></label>
                          <input v-model="dailyMoney" type="number" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label>Role<sup class="text-danger">*</sup></label>
                          <select v-model="role" class="form-control form-control-sm">
                            <option v-for="role in filteredRoles" v-bind:value="role.id">@{{ role.text }}</option>
                          </select>
                        </div>
                      </div>
                      <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Add</button>
                      </div>
                    </form>
                    <hr>

                  </div>
                  <div class="text-center py-3" v-if="members.length < 1">
                    <i class="fa fa-fw fa-folder-open fa-5x"></i>
                    <p class="h6">No Member yet</p>
                  </div>
                  <ul class="list-group list-group-flush" v-if="members.length > 0">
                    <li class="list-group-item list-group-item-info">
                      <div class="row">
                        <div class="col-md-2"><strong>Member</strong></div>
                        <div class="col-md-2"><strong>ID</strong></div>
                        <div class="col-md-2"><strong>Name</strong></div>
                        <div class="col-md-3"><strong>Daily Money</strong></div>
                        <div class="col-md-2"><strong>Role</strong></div>
                        <div class="col-md-1"></div>
                      </div>
                    </li>
                    <li class="list-group-item" v-for="(member, index) in members">
                      <div class="row" v-if="member.freelancer">
                        <div class="col-md-2">Freelancer</div>
                        <div class="col-md-2">@{{ member.freelancer.freelancer_id }}</div>
                        <div class="col-md-2">@{{ member.freelancer.first_name }} @{{ member.freelancer.last_name }}</div>
                        <div class="col-md-3">@{{ member.daily_money }}</div>
                        <div class="col-md-2 text-capitalize">@{{ member.role }}</div>
                        <div class="col-md-1 text-right">
                          <!-- <button type="button" @click="onModalOpen(budget.id, index)" class="btn btn-sm btn-light" data-toggle="modal" data-target="#editModal"><i class="fas fa-fw fa-pencil-alt"></i></button> -->
                          <button type="button" @click="deleteMember(index, member.id)" class="btn btn-sm btn-light"><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                      </div>
                      <div class="row" v-if="member.employee">
                        <div class="col-md-2">Employee</div>
                        <div class="col-md-2">@{{ member.employee.employee_id }}</div>
                        <div class="col-md-2">@{{ member.employee.first_name }} @{{ member.employee.last_name }}</div>
                        <div class="col-md-3">@{{ member.daily_money }}</div>
                        <div class="col-md-2 text-capitalize">@{{ member.role }}</div>
                        <div class="col-md-1 text-right">
                          <!-- <button type="button" @click="onModalOpen(budget.id, index)" class="btn btn-sm btn-light" data-toggle="modal" data-target="#editModal"><i class="fas fa-fw fa-pencil-alt"></i></button> -->
                          <button type="button" @click="deleteMember(index, member.id)" class="btn btn-sm btn-light"><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                      </div>
                    </li>
                  </ul>
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
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
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
          // vm.$emit('selected', this.value);
          vm.$emit("input", this.value);
        });
    },
    watch: {
      value: function(value) {
        // update value
        $(this.$el)
          .val(value)
          .trigger("change");
      },
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
      budgetId: '',
      budgetIndex: '',
      tasks: JSON.parse('{!! $event->tasks !!}'),
      members: JSON.parse('{!! $event_member->members !!}'),
      type: 'employees',
      employees: JSON.parse('{!! $employees !!}'),
      employee: '',
      employeeLoading: false,
      dailyMoney: '',
      roles: [{
        id: 'regular',
        text: 'Regular Member'
      }, {
        id: 'pic',
        text: 'PIC Event'
      }],
      role: 'regular',
      loading: false,
      loadingEdit: false,
      toast: false,
      toastType: 'success',
      toastTitle: 'Success',
      toastText: 'Task has been saved',
      eventId: '{{ $event->id }}',
      status: '{{ $event->status }}',
    },
    computed: {
      filteredRoles: function() {
        let picMemberIndex = _.findIndex(this.members, {
          role: 'pic'
        });
        if (picMemberIndex < 0) {
          return [{
            id: 'regular',
            text: 'Regular Member'
          }, {
            id: 'pic',
            text: 'PIC Event'
          }];
        } else {
          return [{
            id: 'regular',
            text: 'Regular Member'
          }];
        }

        return [];
      },
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
      addMember: function() {
        let vm = this;
        vm.loading = true;
        axios.post('/event-member', {
            daily_money: this.dailyMoney,
            event_id: this.eventId,
            employee: this.employee,
            type: this.type,
            role: this.role,
            // amount: this.amount,
            // transfer_date: this.transferDate,
            // transfer_to: this.transferTo,
            // effective_date: this.effectiveDate,
            // expire_date: this.expireDate,
            // event_id: this.eventId,
          })
          .then(function(response) {
            console.log(response)
            vm.loading = false;
            vm.showToast('Success', 'Member has been added', 'success');
            vm.members.push(response.data.data);
            vm.dailyMoney = '';
            vm.employee = '';
            vm.role = 'regular';
          })
          .catch(function(error) {
            vm.showToast('Error', 'Failed to add member', 'error');
            vm.loading = false;
            console.log(error);
          });
      },
      deleteMember: function(index, id) {
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
            return axios.delete('/event-member/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Failed to remove member', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Member has been removed', 'success');
            vm.members.splice(index, 1);
          }
        })
      },
      editBudget: function() {
        let vm = this;
        vm.loadingEdit = true;
        axios.patch('/event-member/' + this.budgetId, {

          })
          .then(function(response) {
            vm.loadingEdit = false;
            $('#editModal').modal('hide');
            vm.budgets[vm.budgetIndex] = response.data.data;
            vm.budgetId = '';
            vm.budgetIndex = '';
            vm.showToast('Success', 'Changes has been saved', 'success');
          })
          .catch(function(error) {
            vm.loadingEdit = false;
            console.log(error);
            vm.showToast('Error', 'Failed to change data', 'error');
          });
      },
      resetEditForm: function() {
        this.amountEdit = '';
        this.transferDateEdit = '';
        this.transferToEdit = '';
        this.effectiveDateEdit = '';
        this.expireDateEdit = '';
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
      onModalOpen: function(id, index) {
        this.budgetId = id;
        this.budgetIndex = index;
        let budget = this.budgets.filter(budget => budget.id == id);
        // console.log(budget);
        if (budget.length > 0) {
          this.amountEdit = budget[0].amount;
          this.transferDateEdit = budget[0].transfer_date;
          this.transferToEdit = budget[0].transfer_to;
          this.imageEdit = budget[0].image;
          this.effectiveDateEdit = budget[0].effective_date;
          this.expireDateEdit = budget[0].expire_date;
        }
      },
      swapDate: function() {
        if (this.effectiveDate !== '' || this.expireDate !== '') {
          let effectiveDate = new Date(this.effectiveDate);
          let expireDate = new Date(this.expireDate);

          if (effectiveDate > expireDate) {
            [this.effectiveDate, this.expireDate] = [this.expireDate, this.effectiveDate];
          }
        }
      },

      onChangeMember: function() {
        let vm = this;
        let type = event.target.value;
        this.employeeLoading = true;
        this.employees = [{
          id: '',
          text: 'Choose Person'
        }];

        axios.get('/api/' + type).then((res) => {
          // console.log(res);
          // Check added member
          let filteredEmployees = res.data.data.filter(employee => {
            if (type == 'employees') {
              return _.findIndex(vm.members, {
                employee_id: employee.id
              }) < 0
            } else {
              return _.findIndex(vm.members, {
                freelancer_id: employee.id
              }) < 0
            }
          })

          // Assign data from api to employees/freelancers select
          filteredEmployees.forEach(employee => {
            vm.employees.push({
              id: employee.id,
              text: `(${((type == 'employees') ? employee.employee_id : employee.freelancer_id )}) ${employee.first_name} ${employee.last_name}`,
            })
          })

          this.employeeLoading = false;
        });

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