@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/buttons.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/select.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/fixedHeader.bootstrap4.css') }}"> -->
@endsection

@section('title', 'Magenta HRD')

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
            <h2 class="pageheader-title">Setting </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item">Setting</li>
                  <li class="breadcrumb-item active" aria-current="page">Salary</li>
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
      <div class="row">
        <!-- ============================================================== -->
        <!-- basic table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="card">
            <!-- 1 -->
            <div class="card-header d-flex bg-light">
              <h5 class="card-header-title">Silakan edit kalender kerja berikut sesuai dengan kebijakan perusahaan Anda. Pengaturan kalender ini akan mempengaruhi catatan kehadiran. Anda dapat mengubah pengaturan ini kapan saja.</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
              </div>
            </div>
            <div class="card-body">
              @if(in_array("addCalendarSetting", $userLoginPermissions))
              <div class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#formCalendar" aria-expanded="false" aria-controls="formCalendar"><i class="fas fa-fw fa-plus"></i> Tambah Event</button>
              </div>
              @endif
              <div id="formCalendar" class="collapse">
                <form @submit.prevent="addCalendar" autocomplete="off">
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Tanggal</label>
                      <input v-model="calendarModel.add.date" type="text" id="calendar-add-date" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                      <label>Nama Event</label>
                      <input v-model="calendarModel.add.name" type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                      <label>Tipe</label>
                      <select v-model="calendarModel.add.type" class="form-control ">
                        <option value="libur nasional">Libur Nasional</option>
                        <option value="cuti bersama">Cuti Bersama</option>
                        <option value="event non libur">Event Non Libur</option>
                      </select>
                    </div>
                  </div>
                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingCalendar"><span v-if="loadingCalendar" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                  </div>
                </form>
              </div>
              <div class="pt-3">
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col" class="text-center border-0">Tanggal</th>
                      <th scope="col" class="text-center border-0">Nama Event</th>
                      <th scope="col" class="text-center border-0">Tipe</th>
                      <th scope="col" class="text-center border-0">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td v-if="calendars.length < 1" colspan="6" class="text-center text-light">
                        <i class="fa fa-fw fa-folder-open fa-3x"></i>
                        <h6>Belum ada data</h6>
                      </td>
                    </tr>
                    <tr is="calendar" v-for="(calendar, index) in calendars" :key="calendar.id" :id="calendar.id" :index="index" :date="calendar.date" :name="calendar.name" :type="calendar.type" :ondelete="deleteCalendar" :onopenmodal="openEditCalendarModal" :moment="moment"></tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End: 1 -->
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
<!-- Toast -->
<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; top: 70px;">
  <div ref="toast" id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header position-relative">
      <strong class="mr-5"><i class="fa fa-fw" v-bind:class="toastType == 'success' ? 'fa-check text-success' : 'fa-times text-danger'"></i> @{{ toastTitle }}</strong>
      <button type="button" class="mb-1 close" style="position: absolute; right: 10px;" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      @{{toastText}}
    </div>
  </div>
</div>
<!-- End:Toast -->
<!-- Modal -->
<div class="modal fade" id="editCalendarModal" tabindex="-1" role="dialog" aria-labelledby="editCalendarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCalendarModalLabel">Edit Event Kalender</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="editCalendar(calendarEditIndex, calendarEditId)">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Tanggal</label>
              <input v-model="calendarModel.edit.date" type="date" class="form-control">
            </div>
            <div class="form-group col-md-4">
              <label>Nama Event</label>
              <input v-model="calendarModel.edit.name" type="text" class="form-control">
            </div>
            <div class="form-group col-md-4">
              <label>Tipe</label>
              <select v-model="calendarModel.edit.type" class="form-control ">
                <option value="libur nasional">Libur Nasional</option>
                <option value="cuti bersama">Cuti Bersama</option>
                <option value="event non libur">Event Non Libur</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingCalendarEdit"><span v-if="loadingCalendarEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<!-- slimscroll js -->
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
<!-- additional script -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
<script src="{{ asset('vendor/moment/moment-with-locales.min.js') }}"></script>
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  moment.locale('id');

  Vue.component('calendar', {
    props: ['id', 'index', 'date', 'name', 'type', 'ondelete', 'onopenmodal', 'moment'],
    template: `
    <tr>
      <td class="text-center">@{{ moment(date).format('LL') }}</td>
      <td class="text-center">@{{ name }}</td>
      <td class="text-center text-capitalize">@{{ type }}</td>
      <td class="text-center">
        <div class="btn-group" role="group" aria-label="Action Buttons">
          @if(in_array("editCalendarSetting", $userLoginPermissions))
          <button class="btn btn-sm btn-light" @click="onopenmodal(index, id)"><i class="fas fa-fw fa-pencil-alt"></i></button>
          @endif
          @if(in_array("deleteCalendarSetting", $userLoginPermissions))
          <button type="button" class="btn btn-sm btn-light" @click="ondelete(index, id)"><i class="fas fa-fw fa-trash"></i></button>
          @endif
        </div>
      </td>
    </tr>
    `,
  })


  let app = new Vue({
    el: '#app',
    data: {
      calendars: JSON.parse(String.raw `{!! $calendars !!}`),
      calendarModel: {
        add: {
          date: '',
          name: '',
          type: '',
        },
        edit: {
          date: '',
          name: '',
          type: '',
        }
      },
      calendarEditId: null,
      calendarEditIndex: null,
      loadingCalendar: false,
      loadingCalendarEdit: false,

      permissions: [],
      permissionEditId: null,
      permissionEditIndex: null,
      loadingPermission: false,
      loadingPermissionEdit: false,
      toast: false,
      toastType: 'success',
      toastTitle: 'Success',
      toastText: 'Task has been saved',
    },
    methods: {
      moment: function(date = null) {
        return moment(date);
      },
      addCalendar: function() {
        let vm = this;
        vm.loadingCalendar = true;
        axios.post('/calendar', {
            date: this.calendarModel.add.date,
            name: this.calendarModel.add.name,
            type: this.calendarModel.add.type,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingCalendar = false;
            vm.showToast('Success', 'Event berhasil ditambahkan', 'success');
            vm.calendars.push(response.data.data);
            vm.resetCalendarForm();
          })
          .catch(function(error) {
            vm.showToast('Error', 'Gagal menambahkan event', 'error');
            vm.loadingCalendar = false;
            console.log(error);
          });
      },
      resetCalendarForm: function() {
        calendarModel = {
          add: {
            date: '',
            name: '',
            type: '',
          },
          edit: {
            date: '',
            name: '',
            type: '',
          }
        }
      },
      openEditCalendarModal: function(index, id) {
        this.calendarEditId = id;
        this.calendarEditIndex = index;
        this.calendarModel.edit.name = this.calendars[index].name;
        this.calendarModel.edit.date = this.calendars[index].date;
        this.calendarModel.edit.type = this.calendars[index].type;
        $('#editCalendarModal').modal('show');
      },
      editCalendar: function(index, id) {
        let vm = this;
        vm.loadingCalendarEdit = true;
        axios.patch('/calendar/' + id, {
            name: this.calendarModel.edit.name,
            date: this.calendarModel.edit.date,
            type: this.calendarModel.edit.type,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingCalendarEdit = false;
            // vm.salaryIncomes.push(response.data.data);
            vm.calendars[index] = response.data.data;
            $('#editCalendarModal').modal('hide');
            vm.resetCalendarForm();
            vm.showToast('Success', 'Event berhasil diubah', 'success');
          })
          .catch(function(error) {
            vm.showToast('Error', 'Event gagal diubah', 'error');
            vm.loadingCalendarEdit = false;
            console.log(error);
          });
      },
      deleteCalendar: function(index, id) {
        let vm = this;

        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Data akan dihapus",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Delete',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.delete('/calendar/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Gagal menghapus event', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Event berhasil dihapus', 'success');
            vm.calendars.splice(index, 1);
          }
        })
      },

      addPermission: function() {
        let vm = this;
        vm.loadingPermission = true;
        axios.post('/permission-category', {
            name: this.permissionModel.add.name,
            max_day: this.permissionModel.add.maxDay,
            is_active: this.permissionModel.add.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingPermission = false;
            vm.showToast('Success', 'Jenis izin berhasil ditambahkan', 'success');
            vm.permissions.push(response.data.data);
            vm.resetPermissionAdd();
          })
          .catch(function(error) {
            vm.showToast('Error', 'Gagal menambahkan jenis izin', 'error');
            vm.loadingPermission = false;
            console.log(error);
          });
      },
      openEditPermissionModal: function(index, id) {
        this.permissionEditId = id;
        this.permissionEditIndex = index;
        this.permissionModel.edit.name = this.permissions[index].name;
        this.permissionModel.edit.maxDay = this.permissions[index].max_day;
        this.permissionModel.edit.isActive = this.permissions[index].is_active;
        $('#editPermissionModal').modal('show');
      },
      editPermission: function(index, id) {
        let vm = this;
        vm.loadingPermissionEdit = true;
        axios.patch('/permission-category/' + id, {
            name: this.permissionModel.edit.name,
            max_day: this.permissionModel.edit.maxDay,
            is_active: this.permissionModel.edit.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingPermissionEdit = false;
            // vm.salaryIncomes.push(response.data.data);
            vm.permissions[index] = response.data.data;
            $('#editPermissionModal').modal('hide');
            vm.resetPermissionEdit();
            vm.showToast('Success', 'Jenis Izin berhasil diubah', 'success');
          })
          .catch(function(error) {
            vm.showToast('Error', 'Jenis Izin gagal diubah', 'error');
            vm.loadingPermissionEdit = false;
            console.log(error);
          });
      },
      resetPermissionEdit: function() {
        this.permissionModel.edit.name = '';
        this.permissionModel.edit.maxDay = '';
        this.permissionModel.edit.isActive = '';
      },
      deletePermission: function(index, id) {
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
            return axios.delete('/permission-category/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Gagal menghapus jenis izin', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Jenis izin berhasil dihapus', 'success');
            vm.permissions.splice(index, 1);
          }
        })
      },
      resetPermissionAdd: function() {
        this.permissionModel.add.name = '';
        this.permissionModel.add.maxDay = '';
        this.permissionModel.add.isActive = 1;
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
    },

  })
</script>
<script>
  $(function() {
    $('#calendar-add-date').datepicker({
      format: 'yyyy-mm-dd',
    }).on('changeDate', function(e) {
      app.$data.calendarModel.add.date = e.format(0, 'yyyy-mm-dd');
    }).on('hide', function(e) {
      app.$data.calendarModel.add.date = e.format(0, 'yyyy-mm-dd');
    })
  })
</script>

@endsection