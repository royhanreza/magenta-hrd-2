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

  .col-form-label {
    font-size: 13px;
    white-space: normal;
  }

  .input-group-text {
    line-height: 0.5;
  }

  .form-group.row label {
    white-space: normal;
  }

  .input-date-bs:read-only {
    background-color: #fff;
  }

  select.form-control {
    background-position: 97% 52%;
  }
</style>
@endsection

@section('content')
@php
$userLoginPermissions = [];
if (request()->session()->has('userLoginPermissions')) {
$userLoginPermissions = request()->session()->get('userLoginPermissions');
}
@endphp
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
            <h2 class="pageheader-title">Setting </h2>
            <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                  <li class="breadcrumb-item">Setting</li>
                  <li class="breadcrumb-item active" aria-current="page">BPJS</li>
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
      <div class="row">
        <!-- ============================================================== -->
        <!-- basic table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="card">
            <!-- 1 -->
            <div class="card-header d-flex bg-light">
              <h5 class="card-header-title">1. Berapakah Upah Minimum Provinsi (UMP) yang berlaku di perusahaan Anda ?</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
              </div>
            </div>
            <div class="card-body">
              @if(in_array("editBpjsSetting", $userLoginPermissions))
              <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#formUmp" aria-expanded="false" aria-controls="formUmp"><i class="fas fa-fw fa-plus"></i> Add Pendapatan</button>
              </div>
              @endif
              <div id="formUmp" class="collapse">
                <form @submit.prevent="addWage" class="pb-3">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Name</label>
                      <input type="text" v-model="wageModel.add.name" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Amount</label>
                      <input type="number" v-model="wageModel.add.value" class="form-control text-right">
                    </div>
                  </div>
                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingWage"><span v-if="loadingWage" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                  </div>
                </form>
              </div>
              <table class="table">
                <thead style="background-color: #d1ecf1">
                  <tr>
                    <th scope="col">Nama UMP</th>
                    <th scope="col">Nilai</th>
                    <th scope="col" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!--<tr is="province-wage" v-for="(wage, index) in wages" :key="wage.id" :id="wage.id" :index="index" :name="wage.name" :value="wage.value" :ondelete="deleteWage" :onopenmodal="openEditWageModal"></tr>-->
                  <!-- <tr>
                    <td>Bandung</td>
                    <td>Rp. 3.750.000</td>
                    <td class="text-center">
                      <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="" class="btn btn-sm btn-light"><i class="fas fa-fw fa-pencil-alt"></i></a>
                        <button type="button" class="btn btn-sm btn-light btn-delete" data-id=""><i class="fas fa-fw fa-trash"></i></button>
                      </div>
                    </td>
                  </tr> -->
                </tbody>
              </table>
            </div>
            <!-- End: 1 -->
            <!-- Begin: 2 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">2. Apakah perusahaan Anda menerapkan BPJS Ketenagakerjaan?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editPphSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form2" aria-expanded="false" aria-controls="form2"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i> @{{ (hasBpjsKetenagakerjaanSummary) ? 'Ya' : 'Tidak' }}
              </div>
              <div id="form2" class="collapse">
                <form @submit.prevent="editBpjsKetenagakerjaan">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasBpjsKetenagakerjaan" value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasBpjsKetenagakerjaan" value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>

                  <div v-if="hasBpjsKetenagakerjaan == 1" class="p-3">
                    <div class="row bg-light">
                      <h3 class="p-3">Silakan masukkan informasi terkait BPJS Ketenagakerjaan di perusahaan Anda.</h3>
                      <div class="col-md-6 col-xs-12">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">NPP</label>
                          <div class="col-sm-7">
                            <input type="text" v-model="ketenagakerjaan.npp" class="form-control form-control-sm">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Basis Pengali</label>
                          <div class="col-sm-7">
                            <select v-model="ketenagakerjaan.baseMultiplier" class="form-control form-control-sm">
                              <option value="gaji_pokok">Gaji Pokok</option>
                              <option value="gaji_pokok_tunjangan">Gaji Pokok + Tunjangan</option>
                              <option value="ump">Upah Minimum Provinsi (UMP)</option>
                              <option value="lainnya">Basis Pengali Lainnya</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Jaminan Kecelakaan Kerja (JKK)</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <select v-model="ketenagakerjaan.jkk" class="form-control form-control-sm">
                                <option value="0.24">Kel I 0.24</option>
                                <option value="0.54">Kel II 0.54</option>
                                <option value="0.89">Kel III 0.89</option>
                                <option value="1.27">Kel IV 1.27</option>
                                <option value="1.74">Kel V 1.74</option>
                                <option value="0.0024">Kel I - Relaksasi Iuran Covid-19 0.0024</option>
                                <option value="0.0054">Kel II - Relaksasi Iuran Covid-19 0.0054</option>
                                <option value="0.0089">Kel III - Relaksasi Iuran Covid-19 0.0089</option>
                                <option value="0.0127">Kel IV - Relaksasi Iuran Covid-19 0.0127</option>
                                <option value="0.0174">Kel V - Relaksasi Iuran Covid-19 0.0174</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Jaminan Kematian (JKM)</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <select v-model="ketenagakerjaan.jkm" class="form-control form-control-sm">
                                <option value="0.30">JKM 0.30</option>
                                <option value="0.0030">JKM - Relaksasi Iuran Covid-19 0.0030</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Jaminan Hari Tua (JHT) (Ditanggung Perusahaan)</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <select v-model="ketenagakerjaan.jhtCompany" class="form-control form-control-sm">
                                <option value="3.7">3.7</option>
                                <option value="5.7">5.7</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Jaminan Hari Tua (JHT) (Ditanggung Pegawai)</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <input v-model="ketenagakerjaan.jhtEmployee" type="text" class="form-control form-control-sm" readonly>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Apakah JHT (Ditanggung Perusahaan) dihitung PPh 21?</label>
                          <div class="col-sm-7">
                            <select v-model="ketenagakerjaan.isJhtCompanyPph" class="form-control form-control-sm">
                              <option value="0">Tidak</option>
                              <option value="1">Ya</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6 col-xs-12">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Apakah perusahaan memakai JP (Jaminan Pensiun) atau tidak?</label>
                          <div class="col-sm-7">
                            <select v-model="ketenagakerjaan.hasJp" class="form-control form-control-sm">
                              <option value="1">Ya</option>
                              <option value="0">Tidak</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Apakah JP Ditanggung perusahaan dihitung PPh 21?</label>
                          <div class="col-sm-7">
                            <select v-model="ketenagakerjaan.isJpPph" class="form-control form-control-sm">
                              <option value="0">Tidak</option>
                              <option value="1">Ya</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Jaminan Pensiun (JP) (Ditanggung Perusahaan)</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <select v-model="ketenagakerjaan.jpCompany" class="form-control form-control-sm">
                                <option value="2">2</option>
                                <option value="3">3</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Jaminan Pensiun (JP) (Ditanggung Pegawai)</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <input type="text" v-model="ketenagakerjaan.jpEmployee" class="form-control form-control-sm" readonly>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Nilai Maksimal Pengali JP</label>
                          <div class="col-sm-7">
                            <input type="text" v-model="ketenagakerjaan.maxJpMultiplier" class="form-control form-control-sm" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Apakah untuk pegawai berkewarganegaraan asing dihitung JP atau tidak?</label>
                          <div class="col-sm-7">
                            <select v-model="ketenagakerjaan.isForeignerHasJp" class="form-control form-control-sm">
                              <option value="0">Tidak</option>
                              <option value="1">Ya</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Apakah untuk personalia dengan usia > 57 tahun dihitung JP atau tidak?</label>
                          <div class="col-sm-7">
                            <select v-model="ketenagakerjaan.isOldEmployeeHasJp" class="form-control form-control-sm">
                              <option value="0">Tidak</option>
                              <option value="1">Ya</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Peraturan ini mulai berlaku</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <input type="date" v-model="ketenagakerjaan.effectiveDate" class="form-control form-control-sm">
                              <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-calendar-alt" style="line-height: 0;"></i></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingBpjsKetenagakerjaan"><span v-if="loadingBpjsKetenagakerjaan" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form2" aria-expanded="false" aria-controls="form2">Cancel</button>
                  </div>
                </form>
              </div>

            </div>
            <!-- End: 2 -->
            <!-- Begin: 3 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">3. Apakah perusahaan Anda menerapkan BPJS Kesehatan?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i> @{{ (hasBpjsKesehatanSummary) ? 'Ya' : 'Tidak' }}
              </div>
              <div id="form3" class="collapse">
                <form @submit.prevent="editBpjsKesehatan">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasBpjsKesehatan" value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasBpjsKesehatan" value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>
                  <div v-if="hasBpjsKesehatan == 1" class="p-3">
                    <div class="row bg-light">
                      <h3 class="p-3">Silakan masukkan informasi terkait BPJS Kesehatan di perusahaan Anda.</h3>
                      <div class="col-md-6 col-xs-12">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Kode Badan Usaha</label>
                          <div class="col-sm-7">
                            <input type="text" v-model="kesehatan.businessCode" class="form-control form-control-sm">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Persentase Tanggungan Perusahaan</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <select v-model="kesehatan.companyPercentage" class="form-control form-control-sm">
                                <option value="5">5</option>
                                <option value="4">4</option>
                              </select>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Persentase Tanggungan Pegawai</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <input type="text" v-model="kesehatan.employePercentage" class="form-control form-control-sm" readonly>
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Basis Pengali</label>
                          <div class="col-sm-7">
                            <select v-model="kesehatan.baseMultiplier" class="form-control form-control-sm">
                              <option value="gaji_pokok">Gaji Pokok</option>
                              <option value="gaji_pokok_tunjangan">Gaji Pokok + Tunjangan</option>
                              <option value="ump">Upah Minimum Provinsi (UMP)</option>
                              <option value="lainnya">Basis Pengali Lainnya</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Nilai Maksimal Pengali BPJS Kesehatan</label>
                          <div class="col-sm-7">
                            <input type="text" v-model="kesehatan.maxMultiplier" class="form-control form-control-sm" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Peraturan ini mulai berlaku</label>
                          <div class="col-sm-7">
                            <div class="input-group mb-3">
                              <input type="text" v-model="kesehatan.effectiveDate" class="form-control form-control-sm" readonly>
                              <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-calendar-alt" style="line-height: 0;"></i></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingBpjsKesehatan"><span v-if="loadingBpjsKesehatan" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 3 -->
          </div>

        </div>
        <!-- ============================================================== -->
        <!-- end basic table  -->
        <!-- ============================================================== -->
        <!-- scrollspy  -->
        <!-- ============================================================== -->
        <!-- <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
          <div class="sidebar-nav-fixed">
            <ul class="list-unstyled">
              <li><a href="#overview" class="active">Overview</a></li>
              <li><a href="#cards">Cards</a></li>
              <li><a href="#image-card">Card Images</a></li>
            </ul>
          </div>
        </div> -->
        <!-- scrollspy  -->
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
<!-- Toast -->
<div class="modal fade" id="editWageModal" tabindex="-1" role="dialog" aria-labelledby="editWageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editWageModalLabel">Edit Komponen Potongan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="editWage(wageEditIndex, wageEditId)" class="pb-3">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Name</label>
              <input type="text" v-model="wageModel.edit.name" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label>Amount</label>
              <input type="number" v-model="wageModel.edit.value" class="form-control text-right">
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingWageEdit"><span v-if="loadingWageEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; top: 70px;">
  <div ref="toast" id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header">
      <strong class="mr-5"><i class="fa fa-fw" v-bind:class="toastType == 'success' ? 'fa-check text-success' : 'fa-times text-danger'"></i> @{{ toastTitle }}</strong>
      <button type="button" class="ml-5 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body pr-5">
      @{{toastText}}
    </div>
  </div>
</div>
<!-- End:Toast -->
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
  Vue.component('province-wage', {
    props: ['id', 'index', 'name', 'value', 'ondelete', 'onopenmodal'],
    template: `
    <tr>
      <td>@{{ name }}</td>
      <td>@{{ value }}</td>
      <td class="text-center">
        @if(in_array("editPphSetting", $userLoginPermissions))
        <div class="btn-group" role="group" aria-label="Action Buttons">
          <button class="btn btn-sm btn-light" @click="onopenmodal(index, id)"><i class="fas fa-fw fa-pencil-alt"></i></button>
          <button type="button" class="btn btn-sm btn-light" @click="ondelete(index, id)"><i class="fas fa-fw fa-trash"></i></button>
        </div>
        @endif
      </td>
    </tr>
    `,
  })

  let app = new Vue({
    el: '#app',
    data: {
      wages: JSON.parse('{!! $wages !!}'),
      loadingWage: false,
      loadingWageEdit: false,
      wageEditId: null,
      wageEditIndex: null,
      wageModel: {
        add: {
          name: '',
          value: '',
        },
        edit: {
          name: '',
          value: '',
        }
      },
      hasBpjsKetenagakerjaan: Number('{{$bpjs_ketenagakerjaan->has_bpjs_ketenagakerjaan}}'),
      hasBpjsKesehatan: Number('{{ $bpjs_kesehatan->has_bpjs_kesehatan }}'),
      ketenagakerjaan: {
        npp: '{{ $bpjs_ketenagakerjaan->npp }}',
        baseMultiplier: '{{ $bpjs_ketenagakerjaan->base_multiplier }}',
        isCompareSalaryUmp: '{{ $bpjs_ketenagakerjaan->is_compare_salary_ump }}',
        jkk: '{{ $bpjs_ketenagakerjaan->jkk }}',
        jkm: '{{ $bpjs_ketenagakerjaan->jkm }}',
        jhtCompany: '{{ $bpjs_ketenagakerjaan->jht_company }}',
        jhtEmployee: '{{ $bpjs_ketenagakerjaan->jht_employee }}',
        isJhtCompanyPph: '{{ $bpjs_ketenagakerjaan->is_jht_company_pph }}',
        hasJp: '{{ $bpjs_ketenagakerjaan->has_jp }}',
        isJpPph: '{{ $bpjs_ketenagakerjaan->is_jp_pph }}',
        jpCompany: '{{ $bpjs_ketenagakerjaan->jp_company }}',
        jpEmployee: '{{ $bpjs_ketenagakerjaan->jp_employee }}',
        maxJpMultiplier: '{{ $bpjs_ketenagakerjaan->max_jp_multiplier }}',
        isForeignerHasJp: '{{ $bpjs_ketenagakerjaan->is_foreigner_has_jp }}',
        isOldEmployeeHasJp: '{{ $bpjs_ketenagakerjaan->is_old_employee_has_jp }}',
        effectiveDate: '{{ $bpjs_ketenagakerjaan->effective_date }}',
      },
      loadingBpjsKetenagakerjaan: false,
      loadingBpjsKesehatan: false,
      kesehatan: {
        businessCode: '{{ $bpjs_kesehatan->business_code }}',
        companyPercentage: '{{ $bpjs_kesehatan->company_percentage }}',
        employeePercentage: '{{ $bpjs_kesehatan->employee_percentage }}',
        baseMultiplier: '{{ $bpjs_kesehatan->base_multiplier }}',
        maxMultiplier: '{{ $bpjs_kesehatan->max_multiplier }}',
        effectiveDate: '{{ $bpjs_kesehatan->effective_date }}',
      },
      hasBpjsKetenagakerjaanSummary: Number('{{$bpjs_ketenagakerjaan->has_bpjs_ketenagakerjaan}}'),
      hasBpjsKesehatanSummary: Number('{{ $bpjs_kesehatan->has_bpjs_kesehatan }}'),
      toast: false,
      toastType: 'success',
      toastTitle: 'Success',
      toastText: 'Task has been saved',
    },
    methods: {
      addWage: function() {
        let vm = this;
        vm.loadingWage = true;
        axios.post('/province-wage', {
            name: this.wageModel.add.name,
            value: this.wageModel.add.value,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingWage = false;
            vm.showToast('Success', 'Minimum wage has been added', 'success');
            vm.wages.push(response.data.data);
            // vm.resetSalaryIncomeAdd();
          })
          .catch(function(error) {
            vm.showToast('Error', 'Failed to add Minimum wage', 'error');
            vm.loadingWage = false;
            console.log(error);
          });
      },
      openEditWageModal: function(index, id) {
        this.wageEditId = id;
        this.wageEditIndex = index;
        this.wageModel.edit.name = this.wages[index].name;
        this.wageModel.edit.value = this.wages[index].value;
        $('#editWageModal').modal('show');
      },
      editWage: function(index, id) {
        let vm = this;
        vm.loadingWageEdit = true;
        axios.patch('/province-wage/' + id, {
            name: this.wageModel.edit.name,
            value: this.wageModel.edit.value,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingWageEdit = false;
            // vm.salaryIncomes.push(response.data.data);
            vm.wages[index] = response.data.data;
            $('#editWageModal').modal('hide');
            vm.resetWageEdit();
            vm.showToast('Success', 'UMP berhasil diubah', 'success');
          })
          .catch(function(error) {
            vm.showToast('Error', 'UMP gagal diubah', 'error');
            vm.loadingWageEdit = false;
            console.log(error);
          });
      },
      resetWageEdit: function() {
        this.wageModel.edit.name = '';
        this.wageModel.edit.value = '';
      },
      deleteWage: function(index, id) {
        let vm = this;

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
            return axios.delete('/province-wage/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Failed to remove wage', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Salary income has been removed', 'success');
            vm.wages.splice(index, 1);
          }
        })
      },
      editBpjsKetenagakerjaan: function() {
        const vm = this;
        vm.loadingBpjsKetenagakerjaan = true;
        const data = {
          has_bpjs_ketenagakerjaan: this.hasBpjsKetenagakerjaan,
          npp: this.ketenagakerjaan.npp,
          base_multiplier: this.ketenagakerjaan.baseMultiplier,
          is_compare_salary_ump: this.ketenagakerjaan.isCompareSalaryUmp,
          jkk: this.ketenagakerjaan.jkk,
          jkm: this.ketenagakerjaan.jkm,
          jht_company: this.ketenagakerjaan.jhtCompany,
          jht_employee: this.ketenagakerjaan.jhtEmployee,
          is_jht_company_pph: this.ketenagakerjaan.isJhtCompanyPph,
          has_jp: this.ketenagakerjaan.hasJp,
          is_jp_pph: this.ketenagakerjaan.isJpPph,
          jp_company: this.ketenagakerjaan.jpCompany,
          jp_employee: this.ketenagakerjaan.jpEmployee,
          max_jp_multiplier: this.ketenagakerjaan.maxJpMultiplier,
          is_foreigner_has_jp: this.ketenagakerjaan.isForeignerHasJp,
          is_old_employee_has_jp: this.ketenagakerjaan.isOldEmployeeHasJp,
          effective_date: this.ketenagakerjaan.effectiveDate,
        };
        axios.patch(`/setting/bpjs/{{ $bpjs_ketenagakerjaan->id }}/ketenagakerjaan`,
          data
        ).then(function(response) {
          // console.log(response)
          vm.loadingBpjsKetenagakerjaan = false;

          vm.showToast('Success', 'Changes has been saved', 'success');
        }).catch(function(error) {
          vm.showToast('Error', 'Failed to save changes', 'error');
          vm.loadingBpjsKetenagakerjaan = false;
          console.log(error);
        })
      },
      editBpjsKesehatan: function() {
        const vm = this;
        vm.loadingBpjsKesehatan = true;
        const data = {
          has_bpjs_kesehatan: this.hasBpjsKesehatan,
          business_code: this.kesehatan.businessCode,
          company_percentage: this.kesehatan.companyPercentage,
          employee_percentage: this.kesehatan.employeePercentage,
          base_multiplier: this.kesehatan.baseMultiplier,
          max_multiplier: this.kesehatan.maxMultiplier,
          effective_date: this.kesehatan.effectiveDate,
        };
        axios.patch(`/setting/bpjs/{{ $bpjs_kesehatan->id }}/kesehatan`,
          data
        ).then(function(response) {
          // console.log(response)
          vm.loadingBpjsKesehatan = false;
          vm.showToast('Success', 'Changes has been saved', 'success');
        }).catch(function(error) {
          vm.showToast('Error', 'Failed to save changes', 'error');
          vm.loadingBpjsKesehatan = false;
          console.log(error);
        })
      },
      showToast: function(title, text, type = 'success') {
        this.toastTitle = title;
        this.toastText = text;
        this.toastType = type;
        $('#liveToast').toast('show');
      },
      hideToast: function() {
        // this.toast = false;
      },
    }
  })
</script>
@endsection