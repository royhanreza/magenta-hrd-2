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
                  <a class="nav-link active show" id="card-tab-1" data-toggle="tab" href="#card-1" role="tab" aria-controls="card-1" aria-selected="true"><i class="fa fa-fw fa-cogs"></i> Basic Information</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="card-tab-2" href="/mapping-event/{{ $event->id }}/task"><i class="fa fa-fw fa-tasks"></i> Tasks<span v-if="tasks.length > 0"> (@{{ tasks.length }})</span></a>
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
                <div class="tab-pane fade active show" id="card-1" role="tabpanel" aria-labelledby="card-tab-1">
                  <form autocomplete="off" @submit.enter.prevent="submitForm">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="quotation-number">Quotation Number</label>
                        <input v-model="quotationNumber" type="text" class="form-control form-control-sm" id="quotation-number" readonly>
                        <!-- <label for="quotation-number">Quotation Number</label>
                        <select v-model="quotation" name="" id="quotation-number" class="form-control form-control-sm">
                          <option value="1">QT-31232 | Event Nutricia</option>
                        </select> -->
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
                        <input v-model="quotationNote" type="text" class="form-control form-control-sm" id="note" readonly required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="pic-event">PIC Event</label>
                        <input v-model="quotationPicEvent" type="text" class="form-control form-control-sm" name="pic-event" value="Nutricia" readonly required>
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
                      <button type="submit" class="btn btn-primary px-5" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save Changes</button>
                    </div>
                  </form>
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
      status: '{{ $event->status }}',
      loading: false,
      // approved: false,
      // rejected: false,
      // btnApproveText: 'Approve',
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
            quotation_number: this.quotationNumber,
            city_id: this.city,
            // budget: this.budget,
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
            )
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