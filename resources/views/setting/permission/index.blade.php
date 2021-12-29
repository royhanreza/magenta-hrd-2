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
              <h5 class="card-header-title">Izin khusus apa saja yang berlaku di perusahaan Anda?</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
              </div>
            </div>
            <div class="card-body">
              @if(in_array("editPermissionSetting", $userLoginPermissions))
              <div class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#formPermission" aria-expanded="false" aria-controls="formPermission"><i class="fas fa-fw fa-plus"></i> Tambah Jenis Izin</button>
              </div>
              @endif
              <div id="formPermission" class="collapse">
                <form @submit.prevent="addPermission">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Jenis Izin</label>
                      <input v-model="permissionModel.add.name" type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Maksimal Hari Pengajuan</label>
                      <input v-model="permissionModel.add.maxDay" type="number" class="form-control">
                    </div>
                  </div>
                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingPermission"><span v-if="loadingPermission" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                  </div>
                </form>
              </div>
              <div class="pt-3">
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col" class="border-0">Jenis Izin</th>
                      <th scope="col" class="text-center border-0">Maks. Hari/Pengajuan</th>
                      <th scope="col" class="text-center border-0">Status</th>
                      <th scope="col" class="text-center border-0">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td v-if="permissions.length < 1" colspan="6" class="text-center text-light">
                        <i class="fa fa-fw fa-folder-open fa-3x"></i>
                        <h6>Belum ada data</h6>
                      </td>
                    </tr>
                    <tr is="permission" v-for="(permission, index) in permissions" :key="permission.id" :id="permission.id" :index="index" :name="permission.name" :maxday="permission.max_day" :isactive="permission.is_active" :ondelete="deletePermission" :onopenmodal="openEditPermissionModal"></tr>
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
<div class="modal fade" id="editPermissionModal" tabindex="-1" role="dialog" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPermissionModalLabel">Edit Jenis Izin</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="editPermission(permissionEditIndex, permissionEditId)">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Jenis Izin</label>
              <input v-model="permissionModel.edit.name" type="text" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label>Maksimal Hari Pengajuan</label>
              <input v-model="permissionModel.edit.maxDay" type="number" class="form-control">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Status</label>
              <select v-model="permissionModel.edit.isActive" class="form-control form-control-sm">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingPermissionEdit"><span v-if="loadingPermissionEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
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
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  Vue.component('permission', {
    props: ['id', 'index', 'name', 'maxday', 'isactive', 'ondelete', 'onopenmodal'],
    template: `
    <tr>
      <td>@{{ name }}</td>
      <td class="text-center">@{{ maxday }} Hari</td>
      <td v-if="isactive == '1'" class="text-center"><span class="badge badge-success">Active</span></td>
      <td v-else class="text-center"><span class="badge badge-warning">Inactive</span></td>
      <td class="text-center">
        @if(in_array("editPermissionSetting", $userLoginPermissions))
        <div class="btn-group" role="group" aria-label="Action Buttons">
          <button class="btn btn-sm btn-light" @click="onopenmodal(index, id)"><i class="fas fa-fw fa-pencil-alt"></i></button>
          <button type="button" class="btn btn-sm btn-light" @click="ondelete(index, id)"><i class="fas fa-fw fa-trash"></i></button>
        </div>
        @endif
      </td>
    </tr>
    `,
  })


  let app = new Vue({
    el: '#app',
    data: {
      permissions: JSON.parse('{!! $permissions !!}'),
      permissionModel: {
        add: {
          name: '',
          maxDay: '',
          isActive: 1,
        },
        edit: {
          name: '',
          maxDay: '',
          isActive: 1,
        }
      },
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

@endsection