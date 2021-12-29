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
            <!-- Begin: 1 -->
            <div class="card-header d-flex border-top bg-light">
              <h5 class="card-header-title">1. Pilih periode cuti yang berlaku di perusahaan Anda:</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editLeaveSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form1" aria-expanded="false" aria-controls="form1"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="periodTypeSummary == 'individu'">Periode Individu (berdasarkan tanggal masuk tiap personalia)</span>
                <span v-else>Periode bersama yang diperbaharui setiap tanggal: @{{ massPeriodDate }}</span>

              </div>
              <div id="form1" class="collapse">
                <form @submit.prevent="changeLeaveSetting(
                    { 
                      period_type: periodType, 
                      mass_period_type_start_date: massPeriodDay + '-' + massPeriodMonth, 
                    }, 
                    function(){
                      periodTypeSummary = periodType;
                      massPeriodTypeStartDateSummary = massPeriodDay + '-' + massPeriodMonth;
                    }
                  )">
                  <!-- <label class="custom-control custom-radio">
                    <input type="radio" v-model="periodType" value="individu" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Periode Individu (berdasarkan tanggal masuk tiap personalia)</span>
                  </label> -->
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="periodType" value="bersama" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Periode bersama yang diperbaharui setiap tanggal:</span>
                  </label>
                  <div class="row pl-5">
                    <select v-model="massPeriodDay" class="form-control form-control-sm col-sm-1 col-xs-4 mr-2">
                      @for($i = 1; $i <= 28; $i++) <option value="{{ '0' . $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                    <select v-model="massPeriodMonth" class="form-control form-control-sm col-sm-2 col-xs-8">
                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mei</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
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
              <h5 class="card-header-title">2. Kapan Personalia mendapatkan plafon cuti tahunan?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editLeaveSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form2" aria-expanded="false" aria-controls="form2"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="startLeaveTypeSummary == 'after_start_work'">Di tanggal mulai bekerja personalia di perusahaan</span>
                <span v-if="startLeaveTypeSummary == 'after_month_work'">@{{ afterMonthWorkMonthsNumberSummary }} Bulan setelah tanggal mulai bekerja personalia</span>
              </div>
              <div id="form2" class="collapse">
                <form @submit.prevent="changeLeaveSetting(
                    { 
                      start_leave_type: startLeaveType, 
                      after_month_work_months_number: afterMonthWorkMonthsNumber,
                    }, 
                    function(){
                      startLeaveTypeSummary = startLeaveType;
                      afterMonthWorkMonthsNumberSummary = afterMonthWorkMonthsNumber;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="startLeaveType" value="after_month_work" class="custom-control-input"><input type="number" v-model="afterMonthWorkMonthsNumber" class="form-control form-control-sm d-inline-block mr-2" style="width: 100px;"><span class="custom-control-label" style="margin-top: 3px;">Bulan setelah tanggal mulai bekerja personalia</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="startLeaveType" value="afer_start_work" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Di tanggal mulai bekerja personalia di perusahaan</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 2 -->
            <!-- Begin: 3 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">3. Pilih metode plafon cuti yang diterapkan di perusahaan Anda:</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editLeaveSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="leavePlafondTypeSummary == 'single'">Single Plafon, @{{ singlePlafondMaxDaySummary }} hari maksimal cuti dalam setahun</span>
              </div>
              <div id="form3" class="collapse">
                <form @submit.prevent="changeLeaveSetting(
                    { 
                      leave_plafond_type: leavePlafondType, 
                      single_plafond_max_day: singlePlafondMaxDay,
                    }, 
                    function(){
                      leavePlafondTypeSummary = leavePlafondType;
                      singlePlafondMaxDaySummary = singlePlafondMaxDay;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="leavePlafondType" value="single" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Single Plafon</span>
                  </label>
                  <div class="row align-items-center pl-4">
                    <div class="col-sm-2">
                      <input type="number" v-model="singlePlafondMaxDay" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-8">
                      <span>hari maksimal cuti dalam setahun</span>
                    </div>
                  </div>
                  <!-- <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasBpjsKesehatan" value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Di tanggal mulai bekerja personalia di perusahaan</span>
                  </label> -->
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 3 -->
            <!-- Begin: 4 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">4. Pilih metode pencairan plafon cuti yang didapatkan oleh personalia:</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editLeaveSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form4" aria-expanded="false" aria-controls="form4"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="leaveDistributionMethodSummary == 'standar'">Standar (Plafon cuti diberikan sekaligus di awal periode cuti)</span>
                <span v-if="leaveDistributionMethodSummary == 'permonth'">Perbulan (Plafon cuti diberikan secara proporsional setiap bulan)</span>
              </div>
              <div id="form4" class="collapse">
                <form @submit.prevent="changeLeaveSetting(
                    { 
                      leave_distribution_method: leaveDistributionMethod, 
                    }, 
                    function(){
                      leaveDistributionMethodSummary = leaveDistributionMethod
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="leaveDistributionMethod" value="standar" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Standar (Plafon cuti diberikan sekaligus di awal periode cuti)</span>
                  </label>
                  <!-- <label class="custom-control custom-radio">
                    <input type="radio" v-model="leaveDistributionMethod" value="permonth" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Perbulan (Plafon cuti diberikan secara proporsional setiap bulan)</span>
                  </label> -->
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 4 -->
            <!-- Begin: 5 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">5. Pilih metode perlakuan sisa plafon cuti personalia di akhir periode:</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editLeaveSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form5" aria-expanded="false" aria-controls="form5"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="remainLeaveMethodSummary == 'carry forward'">
                  Sisa cuti digunakan di periode selanjutnya (Carry Forward)<br>
                  - Jumlah maksimal @{{ carryForwardMaxDaySummary }} hari cuti dalam satu periode <br>
                  - Berlaku sampai @{{ carryForwardEffectiveMonthSummary }} bulan sejak periode berikutnya dimulai
                </span>
                <span v-if="remainLeaveMethodSummary == 'cashed'">
                  Sisa cuti diuangkan<br>
                  - Basis pengali: @{{ cashedBaseMultiplierSummary == 'gaji_pokok' ? 'Gaji Pokok' : 'Gaji Pokok + Tunjangan' }}<br>
                  - Jumlah maksimal @{{ cashedMaxLeaveDaySummary }} hari cuti bisa diuangkan<br>
                  - Jumlah maksimal @{{ cashedMaxDayPerMonthSummary }} hari kerja per bulan <br>
                  - Dipotong pajak: @{{ parseInt(cashedTaxed) ? 'Ya' : 'Tidak' }}<br>
                </span>
              </div>
              <div id="form5" class="collapse">
                <form @submit.prevent="changeLeaveSetting(
                    { 
                      remain_leave_method: remainLeaveMethod, 
                      carry_forward_max_day: carryForwardMaxDay, 
                      carry_forward_effective_month: carryForwardMaxDay, 
                      cashed_base_multiplier: cashedBaseMultiplier,
                      cashed_max_leave_day: cashedMaxLeaveDay,
                      cashed_max_day_per_month: cashedMaxDayPerMonth,
                      cashed_taxed: cashedTaxed,
                    }, 
                    function(){
                      remainLeaveMethodSummary = remainLeaveMethod;
                      carryForwardMaxDaySummary = carryForwardMaxDay;
                      carryForwardEffectiveMonthSummary = carryForwardEffectiveMonth;
                      cashedBaseMultiplierSummary = cashedBaseMultiplier;
                      cashedMaxLeaveDaySummary = cashedMaxLeaveDay;
                      cashedMaxDayPerMonthSummary = cashedMaxDayPerMonth;
                      cashedTaxedSummary = cashedTaxed;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="remainLeaveMethod" value="carry forward" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Sisa cuti digunakan di periode selanjutnya (Carry Forward)</span>
                  </label>
                  <div v-if="remainLeaveMethod == 'carry forward'">
                    <div class="d-flex align-items-center pl-4">
                      <div>
                        <span>Jumlah maksimal</span>
                      </div>
                      <div class="mx-2">
                        <input type="number" v-model="carryForwardMaxDay" class="form-control form-control-sm">
                      </div>
                      <div>
                        <span>hari cuti dalam satu periode</span>
                      </div>
                    </div>
                    <div class="d-flex align-items-center pl-4 mt-2">
                      <div>
                        <span>Berlaku sampai</span>
                      </div>
                      <div class="mx-2">
                        <input type="number" v-model="carryForwardEffectiveMonth" class="form-control form-control-sm">
                      </div>
                      <div>
                        <span>bulan sejak periode berikutnya dimulai</span>
                      </div>
                    </div>
                  </div>
                  <label class="custom-control custom-radio mt-3">
                    <input type="radio" v-model="remainLeaveMethod" value="cashed" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Sisa cuti diuangkan</span>
                  </label>
                  <div v-if="remainLeaveMethod == 'cashed'">
                    <div class="d-flex align-items-center pl-4">
                      <div>
                        <span>Basis pengali</span>
                      </div>
                      <div class="mx-2">
                        <label class="custom-control custom-radio d-inline-block">
                          <input type="radio" v-model="cashedBaseMultiplier" value="gaji_pokok" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Gaji Pokok</span>
                        </label>
                        <label class="custom-control custom-radio d-inline-block ml-3">
                          <input type="radio" v-model="cashedBaseMultiplier" value="gaji_pokok_tunjangan" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Gaji Pokok + Tunjangan</span>
                        </label>
                      </div>
                    </div>
                    <div class="d-flex align-items-center pl-4 mt-2">
                      <div>
                        <span>Jumlah maksimal</span>
                      </div>
                      <div class="mx-2">
                        <input type="number" v-model="cashedMaxLeaveDay" class="form-control form-control-sm">
                      </div>
                      <div>
                        <span>hari cuti bisa diuangkan</span>
                      </div>
                    </div>
                    <div class="d-flex align-items-center pl-4 mt-2">
                      <div>
                        <span>Jumlah</span>
                      </div>
                      <div class="mx-2">
                        <input type="number" v-model="cashedMaxDayPerMonth" class="form-control form-control-sm">
                      </div>
                      <div>
                        <span>hari kerja per bulan</span>
                      </div>
                    </div>
                    <div class="d-flex align-items-center pl-4 mt-2">
                      <div>
                        <span>Dipotong pajak</span>
                      </div>
                      <div class="mx-2">
                        <label class="custom-control custom-radio d-inline-block">
                          <input type="radio" v-model="cashedTaxed" value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                        </label>
                        <label class="custom-control custom-radio d-inline-block ml-3">
                          <input type="radio" v-model="cashedTaxed" value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form5" aria-expanded="false" aria-controls="form5">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End:5  -->
            <!-- Begin: 6 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">6. Apakah perusahaan Anda menerapkan sistem hutang cuti?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editLeaveSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form6" aria-expanded="false" aria-controls="form6"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="hasLeaveDepositSummary == '1'">Ya, Jumlah maksimal @{{ leaveDepositMaxDaySummary }} hari hutang cuti</span>
                <span v-else>Tidak</span>
              </div>
              <div id="form6" class="collapse">
                <form @submit.prevent="changeLeaveSetting(
                    { 
                      has_leave_deposit: hasLeaveDeposit,
                      leave_deposit_max_day: leaveDepositMaxDay, 
                    }, 
                    function(){
                      hasLeaveDepositSummary = hasLeaveDeposit;
                      leaveDepositMaxDaySummary = leaveDepositMaxDay;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasLeaveDeposit" value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <div class="d-flex align-items-center pl-4 mt-2">
                    <div>
                      <span>Jumlah maksimal</span>
                    </div>
                    <div class="mx-2">
                      <input type="number" v-model="leaveDepositMaxDay" class="form-control form-control-sm">
                    </div>
                    <div>
                      <span>hari hutang cuti</span>
                    </div>
                  </div>
                  <label class="custom-control custom-radio mt-3">
                    <input type="radio" v-model="hasLeaveDeposit" value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form6" aria-expanded="false" aria-controls="form6">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End:6  -->
            <!-- Begin: 7 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">7. Apakah perusahaan Anda menerapkan sistem Block Leave?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editLeaveSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form7" aria-expanded="false" aria-controls="form7"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="hasBlockLeaveSummary == '1'">Ya, Jumlah @{{ blockLeaveNumberOfDaysSummary }} hari block leave dalam satu pengajuan cuti</span>
                <span v-else>Tidak</span>
              </div>
              <div id="form7" class="collapse">
                <form @submit.prevent="changeLeaveSetting(
                    { 
                      has_block_leave: hasBlockLeave,
                      block_leave_number_of_days: blockLeaveNumberOfDays,
                    }, 
                    function(){
                      hasBlockLeaveSummary = hasBlockLeave;
                      blockLeaveNumberOfDaysSummary = blockLeaveNumberOfDays;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasBlockLeave" value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <div class="d-flex align-items-center pl-4 mt-2 mb-3" v-if="hasBlockLeave == '1'">
                    <div>
                      <span>Jumlah</span>
                    </div>
                    <div class="mx-2">
                      <input type="number" v-model="blockLeaveNumberOfDays" class="form-control form-control-sm">
                    </div>
                    <div>
                      <span>hari block leave dalam satu pengajuan cuti</span>
                    </div>
                  </div>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasBlockLeave" value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form7" aria-expanded="false" aria-controls="form7">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End:7  -->
            <!-- Begin: 8 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">8. Apakah di Perusahaan Anda cuti bersama memotong plafon cuti tahunan?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editLeaveSetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form8" aria-expanded="false" aria-controls="form8"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i> @{{ (isMassLeaveCutLeavePlafondSummary == '1') ? 'Ya' : 'Tidak' }}
              </div>
              <div id="form8" class="collapse">
                <form @submit.prevent="changeLeaveSetting(
                    { 
                      is_mass_leave_cut_leave_plafond: isMassLeaveCutLeavePlafond,
                    }, 
                    function(){
                      isMassLeaveCutLeavePlafondSummary = isMassLeaveCutLeavePlafond;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="isMassLeaveCutLeavePlafond" value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="isMassLeaveCutLeavePlafond" value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End:8  -->
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
    props: ['id', 'index', 'name', 'value', 'ondelete'],
    template: `
    <tr>
      <td>@{{ name }}</td>
      <td>@{{ value }}</td>
      <td class="text-center">
        <div class="btn-group" role="group" aria-label="Action Buttons">
          <button class="btn btn-sm btn-light" data-toggle="modal" data-target="#editIncomeModal"><i class="fas fa-fw fa-pencil-alt"></i></button>
          <button type="button" class="btn btn-sm btn-light" @click="ondelete(index, id)"><i class="fas fa-fw fa-trash"></i></button>
        </div>
      </td>
    </tr>
    `,
  })

  let app = new Vue({
    el: '#app',
    data: {
      periodType: '{{ $setting->period_type }}',
      massPeriodTypeStartDate: '{{ $setting->mass_period_type_start_date }}',
      massPeriodDay: ('{{ $setting->mass_period_type_start_date }}').split('-')[0],
      massPeriodMonth: ('{{ $setting->mass_period_type_start_date }}').split('-')[1],
      startLeaveType: '{{ $setting->start_leave_type }}',
      afterMonthWorkMonthsNumber: '{{ $setting->after_month_work_months_number }}',
      leavePlafondType: '{{ $setting->leave_plafond_type }}',
      singlePlafondMaxDay: '{{ $setting->single_plafond_max_day }}',
      leaveDistributionMethod: '{{ $setting->leave_distribution_method }}',
      remainLeaveMethod: '{{ $setting->remain_leave_method }}',
      carryForwardMaxDay: '{{ $setting->carry_forward_ma_day }}',
      carryForwardEffectiveMonth: '{{ $setting->carry_forward_effective_month }}',
      cashedBaseMultiplier: '{{ $setting->cashed_base_multiplier }}',
      cashedMaxLeaveDay: '{{ $setting->cashed_max_leave_day }}',
      cashedMaxDayPerMonth: '{{ $setting->cashed_max_day_per_month }}',
      cashedTaxed: '{{ $setting->cashed_taxed }}',
      hasLeaveDeposit: '{{ $setting->has_leave_deposit }}',
      leaveDepositMaxDay: '{{ $setting->leave_deposit_max_day }}',
      hasBlockLeave: '{{ $setting->has_block_leave }}',
      blockLeaveNumberOfDays: '{{ $setting->block_leave_number_of_days }}',
      isMassLeaveCutLeavePlafond: '{{ $setting->is_mass_leave_cut_leave_plafond }}',
      effectiveDate: '{{ $setting->effective_date }}',
      // SUMMARY
      periodTypeSummary: '{{ $setting->period_type }}',
      massPeriodTypeStartDateSummary: '{{ $setting->mass_period_type_start_date }}',
      startLeaveTypeSummary: '{{ $setting->start_leave_type }}',
      afterMonthWorkMonthsNumberSummary: '{{ $setting->after_month_work_months_number }}',
      leavePlafondTypeSummary: '{{ $setting->leave_plafond_type }}',
      singlePlafondMaxDaySummary: '{{ $setting->single_plafond_max_day }}',
      leaveDistributionMethodSummary: '{{ $setting->leave_distribution_method }}',
      remainLeaveMethodSummary: '{{ $setting->remain_leave_method }}',
      carryForwardMaxDaySummary: '{{ $setting->carry_forward_ma_day }}',
      carryForwardEffectiveMonthSummary: '{{ $setting->carry_forward_effective_month }}',
      cashedBaseMultiplierSummary: '{{ $setting->cashed_base_multiplier }}',
      cashedMaxLeaveDaySummary: '{{ $setting->cashed_max_leave_day }}',
      cashedMaxDayPerMonthSummary: '{{ $setting->cashed_max_day_per_month }}',
      cashedTaxedSummary: '{{ $setting->cashed_taxed }}',
      hasLeaveDepositSummary: '{{ $setting->has_leave_deposit }}',
      leaveDepositMaxDaySummary: '{{ $setting->leave_deposit_max_day }}',
      hasBlockLeaveSummary: '{{ $setting->has_block_leave }}',
      blockLeaveNumberOfDaysSummary: '{{ $setting->block_leave_number_of_days }}',
      isMassLeaveCutLeavePlafondSummary: '{{ $setting->is_mass_leave_cut_leave_plafond }}',
      effectiveDateSummary: '{{ $setting->effective_date }}',
      // wages: '',
      // loadingWage: false,
      // wageModel: {
      //   add: {
      //     name: '',
      //     value: '',
      //   }
      // },
      // hasBpjsKetenagakerjaan: '',
      // hasBpjsKesehatan: '',

      // hasBpjsKetenagakerjaanSummary: '',
      // hasBpjsKesehatanSummary: '',
      loadingSetting: false,
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
      showToast: function(title, text, type = 'success') {
        this.toastTitle = title;
        this.toastText = text;
        this.toastType = type;
        $('#liveToast').toast('show');
      },
      hideToast: function() {
        // this.toast = false;
      },
      changeLeaveSetting: function(body, assignSummary) {
        const vm = this;
        vm.loadingSetting = true;
        axios.post('/setting/leave',
          body
          // {
          //   has_overtime: this.hasOvertime,
          //   overtime_formula: this.overtimeFormula,
          //   overtime_nominal_per_hour: this.overtimeNominalPerHour,
          //   thr_min_months_of_service: this.thrMinMonthsOfService,
          //   thr_amount: this.thrAmount,
          //   thr_type: this.thrType,
          //   thr_for_less_one_year: this.thrForLessOneYear,
          //   has_leave: this.hasLeave,
          //   salary_for_career_changes: this.salaryForCareerChanges,
          //   proporsional_formula_career_changes: this.proporsionalFormulaCareerChanges,s
          //   salary_for_middle_out: this.salaryForMiddleOut,
          //   proporsional_formula_middle_out: this.proporsionalFormulaMiddleOut,
          //   work_day_per_month: this.workDayPerMonth,
          //   work_monday: this.workMonday,
          //   work_tuesday: this.workTuesday,
          //   work_wednesday: this.workWednesday,
          //   work_thursday: this.workThursday,
          //   work_friday: this.workFriday,
          //   work_saturday: this.workSaturday,
          //   work_sunday: this.workSunday,
          //   has_digital_account: this.hasDigitalAccount,
          // }

        ).then(function(response) {
          // console.log(response)
          vm.loadingSetting = false;
          // vm.hasOvertimeSummary = vm.hasOvertime;
          // vm.overtimeFormulaSummary = vm.overtimeFormula;
          // vm.overtimeNominalPerHourSummary = vm.overtimeNominalPerHour;
          // vm.thrMinMonthsOfServiceSummary = vm.thrMinMonthsOfService;
          // vm.thrAmountSummary = vm.thrAmount;
          // vm.thrTypeSummary = vm.thrType;
          // vm.thrForLessOneYearSummary = vm.thrForLessOneYear;
          // vm.hasLeaveSummary = vm.hasLeave;
          // vm.salaryForCareerChangesSummary = vm.salaryForCareerChanges;
          // vm.proporsionalFormulaCareerChangesSummary = vm.proporsionalFormulaCareerChanges;
          // vm.salaryForMiddleOutSummary = vm.salaryForMiddleOut;
          // vm.proporsionalFormulaMiddleOutSummary = vm.proporsionalFormulaMiddleOut;
          // vm.hasDigitalAccountSummary = vm.hasDigitalAccount;
          assignSummary();
          vm.showToast('Success', 'Changes has been saved', 'success');
        }).catch(function(error) {
          vm.showToast('Error', 'Failed to save changes', 'error');
          vm.loadingSetting = false;
          console.log(error);
        })

      },
    },
    computed: {
      massPeriodDate: function() {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const day = parseInt(this.massPeriodTypeStartDateSummary.split('-')[0]);
        const month = months[parseInt(this.massPeriodTypeStartDateSummary.split('-')[1]) - 1];

        return `${day} ${month}`;
      }
    }
  })
</script>
@endsection