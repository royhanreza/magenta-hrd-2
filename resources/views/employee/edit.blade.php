@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link href="{{ asset('vendor/select2/css/select2.css') }}" rel="stylesheet" />
<!-- <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" /> -->
@endsection

@section('title', 'Magenta HRD')

@section('pagestyle')
<style>
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
            <!-- <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p> -->
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="/employee" class="breadcrumb-link">Employee</a></li>
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

          <form autocomplete="off" enctype="multipart/form-data" @submit.prevent="submitForm">
            <div class="card">
              <div class="card-header bg-light mb-0">
                <h5 class="card-title mb-0">Form Tambah Pegawai</h5>
              </div>
              <!-- Basic Information -->
              <div class="card-body">
                <div class="section-block m-0">
                  <h3 class="section-title">Informasi Pribadi</h3>
                  <p>Berisi informasi pribadi pegawai</p>
                </div>
                <div class="row justify-content-between">
                  <div class="col-md-9">
                    <div class="form-group row justify-content-between">
                      <label for="employee-id" class="col-sm-3 col-form-label">ID Pegawai<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <!-- <input v-model="employeeId" type="text" class="form-control form-control-sm" id="employee-id" required readonly> -->
                        <div class="row">
                          <div class="col">
                            <select v-model="prefixId" class="form-control form-control-sm" required>
                              <option value="MM">MM (Magenta Mediatama)</option>
                              <option value="UL">UL (Unilabel)</option>
                              <option value="SRC">SRC</option>
                              <option value="BIS">BIS</option>
                              <option value="EO">EO</option>
                              <option value="OELLO">OELLO</option>
                            </select>
                          </div>
                          <div>
                            <span>-</span>
                          </div>
                          <div class="col">
                            <input type="text" v-model="yearId" class="form-control form-control-sm" readonly>
                          </div>
                          <div>
                            <span>-</span>
                          </div>
                          <div class="col">
                            <input type="text" v-model="maxId" class="form-control form-control-sm" readonly>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="first-name" class="col-sm-3 col-form-label">Nama<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <input v-model="firstName" type="text" class="form-control form-control-sm" required>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Tanggal Mulai Bekerja<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <input type="date" v-model="startWorkDate" class="form-control form-control-sm" required>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label for="gender" class="col-sm-3 col-form-label">Jenis Kelamin<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="gender" name="" class="form-control form-control-sm" required>
                          <option value="">Pilih Jenis Kelamin</option>
                          <option value="male">Pria</option>
                          <option value="female">Wanita</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Kewarganegaraan<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="citizenship" class="form-control form-control-sm">
                          <option value="wni">WNI</option>
                          <option value="wna">WNA</option>
                        </select>
                      </div>
                    </div>

                    <div v-if="citizenship == 'wna' && citizenship !== ''" class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Negara<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="citizenshipCountry" class="form-control form-control-sm" required>
                          <option value="Amerika Serikat">Amerika Serikat</option>
                          <option value="Jepang">Jepang</option>
                          <option value="Inggris">Inggris</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Identitas Diri<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="identityType" class="form-control form-control-sm" required>
                          <option value="ktp">KTP</option>
                          <option value="passport">Passport</option>
                          <option value="kitas">KITAS</option>
                          <option value="kitap">KITAP</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">No Identitas<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <input v-model="identityNumber" type="text" class="form-control form-control-sm" required>
                      </div>
                    </div>

                    <div v-if="identityType !== 'ktp'" class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Tanggal akhir berlaku identitas<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <input type="date" v-model="identityExpireDate" class="form-control form-control-sm" required>
                      </div>
                    </div>
                    <!-- <div class="form-group row justify-content-between">
                      <label for="last-name" class="col-sm-3 col-form-label">Last Name<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <input v-model="lastName" type="text" class="form-control form-control-sm" required>
                      </div>
                    </div> -->

                    <!-- <div class="form-group row justify-content-between">
                      <label for="company-name" class="col-sm-3 col-form-label bg-danger">Perusahaan<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select2 v-model="company" :options="companies" class="form-control form-control-sm" @selected="onChangeCompany" required></select2>
                      </div>
                    </div> -->

                    <!-- <div class="form-group row justify-content-between">
                      <label for="company-name" class="col-sm-3 col-form-label bg-danger">Departemen<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select2 v-model="department" :options="departments" class="form-control form-control-sm" v-bind:disabled="!companySelected || departmentLoading" @selected="onChangeDepartment" required></select2>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="registration-number" class="col-sm-3 col-form-label bg-danger">Divisi<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select2 v-model="designation" :options="designations" class="form-control form-control-sm" v-bind:disabled="!departmentSelected || designationLoading" required></select2>
                      </div>
                    </div> -->

                    <div class="form-group row justify-content-between">
                      <label for="place-of-birth" class="col-sm-3 col-form-label">Tempat Lahir<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <input type="text" v-model="placeOfBirth" class="form-control form-control-sm" required>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label for="date-of-birth" class="col-sm-3 col-form-label">Tanggal Lahir<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <input v-model="dateOfBirth" type="date" class="form-control form-control-sm" required>
                      </div>
                    </div>

                    <!-- <div class="form-group row justify-content-between">
                      <label for="registration-number" class="col-sm-3 col-form-label">Kantor<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select2 v-model="location" :options="locations" class="form-control form-control-sm" v-bind:disabled="!companySelected || locationLoading" required></select2>
                      </div>
                    </div> -->

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Status Perkawinan</label>
                      <div class="col-sm-9">
                        <select v-model="maritalStatus" class="form-control form-control-sm">
                          <option value="lajang">Lajang</option>
                          <option value="menikah">Menikah</option>
                          <option value="duda">Duda</option>
                          <option value="janda">Janda</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Agama</label>
                      <div class="col-sm-9">
                        <select v-model="religion" class="form-control form-control-sm">
                          <option value="islam">Islam</option>
                          <option value="kristen">Kristen</option>
                          <option value="katolik">Katolik</option>
                          <option value="hindu">Hindu</option>
                          <option value="buddha">Buddha</option>
                          <option value="khonghucu">Khonghucu</option>
                          <option value="lainnya">Lainnya</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Golongan Darah</label>
                      <div class="col-sm-9">
                        <select v-model="bloodType" class="form-control form-control-sm">
                          <option value="A">A</option>
                          <option value="A-">A-</option>
                          <option value="A+">A+</option>
                          <option value="B">B</option>
                          <option value="B-">B-</option>
                          <option value="B+">B+</option>
                          <option value="AB">AB</option>
                          <option value="AB-">AB-</option>
                          <option value="AB+">AB+</option>
                          <option value="O">O</option>
                          <option value="O-">O-</option>
                          <option value="O+">O+</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Pendidikan Terakhir</label>
                      <div class="col-sm-9">
                        <select v-model="lastEducation" class="form-control form-control-sm">
                          <option value=""></option>
                          <option value="SD">SD</option>
                          <option value="SMP">SMP</option>
                          <option value="SMA">SMA</option>
                          <option value="SMEA">SMEA</option>
                          <option value="SMK">SMK</option>
                          <option value="STM">STM</option>
                          <option value="D1">D1</option>
                          <option value="D2">D2</option>
                          <option value="D3">D3</option>
                          <option value="D4">D4</option>
                          <option value="S1">S1</option>
                          <option value="S2">S2</option>
                          <option value="S3">S3</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Nama Institusi Pendidikan</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="lastEducationName" class="form-control form-control-sm">
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Jurusan / Program Studi</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="studyProgram" class="form-control form-control-sm">
                      </div>
                    </div>

                    <!-- <div class="form-group row justify-content-between">
                      <label for="office-shift" class="col-sm-3 col-form-label">Shift Kerja</label>
                      <div class="col-sm-9">
                        <select2 v-model="officeShift" :options="officeShifts" class="form-control form-control-sm" v-bind:disabled="!companySelected || officeShiftLoading"></select2>
                      </div>
                    </div> -->

                    <!-- <div class="form-group row justify-content-between">
                      <label for="report-to" class="col-sm-3 col-form-label">Atasan</label>
                      <div class="col-sm-9">
                        <select2 v-model="reportTo" :options="reportToOptions" class="form-control form-control-sm"></select2>
                      </div>
                    </div> -->

                    <!-- <div class="form-group row justify-content-between">
                      <label for="leave-category" class="col-sm-3 col-form-label">Leave Category</label>
                      <div class="col-sm-9">
                        <select2 v-model="leaveCategory" :options="leaveCategories" class="form-control form-control-sm"></select2>
                      </div>
                    </div> -->
                    <div class="form-group row justify-content-between">
                      <label for="work-placement" class="col-sm-3 col-form-label">Work Placement<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="workPlacement" id="work-placement" class="form-control form-control-sm" required>
                          <option value="">-Pilih Work Placement-</option>
                          <option value="office">Office</option>
                          <option value="event organizer">Event Organizer</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label for="type" class="col-sm-3 col-form-label">Tipe Pegawai<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="type" id="type" class="form-control form-control-sm" required>
                          <option value="">-Pilih Tipe Pegawai-</option>
                          <option value="staff">Staff</option>
                          <option value="non staff">Non Staff</option>
                          <option value="freelancer">Freelancer</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="type" class="col-sm-3 col-form-label">Izinkan Lihat Slip Gaji<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="payslipPermission" class="form-control form-control-sm" required>
                          <option value="1">Ya</option>
                          <option value="0">Tidak</option>
                        </select>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-2"></div>
                </div>
              </div>
              <!-- END::Basic Information -->

              <!-- Contact Information -->
              <div class="card-body border-top">
                <div class="section-block m-0 mb-4">
                  <h3 class="section-title">Informasi Kontak</h3>
                  <p>Berisi informasi kontak pegawai</p>
                </div>
                <div class="row">
                  <div class="col-md-9">
                    <div class="form-group row justify-content-between">
                      <label for="email" class="col-sm-3 col-form-label">Email</label>
                      <div class="col-sm-9">
                        <input v-model="email" ref="emailInput" type="email" class="form-control form-control-sm" v-bind:class="{ 'is-invalid': emailExist }" v-on:input="onInputEmail">
                        <div class="invalid-feedback">
                          Email sudah digunakan
                        </div>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="contact-number" class="col-sm-3 col-form-label">No. HP</label>
                      <div class="col-sm-9">
                        <input v-model="contactNumber" type="text" class="form-control form-control-sm" id="contact-number">
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="address" class="col-sm-3 col-form-label">Alamat</label>
                      <div class="col-sm-9">
                        <textarea v-model="address" class="form-control form-control-sm"></textarea>
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Nama Kontak Darurat</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="emergencyContactName" class="form-control form-control-sm">
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Hubungan Kontak Darurat</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="emergencyContactRelation" class="form-control form-control-sm">
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Telepon Darurat</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="emergencyContactNumber" class="form-control form-control-sm">
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <!-- END::Contact Information -->

              <!-- Bank Account Information -->
              <div class="card-body border-top">
                <div class="section-block m-0 mb-4">
                  <h3 class="section-title">Rekening Bank</h3>
                  <p>Berisi informasi rekening bank pegawai</p>
                </div>
                <div class="row">
                  <div class="col-md-9">

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Nama Bank</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="bankAccountName" class="form-control form-control-sm">
                      </div>
                    </div>

                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Nama Pemegang Rekening</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="bankAccountOwner" class="form-control form-control-sm">
                      </div>
                    </div>


                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">No Rekening</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="bankAccountNumber" class="form-control form-control-sm">
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label class="col-sm-3 col-form-label">Kantor Cabang Bank</label>
                      <div class="col-sm-9">
                        <input type="text" v-model="bankAccountBranch" class="form-control form-control-sm">
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <!-- END::Bank Account Information -->

              <!-- Other Information -->
              <div class="card-body border-top">
                <div class="section-block m-0 mb-4">
                  <h3 class="section-title">Informasi Lainnya</h3>
                  <p>Berisi informasi tambahan pegawai</p>
                </div>
                <div class="row">
                  <div class="col-md-9">
                    <div class="form-group row justify-content-between">
                      <label for="type" class="col-sm-3 col-form-label">Izinkan Lihat Slip Gaji<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="payslipPermission" class="form-control form-control-sm" required>
                          <option value="1">Ya</option>
                          <option value="0">Tidak</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="type" class="col-sm-3 col-form-label">Lokasi Kantor<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="officeLocation" class="form-control form-control-sm" required>
                          <option value="">-Lokasi Kantor-</option>
                          @if(count($locations) > 0)
                          @foreach($locations as $location)
                          <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                    </div>
                    <div class="form-group row justify-content-between">
                      <label for="type" class="col-sm-3 col-form-label">Ganti Foto</label>
                      <div class="col-sm-9">
                        <input type="file" ref="image" v-on:change="handleFileUpload" accept=".jpg, .jpeg, .png" class="form-control form-control-sm">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3"></div>
                      <div class="col-sm-9">
                        <div style="width: 100px; height: 120px;" class="p-1 border">
                          <img src="{{ ($employee->photo !== null) ? Storage::disk('s3')->url($employee->photo) : '' }}" alt="" style="height: 100%; width: 100%; object-fit: cover">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END::Other Information -->


              <!-- Account Information -->
              <!-- <div class="card-body border-top">
                <div class="section-block m-0 mb-4">
                  <div class="row justify-content-between">
                    <div class="col-md-9">
                      <h3 class="section-title">Informasi Akun</h3>
                      <p>Berisi informasi yang akan digunakan untuk akses aplikasi Magenta HRD (Web & Mobile)</p>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" v-model="withoutAccount" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px"><strong>Jangan buat akun</strong></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" v-if="!withoutAccount">
                  <div class="col-md-9">
                    <div class="form-group row">
                      <label for="username" class="col-sm-3 col-form-label">Username<sup class="text-danger">*</sup> (3 Karakter atau lebih)</label>
                      <div class="col-sm-9">
                        <input v-model="username" ref="usernameInput" type="text" class="form-control form-control-sm" minlength="3" v-bind:class="{ 'is-invalid': usernameExist }" v-on:input="onInputUsername" required>
                        <div class="invalid-feedback">
                          Username sudah digunakan
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="password" class="col-sm-3 col-form-label">Password<sup class="text-danger">*</sup> (8 Karakter atau lebih)</label>
                      <div class="col-sm-9">
                        <input v-model="password" type="text" class="form-control form-control-sm" minlength="8" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="confirm-password" class="col-sm-3 col-form-label">Konfirmasi Password<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <input v-model="confirmPassword" ref="confirmPasswordInput" type="text" class="form-control form-control-sm" v-on:input="onInputConfirmPassword" v-bind:class="{ 'is-invalid': confirmPasswordIsInvalid }" required>
                        <div class="invalid-feedback">
                          Konfirmasi password tidak sesuai
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="pin-code" class="col-sm-3 col-form-label">PIN (6 Digit)</label>
                      <div class="col-sm-9">
                        <input v-model="pinCode" type="number" class="form-control form-control-sm" minlength="6" maxlength="6">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="role" class="col-sm-3 col-form-label">Role<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select2 v-model="role" :options="roles" class="form-control form-control-sm" v-bind:disabled="!companySelected || departmentLoading" required></select2>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" v-model="mobileAccess"><span class="custom-control-label" style="margin-top: 3px"><strong>Izinkan akses ke aplikasi mobile</strong></span>
                      </label>
                    </div>
                    <div class="form-group row" v-if="mobileAccess">
                      <label for="" class="col-sm-3 col-form-label">Tipe akses<sup class="text-danger">*</sup></label>
                      <div class="col-sm-9">
                        <select v-model="mobileAccessType" class="form-control form-control-sm" required>
                          <option value="">Pilih tipe akses</option>
                          <option value="employee">Regular</option>
                          <option value="admin">Admin</option>
                        </select>
                        <span v-if="mobileAccessType == 'admin'" class="d-block mt-1"><em>Pegawai dapat mengkakses aplikasi mobile sebagai admin</em></span>
                      </div>
                    </div>

                  </div>
                </div>
              </div> -->
              <!-- END::Account Information -->


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
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<!-- slimscroll js -->
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
<!-- additional script -->
<!-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> -->
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
          vm.$emit('selected', this.value);
          vm.$emit("input", this.value);
        });
    },
    watch: {
      // value: function(value) {
      // // update value
      // $(this.$el)
      //     .val(value)
      //     .trigger("change");
      // },
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
    data: {
      prefixId: '{{ explode("-", $employee->employee_id)[0] }}',
      yearId: '{{ explode("-", $employee->employee_id)[1] }}',
      maxId: '{{ explode("-", $employee->employee_id)[2] }}',
      photo: '',
      officeLocation: '{{ $employee->company_location_id }}',
      companySelected: false,
      employeeId: '{{ $employee->employee_id }}',
      firstName: '{{ $employee->first_name }}',
      // lastName: '',
      // company: '',
      startWorkDate: '{{ $employee->start_work_date }}',
      citizenship: '{{ $employee->citizenship }}',
      citizenshipCountry: '{{ $employee->citizenship_country }}',
      identityType: '{{ $employee->identity_type }}',
      identityNumber: '{{ $employee->identity_number }}',
      identityExpireDate: '{{ $employee->identity_expire_date }}',
      placeOfBirth: '{{ $employee->place_of_birth }}',
      maritalStatus: '{{ $employee->marital_status }}',
      religion: '{{ $employee->religion }}',
      bloodType: '{{ $employee->blood_type }}',
      lastEducation: '{{ $employee->last_education }}',
      lastEducationName: '{{ $employee->last_education_name }}',
      studyProgram: '{{ $employee->study_program }}',
      emergencyContactName: '{{ $employee->emergency_contact_name }}',
      emergencyContactRelation: '{{ $employee->emergency_contact_relation }}',
      emergencyContactNumber: '{{ $employee->emergency_contact_number }}',
      bankAccountName: '{{ $employee->bank_account_name }}',
      bankAccountOwner: '{{ $employee->bank_account_owner }}',
      bankAccountNumber: '{{ $employee->bank_account_number }}',
      bankAccountBranch: '{{ $employee->bank_account_branch }}',
      dateOfBirth: '{{ $employee->date_of_birth }}',
      contactNumber: '{{ $employee->contact_number }}',
      address: `{{ $employee->address }}`,
      gender: '{{ $employee->gender }}',
      workPlacement: '{{ $employee->work_placement }}',
      type: '{{ $employee->type }}',
      email: '{{ $employee->email }}',

      locations: [{
        id: '',
        text: 'Choose Office Location'
      }],
      location: '',
      locationLoading: false,
      departments: [{
        id: '',
        text: 'Choose Main Department'
      }],
      department: '',
      departmentLoading: false,
      departmentSelected: false,
      designations: [{
        id: '',
        text: 'Choose Division'
      }],
      designation: '',
      designationLoading: false,

      officeShifts: [{
        id: '',
        text: 'Choose Shift'
      }],
      officeShift: '',
      officeShiftLoading: false,
      reportToOptions: [{
        id: '',
        text: 'Choose Employee'
      }, {
        id: 1,
        text: 'Rifan'
      }],
      reportTo: '',
      leaveCategories: [{
        id: '',
        text: 'Choose Leave Category'
      }, {
        id: 1,
        text: 'Casual'
      }],
      leaveCategory: '',

      payslipPermission: 1,
      username: '',
      usernameExist: false,

      emailExist: false,
      password: '',
      confirmPassword: '',
      pinCode: '',
      roles: [{
        id: '',
        text: 'Choose Role'
      }],
      role: '',
      roleLoading: false,
      loading: false,
      confirmPasswordIsInvalid: false,
      withoutAccount: false,
      mobileAccess: false,
      mobileAccessType: 'employee',
      isActive: 1,
      url: '/employee',
    },
    methods: {
      handleFileUpload: function() {
        this.photo = this.$refs.image.files[0];
      },
      previewFiles(event) {
        this.photo = event.target.files[0];
      },
      onInputConfirmPassword: function() {
        if (this.confirmPasswordIsInvalid) {
          this.confirmPasswordIsInvalid = false;
        }
        // if(this.confirmPasswordIsInvalid) {
        //   if(this.confirmPassword !== this.password) {
        //     this.confirmPasswordIsInvalid = true;
        //   } else {
        //     this.confirmPasswordIsInvalid = false;
        //   }
        // }
      },
      onInputEmail: function() {
        if (this.emailExist) {
          this.emailExist = false;
        }
      },
      onInputUsername: function() {
        if (this.usernameExist) {
          this.usernameExist = false;
        }
        // if(this.confirmPasswordIsInvalid) {
        //   if(this.confirmPassword !== this.password) {
        //     this.confirmPasswordIsInvalid = true;
        //   } else {
        //     this.confirmPasswordIsInvalid = false;
        //   }
        // }
      },
      submitForm: function() {
        // console.log('submitted');
        if (this.confirmPassword !== this.password) {
          this.confirmPasswordIsInvalid = true;
          this.$refs.confirmPasswordInput.focus();
        } else {
          this.sendData();
        }

      },
      sendData: function() {
        let vm = this;
        vm.loading = true;

        let data = {
          // last_name: this.lastName,
          // company_id: this.company,
          // location_id: this.location,
          // department_id: this.department,
          // designation_id: this.designation,
          // report_to: this.reportTo,
          // leave_id: this.leaveCategory,
          // office_shift_id: this.officeShift,
          photo: this.photo,
          employee_id: vm.finalId,
          first_name: this.firstName,
          place_of_birth: this.placeOfBirth,
          date_of_birth: this.dateOfBirth,
          gender: this.gender,
          start_work_date: this.startWorkDate,
          citizenship: this.citizenship,
          citizenship_country: this.citizenshipCountry,
          identity_type: this.identityType,
          identity_number: this.identityNumber,
          identity_expire_date: this.identityExpireDate,
          marital_status: this.maritalStatus,
          religion: this.religion,
          blood_type: this.bloodType,
          last_education: this.lastEducation,
          last_education_name: this.lastEducationName,
          study_program: this.studyProgram,
          emergency_contact_name: this.emergencyContactName,
          emergency_contact_relation: this.emergencyContactRelation,
          emergency_contact_number: this.emergencyContactNumber,
          bank_account_name: this.bankAccountName,
          bank_account_owner: this.bankAccountOwner,
          bank_account_number: this.bankAccountNumber,
          bank_account_branch: this.bankAccountBranch,
          contact_number: this.contactNumber,
          work_placement: this.workPlacement,
          type: this.type,
          payslip_permission: this.payslipPermission,
          office_location: this.officeLocation,
          address: this.address,
          email: this.email,
          is_active_account: 0,
          is_active: this.isActive,
        }

        let formData = new FormData();
        for (var key in data) {
          formData.append(key, data[key]);
        }

        axios.post('/employee/{{ $employee->id }}', formData)
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
            // console.log(error.response);
            if (error.response.status == 400) {
              if (error.response.data.error_type == 'exist_credential') {
                error.response.data.errors.forEach(error => {
                  // if (error.field == 'username') {
                  //   vm.usernameExist = true;
                  //   vm.$refs.usernameInput.focus();
                  // }
                  if (error.field == 'email') {
                    vm.emailExist = true;
                    vm.$refs.emailInput.focus();
                  }
                })
              }
            } else {
              Swal.fire(
                'Oops!',
                'Something wrong',
                'error'
              )
            }

          });
      },
      onChangeCompany: function(id) {
        this.departmentLoading = true;
        this.departments = [{
          id: '',
          text: 'Choose Main Department'
        }];
        this.locationLoading = true;
        this.locations = [{
          id: '',
          text: 'Choose Office Location'
        }];
        this.officeShiftLoading = true;
        this.officeShifts = [{
          id: '',
          text: 'Choose Office Location'
        }];
        this.roleLoading = true;
        this.roles = [{
          id: '',
          text: 'Choose Role'
        }];
        this.designationLoading = true;
        this.designations = [{
          id: '',
          text: 'Choose Division'
        }];
        let vm = this;
        // let id = event.target.value;
        if (this.companies.length > 0 && this.companies !== null) {
          axios.get('/api/companies/' + id + '/locations').then((res) => {
            // console.log(res);
            res.data.data.forEach(location => {
              vm.locations.push({
                id: location.id,
                text: location.location_name,
              })
            })
            this.locationLoading = false;
            this.companySelected = true;
          }).catch(err => {
            this.locationLoading = false;
            console.log(err);
          });

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

          axios.get('/api/companies/' + id + '/office-shifts').then((res) => {
            // console.log(res);
            res.data.data.forEach(shift => {
              vm.officeShifts.push({
                id: shift.id,
                text: shift.name,
              })
            })
            this.officeShiftLoading = false;
            this.companySelected = true;
          }).catch(err => {
            this.officeShiftLoading = false;
            console.log(err);
          });

          axios.get('/api/companies/' + id + '/roles').then((res) => {
            // console.log(res);
            res.data.data.forEach(role => {
              vm.roles.push({
                id: role.id,
                text: role.name,
              })
            })
            this.roleLoading = false;
            this.companySelected = true;
          }).catch(err => {
            this.roleLoading = false;
            console.log(err);
          });
        }
      },
      onChangeDepartment: function(id) {
        this.designationLoading = true;
        this.designations = [{
          id: '',
          text: 'Choose Division'
        }];
        let vm = this;
        // let id = event.target.value;
        if (this.designations.length > 0 && this.designations !== null) {
          axios.get('/api/departments/' + id + '/designations').then((res) => {
            // console.log(res);
            res.data.data.forEach(designation => {
              vm.designations.push({
                id: designation.id,
                text: designation.name,
              })
            })
            this.designationLoading = false;
            this.departmentSelected = true;
          }).catch(err => {
            this.designationLoading = false;
            console.log(err);
          });
        }
      },
      onInputwithoutAccount: function() {
        if (this.withoutAccount) {
          // this.email = '';
          this.username = '';
          this.password = '';
          this.confirmPassword = '';
          this.pinCode = '';
          this.role = '';
          this.mobileAccess = false;
        }
      }

    },
    computed: {
      finalId: function() {
        return `${this.prefixId}-${this.yearId}-${this.maxId}`;
      }
    }
  })
</script>
<script>

</script>
@endsection