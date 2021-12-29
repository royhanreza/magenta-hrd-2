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
                  <li class="breadcrumb-item active" aria-current="page">Company</li>
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
      <div class="card">
        <div class="card-body">
          <nav class="nav flex-column flex-sm-row">
            <a class="flex-sm-fill text-sm-center nav-link active" href="/company">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-building fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Company</span><br>
                  <small class="text-muted">Manage Companies</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/company-location">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-map fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Location</span><br>
                  <small class="text-muted">Manage Locations</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/company-department">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-star fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Department</span><br>
                  <small class="text-muted">Manage Departments</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/company-designation">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-clone fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Division</span><br>
                  <small class="text-muted">Manage Divisions</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/company-announcement">
              <div class="d-flex justify-content-start align-items-center">
                <div>
                  <i class="far fa-fw fa-bell fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Announcement</span><br>
                  <small class="text-muted">Broadcast announcement</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/company-policy">
              <div class="d-flex justify-content-start align-items-center">
                <div>
                  <i class="far fa-fw fa-check-square fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Policy</span><br>
                  <small class="text-muted">Manage Policies</small>
                </div>
              </div>
            </a>
          </nav>
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- end page nav  -->
      <!-- ============================================================== -->
      <div class="row">
        <!-- ============================================================== -->
        <!-- basic table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="card">
            <div class="card-header d-flex">
              <h5 class="card-header-title">List All Companies</h5>
              <div class="toolbar ml-auto">
                <a href="{{ url('company/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Company</th>
                      <th>Contact</th>
                      <th>Email</th>
                      <th>City</th>
                      <th>Country</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($companies as $company)
                    <tr>
                      <td>{{ $company->name }}</td>
                      <td>{{ $company->contact_number }}</td>
                      <td>{{ $company->email }}</td>
                      <td>{{ $company->city }}</td>
                      <td>{{ $company->country }}</td>
                      <td class="text-center" style="width: 15%;">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <a href="{{ url('company/edit') . '/' . $company->id }}" class="btn btn-sm btn-light"><i class="fas fa-fw fa-pencil-alt"></i></a>
                          <button type="button" class="btn btn-sm btn-light btn-delete" data-id="{{ $company->id }}"><i class="fas fa-fw fa-trash"></i></button>
                          <!-- <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Other">
                              <i class="fas fa-fw fa-cog"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="#">Print</a>
                            </div>
                          </div> -->
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot class="text-center">
                    <tr>
                      <th>Company</th>
                      <th>Contact</th>
                      <th>Email</th>
                      <th>City</th>
                      <th>Country</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
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
  $(function() {
    $('table.use-datatable').DataTable();

    $('.btn-delete').on('click', function() {
      const id = $(this).attr('data-id');
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
          return axios.delete('/company/' + id)
            .then(function(response) {
              console.log(response.data);
            })
            .catch(function(error) {
              console.log(error.data);
              Swal.fire({
                icon: 'error',
                title: 'Oops',
                text: 'Something wrong',
              })
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Data has been deleted',
          })
        }
      })
    })
  })
</script>
@endsection