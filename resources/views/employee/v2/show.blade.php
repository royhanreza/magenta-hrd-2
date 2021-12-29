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

@section('pagestyle')
<style>
    .pills-regular .nav.nav-pills .nav-item .nav-link {
        font-size: 13px;
    }

    table,
    .table td {
        border: none;
    }

    .col-form-label,
    .form-group.row label {
        font-size: 13px;
        white-space: normal;
    }
</style>
@endsection

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
                        <h2 class="pageheader-title">Employee </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item">Employee</li>
                                    <li class="breadcrumb-item active" aria-current="page">Career</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end pageheader  -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- ============================================================== -->
                <!-- basic table  -->
                <!-- ============================================================== -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    @include('employee.menu')
                    @include('employee.profile')
                    <div class="section-block">
                        <h3 class="section-title">Informasi Pribadi</h3>
                        <!-- NOTE -->
                        <!-- <div class="alert alert-warning">Dev Note:
              <ul>
                <li>Tambah keterangan kategori staff, non staff, atau freelancer</li>
                <li>Tab: Shift</li>
                <li>Tab: Cuti</li>
                <li>Tab: Sakit</li>
                <li>Tab: Izin</li>
                <li>Tab: Kasbon</li>
              </ul>
            </div> -->
                        <!-- NOTE -->
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="p-3">
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Jenis Kelamin</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ ($employee->gender == 'male') ? 'Pria' : 'Wanita' }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Kewarganegaraan</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;" class="text-uppercase">{{ $employee->citizenship }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Negara</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ ($employee->citizenship == 'wni') ? 'Indonesia' : $employee->citizenship_country }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Identitas Diri</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;" class="text-uppercase">{{ $employee->identity_type }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">No. Identitas</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->identity_number }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Tanggal Akhir Berlaku Identitas</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->identity_expire_date }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Tempat Lahir</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->place_of_birth }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Tanggal Lahir</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->date_of_birth }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Status Perkawinan</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;" class="text-capitalize">{{ $employee->marital_status }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Agama</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;" class="text-capitalize">{{ $employee->religion }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Golongan Darah</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->blood_type }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Pendidikan Terakhir</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->last_education }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Nama Institusi Pendidikan</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->last_education_name }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Jurusan/Program Studi</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->study_program }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Work Placement</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;" class="text-capitalize">{{ $employee->work_placement }}</strong>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Tipe Pegawai</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;" class="text-capitalize">{{ $employee->type }}</strong>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="section-block">
                        <h3 class="section-title">Informasi Kontak</h3>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="p-3">
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Email</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->email }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">No.HP</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->contact_number }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Alamat</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->address }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Nama Kontak Darurat</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->emergency_contact_name }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Hubungan Kontak Darurat</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->emergency_contact_relation }}</strong>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Telepon Darurat</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->emergency_contact_number }}</strong>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="section-block">
                        <h3 class="section-title">Rekening Bank</h3>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="p-3">
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Nama Bank</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->bank_account_name }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Nama Pemegang Rekening</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->bank_account_owner }}</strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">No Rekening</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->bank_account_owner }}</strong>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <span style="color: #71748d;">Kantor Cabang Bank</span>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <strong style="color: #000;">{{ $employee->bank_account_branch }}</strong>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="section-block">
                        <h3 class="section-title">Informasi NPWP</h3>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-light btn-sm" data-toggle="collapse" data-target=".npwp" aria-expanded="false" aria-controls="npwp"><i class="fas fa-pencil-alt"></i> Edit</button>
                            </div>
                            <div class="p-3">
                                <!-- Form -->
                                <div class="collapse npwp">
                                    <form @submit.prevent="saveNpwp" class="pb-3">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">NPWP</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="npwp.number" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">Mulai Sejak</label>
                                            <div class="col-sm-9">
                                                <input type="date" v-model="npwp.effectiveDate" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">NPWP Pemotong</label>
                                            <div class="col-sm-9">
                                                <select v-model="npwp.companyNpwp" class="form-control form-control-sm">
                                                    <option value="">-Pilih NPWP Pemotong-</option>
                                                    @foreach($company_npwps as $npwp)
                                                    <option value="{{ $npwp->id }}">{{ $npwp->company_npwp_name }} ({{$npwp->company_npwp_number}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">Status Wajib Pajak</label>
                                            <div class="col-sm-9">
                                                <select v-model="npwp.type" class="form-control form-control-sm">
                                                    @for($i = 0; $i < 4; $i++) <option value="K{{ $i }}">K{{$i}}</option>
                                                        @endfor
                                                        @for($i = 0; $i < 4; $i++) <option value="TK{{ $i }}">TK{{$i}}</option>
                                                            @endfor
                                                            @for($i = 0; $i < 4; $i++) <option value="HB{{ $i }}">HB{{$i}}</option>
                                                                @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingNpwp"><span v-if="loadingNpwp" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                                            <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target=".npwp" aria-expanded="false" aria-controls="npwp">Cancel</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="npwp collapse show">
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xs-12">
                                            <span style="color: #71748d;">NPWP</span>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <strong style="color: #000;">{{ ($employee_npwp !== null) ? $employee_npwp->number : ''  }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xs-12">
                                            <span style="color: #71748d;">Mulai Sejak</span>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <strong style="color: #000;">{{ ($employee_npwp !== null && $employee_npwp->effective_date) ? date_format(date_create($employee_npwp->effective_date), "d/m/Y") : ''  }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xs-12">
                                            <span style="color: #71748d;">NPWP Pemotong</span>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <strong style="color: #000;">{{ ($employee_npwp !== null && $employee_npwp->companyNpwp !== null) ? $employee_npwp->companyNpwp->company_npwp_name . ' (' . $employee_npwp->companyNpwp->company_npwp_number  . ')' : ''  }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xs-12">
                                            <span style="color: #71748d;">Status Wajib Pajak</span>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <strong style="color: #000;">{{ ($employee_npwp !== null) ? $employee_npwp->type : ''  }}</strong>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="section-block">
                        <h3 class="section-title">Informasi BPJS</h3>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-light btn-sm" data-toggle="collapse" data-target=".bpjs" aria-expanded="false" aria-controls="bpjs"><i class="fas fa-pencil-alt"></i> Edit</button>
                            </div>
                            <div class="p-3">
                                <!-- Form -->
                                <div class="collapse bpjs">
                                    <form @submit.prevent="saveBpjs" class="pb-3">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">No KPJ BPJS Ketenagakerjaan</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="bpjs.ketenagakerjaan.number" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">Tanggal Efektif</label>
                                            <div class="col-sm-9">
                                                <input type="date" v-model="bpjs.ketenagakerjaan.effectiveDate" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">No Kartu BPJS Kesehatan</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="bpjs.kesehatan.number" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">Tanggal Efektif</label>
                                            <div class="col-sm-9">
                                                <input type="date" v-model="bpjs.kesehatan.effectiveDate" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingBpjs"><span v-if="loadingBpjs" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                                            <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target=".bpjs" aria-expanded="false" aria-controls="npwp">Cancel</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="bpjs collapse show">
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xs-12">
                                            <span style="color: #71748d;">No KPJ BPJS Ketenagakerjaan</span>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <strong style="color: #000;">{{ ($employee_bpjs !== null) ? $employee_bpjs->bpjs_ketenagakerjaan_number : ''  }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xs-12">
                                            <span style="color: #71748d;">Tanggal Efektif BPJS Ketenagakerjaan</span>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <strong style="color: #000;">{{ ($employee_bpjs !== null && $employee_bpjs->bpjs_ketenagakerjaan_effective_date !== null) ? date_format(date_create($employee_bpjs->bpjs_ketenagakerjaan_effective_date), "d/m/Y") : ''  }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xs-12">
                                            <span style="color: #71748d;">No Kartu BPJS Kesehatan</span>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <strong style="color: #000;">{{ ($employee_bpjs !== null) ? $employee_bpjs->bpjs_kesehatan_number : ''  }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xs-12">
                                            <span style="color: #71748d;">Tanggal Efektif BPJS Kesehatan</span>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <strong style="color: #000;">{{ ($employee_bpjs !== null && $employee_bpjs->bpjs_kesehatan_effective_date) ? date_format(date_create($employee_bpjs->bpjs_kesehatan_effective_date), "d/m/Y") : ''  }}</strong>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="section-block">
                        <h3 class="section-title">File Pegawai</h3>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-light btn-sm" data-toggle="collapse" data-target=".employee-file-collapse" aria-expanded="false" aria-controls="employee-file-collapse"><i class="fas fa-plus"></i> Tambah</button>
                            </div>
                            <div class="p-3">
                                <!-- Form -->
                                <div class="collapse employee-file-collapse">
                                    <form @submit.prevent="uploadEmployeeFile" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="employee-file-name">Nama File</label>
                                                <input type="text" v-model="employeeFileName" class="form-control" id="employee-file-name" placeholder="Nama File">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="employee-file">File</label>
                                                <input type="file" ref="employeeFile" v-on:change="handleFileUpload" accept=".jpg, .png, .jpeg, .pdf" class="form-control" id="employee-file">
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target=".employee-file-collapse" aria-expanded="false" aria-controls="employee-file-collapse">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-primary" :disabled="loadingEmployeeFile"><span v-if="loadingEmployeeFile" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Simpan</button>
                                        </div>
                                    </form>
                                </div>
                                <div v-if="!employeeFiles.length" class="text-center">
                                    <i class="fas fa-file-alt text-muted" style="font-size: 3em;"></i>
                                    <p class="text-muted">Belum Ada File</p>
                                </div>
                                <div class="employee-file-collapse collapse show">
                                    <div v-for="(employeeFile, index) in employeeFiles" class="border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div v-if="employeeFile.extension == 'jpg' || employeeFile.extension == 'jpeg' || employeeFile.extension == 'png'">
                                                        <img :src="employeeFile.url" height="50" alt="">
                                                    </div>
                                                    <div v-else>
                                                        <i class="fas fa-file-alt" style="font-size: 3em;"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h4 class="m-0">@{{ employeeFile.name }}</h4>
                                                        <a :href="employeeFile.url" target="_blank">
                                                            <p class="text-primary">@{{ employeeFile.path }} <i class="fas fa-external-link"></i></p>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="width: 10px;">
                                                <a href="#" @click.prevent="deleteEmployeeFile(employeeFile.id, index)"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            employeeId: '{{ $employee->id }}',
            loadingNpwp: false,
            loadingBpjs: false,
            loadingEmployeeFile: false,
            npwp: {
                number: '{{ ($employee_npwp !== null) ? $employee_npwp->number : ""  }}',
                effectiveDate: '{{ ($employee_npwp !== null) ? $employee_npwp->effective_date : ""  }}',
                companyNpwp: '{{ ($employee_npwp !== null) ? $employee_npwp->company_npwp_id : ""  }}',
                type: '{{ ($employee_npwp !== null) ? $employee_npwp->type : ""  }}',
            },
            bpjs: {
                ketenagakerjaan: {
                    number: '{{ ($employee_bpjs !== null) ? $employee_bpjs->bpjs_ketenagakerjaan_number : ""  }}',
                    effectiveDate: '{{ ($employee_bpjs !== null) ? $employee_bpjs->bpjs_ketenagakerjaan_effective_date : ""  }}',
                    jkk: {
                        company: '',
                        personal: '',
                    },
                    jkm: {
                        company: '',
                        personal: '',
                    },
                    jht: {
                        company: '',
                        personal: '',
                    },
                    jp: {
                        company: '',
                        personal: '',
                    },
                },
                kesehatan: {
                    number: '{{ ($employee_bpjs !== null) ? $employee_bpjs->bpjs_kesehatan_number : ""  }}',
                    effectiveDate: '{{ ($employee_bpjs !== null) ? $employee_bpjs->bpjs_kesehatan_effective_date : ""  }}',
                    company: '',
                    personal: '',
                },
            },
            employeeFiles: JSON.parse(String.raw `{!! $employee_files !!}`),
            employeeFileName: '',
            employeeFile: '',
        },
        methods: {
            saveNpwp: function() {
                // console.log('submitted');
                let vm = this;
                vm.loadingNpwp = true;
                axios.patch('/employee/{{ ($employee_npwp !== null) ? $employee_npwp->id : ""  }}/edit-npwp', {
                        number: vm.npwp.number,
                        effective_date: vm.npwp.effectiveDate,
                        company_npwp_id: vm.npwp.companyNpwp,
                        type: vm.npwp.type,
                    })
                    .then(function(response) {
                        vm.loadingNpwp = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Your data has been saved',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                        // console.log(response);
                    })
                    .catch(function(error) {
                        vm.loadingNpwp = false;
                        console.log(error);
                        Swal.fire(
                            'Oops!',
                            'Something wrong',
                            'error'
                        )

                    });
            },
            saveBpjs: function() {
                // console.log('submitted');
                let vm = this;
                vm.loadingBpjs = true;
                axios.patch('/employee/{{ ($employee_bpjs !== null) ? $employee_bpjs->id : ""  }}/edit-bpjs', {
                        bpjs_ketenagakerjaan_number: vm.bpjs.ketenagakerjaan.number,
                        bpjs_ketenagakerjaan_effective_date: vm.bpjs.ketenagakerjaan.effectiveDate,
                        bpjs_kesehatan_number: vm.bpjs.kesehatan.number,
                        bpjs_kesehatan_effective_date: vm.bpjs.kesehatan.effectiveDate,
                    })
                    .then(function(response) {
                        vm.loadingBpjs = false;
                        Swal.fire({
                            title: 'Success',
                            text: 'Your data has been saved',
                            icon: 'success',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                        // console.log(response);
                    })
                    .catch(function(error) {
                        vm.loadingBpjs = false;
                        console.log(error);
                        Swal.fire(
                            'Oops!',
                            'Something wrong',
                            'error'
                        )

                    });
            },
            handleFileUpload: function() {
                this.employeeFile = this.$refs.employeeFile.files[0];
            },
            uploadEmployeeFile: function() {
                let vm = this;
                vm.loadingEmployeeFile = true;

                const data = {
                    name: vm.employeeFileName,
                    path: vm.employeeFile,
                    employee_id: vm.employeeId
                }

                let formData = new FormData();
                for (var key in data) {
                    formData.append(key, data[key]);
                }

                axios.post('/employee-file', formData)
                    .then(function(response) {
                        console.log(response);
                        vm.loadingEmployeeFile = false;
                        $('.employee-file-collapse').collapse('toggle');
                        vm.employeeFileName = '';
                        vm.employeeFile = '';
                        // if (result.isConfirmed) {
                        //     window.location.href = vm.url;
                        // }
                        vm.employeeFiles.push(response.data.data);
                    })
                    .catch(function(error) {
                        vm.loadingEmployeeFile = false;
                        Swal.fire(
                            'Oops!',
                            'Something wrong',
                            'error'
                        )
                    });
            },
            deleteEmployeeFile: function(id, index) {
                let vm = this;
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "File akan dihapus",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.delete('/employee-file/' + id)
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
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    if (result.isConfirmed) {
                        vm.employeeFiles.splice(index, 1);
                        // Swal.fire({
                        //     icon: 'success',
                        //     title: 'Success',
                        //     text: 'Data has been deleted',
                        // }).then((result) => {
                        //     if (result.isConfirmed) {
                        //         window.location.reload();
                        //     }
                        // })
                    }
                })
            },
            inactivateEmployee: function(id) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Pegawai akan dinonaktifkan",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Nonaktifkan',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.post('/employee/' + id + '/inactivate-employee')
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
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Pegawai telah dinonaktifkan',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            },
            activateEmployee: function(id) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Pegawai akan diaktifkan",
                    icon: 'warning',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aktifkan',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios.post('/employee/' + id + '/activate-employee')
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
                    allowOutsideClick: () => !Swal.isLoading(),
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Pegawai telah diaktifkan',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                })
            },
        }
    })
</script>

@endsection