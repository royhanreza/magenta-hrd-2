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
                  <li class="breadcrumb-item active" aria-current="page">PPh</li>
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
            <!-- Begin: 1 -->
            <div class="card-header d-flex border-top bg-light">
              <h5 class="card-header-title">1. Apakah perusahaan Anda menerapkan perhitungan Pajak PPh 21/26?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editPphSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form1" aria-expanded="false" aria-controls="form1"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i> @{{ hasPphSummary ? 'Ya' : 'Tidak' }}
              </div>
              <div id="form1" class="collapse">
                <form @submit.prevent="changePphSetting(
                    { has_pph: hasPph }, 
                    function(){
                      hasPphSummary = hasPph;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasPph" :value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasPph" :value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form1" aria-expanded="false" aria-controls="form1">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 1 -->
            <!-- Begin: 2 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">2. Input Nama Pemotong dan NPWP Pemotong</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editPphSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form2" aria-expanded="false" aria-controls="#form2"><i class="fas fa-fw fa-plus"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- <div id="form2" class="collapse">
                <form @submit.prevent="changePphSetting(
                    { 
                      npwp_company_name: npwpCompanyName,
                      npwp_company_number: npwpCompanyNumber,
                      npwp_leader_name: npwpLeaderName,
                      npwp_leader_number: npwpLeaderNumber,
                    }, 
                    function(){
                      npwpCompanyNameSummary = npwpCompanyName;
                      npwpCompanyNumberSummary = npwpCompanyNumber;
                      npwpLeaderNameSummary = npwpLeaderName;
                      npwpLeaderNumberSummary = npwpLeaderNumber;
                    }
                  )">
                  <h5>Nama & NPWP Perusahaan/Badan Usaha</h5>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="npwpCompanyName" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">NPWP</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="npwpCompanyNumber" class="form-control">
                    </div>
                  </div>
                  <hr>
                  <h5>Nama & NPWP Pimpinan Perusahaan/Kuasa</h5>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="npwpLeaderName" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">NPWP</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="npwpLeaderNumber" class="form-control">
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form2" aria-expanded="false" aria-controls="form2">Cancel</button>
                  </div>
                </form>
              </div> -->
              <div id="form2" class="collapse">
                <form @submit.prevent="addNpwp" class="pb-3">
                  <h5>Nama & NPWP Perusahaan/Badan Usaha</h5>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="npwpCompanyName" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">NPWP</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="npwpCompanyNumber" class="form-control">
                    </div>
                  </div>
                  <hr>
                  <h5>Nama & NPWP Pimpinan Perusahaan/Kuasa</h5>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="npwpLeaderName" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">NPWP</label>
                    <div class="col-sm-9">
                      <input type="text" v-model="npwpLeaderNumber" class="form-control">
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingNpwp"><span v-if="loadingNpwp" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form2" aria-expanded="false" aria-controls="form2">Cancel</button>
                  </div>
                </form>
              </div>

              <!-- <div class="collapse npwp">
                
              </div> -->

              <div v-for="(npwp, index) in npwps" class="p-3 mx-1 bg-light border mb-3">
                @if(in_array("editPphSetting", $userLoginPermissions))
                <div class="d-flex justify-content-end">
                  <a href="#" @click.prevent="openEditNpwpModal(index, npwp.id)"><i class="fas fa-pencil-alt mr-3"></i></a>
                  <a href="#" @click.prevent="deleteNpwp(index, npwp.id)"><i class="fas fa-trash-alt"></i></a>
                </div>
                @endif
                <div class="row">
                  <div class="col-md-6 col-xs-12">
                    <span>Nama & NPWP Perusahaan/Badan Usaha</span>
                    <table class="table">
                      <tr>
                        <td>Nama</td>
                        <td>@{{ npwp.company_npwp_name }}</td>
                      </tr>
                      <tr>
                        <td>NPWP</td>
                        <td>@{{ npwp.company_npwp_number }}</td>
                      </tr>
                    </table>
                  </div>
                  <div class="col-md-6 col-xs-12">
                    <span>Nama & NPWP Pimpinan Perusahaan/Kuasa</span>
                    <table class="table">
                      <tr>
                        <td>Nama</td>
                        <td>@{{ npwp.leader_npwp_name }}</td>
                      </tr>
                      <tr>
                        <td>NPWP</td>
                        <td>@{{ npwp.leader_npwp_number }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>

              <div v-if="npwps.length < 1" colspan="6" class="text-center">
                <i class="fa fa-fw fa-folder-open fa-3x"></i>
                <h6>Belum ada NPWP</h6>
                <!-- <span>Tekan '+' untuk menambahkan</span> -->
              </div>
              <!-- <div class="p-3 mx-1 bg-light border row mb-3">
                <div class="col-md-6 col-xs-12">
                  <span>Nama & NPWP Perusahaan/Badan Usaha</span>
                  <table class="table">
                    <tr>
                      <td>Nama</td>
                      <td>@{{ npwpCompanyNameSummary }}</td>
                    </tr>
                    <tr>
                      <td>NPWP</td>
                      <td>@{{ npwpCompanyNumberSummary }}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6 col-xs-12">
                  <span>Nama & NPWP Pimpinan Perusahaan/Kuasa</span>
                  <table class="table">
                    <tr>
                      <td>Nama</td>
                      <td>@{{ npwpLeaderNameSummary }}</td>
                    </tr>
                    <tr>
                      <td>NPWP</td>
                      <td>@{{ npwpLeaderNumberSummary }}</td>
                    </tr>
                  </table>
                </div>
              </div> -->

            </div>
            <!-- End: 2 -->
            <!-- Begin: 3 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">3. Metode apa yang digunakan perusahaan Anda dalam menghitung PPh 21/26?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editPphSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="p-3 mx-1 bg-light border row mb-3">
                <div class="col-md-6 col-xs-12">
                  <table class="table">
                    <tr>
                      <td class="border-0">Karyawan Tetap Percobaan</td>
                      <td class="border-0 text-capitalize">@{{ pphMethodKaryawanTetapPercobaanSummary }}</td>
                    </tr>
                    <tr>
                      <td>Karyawan Tetap Permanen</td>
                      <td class="text-capitalize">@{{ pphMethodKaryawanTetapPermanenSummary }}</td>
                    </tr>
                    <tr>
                      <td>Karyawan PKWT</td>
                      <td class="text-capitalize">@{{ pphMethodKaryawanPkwtSummary }}</td>
                    </tr>
                    <tr>
                      <td>Karyawan Lepas</td>
                      <td class="text-capitalize">@{{ pphMethodKaryawanLepasSummary }}</td>
                    </tr>
                  </table>
                </div>
              </div>
              <div id="form3" class="collapse">
                <form @submit.prevent="changePphSetting(
                    { 
                      pph_method_karyawan_tetap_percobaan: pphMethodKaryawanTetapPercobaan,
                      pph_method_karyawan_tetap_permanen: pphMethodKaryawanTetapPermanen,
                      pph_method_karyawan_pkwt: pphMethodKaryawanPkwt,
                      pph_method_karyawan_lepas: pphMethodKaryawanLepas,
                    }, 
                    function(){
                      pphMethodKaryawanTetapPercobaanSummary = pphMethodKaryawanTetapPercobaan;
                      pphMethodKaryawanTetapPermanenSummary = pphMethodKaryawanTetapPermanen;
                      pphMethodKaryawanPkwtSummary = pphMethodKaryawanPkwt;
                      pphMethodKaryawanLepasSummary = pphMethodKaryawanLepas;
                    }
                  )">
                  <table class="table">
                    <thead class="text-center">
                      <tr>
                        <td>Jenis Karyawan</td>
                        <td>Gross Up Method</td>
                        <td>Gross Method</td>
                        <td>Nett Method</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Karyawan Tetap Percobaan</td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanTetapPercobaan" :value="'gross up'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanTetapPercobaan" :value="'gross'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanTetapPercobaan" :value="'nett'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Karyawan Tetap Permanen</td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanTetapPermanen" :value="'gross up'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanTetapPermanen" :value="'gross'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanTetapPermanen" :value="'nett'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Karyawan PKWT</td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanPkwt" :value="'gross up'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanPkwt" :value="'gross'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanPkwt" :value="'nett'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>Karyawan Lepas</td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanLepas" :value="'gross up'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanLepas" :value="'gross'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <label class="custom-control custom-radio">
                              <input type="radio" v-model="pphMethodKaryawanLepas" :value="'nett'" class="custom-control-input"><span class="custom-control-label"></span>
                            </label>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3">Cancel</button>
                  </div>
                </form>

              </div>
            </div>
            <!-- End: 3 -->
            <!-- Begin: 4 -->
            <!-- <div class="card-header d-flex border-top bg-light">
              <h5 class="card-header-title">Informasi PTKP</h5>
            </div>
            <div class="card-body">
              <form action="">
                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label" style="white-space: normal;">Nilai PTKP Diri Wajib Pajak Orang Pribadi</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="ptkpPersonal" class="form-control" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label" style="white-space: normal;">PTKP istri/masing-masing tanggungan</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="ptkpDependants" class="form-control" readonly>
                  </div>
                </div>
              </form>
              <div class="alert alert-success mt-5">
                Note <br> Data Pph 21/26 mulai awal tahun berjalan hingga menggunakan Gadjian dapat diisi sesudah Anda melengkapi Data Personalia
              </div>
            </div> -->
            <!-- End: 4 -->
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
<!-- ============================================================== -->
<!-- end wrapper  -->
<!-- ============================================================== -->
<!-- Toast -->
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

<div class="modal fade" id="editNpwpModal" tabindex="-1" role="dialog" aria-labelledby="editNpwpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editNpwpModalLabel">Edit Komponen Potongan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="editNpwp" class="pb-3">
          <h5>Nama & NPWP Perusahaan/Badan Usaha</h5>
          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
              <input type="text" v-model="npwpCompanyNameEdit" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">NPWP</label>
            <div class="col-sm-9">
              <input type="text" v-model="npwpCompanyNumberEdit" class="form-control">
            </div>
          </div>
          <hr>
          <h5>Nama & NPWP Pimpinan Perusahaan/Kuasa</h5>
          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
              <input type="text" v-model="npwpLeaderNameEdit" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">NPWP</label>
            <div class="col-sm-9">
              <input type="text" v-model="npwpLeaderNumberEdit" class="form-control">
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingNpwpEdit"><span v-if="loadingNpwpEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
            <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form2" aria-expanded="false" aria-controls="form2">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
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
  let app = new Vue({
    el: '#app',
    data: {
      hasPph: parseInt('{{ $setting->has_pph }}'),
      npwps: JSON.parse('{!! $npwps !!}'),

      npwpCompanyName: '{{ $setting->npwp_company_name }}',
      npwpCompanyNumber: '{{ $setting->npwp_company_number }}',
      npwpLeaderName: '{{ $setting->npwp_leader_name }}',
      npwpLeaderNumber: '{{ $setting->npwp_leader_number }}',
      //EDIT
      npwpCompanyNameEdit: '',
      npwpCompanyNumberEdit: '',
      npwpLeaderNameEdit: '',
      npwpLeaderNumberEdit: '',
      npwpEditId: null,
      npwpEditIndex: null,

      pphMethodKaryawanTetapPercobaan: '{{ $setting->pph_method_karyawan_tetap_percobaan }}',
      pphMethodKaryawanTetapPermanen: '{{ $setting->pph_method_karyawan_tetap_permanen }}',
      pphMethodKaryawanPkwt: '{{ $setting->pph_method_karyawan_pkwt }}',
      pphMethodKaryawanLepas: '{{ $setting->pph_method_karyawan_lepas }}',
      ptkpPersonal: '{{ $setting->ptkp_personal }}',
      ptkpDependants: '{{ $setting->ptkp_dependants }}',
      // Begin::summary
      hasPphSummary: parseInt('{{ $setting->has_pph }}'),
      npwpCompanyNameSummary: '{{ $setting->npwp_company_name }}',
      npwpCompanyNumberSummary: '{{ $setting->npwp_company_number }}',
      npwpLeaderNameSummary: '{{ $setting->npwp_leader_name }}',
      npwpLeaderNumberSummary: '{{ $setting->npwp_leader_number }}',
      pphMethodKaryawanTetapPercobaanSummary: '{{ $setting->pph_method_karyawan_tetap_percobaan }}',
      pphMethodKaryawanTetapPermanenSummary: '{{ $setting->pph_method_karyawan_tetap_permanen }}',
      pphMethodKaryawanPkwtSummary: '{{ $setting->pph_method_karyawan_pkwt }}',
      pphMethodKaryawanLepasSummary: '{{ $setting->pph_method_karyawan_lepas }}',
      // End::summary
      loadingNpwp: false,
      loadingNpwpEdit: false,
      loadingSetting: false,
      toast: false,
      toastType: 'success',
      toastTitle: 'Success',
      toastText: 'Task has been saved',
    },
    methods: {
      changePphSetting: function(body, assignSummary) {
        const vm = this;
        vm.loadingSetting = true;
        axios.post('/setting/pph', body).then(function(response) {
          // console.log(response)
          vm.loadingSetting = false;
          assignSummary();
          vm.showToast('Success', 'Changes has been saved', 'success');
        }).catch(function(error) {
          vm.showToast('Error', 'Failed to save changes', 'error');
          vm.loadingSetting = false;
          console.log(error);
        })

      },
      addNpwp: function() {
        let vm = this;
        vm.loadingNpwp = true;
        axios.post('/company-npwp', {
            company_npwp_name: this.npwpCompanyName,
            company_npwp_number: this.npwpCompanyNumber,
            leader_npwp_name: this.npwpLeaderName,
            leader_npwp_number: this.npwpLeaderNumber,
          })
          .then(function(response) {
            vm.loadingNpwp = false;
            vm.npwps.push(response.data.data);
            vm.showToast('Success', 'NPWP pemotong berhasil ditambahkan', 'success');
          })
          .catch(function(error) {
            vm.loadingNpwp = false;
            vm.showToast('Error', 'NPWP pemotong gagal ditambahkan', 'error');
          });

      },
      openEditNpwpModal: function(index, id) {
        this.npwpEditId = id;
        this.npwpEditIndex = index;
        this.npwpCompanyNameEdit = this.npwps[index].company_npwp_name;
        this.npwpCompanyNumberEdit = this.npwps[index].company_npwp_number;
        this.npwpLeaderNameEdit = this.npwps[index].leader_npwp_name;
        this.npwpLeaderNumberEdit = this.npwps[index].leader_npwp_number;

        $('#editNpwpModal').modal('show');
      },
      editNpwp: function() {
        let vm = this;
        vm.loadingNpwpEdit = true;
        axios.patch('/company-npwp/' + vm.npwpEditId, {
            company_npwp_name: this.npwpCompanyNameEdit,
            company_npwp_number: this.npwpCompanyNumberEdit,
            leader_npwp_name: this.npwpLeaderNameEdit,
            leader_npwp_number: this.npwpLeaderNumberEdit,
          })
          .then(function(response) {
            vm.loadingNpwpEdit = false;
            vm.npwps[vm.npwpEditIndex] = response.data.data;
            // vm.npwps.push(response.data.data);
            $('#editNpwpModal').modal('hide');
            vm.resetEditNpwpForm();
            vm.showToast('Success', 'Data NPWP pemotong berhasil diubah', 'success');
          })
          .catch(function(error) {
            vm.loadingNpwpEdit = false;
            vm.showToast('Error', 'Data NPWP pemotong gagal diubah', 'error');
          });

      },
      resetEditNpwpForm: function() {
        this.npwpCompanyNameEdit = '';
        this.npwpCompanyNumberEdit = '';
        this.npwpLeaderNameEdit = '';
        this.npwpLeaderNumberEdit = '';
      },
      deleteNpwp: function(index, id) {
        let vm = this;

        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Data akan dihapus",
          icon: 'warning',
          reverseButtons: true,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batalkan',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return axios.delete('/company-npwp/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Gagal menghapus NPWP', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'NPWP berhasil dihapus', 'success');
            vm.npwps.splice(index, 1);
          }
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