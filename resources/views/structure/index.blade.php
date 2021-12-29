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
              <h5 class="card-header-title">1. Apa saja Departemen yang ada di perusahaan Anda?</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
              </div>
            </div>
            <div class="card-body">
              @if(in_array("addDepartment", $userLoginPermissions))
              <div class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#formDepartment" aria-expanded="false" aria-controls="formDepartment"><i class="fas fa-fw fa-plus"></i> Tambah Departemen</button>
              </div>
              @endif
              <div id="formDepartment" class="collapse">
                <form @submit.prevent="addDepartment">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Nama Departemen</label>
                      <input v-model="departmentModel.add.name" type="text" class="form-control">
                    </div>
                  </div>
                  <div>
                    <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingDepartment"><span v-if="loadingDepartment" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                  </div>
                </form>
              </div>
              <div class="pt-3">
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col" class="border-0">Nama Departemen</th>
                      <!-- <th scope="col" class="text-center border-0">Status</th> -->
                      <th scope="col" class="text-center border-0">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td v-if="departments.length < 1" colspan="6" class="text-center text-light">
                        <i class="fa fa-fw fa-folder-open fa-3x"></i>
                        <h6>Belum ada data</h6>
                      </td>
                    </tr>
                    <tr is="department" v-for="(department, index) in departments" :key="department.id" :id="department.id" :index="index" :name="department.name" :isactive="department.is_active" :ondelete="deleteDepartment" :onopenmodal="openEditDepartmentModal"></tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End: 1 -->
            <!-- 2 -->
            <div class="card-header d-flex bg-light">
              <h5 class="card-header-title">2. Apa saja Bagian/Divisi yang ada di perusahaan Anda?</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
              </div>
            </div>
            <div class="card-body">
              @if(in_array("addDesignation", $userLoginPermissions))
              <div class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#formDivision" aria-expanded="false" aria-controls="formDivision"><i class="fas fa-fw fa-plus"></i> Tambah Bagian</button>
              </div>
              @endif
              <div id="formDivision" class="collapse">
                <form @submit.prevent="addDivision">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Nama Bagian</label>
                      <input v-model="divisionModel.add.name" type="text" class="form-control">
                    </div>
                  </div>
                  <div>
                    <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingDivision"><span v-if="loadingDivision" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                  </div>
                </form>
              </div>
              <div class="pt-3">
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col" class="border-0">Nama Bagian</th>
                      <!-- <th scope="col" class="text-center border-0">Status</th> -->
                      <th scope="col" class="text-center border-0">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td v-if="divisions.length < 1" colspan="6" class="text-center text-light">
                        <i class="fa fa-fw fa-folder-open fa-3x"></i>
                        <h6>Belum ada data</h6>
                      </td>
                    </tr>
                    <tr is="division" v-for="(division, index) in divisions" :key="division.id" :id="division.id" :index="index" :name="division.name" :isactive="division.is_active" :ondelete="deleteDivision" :onopenmodal="openEditDivisionModal"></tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End: 2 -->
            <!-- 3 -->
            <div class="card-header d-flex bg-light">
              <h5 class="card-header-title">3. Apa saja Pekerjaan/Job Title yang ada di perusahaan Anda?</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
              </div>
            </div>
            <div class="card-body">
              @if(in_array("addJobTitle", $userLoginPermissions))
              <div class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#formJobTitle" aria-expanded="false" aria-controls="formJobTitle"><i class="fas fa-fw fa-plus"></i> Tambah Job Title</button>
              </div>
              @endif
              <div id="formJobTitle" class="collapse">
                <form @submit.prevent="addJobTitle">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Nama Job Title</label>
                      <input v-model="jobTitleModel.add.name" type="text" class="form-control">
                    </div>
                  </div>
                  <div>
                    <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingJobTitle"><span v-if="loadingJobTitle" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                  </div>
                </form>
              </div>
              <div class="pt-3">
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col" class="border-0">Nama Job Title</th>
                      <!-- <th scope="col" class="text-center border-0">Status</th> -->
                      <th scope="col" class="text-center border-0">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td v-if="jobTitles.length < 1" colspan="6" class="text-center text-light">
                        <i class="fa fa-fw fa-folder-open fa-3x"></i>
                        <h6>Belum ada data</h6>
                      </td>
                    </tr>
                    <tr is="job-title" v-for="(jobTitle, index) in jobTitles" :key="jobTitle.id" :id="jobTitle.id" :index="index" :name="jobTitle.name" :isactive="parseInt(jobTitle.status)" :ondelete="deleteJobTitle" :onopenmodal="openEditJobTitleModal"></tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End: 3 -->

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
<div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDepartmentModalLabel">Edit Departemen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="editDepartment(departmentEditIndex, departmentEditId)">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Nama Departemen</label>
              <input v-model="departmentModel.edit.name" type="text" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingDepartmentEdit"><span v-if="loadingDepartmentEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="editDivisionModal" tabindex="-1" role="dialog" aria-labelledby="editDivisionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDivisionModalLabel">Edit Bagian/Divisi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="editDivision(divisionEditIndex, divisionEditId)">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Nama Bagian/Divisi</label>
              <input v-model="divisionModel.edit.name" type="text" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingDivisionEdit"><span v-if="loadingDivisionEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="editJobTitleModal" tabindex="-1" role="dialog" aria-labelledby="editJobTitleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editJobTitleModalLabel">Edit Job Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="editJobTitle(jobTitleEditIndex, jobTitleEditId)">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Nama Job Title</label>
              <input v-model="jobTitleModel.edit.name" type="text" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingJobTitleEdit"><span v-if="loadingJobTitleEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save changes</button>
        </div>
      </form>
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
  // <td v-if="isactive" class="text-center"><span class="badge badge-success">Active</span></td>
  // <td v-else class="text-center"><span class="badge badge-warning">Inactive</span></td>
  Vue.component('department', {
    props: ['id', 'index', 'name', 'isactive', 'ondelete', 'onopenmodal'],
    template: `
    <tr>
      <td>@{{ name }}</td>
      <td class="text-center">
        <div class="btn-group" role="group" aria-label="Action Buttons">
          @if(in_array("editDepartment", $userLoginPermissions))
          <button class="btn btn-sm btn-light" @click="onopenmodal(index, id)"><i class="fas fa-fw fa-pencil-alt"></i></button>
          @endif
          @if(in_array("deleteDepartment", $userLoginPermissions))
          <button type="button" class="btn btn-sm btn-light" @click="ondelete(index, id)"><i class="fas fa-fw fa-trash"></i></button>
          @endif
        </div>
      </td>
    </tr>
    `,
  })

  Vue.component('division', {
    props: ['id', 'index', 'name', 'isactive', 'ondelete', 'onopenmodal'],
    template: `
    <tr>
      <td>@{{ name }}</td>
      <td class="text-center">
        <div class="btn-group" role="group" aria-label="Action Buttons">
          @if(in_array("editDesignation", $userLoginPermissions))
          <button class="btn btn-sm btn-light" @click="onopenmodal(index, id)"><i class="fas fa-fw fa-pencil-alt"></i></button>
          @endif
          @if(in_array("deleteDesignation", $userLoginPermissions))
          <button type="button" class="btn btn-sm btn-light" @click="ondelete(index, id)"><i class="fas fa-fw fa-trash"></i></button>
          @endif
        </div>
      </td>
    </tr>
    `,
  })

  Vue.component('job-title', {
    props: ['id', 'index', 'name', 'isactive', 'ondelete', 'onopenmodal'],
    template: `
    <tr>
      <td>@{{ name }}</td>
      <td class="text-center">
        <div class="btn-group" role="group" aria-label="Action Buttons">
          @if(in_array("editJobTitle", $userLoginPermissions))
          <button class="btn btn-sm btn-light" @click="onopenmodal(index, id)"><i class="fas fa-fw fa-pencil-alt"></i></button>
          @endif
          @if(in_array("deleteJobTitle", $userLoginPermissions))
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
      departments: JSON.parse('{!! $departments !!}'),
      departmentModel: {
        add: {
          name: '',
          isActive: 1,
        },
        edit: {
          name: '',
          isActive: 1,
        }
      },
      departmentEditId: null,
      departmentEditIndex: null,
      loadingDepartment: false,
      loadingDepartmentEdit: false,
      divisions: JSON.parse('{!! $designations !!}'),
      divisionModel: {
        add: {
          name: '',
          isActive: 1,
        },
        edit: {
          name: '',
          isActive: 1,
        }
      },
      divisionEditId: null,
      divisionEditIndex: null,
      loadingDivision: false,
      loadingDivisionEdit: false,
      // JOB TITLES
      jobTitles: JSON.parse('{!! $job_titles !!}'),
      jobTitleModel: {
        add: {
          name: '',
          isActive: 1,
        },
        edit: {
          name: '',
          isActive: 1,
        }
      },
      jobTitleEditId: null,
      jobTitleEditIndex: null,
      loadingJobTitle: false,
      loadingJobTitleEdit: false,
      hasOvertime: parseInt('{{ $salary_setting->has_overtime }}'),
      loadingSetting: false,
      // Summary
      hasOvertimeSummary: parseInt('{{ $salary_setting->has_overtime }}'),

      // End::Summary
      toast: false,
      toastType: 'success',
      toastTitle: 'Success',
      toastText: 'Task has been saved',
    },
    methods: {
      addDepartment: function() {
        let vm = this;
        vm.loadingDepartment = true;
        axios.post('/company-department', {
            department_name: this.departmentModel.add.name,
            is_active: this.departmentModel.add.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingDepartment = false;
            vm.showToast('Success', 'Departemen berhasil ditambahkan', 'success');
            vm.departments.push(response.data.data);
            vm.resetDepartmentAdd();
          })
          .catch(function(error) {
            vm.showToast('Error', 'Gagal menambahkan departemen', 'error');
            vm.loadingDepartment = false;
            console.log(error);
          });
      },
      openEditDepartmentModal: function(index, id) {
        this.departmentEditId = id;
        this.departmentEditIndex = index;
        this.departmentModel.edit.name = this.departments[index].name;
        // this.departmentModel.edit.isActive = this.departments[index].is_active;
        $('#editDepartmentModal').modal('show');
      },
      editDepartment: function(index, id) {
        let vm = this;
        vm.loadingDepartmentEdit = true;
        axios.patch('/company-department/' + id, {
            department_name: this.departmentModel.edit.name,
            // is_active: this.departmentModel.edit.isActive,
          })
          .then(function(response) {
            // console.log(response)
            vm.loadingDepartmentEdit = false;
            vm.departments[index] = response.data.data;
            vm.departmentEditIndex = null;
            vm.departmentEditId = null;
            $('#editDepartmentModal').modal('hide');
            vm.resetDepartmentEdit();
            vm.showToast('Success', 'Departemen berhasil diubah', 'success');
          })
          .catch(function(error) {
            vm.showToast('Error', 'Departemen gagal diubah', 'error');
            vm.loadingDepartmentEdit = false;
            console.log(error);
          });
      },
      resetDepartmentEdit: function() {
        this.departmentModel.edit.name = '';
        // this.departmentModel.edit.isActive = '';
      },
      deleteDepartment: function(index, id) {
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
            return axios.delete('/company-department/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Gagal menghapus departemen', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Departemen berhasil dihapus', 'success');
            vm.departments.splice(index, 1);
          }
        })
      },
      resetDepartmentAdd: function() {
        this.departmentModel.add.name = '';
        this.departmentModel.add.isActive = 1;
      },
      // DIVISION
      addDivision: function() {
        let vm = this;
        vm.loadingDivision = true;
        axios.post('/company-designation', {
            name: this.divisionModel.add.name,
            is_active: this.divisionModel.add.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingDivision = false;
            vm.showToast('Success', 'Bagian berhasil ditambahkan', 'success');
            vm.divisions.push(response.data.data);
            vm.resetDivisionAdd();
          })
          .catch(function(error) {
            vm.showToast('Error', 'Gagal menambahkan bagian', 'error');
            vm.loadingDivision = false;
            console.log(error);
          });
      },
      openEditDivisionModal: function(index, id) {
        this.divisionEditId = id;
        this.divisionEditIndex = index;
        this.divisionModel.edit.name = this.divisions[index].name;
        // this.divisionModel.edit.isActive = this.divisions[index].is_active;
        $('#editDivisionModal').modal('show');
      },
      editDivision: function(index, id) {
        let vm = this;
        vm.loadingDivisionEdit = true;
        axios.patch('/company-designation/' + id, {
            name: this.divisionModel.edit.name,
            // is_active: this.divisionModel.edit.isActive,
          })
          .then(function(response) {
            // console.log(response)
            vm.loadingDivisionEdit = false;
            vm.divisions[index] = response.data.data;
            vm.divisionEditIndex = null;
            vm.divisionEditId = null;
            $('#editDivisionModal').modal('hide');
            vm.resetDivisionEdit();
            vm.showToast('Success', 'Bagian berhasil diubah', 'success');
          })
          .catch(function(error) {
            vm.showToast('Error', 'Bagian gagal diubah', 'error');
            vm.loadingDivisionEdit = false;
            console.log(error);
          });
      },
      resetDivisionEdit: function() {
        this.divisionModel.edit.name = '';
        // this.divisionModel.edit.isActive = '';
      },
      deleteDivision: function(index, id) {
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
            return axios.delete('/company-designation/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Gagal menghapus bagian', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Bagian berhasil dihapus', 'success');
            vm.divisions.splice(index, 1);
          }
        })
      },
      resetDivisionAdd: function() {
        this.divisionModel.add.name = '';
        this.divisionModel.add.isActive = 1;
      },
      // JOB TITLE
      addJobTitle: function() {
        let vm = this;
        vm.loadingJobTitle = true;
        axios.post('/job-title', {
            name: this.jobTitleModel.add.name,
            is_active: this.jobTitleModel.add.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingJobTitle = false;
            vm.showToast('Success', 'Job title berhasil ditambahkan', 'success');
            vm.jobTitles.push(response.data.data);
            vm.resetJobTitleAdd();
          })
          .catch(function(error) {
            vm.showToast('Error', 'Gagal menambahkan job title', 'error');
            vm.loadingJobTitle = false;
            console.log(error);
          });
      },
      openEditJobTitleModal: function(index, id) {
        this.jobTitleEditId = id;
        this.jobTitleEditIndex = index;
        this.jobTitleModel.edit.name = this.jobTitles[index].name;
        // this.jobTitleModel.edit.isActive = this.jobTitles[index].is_active;
        $('#editJobTitleModal').modal('show');
      },
      editJobTitle: function(index, id) {
        let vm = this;
        vm.loadingJobTitleEdit = true;
        axios.patch('/job-title/' + id, {
            name: this.jobTitleModel.edit.name,
            // is_active: this.jobTitleModel.edit.isActive,
          })
          .then(function(response) {
            // console.log(response)
            vm.loadingJobTitleEdit = false;
            vm.jobTitles[index] = response.data.data;
            vm.jobTitleEditIndex = null;
            vm.jobTitleEditId = null;
            $('#editJobTitleModal').modal('hide');
            vm.resetJobTitleEdit();
            vm.showToast('Success', 'Job title berhasil diubah', 'success');
          })
          .catch(function(error) {
            vm.showToast('Error', 'Job title gagal diubah', 'error');
            vm.loadingJobTitleEdit = false;
            console.log(error);
          });
      },
      resetJobTitleEdit: function() {
        this.jobTitleModel.edit.name = '';
        // this.jobTitleModel.edit.isActive = '';
      },
      deleteJobTitle: function(index, id) {
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
            return axios.delete('/job-title/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Gagal menghapus job title', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Job title berhasil dihapus', 'success');
            vm.jobTitles.splice(index, 1);
          }
        })
      },
      resetJobTitleAdd: function() {
        this.jobTitleModel.add.name = '';
        this.jobTitleModel.add.isActive = 1;
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