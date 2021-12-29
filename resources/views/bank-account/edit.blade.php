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
                        <h2 class="pageheader-title">Bank Account </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/company" class="breadcrumb-link">Bank Account</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                        <h5 class="card-header">Add New Bank Account</h5>
                        <div class="card-body">
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="account-owner">Account Owner<sup class="text-danger">*</sup></label>
                              <input v-model="accountOwner" type="text" class="form-control form-control-sm" id="account-owner" required>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="account-number">Account Number<sup class="text-danger">*</sup></label>
                              <input v-model="accountNumber" type="text" class="form-control form-control-sm" id="account-number" required>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="bank-name">Bank Name<sup class="text-danger">*</sup></label>
                              <input v-model="bankName" type="text" class="form-control form-control-sm" id="bank-name" required>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="bank-code">Bank Code</label>
                              <input v-model="bankCode" type="text" class="form-control form-control-sm" id="bank-code">
                            </div>
                          </div>
                          <div class="form-group">
                              <label for="bank-branch">Bank Branch</label>
                              <input v-model="bankBranch" type="text" class="form-control form-control-sm" id="bank-branch">
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
      accountOwner: '{{ $bank_account->account_owner }}',
      accountNumber: '{{ $bank_account->account_number }}',
      bankName: '{{ $bank_account->bank_name }}',
      bankCode: '{{ $bank_account->bank_code }}',
      bankBranch: '{{ $bank_account->bank_branch }}',
      loading: false,
      url: '/bank-account',
    },
    methods: {
      submitForm: function() {
        // console.log('submitted');
        let vm = this;
        vm.loading = true;
        axios.patch('/bank-account/{{ $bank_account->id }}', {
          account_owner: this.accountOwner,
          account_number: this.accountNumber,
          bank_name: this.bankName,
          bank_code: this.bankCode,
          bank_branch: this.bankBranch,
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