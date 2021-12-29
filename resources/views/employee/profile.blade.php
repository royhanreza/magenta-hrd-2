@php
$userLoginPermissions = [];
if (request()->session()->has('userLoginPermissions')) {
$userLoginPermissions = request()->session()->get('userLoginPermissions');
}
@endphp
<div class="card">
  <div class="card-body rounded" style="background-image: url('/images/employee-detail-bg.jpg');background-size: cover; background-position: center">
    <!-- <div class="rounded" style="background-image: url('/images/employee-detail-bg.jpg'); padding: 1.25rem;"> -->
    <div class="row px-3 align-items-center">
      <div class="col-md-4">
        <div class="d-flex align-items-center">
          <div style="min-width: 100px; min-height: 100px; border-radius: 50%; border: 2px solid #fff; background-image: url('{{ ($employee->photo !== null) ? Storage::disk("s3")->url($employee->photo) : "https://cabdindikwil1.com/wp-content/uploads/2020/12/male.png" }}'); background-position: center; background-size: cover;"></div>
          <div class="ml-3 text-white">
            <h4 class="m-0 text-white">{{ $employee->first_name }}</h4>
            @if( $last_career !== null && $last_career->jobTitle !== null )
            <span class="d-block my-1" style="color: #ecf0f1">{{ $last_career->jobTitle->name }}</span>
            @else
            <a href="/career/create/{{$employee->id}}" class="d-inline-block my-1" style="color: #ecf0f1; text-decoration: underline; text-decoration-style: dotted;">Atur Karir <i class="fas fa-pencil-alt fa-xs"></i></a><br>
            @endif

            @if($employee->is_active == 1)
            <span class="badge badge-success">Aktif</span>
            @else
            <span class="badge badge-danger">Nonaktif</span>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-8 text-white">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <i class="far fa-calendar-alt"></i>
            <span class="d-block" style="color: #ecf0f1;">Tanggal Mulai Kerja</span>
            <h5 class="m-0 text-white">{{ \Carbon\Carbon::parse($employee->start_work_date)->isoFormat('LL') }}</h5>
          </div>
          <div>
            <i class="far fa-flag"></i>
            <span class="d-block" style="color: #ecf0f1;">Status Karyawan</span>
            @if( $last_career !== null )
            <h5 class="m-0 text-white">{{ $last_career->employee_status }}</h5>
            @else
            <a href="/career/create/{{$employee->id}}" class="d-inline-block my-1" style="color: #ecf0f1; text-decoration: underline; text-decoration-style: dotted;">
              <h5 class="m-0 text-white">Atur Karir <i class="fas fa-pencil-alt fa-xs"></i></h5>
            </a>
            @endif
          </div>
          <div>
            <i class="far fa-star"></i>
            <span class="d-block" style="color: #ecf0f1;">Divisi</span>
            @if( $last_career !== null )
            <h5 class="m-0 text-white">{{ $last_career->designation->name }}</h5>
            @else
            @if(in_array("addEmployeeCareer", $userLoginPermissions))
            <a href="/career/create/{{$employee->id}}" class="d-inline-block my-1" style="color: #ecf0f1; text-decoration: underline; text-decoration-style: dotted;">
              <h5 class="m-0 text-white">Atur Karir <i class="fas fa-pencil-alt fa-xs"></i></h5>
            </a>
            @endif
            @endif

          </div>
        </div>
      </div>
    </div>
    @if(in_array("editEmployee", $userLoginPermissions))
    <div class="d-flex flex-row-reverse px-3">
      <a href="/employee/edit/{{ $employee->id }}" class="btn btn-rounded btn-primary btn-sm px-4"><i class="fas fa-pencil-alt fa-xs"></i> <span>Edit Pegawai</span></a>
      @if($employee->is_active == 1)
      <a href="#" @click.prevent="inactivateEmployee({{ $employee->id }})" class="btn btn-rounded btn-danger btn-sm px-4 mr-3"><i class="fas fa-ban fa-xs"></i> <span>Nonaktifkan Pegawai</span></a>
      @else
      <a href="#" @click.prevent="activateEmployee({{ $employee->id }})" class="btn btn-rounded btn-success btn-sm px-4 mr-3"><i class="fas fa-user fa-xs"></i> <span>Aktifkan Pegawai</span></a>
      @endif
    </div>
    @endif
    <!-- </div> -->
  </div>
</div>