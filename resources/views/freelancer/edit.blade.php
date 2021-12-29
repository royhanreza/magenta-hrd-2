@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.css') }}"> -->
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/buttons.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/select.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/css/fixedHeader.bootstrap4.css') }}"> -->
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
                        <h2 class="pageheader-title">Freelancer </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/freelancer" class="breadcrumb-link">Freelancer</a></li>
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
                  <form  autocomplete="off" @submit.prevent="submitForm">
                    <div class="card">
                        <h5 class="card-header">Add New Freelancer</h5>
                        <div class="card-body">
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="employee-id">Employee ID</label>
                                    <input v-model="employeeId" type="text" class="form-control" id="employee-id" readonly>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="identity-number">Identity Number<sup class="text-danger">*</sup></label>
                                    <input v-model="identityNumber" type="text" class="form-control" id="identity-number">
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="first-name">First Name<sup class="text-danger">*</sup></label>
                                    <input v-model="firstName" type="text" class="form-control" id="first-name" required>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="last-name">Last Name<sup class="text-danger">*</sup></label>
                                    <input v-model="lastName" type="text" class="form-control" id="last-name" required>
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="email">Email<sup class="text-danger">*</sup></label>
                                    <input v-model="email" type="email" class="form-control" id="email" required>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="contact-number">Contact Number<sup class="text-danger">*</sup></label>
                                    <input v-model="contactNumber" type="text" class="form-control" id="contact-number" required>
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="gender">Gender<sup class="text-danger">*</sup></label>
                                    <select v-model="gender" name="gender" id="gender" class="form-control" required>
                                      <option value="male">Male</option>
                                      <option value="female">Female</option>
                                    </select>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="marital-status">Marital Status<sup class="text-danger">*</sup></label>
                                    <select v-model="maritalStatus" name="marital-status" id="marital-status" class="form-control" required>
                                      <option value="single">Single</option>
                                      <option value="married">Married</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="date-of-birth">Date of Birth<sup class="text-danger">*</sup></label>
                                    <input v-model="dateOfBirth" type="date" class="form-control" name="date-of-birth" id="date-of-birth" required>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="status">Status<sup class="text-danger">*</sup></label>
                                    <select v-model="status" name="status" id="status" class="form-control">
                                      <option value="1">Active</option>
                                      <option value="0">Inactive</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="date-joining">Date Joining<sup class="text-danger">*</sup></label>
                                    <input v-model="dateJoining" type="date" class="form-control" name="date-joining" id="date-joining" required>
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="religion">Religion<sup class="text-danger">*</sup></label>
                                    <select v-model="religion" name="religion" id="religion" class="form-control">
                                      <option value="islam">Islam</option>
                                      <option value="kristen">Kristen</option>
                                      <option value="hindu">Hindu</option>
                                      <option value="buddha">Buddha</option>
                                    </select>
                                  </div>
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
                                  <label for="address">Address<sup class="text-danger">*</sup></label>
                                  <input v-model="address" type="text" class="form-control" id="address" required>
                                </div>
                                <div class="row mt-3">
                                  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                      <!-- .card -->
                                      <div class="card card-figure">
                                          <!-- .card-figure -->
                                          <figure class="figure">
                                              <!-- .figure-img -->
                                              <div class="figure-img">
                                                  <img class="img-fluid" src="{{asset('images/avatar-illustration-1.png')}}" alt="Card image cap">
                                                  <div class="figure-tools">
                                                      <a href="#" class="tile tile-circle tile-sm mr-auto"><span class="oi oi-data-transfer-download"></span></a>
                                                      <span class="badge badge-warning">Profile Picture</span>
                                                  </div>
                                                  <div class="figure-action">
                                                      <a href="#" class="btn btn-block btn-sm btn-primary">Choose Image</a>
                                                  </div>
                                              </div>
                                              <!-- /.figure-img -->
                                              <figcaption class="figure-caption">
                                                  <ul class="list-inline d-flex text-muted mb-0">
                                                      <li class="list-inline-item mr-auto">
                                                          <span class="oi oi-paperclip"></span> Photo </li>
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
                                                      <span class="badge badge-warning">Identity Card</span>
                                                  </div>
                                                  <div class="figure-action">
                                                      <a href="#" class="btn btn-block btn-sm btn-primary">Choose Image</a>
                                                  </div>
                                              </div>
                                              <!-- /.figure-img -->
                                              <figcaption class="figure-caption">
                                                  <ul class="list-inline d-flex text-muted mb-0">
                                                      <li class="list-inline-item mr-auto">
                                                          <span class="oi oi-paperclip"></span> KTP / NPWP </li>
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
<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script> -->
<!-- <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script> -->
<!-- main js -->
<script src="{{ asset('libs/js/main-js.js') }}"></script>
@endsection

@section('pagescript')
<script>
  let app = new Vue({
    el: '#app',
    data: {
        employeeId: '{{ $freelancer->employee_id }}',
        identityNumber: '{{ $freelancer->identity_number }}',
        firstName: '{{ $freelancer->first_name }}',
        lastName: '{{ $freelancer->last_name }}',
        email: '{{ $freelancer->email }}',
        contactNumber: '{{ $freelancer->contact_number }}',
        gender: '{{ $freelancer->gender }}',
        maritalStatus: '{{ $freelancer->marital_status }}',
        dateOfBirth: '{{ $freelancer->date_of_birth }}',
        status: '{{ $freelancer->status }}',
        dateJoining: '{{ $freelancer->date_joining }}',
        religion: '{{ $freelancer->religion }}',
        address: '{{ $freelancer->address }}',
        province: '{{ $freelancer->province }}',
        city: '{{ $freelancer->city }}',
        zipCode: '{{ $freelancer->zip_code }}',
        // country: 'Indonesia',
        // photo: '',
        // identityImage: '',
        loading: false,
        url: '/freelancer'
    },
    methods: {
      submitForm: function() {
        // console.log('submitted');
        let vm = this;
        vm.loading = true;
        axios.patch('/freelancer/{{ $freelancer->id }}', {
          employee_id: this.employeeId,
          identity_number: this.identityNumber,
          first_name: this.firstName,
          last_name: this.lastName,
          email: this.email,
          contact_number: this.contactNumber,
          gender: this.gender,
          marital_status: this.maritalStatus,
          date_of_birth: this.dateOfBirth,
          status: this.status,
          date_joining: this.dateJoining,
          religion: this.religion,
          address: this.address,
          province: this.province,
          city: this.city,
          zip_code: this.zipCode,
          // country: this.country,
          // photo: this.photo,
          // identity_image: this.identityImage,
        })
        .then(function (response) {
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