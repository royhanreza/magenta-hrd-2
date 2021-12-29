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
            <h2 class="pageheader-title">Company </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="/company" class="breadcrumb-link">Company</a></li>
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
              <h5 class="card-header">Add New Company</h5>
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="company-name">Company Name<sup class="text-danger">*</sup></label>
                    <input v-model="name" type="text" class="form-control form-control-sm" id="company-name" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="registration-number">Registration Number</label>
                    <input v-model="registrationNumber" type="text" class="form-control form-control-sm" id="registration-number">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="contact-number">Contact Number</label>
                    <input v-model="contactNumber" type="text" class="form-control form-control-sm" id="contact-number">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input v-model="email" type="email" class="form-control form-control-sm" id="email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="website">Website</label>
                  <input v-model="website" type="text" class="form-control form-control-sm" id="website">
                </div>
                <div class="form-group">
                  <label for="npwp">NPWP</label>
                  <input v-model="npwp" type="text" class="form-control form-control-sm" id="npwp">
                </div>
                <div class="form-group">
                  <label for="address">Address<sup class="text-danger">*</sup></label>
                  <textarea v-model="address" name="address" id="address" class="form-control form-control-sm"></textarea>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-5">
                    <label for="province">State/Province<sup class="text-danger">*</sup></label>
                    <input v-model="province" type="text" class="form-control" id="province" required>
                  </div>
                  <div class="form-group col-md-5">
                    <label for="city">City<sup class="text-danger">*</sup></label>
                    <input v-model="city" type="text" class="form-control" id="city" required>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="zip-code">Zip Code<sup class="text-danger">*</sup></label>
                    <input v-model="zipCode" type="text" class="form-control" id="zip-code" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="country">Country<sup class="text-danger">*</sup></label>
                  <input v-model="country" type="text" class="form-control form-control-sm" id="country" value="Indonesia" required>
                </div>
                <div class="row mt-3">
                  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <!-- .card -->
                    <div class="card card-figure">
                      <!-- .card-figure -->
                      <figure class="figure">
                        <!-- .figure-img -->
                        <div class="figure-img">
                          <img class="img-fluid" src="{{asset('images/card-img.jpg')}}" alt="Card image cap">
                          <div class="figure-tools">
                            <a href="#" class="tile tile-circle tile-sm mr-auto"><span class="oi oi-data-transfer-download"></span></a>
                            <span class="badge badge-warning">Company Logo</span>
                          </div>
                          <div class="figure-action">
                            <a href="#" class="btn btn-block btn-sm btn-primary">Choose Image</a>
                          </div>
                        </div>
                        <!-- /.figure-img -->
                        <figcaption class="figure-caption">
                          <ul class="list-inline d-flex text-muted mb-0">
                            <li class="list-inline-item mr-auto">
                              <span class="oi oi-paperclip"></span> Logo
                            </li>
                            <li class="list-inline-item">
                              <span class="oi oi-calendar"></span>
                            </li>
                          </ul>
                        </figcaption>
                      </figure>
                      <!-- /.card-figure -->
                    </div>
                    <!-- /.card -->
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
      name: '{{ $company->name }}',
      registrationNumber: '{{ $company->registration_number }}',
      contactNumber: '{{ $company->contact_number }}',
      email: '{{ $company->email }}',
      website: '{{ $company->website }}',
      npwp: '{{ $company->npwp }}',
      address: `{{ $company->address }}`,
      province: '{{ $company->province }}',
      city: '{{ $company->city }}',
      zipCode: '{{ $company->zip_code }}',
      country: '{{ $company->country }}',
      loading: false,
      url: '/company',
    },
    methods: {
      submitForm: function() {
        // console.log('submitted');
        let vm = this;
        vm.loading = true;
        axios.patch('/company/{{ $company->id }}', {
            name: this.name,
            registration_number: this.registrationNumber,
            contact_number: this.contactNumber,
            email: this.email,
            website: this.website,
            npwp: this.npwp,
            address: this.address,
            province: this.province,
            city: this.city,
            zip_code: this.zipCode,
            country: this.country,
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
      }
    }
  })
</script>
@endsection