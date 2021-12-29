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

@section('pagestyle')
<style>
  .btn-sm {
    padding: 4px 12px;
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
                  <a class="nav-link active show" id="card-tab-3" href="/mapping-event/{{ $event->id }}/budget"><i class="fas fa-fw fa-dollar-sign"></i> Budget</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="card-tab-4" href="/mapping-event/{{ $event->id }}/member"><i class="fa fa-fw fa-users"></i> Members</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="card-3" role="tabpanel" aria-labelledby="card-tab-3">
                  <form autocomplete="off" @submit.prevent="editBudgetDate">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div class="form-row" style="width: 70%;">
                        <div class="form-group col-md-4">
                          <label for="effective-date">Effective Date<sup class="text-danger">*</sup></label>
                          <input v-model="effectiveDate" type="date" @change="swapDate()" class="form-control form-control-sm" id="effective-date" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="expire-date">Expire Date<sup class="text-danger">*</sup></label>
                          <input v-model="expireDate" type="date" @change="swapDate()" class="form-control form-control-sm" id="expire-date" required>
                        </div>
                        <div class="form-group col-md-2">
                          <button type="submit" class="btn btn-primary btn-sm mt-4" v-bind:disabled="loadingBudgetDate"><span v-if="loadingBudgetDate" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp; Save Date</button>
                        </div>
                      </div>
                      <div>
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-fw fa-plus"></i> Add Budget</button>
                      </div>
                    </div>
                  </form>
                  <div class="collapse" id="collapseExample">
                    <form autocomplete="off" @submit.prevent="addBudget">
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="amount">Amount<sup class="text-danger">*</sup></label>
                          <input v-model="amount" type="number" class="form-control form-control-sm" id="amount" required>
                        </div>
                        <!-- <div class="form-group col-md-6">
                                            <label for="transfer-date">Transfer Date<sup class="text-danger">*</sup></label>
                                            <input v-model="transferDate" type="date" class="form-control form-control-sm" id="transfer-date" required>
                                          </div> -->
                        <div class="form-row col-md-6 pr-0">
                          <div class="form-group col-md-6">
                            <label for="transfer-date">Transfer Date<sup class="text-danger">*</sup></label>
                            <input v-model="transferDate" type="date" class="form-control form-control-sm" id="transfer-date" required>
                          </div>
                          <div class="form-group col-md-6 pr-0">
                            <label for="transfer-time">Transfer Time<sup class="text-danger">*</sup></label>
                            <input v-model="transferTime" type="time" class="form-control form-control-sm" id="transfer-time" required>
                          </div>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="transfer-to">Transfer To<sup class="text-danger">*</sup></label>
                          <!-- <input type="text" class="form-control form-control-sm" id="amount" required> -->
                          <select2 v-model="transferTo" class="form-control form-control-sm" :options="bankAccounts" required></select2>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="image">Image</label>
                          <input type="file" accept=".jpg, .png, .jpeg" class="form-control form-control-sm" id="image">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="category">Category<sup class="text-danger">*</sup></label>
                          <select2 v-model="category" class="form-control form-control-sm" :options="categories" required></select2>
                        </div>
                        <div class="form-group col-md-6">
                          <label>Note</label>
                          <textarea v-model="note" class="form-control form-control-sm" required></textarea>
                        </div>
                      </div>
                      <!-- <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="effective-date">Effective Date<sup class="text-danger">*</sup></label>
                                            <input v-model="effectiveDate" type="date" @input="swapDate()" class="form-control form-control-sm" id="effective-date" required>
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="expire-date">Expire Date<sup class="text-danger">*</sup></label>
                                            <input v-model="expireDate" type="date" @input="swapDate()" class="form-control form-control-sm" id="expire-date" required>
                                          </div>
                                        </div> -->
                      <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                      </div>
                    </form>
                    <hr>

                  </div>
                  <div class="text-center py-3" v-if="budgets.length < 1">
                    <i class="fa fa-fw fa-folder-open fa-5x"></i>
                    <p class="h6">No Budget yet</p>
                  </div>
                  <ul class="list-group list-group-flush" v-if="budgets.length > 0">
                    <li class="list-group-item list-group-item-info">
                      <div class="row">
                        <div class="col-md-3"><strong>Amount</strong></div>
                        <div class="col-md-3"><strong>Transfer Date</strong></div>
                        <div class="col-md-3"><strong>Transfer To</strong></div>
                        <div class="col-md-3"></div>
                      </div>
                    </li>
                    <li class="list-group-item" v-for="(budget, index) in budgets">
                      <div class="row">
                        <div class="col-md-3">Rp @{{ budget.amount }}</div>
                        <div class="col-md-3">@{{ budget.date }}</div>
                        <div class="col-md-3">@{{ (budget.bank_account !== null) ? `${budget.bank_account.bank_name} ${budget.bank_account.account_number} (${budget.bank_account.account_owner})` : 'Unknown Bank Account (Deleted)' }}</div>
                        <div class="col-md-3 text-right">
                          <button type="button" @click="onModalOpen(budget.id, index)" class="btn btn-sm btn-light" data-toggle="modal" data-target="#editModal"><i class="fas fa-fw fa-pencil-alt"></i></button>
                          <button type="button" @click="deleteBudget(index, budget.id)" class="btn btn-sm btn-light"><i class="fas fa-fw fa-trash"></i></button>
                          <button type="button" class="btn btn-sm btn-light" v-if="budget.hasOwnProperty('image') && budget.image !== null"><i class="fas fa-fw fa-image"></i></button>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Edit Task</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form autocomplete="off" @submit.prevent="editBudget">
                      <div class="modal-body">
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="amount-edit">Amount<sup class="text-danger">*</sup></label>
                            <input v-model="amountEdit" type="number" class="form-control form-control-sm" id="amount-edit" required>
                          </div>
                          <!-- <div class="form-group col-md-6">
                                            <label for="transfer-date-edit">Transfer Date<sup class="text-danger">*</sup></label>
                                            <input v-model="transferDateEdit" type="date" class="form-control form-control-sm" id="transfer-date-edit" required>
                                          </div> -->
                          <div class="form-row col-md-6 pr-0">
                            <div class="form-group col-md-6">
                              <label for="transfer-date-edit">Transfer Date<sup class="text-danger">*</sup></label>
                              <input v-model="transferDateEdit" type="date" class="form-control form-control-sm" id="transfer-date-edit" required>
                            </div>
                            <div class="form-group col-md-6 pr-0">
                              <label for="transfer-time-edit">Transfer Time<sup class="text-danger">*</sup></label>
                              <input v-model="transferTimeEdit" type="time" class="form-control form-control-sm" id="transfer-time-edit" required>
                            </div>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="transfer-to-edit">Transfer To<sup class="text-danger">*</sup></label>
                            <!-- <input type="text" class="form-control form-control-sm" id="amount" required> -->
                            <select2 v-model="transferToEdit" class="form-control form-control-sm" :options="bankAccounts" required></select2>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="image-edit">Image</label>
                            <input type="file" accept=".jpg, .png, .jpeg" class="form-control form-control-sm" id="image-edit">
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="category">Category<sup class="text-danger">*</sup></label>
                            <select2 v-model="categoryEdit" class="form-control form-control-sm" :options="categories" required></select2>
                          </div>
                          <div class="form-group col-md-6">
                            <label>Note</label>
                            <textarea v-model="noteEdit" class="form-control form-control-sm" required></textarea>
                          </div>
                        </div>
                        <!-- <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="effective-date-edit">Effective Date<sup class="text-danger">*</sup></label>
                                            <input v-model="effectiveDateEdit" type="date" @input="swapDateEdit()" class="form-control form-control-sm" id="effective-date-edit" required>
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="expire-date-edit">Expire Date<sup class="text-danger">*</sup></label>
                                            <input v-model="expireDateEdit" type="date" @input="swapDateEdit()" class="form-control form-control-sm" id="expire-date-edit" required>
                                          </div>
                                        </div>                                 -->
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" @click="editBudget" v-bind:disabled="loadingEdit"><span v-if="loadingEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save changes</button>
                      </div>
                    </form>
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
      budgets: JSON.parse('{!! $budgets !!}'),
      amount: '',
      transferDate: '',
      transferTime: '',
      bankAccounts: JSON.parse('{!! $bank_accounts !!}'),
      categories: JSON.parse('{!! $budget_categories !!}'),
      category: '',
      note: 'Deposit',
      transferTo: '',
      image: '',
      effectiveDate: '{{ $event->budget_effective_date }}',
      expireDate: '{{ $event->budget_expire_date }}',
      //EDIT
      amountEdit: '',
      transferDateEdit: '',
      transferTimeEdit: '',
      transferToEdit: '',
      imageEdit: '',
      categoryEdit: '',
      noteEdit: '',
      // effectiveDateEdit: '',
      // expireDateEdit: '',
      //EDIT
      loading: false,
      loadingEdit: false,
      loadingBudgetDate: false,
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
      addBudget: function() {
        let vm = this;
        vm.loading = true;
        axios.post('/event-budget', {
            amount: this.amount,
            transfer_date: this.transferDate,
            transfer_time: this.transferTime,
            transfer_to: this.transferTo,
            // effective_date: this.effectiveDate,
            // expire_date: this.expireDate,
            event_id: this.eventId,
            budget_category_id: this.category,
            note: this.note,
          })
          .then(function(response) {
            // console.log(response)
            vm.loading = false;
            vm.showToast('Success', 'Budget has been saved', 'success');
            vm.budgets.push(response.data.data);
            vm.amount = '';
            vm.transferDate = '';
            vm.transferTo = '';
            vm.category = '';
            vm.note = '';
            // vm.effectiveDate = '';
            // vm.expireDate = '';
          })
          .catch(function(error) {
            vm.showToast('Error', 'Failed to save data', 'error');
            vm.loading = false;
            console.log(error);
          });
      },
      deleteBudget: function(index, id) {
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
            return axios.delete('/event-budget/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Failed to save data', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Budget has been deleted', 'success');
            vm.budgets.splice(index, 1);
          }
        })
      },
      editBudget: function() {
        let vm = this;
        vm.loadingEdit = true;
        axios.patch('/event-budget/' + this.budgetId, {
            amount: this.amountEdit,
            transfer_date: this.transferDateEdit,
            transfer_time: this.transferTimeEdit,
            transfer_to: this.transferToEdit,
            budget_category_id: this.categoryEdit,
            note: this.noteEdit,
            // effective_date: this.effectiveDateEdit,
            // expire_date: this.expireDateEdit,
          })
          .then(function(response) {
            vm.loadingEdit = false;
            $('#editModal').modal('hide');
            vm.budgets[vm.budgetIndex] = response.data.data;
            vm.budgetId = '';
            vm.budgetIndex = '';
            vm.resetEditForm();
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
        this.transferTimeEdit = '';
        this.transferToEdit = '';
        this.categoryEdit = '';
        this.noteEdit = '';
        // this.effectiveDateEdit = '';
        // this.expireDateEdit = '';
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
          this.transferDateEdit = budget[0].date.split(' ')[0];
          this.transferTimeEdit = budget[0].date.split(' ')[1];
          this.transferToEdit = budget[0].transfer_to;
          this.imageEdit = budget[0].image;
          this.categoryEdit = budget[0].budget_category_id;
          this.noteEdit = budget[0].note;
          // this.effectiveDateEdit = budget[0].effective_date;
          // this.expireDateEdit = budget[0].expire_date;
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
      // swapDateEdit: function() {
      //   if(this.effectiveDateEdit !== '' || this.expireDateEdit !== '') {
      //     let effectiveDate = new Date(this.effectiveDateEdit);
      //     let expireDate = new Date(this.expireDateEdit);

      //     if(effectiveDate > expireDate) {
      //       [this.effectiveDateEdit, this.expireDateEdit] = [this.expireDateEdit, this.effectiveDateEdit];
      //     }
      //   }
      // },
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
      editBudgetDate: function() {
        let vm = this;
        vm.loadingBudgetDate = true;
        axios.patch('/event/{{ $event->id }}/update-budget-date', {
            effective_date: this.effectiveDate,
            expire_date: this.expireDate,
          })
          .then(function(response) {
            // console.log(response);
            vm.loadingBudgetDate = false;
            vm.showToast('Success', 'Changes has been saved', 'success');
            // console.log(response);
          })
          .catch(function(error) {
            vm.loadingBudgetDate = false;
            console.log(error);
            vm.showToast('Error', 'Failed to save changes', 'error');
          });
      },
    }
  })
</script>
@endsection