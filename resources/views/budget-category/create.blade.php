@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
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
                        <h2 class="pageheader-title">Budget Category </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/company" class="breadcrumb-link">Budget Category</a></li>
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
                        <h5 class="card-header">Add New Budget Category</h5>
                        <div class="card-body">
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="name">Name<sup class="text-danger">*</sup></label>
                              <input v-model="name" type="text" class="form-control form-control-sm" id="name" required>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="type">Type<sup class="text-danger">*</sup></label>
                              <select v-model="type" id="type" class="form-control form-control-sm" required>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                              </select>
                            </div>
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
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
<!-- additional script -->
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  let app = new Vue({
    el: '#app',
    data: {
      name: '',
      type: 'income',
      loading: false,
      url: '/budget-category',
    },
    methods: {
      submitForm: function() {
        // console.log('submitted');
        let vm = this;
        vm.loading = true;
        axios.post('/budget-category', {
          name: this.name,
          type: this.type,
        })
        .then(function (response) {
            vm.loading = false;
            Swal.fire({
                title: 'Success',
                text: 'Your data has been saved',
                icon: 'success',
                allowOutsideClick: false,
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = vm.url;
              }
            })
            // console.log(response);
        })
        .catch(function (error) {
            vm.loading = false;
            console.log(error);
            Swal.fire(
                'Oops!',
                'Something wrong',
                'error'
            )
            
        });
      }
    }
  })
</script>
@endsection