@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link href="{{ asset('vendor/select2/css/select2.css') }}" rel="stylesheet" />
@endsection

@section('title', 'Magenta HRD')

@section('content')
<!-- ============================================================== -->
<!-- wrapper  -->
<!-- ============================================================== -->
<div class="dashboard-wrapper">
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content">
            <!-- ============================================================== -->
            <!-- pageheader  -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <h2 class="pageheader-title">Role </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/role" class="breadcrumb-link">Role</a></li>
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
                    <form autocomplete="off" @submit.prevent="submitForm">
                        <div class="card">
                            <h5 class="card-header">Add New Role</h5>
                            <div class="card-body">
                                <div class="alert alert-info" role="alert">
                                    <span title="Kosongkan field waktu jika libur"><i class="fa fa-fw fa-lightbulb"></i> Press Ctrl + F to search</span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="role-name">Role Name<sup class="text-danger">*</sup></label>
                                        <input v-model="name" type="text" class="form-control form-control-sm" id="role-name" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="company">Company<sup class="text-danger">*</sup></label>
                                        <select v-model="company" name="company" id="company" class="form-control form-control-sm">
                                            <option value="" disabled>Choose Company</option>
                                            <option v-for="company in companies" :key="company.id" :value="company.id">@{{ company.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <permission-item v-for="permission in permissions" v-bind:key="permission.heading" v-bind:heading="permission.heading" v-bind:items="permission.items" v-bind:checkedpermissions="checkedPermissions" v-model="checkedPermissions">
                                    </role-item>
                            </div>
                            <div class="card-footer d-flex justify-content-end">

                                <button type="submit" class="btn btn-primary px-5" v-bind:disabled="loading"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
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
<!-- slimscroll js -->
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
<!-- additional script -->
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
    function jsonEscape(str) {
        return str.replace(/\n/g, "\\\\n").replace(/\r/g, "\\\\r").replace(/\t/g, "\\\\t");
    }

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

    let permissionItem = Vue.component('permission-item', {
        props: ['heading', 'items', 'checkedpermissions'],
        methods: {
            onChange(e) {
                let currentValue = [...this.checkedpermissions]
                if (e.target.checked) {
                    currentValue.push(e.target.value)
                } else {
                    currentValue = currentValue.filter(item => item !== e.target.value)
                }
                this.$emit('input', currentValue);
            },
            isChecked: function(value) {
                return this.checkedpermissions.indexOf(value) > -1;
            }
        },
        template: `<div>
                <h5><i class="fas fa-chevron-right"></i> @{{ heading }}</h5>
                <table class="table table-borderless">
                    <tr v-for="item in items">
                        <td style="width: 170px">@{{ item.title }}</td>
                        <td v-for="(attribute, index) in item.attributes">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" v-bind:value="attribute.value" v-on:change="onChange" v-bind:checked="isChecked(attribute.value)"><span class="custom-control-label" style="margin-top: 3px;">@{{ attribute.title }}</span>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>`
    })

    let app = new Vue({
        el: '#app',
        data: {
            companies: JSON.parse(jsonEscape('{!! $companies !!}')),
            company: '{{ $role->company_id }}',
            name: '{{ $role->name }}',
            checkedPermissions: JSON.parse('{!! $role->role_permissions !!}'),
            loading: false,
            permissions: [{
                    heading: 'Pegawai',
                    items: [{
                            title: 'Pegawai',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewEmployee'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addEmployee'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editEmployee'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteEmployee'
                                },
                            ]
                        },
                        {
                            title: 'Role',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewRole'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addRole'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editRole'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteRole'
                                },
                            ]
                        },
                        {
                            title: 'Shift Kerja',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewShift'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addShift'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editShift'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteShift'
                                },
                            ]
                        },
                    ],
                },
                {
                    heading: 'Detail Pegawai',
                    items: [{
                            title: 'Akun',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewEmployeeAccount'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editEmployeeAccount'
                                },
                            ]
                        },
                        {
                            title: 'Karir & Remunerasi',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewEmployeeCareer'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addEmployeeCareer'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editEmployeeCareer'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteEmployeeCareer'
                                },
                            ]
                        },
                        {
                            title: 'Absensi',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewEmployeeAttendance'
                                },
                                {
                                    title: 'Edit Lembur',
                                    value: 'editEmployeeOvertime'
                                },
                            ]
                        },
                        {
                            title: 'Shift Kerja',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewEmployeeShift'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editEmployeeShift'
                                },
                            ]
                        },
                        {
                            title: 'Slip Gaji',
                            attributes: [{
                                title: 'View',
                                value: 'viewEmployeePayslip'
                            }, ]
                        },
                        // {
                        //     title: 'Pengaturan Gaji',
                        //     attributes: [{
                        //             title: 'View',
                        //             value: 'viewEmployeeSalarySetting'
                        //         },
                        //         {
                        //             title: 'Edit',
                        //             value: 'editEmployeeSalarySetting'
                        //         },
                        //     ]
                        // },
                        {
                            title: 'Kasbon',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewEmployeeLoan'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addEmployeeLoan'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editEmployeeLoan'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteEmployeeLoan'
                                },
                            ]
                        },
                        {
                            title: 'Pengaturan Pegawai',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewEmployeeSetting'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editEmployeeSetting'
                                },
                            ]
                        },
                    ],
                },
                {
                    heading: 'Perusahaan',
                    items: [{
                            title: 'Lokasi Kantor',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewLocation'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addLocation'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editLocation'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteLocation'
                                },
                            ]
                        },
                        {
                            title: 'Departemen',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewDepartment'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addDepartment'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editDepartment'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteDepartment'
                                },
                            ]
                        },
                        {
                            title: 'Bagian/Divisi',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewDesignation'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addDesignation'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editDesignation'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteDesignation'
                                },
                            ]
                        },
                        {
                            title: 'Job Title',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewJobTitle'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addJobTitle'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editJobTitle'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteJobTitle'
                                },
                            ]
                        },
                    ],
                },
                {
                    heading: 'Timesheet',
                    items: [{
                        title: 'Absensi',
                        attributes: [{
                                title: 'View',
                                value: 'viewAttendance'
                            },
                            {
                                title: 'Edit Lembur',
                                value: 'editOvertime'
                            },
                        ]
                    }, ],
                },
                {
                    heading: 'Penggajian',
                    items: [{
                            title: 'Gaji Bulanan',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewMonthlySalary'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addMonthlySalary'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editMonthlySalary'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteMonthlySalary'
                                },
                            ]
                        },
                        {
                            title: 'Gaji Harian',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewDailySalary'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addDailySalary'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editDailySalary'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteDailySalary'
                                },
                            ]
                        },
                        {
                            title: 'THR',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewThr'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addThr'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editThr'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteThr'
                                },
                            ]
                        },
                        {
                            title: 'Cuti',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewLeaveSalary'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addLeaveSalary'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editLeaveSalary'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteLeaveSalary'
                                },
                            ]
                        },
                    ],
                },
                {
                    heading: 'Sakit & Izin',
                    items: [{
                            title: 'Pengajuan Sakit',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewSickSubmission'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addSickSubmission'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editSickSubmission'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteSickSubmission'
                                },
                                {
                                    title: 'Approval',
                                    value: 'approvalSickSubmission'
                                },
                            ]
                        },
                        {
                            title: 'Pengajuan Izin',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewPermissionSubmission'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addPermissionSubmission'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editPermissionSubmission'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deletePermissionSubmission'
                                },
                                {
                                    title: 'Approval',
                                    value: 'approvalPermisionSubmission'
                                },
                            ]
                        },
                    ],
                },
                {
                    heading: 'Kelola Cuti',
                    items: [{
                            title: 'Data Cuti',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewLeaveData'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editLeaveData'
                                },
                            ]
                        },
                        {
                            title: 'Pengajuan Cuti',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewLeaveSubmission'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addLeaveSubmission'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editLeaveSubmission'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteLeaveSubmission'
                                },
                                {
                                    title: 'Approval',
                                    value: 'approvalPermissionSubmission'
                                },
                            ]
                        },
                    ],
                },
                {
                    heading: 'Pengaturan',
                    items: [
                        {
                            title: 'Kalender',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewCalendarSetting'
                                },
                                {
                                    title: 'Tambah',
                                    value: 'addCalendarSetting'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editCalendarSetting'
                                },
                                {
                                    title: 'Hapus',
                                    value: 'deleteCalendarSetting'
                                },
                            ]
                        },
                        {
                            title: 'Gaji & THR',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewSalarySetting'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editSalarySetting'
                                },
                            ]
                        },
                        {
                            title: 'Penggajian',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewPayrollSetting'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editPayrollSetting'
                                },
                            ]
                        },
                        {
                            title: 'PPh21/26',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewPphSetting'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editPphSetting'
                                },
                            ]
                        },
                        {
                            title: 'BPJS',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewBpjsSetting'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editBpjsSetting'
                                },
                            ]
                        },
                        {
                            title: 'Cuti',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewLeaveSetting'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editLeaveSetting'
                                },
                            ]
                        },
                        {
                            title: 'Izin',
                            attributes: [{
                                    title: 'View',
                                    value: 'viewPermissionSetting'
                                },
                                {
                                    title: 'Edit',
                                    value: 'editPermissionSetting'
                                },
                            ]
                        },
                    ],
                },
            ],
            url: '/role',

        },
        methods: {
            submitForm: function() {
                // console.log('submitted');
                let vm = this;
                vm.loading = true;
                axios.patch('/role/{{ $role->id }}', {
                        name: this.name,
                        company_id: this.company,
                        role_permissions: JSON.stringify(this.checkedPermissions),
                    })
                    .then(function(response) {
                        vm.loading = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Your data has been saved',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                window.location.href = vm.url;
                            }
                        })
                        console.log(response);
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

        }
    })
</script>
@endsection