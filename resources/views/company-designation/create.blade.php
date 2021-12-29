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
        <div class="container-fluid dashboard-content ">
            <!-- ============================================================== -->
            <!-- pageheader  -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <h2 class="pageheader-title">Division</h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/company-designation" class="breadcrumb-link">Division</a></li>
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
                            <h5 class="card-header">Add New Division</h5>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="designation-name">Division Name<sup class="text-danger">*</sup></label>
                                    <input v-model="name" type="text" class="form-control form-control-sm" id="designation-name" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="company">Company<sup class="text-danger">*</sup></label>
                                        <select v-model="company" v-on:change="onChangeCompany($event)" name="company" id="company" class="form-control form-control-sm">
                                            <option value="" disabled>Choose Company</option>
                                            <option v-for="company in companies" :key="company.id" :value="company.id">@{{ company.name }}</option>
                                        </select>
                                    </div>
                                    <!-- <script type="text/x-template" id="demo-template"> -->
                                    <div class="form-group col-md-6">
                                        <label for="department">Main Department<sup class="text-danger">*</sup><span v-if="!companySelected"> (Please choose company)</span></label>
                                        <select2 v-model="department" :options="departments" id="department" class="form-control form-control-sm" v-bind:disabled="!companySelected || departmentLoading" required>
                                            <!-- <option value="0" disabled>Choose Location Head</option> -->
                                            <!-- <option v-for="employee in employees" :key="employee.id" :value="employee.id">@{{ employee.name }}</option> -->
                                        </select2>
                                        <div class="progress progress-sm mt-2" v-if="departmentLoading">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="department-name">Description</label>
                                    <textarea v-model="description" class="form-control form-control-sm"></textarea>
                                </div>
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

    let app = new Vue({
        el: '#app',
        // template: "#demo-template",
        data: {
            name: '',
            companies: JSON.parse(jsonEscape('{!! $companies !!}')),
            company: '',
            departments: [{
                id: '',
                text: 'Choose Main Department'
            }],
            department: '',
            description: '',
            loading: false,
            departmentLoading: false,
            companySelected: false,
            url: '/company-designation'
        },
        methods: {
            submitForm: function() {
                // console.log('submitted');
                let vm = this;
                vm.loading = true;
                axios.post('/company-designation', {
                        name: this.name,
                        // company: this.company,
                        department: parseInt(this.department),
                        description: this.description,
                    })
                    .then(function(response) {
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
            onChangeCompany: function(event) {
                //   console.log('changed');
                //   console.log(event.target.value);
                this.departmentLoading = true;
                this.departments = [{
                    id: '',
                    text: 'Choose Main Department'
                }];
                let vm = this;
                let id = event.target.value;
                if (this.companies.length > 0 && this.companies !== null) {
                    axios.get('/api/companies/' + id + '/departments').then((res) => {
                        // console.log(res);
                        res.data.data.forEach(department => {
                            vm.departments.push({
                                id: department.id,
                                text: department.name,
                            })
                        })
                        this.departmentLoading = false;
                        this.companySelected = true;
                    }).catch(err => {
                        this.departmentLoading = false;
                        console.log(err);
                    });

                }
            },

        }
    })
</script>

<script>
    $(document).ready(function() {
        $('.use-select2').select2({
            width: '100%',
        });
    })
</script>
@endsection