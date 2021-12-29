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
            <h2 class="pageheader-title">Freelancer </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Freelancer</li>
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
            <a class="flex-sm-fill text-sm-center nav-link" href="/quotation-event">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-handshake fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Quotation</span><br>
                  <small class="text-muted">List All Quotation Event</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/event">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-calendar-check fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Event</span><br>
                  <small class="text-muted">Manage Events</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link" href="/mapping-event">
              <div class="d-flex justify-content-start align-items-center border-right">
                <div>
                  <i class="far fa-fw fa-list-alt fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Mapping Event</span><br>
                  <small class="text-muted">Manage Event's Budget, Member etc.</small>
                </div>
              </div>
            </a>
            <a class="flex-sm-fill text-sm-center nav-link active" href="/freelancer">
              <div class="d-flex justify-content-start align-items-center">
                <div>
                  <i class="far fa-fw fa-user-circle fa-2x"></i>
                </div>
                <div class="text-left ml-2">
                  <span>Freelancer</span><br>
                  <small class="text-muted">Manage Freelancers</small>
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
              <h5 class="card-header-title">List All Freelancers</h5>
              <div class="toolbar ml-auto">
                <a href="{{ url('freelancer/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-plus"></i> Add New</a>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered use-datatable">
                  <thead class="bg-light text-center">
                    <tr>
                      <th>Employee ID</th>
                      <th>Identity Number</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Contact Number</th>
                      <th>City</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($freelancers as $freelancer)
                    <tr>
                      <td>{{ $freelancer->freelancer_id }}</td>
                      <td>{{ $freelancer->identity_number }}</td>
                      <td>{{ $freelancer->first_name . ' ' . $freelancer->last_name }}</td>
                      <td>{{ $freelancer->email }}</td>
                      <td>{{ $freelancer->contact_number }}</td>
                      <td class="text-capitalize">{{ $freelancer->city }}</td>
                      <td class="text-center">
                        @if($freelancer->status == 1)
                        <span class="badge-dot badge-success"></span>Active
                        @else
                        <span class="badge-dot badge-danger"></span>Inactive
                        @endif
                      </td>
                      <td class="text-center" style="width: 15%;">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                          <a href="{{ url('freelancer/edit') . '/' . $freelancer->id }}" class="btn btn-sm btn-light"><i class="fas fa-fw fa-pencil-alt"></i></a>
                          <button type="button" class="btn btn-sm btn-light btn-delete" data-id="{{ $freelancer->id }}"><i class="fas fa-fw fa-trash"></i></button>
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
                      <th>Employee ID</th>
                      <th>Identity Number</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Contact Number</th>
                      <th>City</th>
                      <th>Status</th>
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
          return axios.delete('/freelancer/' + id)
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