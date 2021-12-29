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
                  <li class="breadcrumb-item active" aria-current="page">Salary</li>
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
              <h5 class="card-header-title">1. Komponen pendapatan apa saja yang tersedia di perusahaan Anda (selain tunjangan PPh 21 dan BPJS)?</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
              </div>
            </div>
            <div class="card-body">
              @if(in_array("editSalarySetting", $userLoginPermissions))
              <div class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#formIncome" aria-expanded="false" aria-controls="formIncome"><i class="fas fa-fw fa-plus"></i> Add Pendapatan</button>
              </div>
              @endif
              <div id="formIncome" class="collapse">
                <form @submit.prevent="addSalaryIncome">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Nama Pendapatan</label>
                      <input v-model="salaryIncomeModel.add.name" type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Tipe</label>
                      <select v-model="salaryIncomeModel.add.type" class="form-control">
                        <option value="Jumlah Tetap">Jumlah Tetap</option>
                        <option value="Tergantung Kehadiran">Tergantung Kehadiran</option>
                        <option value="Tergantung Output">Tergantung Output</option>
                        <option value="Manual">Manual</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>PPh 21</label>
                      <select v-model="salaryIncomeModel.add.pph21" class="form-control">
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Tipe A1</label>
                      <select v-model="salaryIncomeModel.add.typeA1" class="form-control">
                        <option value="type_a1_1">Tunjangan Lainnya, Uang Lembur, dsb</option>
                        <option value="type_a1_2">Honorarium dan Imbalan Lainnya Sejenis</option>
                        <option value="type_a1_3">Premi Asuransi yang Dibayar Pemberi Kerja</option>
                        <option value="type_a1_4">Penerima dalam bentuk natura dan kenikmatan lainnya yang dikenakan pemotongan pph 21</option>
                        <option value="type_a1_5">Tantiem, Bonus, Gratifikasi, Jasa Produksi, dan THR</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Penambah THR</label>
                      <select v-model="salaryIncomeModel.add.thrIncome" class="form-control">
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                      </select>
                    </div>
                  </div>
                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingSalaryIncome"><span v-if="loadingSalaryIncome" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                  </div>
                </form>
              </div>
              <div class="pt-3">
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col" class="border-0">Nama Pendapatan</th>
                      <th scope="col" class="border-0">Tipe</th>
                      <th scope="col" class="border-0">PPh21</th>
                      <th scope="col" class="border-0">Tipe A1</th>
                      <th scope="col" class="border-0">Penambah THR</th>
                      <th scope="col" class="text-center border-0">Status</th>
                      <th scope="col" class="text-center border-0">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td v-if="salaryIncomes.length < 1" colspan="6" class="text-center text-light">
                        <i class="fa fa-fw fa-folder-open fa-3x"></i>
                        <h6>No data yet</h6>
                      </td>
                    </tr>
                    <tr is="salary-income" v-for="(income, index) in salaryIncomes" :key="income.id" :id="income.id" :index="index" :name="income.name" :type="income.type" :typea1="income.type_a1" :pph21="income.pph21" :isactive="income.is_active" :isdefault="income.is_default" :typea1items="typeA1Items" :thrincome="income.thr_income" :ondelete="deleteSalaryIncome" :onopenmodal="openEditSalaryIncomeModal"></tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End: 1 -->
            <!-- Begin: 2 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">2. Komponen potongan apa saja yang ada di perusahaan Anda (selain potongan PPh 21, Premi BPJS dan Unpaid Leave)?</h5>
              <div class="toolbar ml-auto">
                <!-- <a href="http://127.0.0.1:8000/employee/create" class="btn btn-primary btn-sm"><i class="fas fa-fw fa-pencil-alt"></i> Edit</a> -->
                <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
              </div>
            </div>
            <div class="card-body">
              @if(in_array("editSalarySetting", $userLoginPermissions))
              <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#formDeduction" aria-expanded="false" aria-controls="formDeduction"><i class="fas fa-fw fa-plus"></i> Add Potongan</button>
              </div>
              @endif
              <div id="formDeduction" class="collapse">
                <form @submit.prevent="addSalaryDeduction">
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label>Nama Potongan</label>
                      <input v-model="salaryDeductionModel.add.name" type="text" class="form-control">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Tipe</label>
                      <select v-model="salaryDeductionModel.add.type" class="form-control">
                        <option value="Jumlah Tetap">Jumlah Tetap</option>
                        <option value="Tergantung Output">Tergantung Output</option>
                        <option value="Manual">Manual</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Pengurang PPh</label>
                      <select v-model="salaryDeductionModel.add.pph" class="form-control">
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                      </select>
                    </div>
                  </div>
                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingSalaryDeduction"><span v-if="loadingSalaryDeduction" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                  </div>
                </form>
              </div>
              <div class="pt-3">
                <table class="table">
                  <thead style="background-color: #d1ecf1">
                    <tr>
                      <th scope="col" class="border-0">Nama Potongan</th>
                      <th scope="col" class="border-0">Tipe</th>
                      <th scope="col" class="border-0">Pengurangan PPh</th>
                      <th scope="col" class="text-center border-0">Status</th>
                      <th scope="col" class="text-center border-0">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td v-if="salaryDeductions.length < 1" colspan="6" class="text-center text-light">
                        <i class="fa fa-fw fa-folder-open fa-3x"></i>
                        <h6>No data yet</h6>
                      </td>
                    </tr>
                    <tr is="salary-deduction" v-for="(deduction, index) in salaryDeductions" :key="deduction.id" :id="deduction.id" :index="index" :name="deduction.name" :type="deduction.type" :pph="deduction.pph" :isactive="deduction.is_active" :ondelete="deleteSalaryDeduction" :onopenmodal="openEditSalaryDeductionModal"></tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End: 2 -->
            <!-- Begin: 3 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">3. Apakah perusahaan Anda menerapkan penghitungan lembur?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form3" aria-expanded="false" aria-controls="form3"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i> @{{ (hasOvertimeSummary) ? 'Ya' : 'Tidak' }}
              </div>
              <div id="form3" class="collapse">
                <form @submit.prevent="changeSalarySetting(
                    { has_overtime: hasOvertime }, 
                    function(){
                      hasOvertimeSummary = hasOvertime;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasOvertime" :value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasOvertime" :value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>
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
              <h5 class="card-header-title">4. Pilih formula yang Anda gunakan untuk menghitung uang lembur:</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form4" aria-expanded="false" aria-controls="form4" :disabled="!hasOvertimeSummary"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="overtimeFormulaSummary == 'gaji_pokok'">Gaji Pokok</span>
                <span v-else-if="overtimeFormulaSummary == 'gaji_pokok_tunjangan'">Gaji Pokok + Tunjangan Tetap / 173</span>
                <span v-else="overtimeFormulaSummary == 'nominal'">Nominal. Jumlah rupiah per jam: @{{ overtimeNominalPerHourSummary }}</span>
              </div>
              <div id="form4" class="collapse" v-if="hasOvertimeSummary">
                <form @submit.prevent="changeSalarySetting(
                    (overtimeFormula == 'nominal') ?
                    {overtime_formula: overtimeFormula, overtime_nominal_per_hour: overtimeNominalPerHour}
                    :
                    { overtime_formula: overtimeFormula }
                    , 
                    function(){
                      overtimeFormulaSummary = overtimeFormula;
                      if(overtimeFormula == 'nominal'){
                        overtimeNominalPerHourSummary = overtimeNominalPerHour;
                      }
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="overtimeFormula" :value="'gaji_pokok'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Gaji Pokok</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="overtimeFormula" :value="'gaji_pokok_tunjangan'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Gaji Pokok + Tunjangan Tetap / 173</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="overtimeFormula" :value="'nominal'" class="custom-control-input">
                    <span class="custom-control-label" style="margin-top: 3px;">Nominal</span>
                  </label>
                  <div v-if="overtimeFormula == 'nominal'" class="form-group row">
                    <div class="col-sm-1"></div>
                    <label class="col-sm-2 col-form-label" style="font-size: 13px;">Jumlah rupiah per jam</label>
                    <div class="col-sm-8">
                      <input v-model="overtimeNominalPerHour" type="text" class="form-control form-control-sm">
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form4" aria-expanded="false" aria-controls="form4">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 4 -->
            <!-- Begin: 5 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">5. Agar menerima Tunjangan Hari Raya (THR), berapa lama seorang pegawai harus melalui masa kerja perusahaan Anda?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form5" aria-expanded="false" aria-controls="form5"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <span>@{{ thrMinMonthsOfServiceSummary }} Bulan</span>
              </div>
              <div id="form5" class="collapse">
                <form @submit.prevent="changeSalarySetting(
                    {thr_min_months_of_service: thrMinMonthsOfService}
                    , 
                    function(){
                      thrMinMonthsOfServiceSummary = thrMinMonthsOfService;
                    }
                  )">
                  <div class="row align-items-center">
                    <div class="col-md-2">
                      <input type="number" v-model="thrMinMonthsOfService" class="form-control">
                    </div>
                    <div class="col-md-2">
                      <span>Bulan</span>
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form5" aria-expanded="false" aria-controls="form5">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 5 -->
            <!-- Begin: 6 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">6. Berapa besar THR yang Anda bayarkan untuk tiap pegawai di perusahaan Anda?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form6" aria-expanded="false" aria-controls="form6"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <span v-if="thrTypeSummary == 'gaji_pokok'">@{{ thrAmountSummary }} Kali, Gaji Pokok</span>
                <span v-else>@{{ thrAmountSummary }} Kali, Gaji Pokok + Tunjangan Tetap</span>
              </div>
              <div id="form6" class="collapse">
                <form @submit.prevent="changeSalarySetting(
                    {thr_amount: thrAmount, thr_type: thrType}
                    , 
                    function(){
                      thrAmountSummary = thrAmount;
                      thrTypeSummary = thrType;
                    }
                  )">
                  <div class="row align-items-center">
                    <div class="col-md-2">
                      <input v-model="thrAmount" type="number" class="form-control">
                    </div>
                    <div class="col-md-1">
                      <span>Kali</span>
                    </div>
                    <div class="col-md-3">
                      <select v-model="thrType" class="form-control">
                        <option :value="'gaji_pokok'">Gaji Pokok</option>
                        <!-- <option :value="'gaji_pokok_tunjangan'">Gaji Pokok + Tunjangan Tetap</option> -->
                      </select>
                    </div>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form6" aria-expanded="false" aria-controls="form6">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 6 -->
            <!-- Begin: 7 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">7. Untuk pegawai yang bekerja kurang dari satu tahun, bagaimanakah cara Anda menentukan besaran THR untuknya?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form7" aria-expanded="false" aria-controls="form7"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="thrForLessOneYearSummary == 'day'">Berdasarkan jumlah hari kalender: antara tanggal ia mulai bekerja hingga tanggal pembuatan THR dibagi 365 hari</span>
                <span v-else>Berdasarkan jumlah bulan: antara bulan ia mulai bekerja dengan bulan pembuatan THR pertama, dibagi 12</span>
              </div>
              <div id="form7" class="collapse">
                <form @submit.prevent="changeSalarySetting(
                    {thr_for_less_one_year: thrForLessOneYear}
                    , 
                    function(){
                      thrForLessOneYearSummary = thrForLessOneYear;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="thrForLessOneYear" :value="'day'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Berdasarkan jumlah hari kalender: antara tanggal ia mulai bekerja hingga tanggal pembuatan THR dibagi 365 hari</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="thrForLessOneYear" :value="'month'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Berdasarkan jumlah bulan: antara bulan ia mulai bekerja dengan bulan pembuatan THR pertama, dibagi 12</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form7" aria-expanded="false" aria-controls="form7">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 7 -->
            <!-- Begin: 8 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">8. Apakah perusahaan Anda menerapkan tunjangan cuti ?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form8" aria-expanded="false" aria-controls="form8"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i> @{{ (hasLeaveSummary) ? 'Ya' : 'Tidak' }}
              </div>
              <div id="form8" class="collapse">
                <form @submit.prevent="changeSalarySetting(
                    {has_leave: hasLeave}
                    , 
                    function(){
                      hasLeaveSummary = hasLeave;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasLeave" :value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasLeave" :value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form8" aria-expanded="false" aria-controls="form8">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 8 -->
            <!-- Begin: 9 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">9. Bagaimanakah kebijakan perusahaan untuk menentukan besaran gaji bagi pegawai yang mengalami perubahan karir (contoh: promosi) di tengah periode penggajian?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form9" aria-expanded="false" aria-controls="form9"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="salaryForCareerChangesSummary == 'proporsional'">Proporsional. Menggabungkan perhitungan pro rata gaji lama dan gaji baru</span>
                <span v-else>Hanya nilai gaji baru saja yang berlaku</span>
              </div>
              <div id="form9" class="collapse">
                <form @submit.prevent="changeSalarySetting(
                    {salary_for_career_changes: salaryForCareerChanges}
                    , 
                    function(){
                      salaryForCareerChangesSummary = salaryForCareerChanges;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="salaryForCareerChanges" :value="'proporsional'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Proporsional. Menggabungkan perhitungan pro rata gaji lama dan gaji baru</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="salaryForCareerChanges" :value="'gaji_baru'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Hanya nilai gaji baru saja yang berlaku</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form9" aria-expanded="false" aria-controls="form9">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 9 -->
            <!-- Begin: 9a -->
            <div v-if="salaryForCareerChangesSummary == 'proporsional'">
              <div class="card-header d-flex border-top bg-light mt-5">
                <h5 class="card-header-title">9.a. Metode perhitungan proporsional yang digunakan?</h5>
                <div class="toolbar ml-auto">
                  <div class="btn-group" role="group" aria-label="Action Buttons">
                    @if(in_array("editSalarySetting", $userLoginPermissions))
                    <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form9a" aria-expanded="false" aria-controls="form9a"><i class="fas fa-fw fa-pencil-alt"></i></button>
                    @endif
                    <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="alert alert-info" role="alert">
                  <i class="fas fa-fw fa-check"></i>
                  <span v-if="proporsionalFormulaCareerChangesSummary == 'kalender'">Berbasis hari kalender</span>
                  <span v-else>Berbasis hari kerja (sesuai dengan pola kerja pegawai)</span>
                </div>
                <div id="form9a" class="collapse">
                  <form @submit.prevent="changeSalarySetting(
                    {proporsional_formula_career_changes: proporsionalFormulaCareerChanges}
                    , 
                    function(){
                      proporsionalFormulaCareerChangesSummary = proporsionalFormulaCareerChanges;
                    }
                  )">
                    <label class="custom-control custom-radio">
                      <input type="radio" v-model="proporsionalFormulaCareerChanges" :value="'kalender'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Berbasis hari kalender</span>
                    </label>
                    <label class="custom-control custom-radio">
                      <input type="radio" v-model="proporsionalFormulaCareerChanges" :value="'hari_kerja'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Berbasis hari kerja (sesuai dengan pola kerja pegawai)</span>
                    </label>
                    <div class="mt-3">
                      <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                      <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form9a" aria-expanded="false" aria-controls="form9a">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End: 9a -->
            <!-- Begin: 10 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">10. Bagaimana kebijakan perusahaan untuk menentukan besaran gaji bagi pegawai yang masuk dan keluar di tengah periode penggajian?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form10" aria-expanded="false" aria-controls="form10"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                <span v-if="salaryForMiddleOutSummary == 'gaji_prorata'">Gaji Prorata</span>
                <span v-else>Gaji Penuh</span>
              </div>
              <div id="form10" class="collapse">
                <form @submit.prevent="changeSalarySetting(
                    {salary_for_middle_out: salaryForMiddleOut}
                    , 
                    function(){
                      salaryForMiddleOutSummary = salaryForMiddleOut;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="salaryForMiddleOut" :value="'gaji_prorata'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Gaji Prorata</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="salaryForMiddleOut" :value="'gaji_penuh'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Gaji Penuh</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form10" aria-expanded="false" aria-controls="form10">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 10 -->
            <!-- Begin: 10a -->
            <div v-if="salaryForMiddleOutSummary == 'gaji_prorata'">
              <div class="card-header d-flex border-top bg-light mt-5">
                <h5 class="card-header-title">10.a. Metode perhitungan proporsional yang digunakan?</h5>
                <div class="toolbar ml-auto">
                  <div class="btn-group" role="group" aria-label="Action Buttons">
                    @if(in_array("editSalarySetting", $userLoginPermissions))
                    <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form10a" aria-expanded="false" aria-controls="form10a"><i class="fas fa-fw fa-pencil-alt"></i></button>
                    @endif
                    <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="alert alert-info" role="alert">
                  <i class="fas fa-fw fa-check"></i>
                  <span v-if="proporsionalFormulaMiddleOutSummary == 'hari_kalender'">Pembagi berdasarkan hari kalender</span>
                  <span v-else-if="proporsionalFormulaMiddleOutSummary == 'hari_kerja'">Pembagi berdasarkan hari kerja</span>
                  <span v-else>Pembagi berdasarkan input manual hari kerja</span>
                </div>
                <div id="form10a" class="collapse">
                  <form @submit.prevent="changeSalarySetting(
                    proporsionalFormulaMiddleOutBody
                    , 
                    function(){
                      proporsionalFormulaMiddleOutSummary = proporsionalFormulaMiddleOut;
                    }
                  )">
                    <label class="custom-control custom-radio">
                      <input type="radio" v-model="proporsionalFormulaMiddleOut" :value="'hari_kalender'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Pembagi berdasarkan hari kalender</span>
                    </label>
                    <label class="custom-control custom-radio">
                      <input type="radio" v-model="proporsionalFormulaMiddleOut" :value="'hari_kerja'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Pembagi berdasarkan hari kerja. Pilih hari kerja yang berlaku di perusahaan Anda !</span>
                    </label>
                    <div v-if="proporsionalFormulaMiddleOut == 'hari_kerja'">
                      <!-- Senin -->
                      <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 col-form-label" style="font-size: 13px;">Senin</label>
                        <div class="col-sm-8">
                          <select v-model="workMonday" class="form-control form-control-sm">
                            <option :value="'hari_kerja'">Hari Kerja</option>
                            <option :value="'hari_libur'">Hari Libur</option>
                          </select>
                        </div>
                      </div>
                      <!-- Selasa -->
                      <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 col-form-label" style="font-size: 13px;">Selasa</label>
                        <div class="col-sm-8">
                          <select v-model="workTuesday" class="form-control form-control-sm">
                            <option :value="'hari_kerja'">Hari Kerja</option>
                            <option :value="'hari_libur'">Hari Libur</option>
                          </select>
                        </div>
                      </div>
                      <!-- Rabu -->
                      <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 col-form-label" style="font-size: 13px;">Rabu</label>
                        <div class="col-sm-8">
                          <select v-model="workWednesday" class="form-control form-control-sm">
                            <option :value="'hari_kerja'">Hari Kerja</option>
                            <option :value="'hari_libur'">Hari Libur</option>
                          </select>
                        </div>
                      </div>
                      <!-- Kamis -->
                      <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 col-form-label" style="font-size: 13px;">Kamis</label>
                        <div class="col-sm-8">
                          <select v-model="workThursday" class="form-control form-control-sm">
                            <option :value="'hari_kerja'">Hari Kerja</option>
                            <option :value="'hari_libur'">Hari Libur</option>
                          </select>
                        </div>
                      </div>
                      <!-- Jumat -->
                      <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 col-form-label" style="font-size: 13px;">Jumat</label>
                        <div class="col-sm-8">
                          <select v-model="workFriday" class="form-control form-control-sm">
                            <option :value="'hari_kerja'">Hari Kerja</option>
                            <option :value="'hari_libur'">Hari Libur</option>
                          </select>
                        </div>
                      </div>
                      <!-- Sabtu -->
                      <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 col-form-label" style="font-size: 13px;">Sabtu</label>
                        <div class="col-sm-8">
                          <select v-model="workSaturday" class="form-control form-control-sm">
                            <option :value="'hari_kerja'">Hari Kerja</option>
                            <option :value="'hari_libur'">Hari Libur</option>
                          </select>
                        </div>
                      </div>
                      <!-- Minggu -->
                      <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-2 col-form-label" style="font-size: 13px;">Minggu</label>
                        <div class="col-sm-8">
                          <select v-model="workSunday" class="form-control form-control-sm">
                            <option :value="'hari_kerja'">Hari Kerja</option>
                            <option :value="'hari_libur'">Hari Libur</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <label class="custom-control custom-radio">
                      <input type="radio" v-model="proporsionalFormulaMiddleOut" :value="'hari_kerja_manual'" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Pembagi berdasarkan input manual hari kerja. Masukkan jumlah hari kerja yang berlaku di perusahaan Anda !</span>
                    </label>
                    <div v-if="proporsionalFormulaMiddleOut == 'hari_kerja_manual'" class="row align-items-center">
                      <div class="col-md-1">
                        <span>Jumlah</span>
                      </div>
                      <div class="col-md-2">
                        <input v-model="workDayPerMonth" type="number" class="form-control">
                      </div>
                      <div class="col-md-3">
                        <span>hari kerja per bulan</span>
                      </div>
                    </div>
                    <div class="mt-3">
                      <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                      <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form10a" aria-expanded="false" aria-controls="form10a">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- End: 10a -->
            <!-- Begin: 11 -->
            <div class="card-header d-flex border-top bg-light mt-5">
              <h5 class="card-header-title">11. Apakah Anda membayarkan sebagian gaji karyawan ke akun digital cash (contoh: e-cash)?</h5>
              <div class="toolbar ml-auto">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                  @if(in_array("editSalarySetting", $userLoginPermissions))
                  <button class="btn btn-light btn-sm" data-toggle="collapse" data-target="#form11" aria-expanded="false" aria-controls="form11"><i class="fas fa-fw fa-pencil-alt"></i></button>
                  @endif
                  <button type="button" class="btn btn-sm btn-light"><i class="fas fa-fw fa-question-circle"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-fw fa-check"></i>
                @{{ (hasDigitalAccountSummary) ? 'Ya' : 'Tidak' }}
              </div>
              <div id="form11" class="collapse">
                <form @submit.prevent="changeSalarySetting(
                    {has_digital_account: hasDigitalAccount}
                    , 
                    function(){
                      hasDigitalAccountSummary = hasDigitalAccount;
                    }
                  )">
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasDigitalAccount" :value="1" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Ya</span>
                  </label>
                  <label class="custom-control custom-radio">
                    <input type="radio" v-model="hasDigitalAccount" :value="0" class="custom-control-input"><span class="custom-control-label" style="margin-top: 3px;">Tidak</span>
                  </label>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary" v-bind:disabled="loadingSetting"><span v-if="loadingSetting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
                    <button type="button" class="btn btn-sm btn-light ml-1" data-toggle="collapse" data-target="#form11" aria-expanded="false" aria-controls="form11">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End: 11 -->
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
              <li><a href="#overview" class="active">Komponen Pendapatan</a></li>
              <li><a href="#cards">Komponen Potongan</a></li>
              <li><a href="#cards">Lembur</a></li>
              <li><a href="#cards">Formula Lembur</a></li>
              <li><a href="#cards">THR</a></li>
              <li><a href="#cards">Minimal Masa Kerja THR</a></li>
              <li><a href="#cards">Besaran THR</a></li>
              <li><a href="#cards">Pegawai Kurang Satu Tahun THR</a></li>
              <li><a href="#cards">Cuti</a></li>
              <li><a href="#cards">Gaji </a></li>
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
<!-- Modal -->
<div class="modal fade" id="editIncomeModal" tabindex="-1" role="dialog" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editIncomeModalLabel">Edit Komponen Pendapatan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="editSalaryIncome(salaryIncomeEditIndex, salaryIncomeEditId)">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Nama Pendapatan</label>
              <input v-model="salaryIncomeModel.edit.name" type="text" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label>Tipe</label>
              <select v-model="salaryIncomeModel.edit.type" class="form-control">
                <option value="Jumlah Tetap">Jumlah Tetap</option>
                <option value="Tergantung Kehadiran">Tergantung Kehadiran</option>
                <option value="Tergantung Output">Tergantung Output</option>
                <option value="Manual">Manual</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>PPh 21</label>
              <select v-model="salaryIncomeModel.edit.pph21" class="form-control">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Tipe A1</label>
              <select v-model="salaryIncomeModel.edit.typeA1" class="form-control">
                <option value="type_a1_1">Tunjangan Lainnya, Uang Lembur, dsb</option>
                <option value="type_a1_2">Honorarium dan Imbalan Lainnya Sejenis</option>
                <option value="type_a1_3">Premi Asuransi yang Dibayar Pemberi Kerja</option>
                <option value="type_a1_4">Penerima dalam bentuk natura dan kenikmatan lainnya yang dikenakan pemotongan pph 21</option>
                <option value="type_a1_5">Tantiem, Bonus, Gratifikasi, Jasa Produksi, dan THR</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Penambah THR</label>
              <select v-model="salaryIncomeModel.edit.thrIncome" class="form-control">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingSalaryIncomeEdit"><span v-if="loadingSalaryIncomeEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editDeductionModal" tabindex="-1" role="dialog" aria-labelledby="editDeductionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDeductionModalLabel">Edit Komponen Potongan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="editSalaryDeduction(salaryDeductionEditIndex, salaryDeductionEditId)">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Nama Potongan</label>
              <input v-model="salaryDeductionModel.edit.name" type="text" class="form-control">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Tipe</label>
              <select v-model="salaryDeductionModel.edit.type" class="form-control">
                <option value="Jumlah Tetap">Jumlah Tetap</option>
                <option value="Tergantung Output">Tergantung Output</option>
                <option value="Manual">Manual</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Pengurang PPh</label>
              <select v-model="salaryDeductionModel.edit.pph" class="form-control">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm" v-bind:disabled="loadingSalaryDeductionEdit"><span v-if="loadingSalaryDeductionEdit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Save</button>
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
  Vue.component('salary-income', {
    props: ['id', 'index', 'name', 'type', 'pph21', 'typea1', 'isactive', 'isdefault', 'typea1items', 'thrincome', 'ondelete', 'onopenmodal'],
    template: `
    <tr>
      <td>@{{ name }}</td>
      <td class="text-capitalize">@{{ type }}</td>
      <td>@{{ (pph21 || pph21 == '1') ? 'Ya' : 'Tidak' }}</td>
      <td>@{{ (typea1) ? typea1items.filter(item => item.id == typea1)[0].text : '' }}</td>
      <td>@{{ (thrincome || thrincome == '1') ? 'Ya' : 'Tidak' }}</td>
      <td v-if="isactive || isactive == '1'" class="text-center"><span class="badge badge-success">Active</span></td>
      <td v-else class="text-center"><span class="badge badge-success">Inactive</span></td>
      <td class="text-center">
        @if(in_array("editSalarySetting", $userLoginPermissions))
        <div v-if="isdefault == 0" class="btn-group" role="group" aria-label="Action Buttons">
          <button class="btn btn-sm btn-light" @click="onopenmodal(index, id)"><i class="fas fa-fw fa-pencil-alt"></i></button>
          <button type="button" class="btn btn-sm btn-light" @click="ondelete(index, id)"><i class="fas fa-fw fa-trash"></i></button>
        </div>
        <em v-else>Default</em>
        @endif
      </td>
    </tr>
    `,
  })

  Vue.component('salary-deduction', {
    props: ['id', 'index', 'name', 'type', 'pph', 'isactive', 'ondelete', 'onopenmodal'],
    template: `
    <tr>
      <td>@{{ name }}</td>
      <td>@{{ type }}</td>
      <td>@{{ (pph) ? 'Ya' : 'Tidak' }}</td>
      <td v-if="isactive" class="text-center"><span class="badge badge-success">Active</span></td>
      <td v-else class="text-center"><span class="badge badge-success">Inactive</span></td>
      <td class="text-center">
        @if(in_array("editSalarySetting", $userLoginPermissions))
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
      salaryIncomes: JSON.parse('{!! $salary_incomes !!}'),
      salaryIncomeModel: {
        add: {
          name: '',
          type: 'Jumlah Tetap',
          pph21: 0,
          typeA1: 'type_a1_1',
          thrIncome: 0,
          isActive: 1,
        },
        edit: {
          name: '',
          type: '',
          pph21: 0,
          typeA1: '',
          thrIncome: 0,
          isActive: 1,
        }
      },
      salaryIncomeEditId: null,
      salaryIncomeEditIndex: null,
      salaryDeductions: JSON.parse('{!! $salary_deductions !!}'),
      salaryDeductionModel: {
        add: {
          name: '',
          type: 'Jumlah Tetap',
          pph: 0,
          isActive: 1,
        },
        edit: {
          name: '',
          type: '',
          pph: 0,
          isActive: 1,
        },
        // edit: {
        //   name: '',
        //   type: '',
        //   pph21: 0,
        //   typeA1: '',
        //   isActive: 1,
        // }
      },
      salaryDeductionEditId: null,
      salaryDeductionEditIndex: null,
      typeA1Items: [{
          id: 'type_a1_1',
          text: 'Tunjangan Lainnya, Uang Lembur, dsb',
        },
        {
          id: 'type_a1_2',
          text: 'Honorarium dan Imbalan Lainnya Sejenis',
        },
        {
          id: 'type_a1_3',
          text: 'Premi Asuransi yang Dibayar Pemberi Kerja',
        },
        {
          id: 'type_a1_4',
          text: 'Penerima dalam bentuk natura dan kenikmatan lainnya yang dikenakan pemotongan pph 21',
        },
        {
          id: 'type_a1_5',
          text: 'Tantiem, Bonus, Gratifikasi, Jasa Produksi, dan THR',
        },
      ],
      loadingSalaryIncome: false,
      loadingSalaryIncomeEdit: false,
      loadingSalaryDeduction: false,
      loadingSalaryDeductionEdit: false,
      hasOvertime: parseInt('{{ $salary_setting->has_overtime }}'),
      overtimeFormula: '{{ $salary_setting->overtime_formula }}',
      overtimeNominalPerHour: '{{ $salary_setting->overtime_nominal_per_hour }}',
      thrMinMonthsOfService: '{{ $salary_setting->thr_min_months_of_service }}',
      thrAmount: '{{ $salary_setting->thr_amount }}',
      thrType: '{{ $salary_setting->thr_type }}',
      thrForLessOneYear: '{{ $salary_setting->thr_for_less_one_year }}',
      hasLeave: parseInt('{{ $salary_setting->has_leave }}'),
      salaryForCareerChanges: '{{ $salary_setting->salary_for_career_changes }}',
      proporsionalFormulaCareerChanges: '{{ $salary_setting->proporsional_formula_career_changes }}',
      salaryForMiddleOut: '{{ $salary_setting->salary_for_middle_out }}',
      proporsionalFormulaMiddleOut: '{{ $salary_setting->proporsional_formula_middle_out }}',
      workDayPerMonth: '{{ $salary_setting->work_day_per_month }}',
      workMonday: '{{ $salary_setting->work_monday }}',
      workTuesday: '{{ $salary_setting->work_tuesday }}',
      workWednesday: '{{ $salary_setting->work_wednesday }}',
      workThursday: '{{ $salary_setting->work_thursday }}',
      workFriday: '{{ $salary_setting->work_friday }}',
      workSaturday: '{{ $salary_setting->work_saturday }}',
      workSunday: '{{ $salary_setting->work_sunday }}',
      hasDigitalAccount: parseInt('{{ $salary_setting->has_digital_account }}'),
      loadingSetting: false,
      // Summary
      hasOvertimeSummary: parseInt('{{ $salary_setting->has_overtime }}'),
      overtimeFormulaSummary: '{{ $salary_setting->overtime_formula }}',
      overtimeNominalPerHourSummary: '{{ $salary_setting->overtime_nominal_per_hour }}',
      thrMinMonthsOfServiceSummary: '{{ $salary_setting->thr_min_months_of_service }}',
      thrAmountSummary: '{{ $salary_setting->thr_amount }}',
      thrTypeSummary: '{{ $salary_setting->thr_type }}',
      thrForLessOneYearSummary: '{{ $salary_setting->thr_for_less_one_year }}',
      hasLeaveSummary: parseInt('{{ $salary_setting->has_leave }}'),
      salaryForCareerChangesSummary: '{{ $salary_setting->salary_for_career_changes }}',
      proporsionalFormulaCareerChangesSummary: '{{ $salary_setting->proporsional_formula_career_changes }}',
      salaryForMiddleOutSummary: '{{ $salary_setting->salary_for_middle_out }}',
      proporsionalFormulaMiddleOutSummary: '{{ $salary_setting->proporsional_formula_middle_out }}',
      hasDigitalAccountSummary: parseInt('{{ $salary_setting->has_digital_account }}'),
      // End::Summary
      toast: false,
      toastType: 'success',
      toastTitle: 'Success',
      toastText: 'Task has been saved',
    },
    methods: {
      addSalaryIncome: function() {
        let vm = this;
        vm.loadingSalaryIncome = true;
        axios.post('/salary-income', {
            name: this.salaryIncomeModel.add.name,
            type: this.salaryIncomeModel.add.type,
            pph21: this.salaryIncomeModel.add.pph21,
            type_a1: this.salaryIncomeModel.add.typeA1,
            thr_income: this.salaryIncomeModel.add.thrIncome,
            is_active: this.salaryIncomeModel.add.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingSalaryIncome = false;
            vm.showToast('Success', 'Salary income has been added', 'success');
            vm.salaryIncomes.push(response.data.data);
            vm.resetSalaryIncomeAdd();
          })
          .catch(function(error) {
            vm.showToast('Error', 'Failed to add salary income', 'error');
            vm.loadingSalaryIncome = false;
            console.log(error);
          });
      },
      openEditSalaryIncomeModal: function(index, id) {
        this.salaryIncomeEditId = id;
        this.salaryIncomeEditIndex = index;
        this.salaryIncomeModel.edit.name = this.salaryIncomes[index].name;
        this.salaryIncomeModel.edit.type = this.salaryIncomes[index].type;
        this.salaryIncomeModel.edit.pph21 = this.salaryIncomes[index].pph21;
        this.salaryIncomeModel.edit.typeA1 = this.salaryIncomes[index].type_a1;
        this.salaryIncomeModel.edit.thrIncome = this.salaryIncomes[index].thr_income;
        this.salaryIncomeModel.edit.isActive = this.salaryIncomes[index].is_active;
        $('#editIncomeModal').modal('show');
      },
      editSalaryIncome: function(index, id) {
        let vm = this;
        vm.loadingSalaryIncomeEdit = true;
        axios.patch('/salary-income/' + id, {
            name: this.salaryIncomeModel.edit.name,
            type: this.salaryIncomeModel.edit.type,
            pph21: this.salaryIncomeModel.edit.pph21,
            type_a1: this.salaryIncomeModel.edit.typeA1,
            thr_income: this.salaryIncomeModel.edit.thrIncome,
            is_active: this.salaryIncomeModel.edit.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingSalaryIncomeEdit = false;
            // vm.salaryIncomes.push(response.data.data);
            vm.salaryIncomes[index] = response.data.data;
            $('#editIncomeModal').modal('hide');
            vm.resetSalaryIncomeEdit();
            vm.showToast('Success', 'Salary income has been changed', 'success');
          })
          .catch(function(error) {
            vm.showToast('Error', 'Failed to edit salary income', 'error');
            vm.loadingSalaryIncomeEdit = false;
            console.log(error);
          });
      },
      deleteSalaryIncome: function(index, id) {
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
            return axios.delete('/salary-income/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Failed to remove member', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Salary income has been removed', 'success');
            vm.salaryIncomes.splice(index, 1);
          }
        })
      },
      addSalaryDeduction: function() {
        let vm = this;
        vm.loadingSalaryDeduction = true;
        axios.post('/salary-deduction', {
            name: this.salaryDeductionModel.add.name,
            type: this.salaryDeductionModel.add.type,
            pph: this.salaryDeductionModel.add.pph,
            is_active: this.salaryDeductionModel.add.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingSalaryDeduction = false;
            vm.showToast('Success', 'Salary deduction has been added', 'success');
            vm.salaryDeductions.push(response.data.data);
            // vm.resetSalaryAdd();
          })
          .catch(function(error) {
            vm.showToast('Error', 'Failed to add salary deduction', 'error');
            vm.loadingSalaryDeduction = false;
            console.log(error);
          });
      },
      openEditSalaryDeductionModal: function(index, id) {
        this.salaryDeductionEditId = id;
        this.salaryDeductionEditIndex = index;
        this.salaryDeductionModel.edit.name = this.salaryDeductions[index].name;
        this.salaryDeductionModel.edit.type = this.salaryDeductions[index].type;
        this.salaryDeductionModel.edit.pph = this.salaryDeductions[index].pph;
        this.salaryDeductionModel.edit.isActive = this.salaryDeductions[index].is_active;
        $('#editDeductionModal').modal('show');
      },
      editSalaryDeduction: function(index, id) {
        let vm = this;
        vm.loadingSalaryDeductionEdit = true;
        axios.patch('/salary-deduction/' + id, {
            name: this.salaryDeductionModel.edit.name,
            type: this.salaryDeductionModel.edit.type,
            pph: this.salaryDeductionModel.edit.pph,
            is_active: this.salaryDeductionModel.edit.isActive,
          })
          .then(function(response) {
            console.log(response)
            vm.loadingSalaryDeductionEdit = false;
            // vm.salaryDeductions.push(response.data.data);
            vm.salaryDeductions[index] = response.data.data;
            $('#editDeductionModal').modal('hide');
            vm.resetSalaryDeductionEdit();
            vm.showToast('Success', 'Salary deduction has been changed', 'success');
          })
          .catch(function(error) {
            vm.showToast('Error', 'Failed to edit salary deduction', 'error');
            vm.loadingSalaryDeductionEdit = false;
            console.log(error);
          });
      },
      deleteSalaryDeduction: function(index, id) {
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
            return axios.delete('/salary-deduction/' + id)
              .then(function(response) {
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error.data);
                vm.showToast('Error', 'Failed to remove member', 'error');
              });
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
          if (result.isConfirmed) {
            vm.showToast('Success', 'Salary deduction has been removed', 'success');
            vm.salaryDeductions.splice(index, 1);
          }
        })
      },
      resetSalaryIncomeAdd: function() {
        this.salaryIncomeModel.add.name = '';
        this.salaryIncomeModel.add.type = 'Jumlah Tetap';
        this.salaryIncomeModel.add.pph21 = 0;
        this.salaryIncomeModel.add.typeA1 = 'type_a1_1';
        this.salaryIncomeModel.add.thrIncome = 0;
        this.salaryIncomeModel.add.isActive = 1;
      },
      resetSalaryIncomeEdit: function() {
        this.salaryIncomeModel.edit.name = '';
        this.salaryIncomeModel.edit.type = 'Jumlah Tetap';
        this.salaryIncomeModel.edit.pph21 = 0;
        this.salaryIncomeModel.edit.typeA1 = 'type_a1_1';
        this.salaryIncomeModel.edit.thrIncome = 0;
        this.salaryIncomeModel.edit.isActive = 1;
      },
      resetSalaryDeductionAdd: function() {
        this.salaryDeductionModel.add.name = '';
        this.salaryDeductionModel.add.type = 'Jumlah Tetap';
        this.salaryDeductionModel.add.pph = 0;
        this.salaryDeductionModel.add.isActive = 1;
      },
      resetSalaryDeductionEdit: function() {
        this.salaryDeductionModel.edit.name = '';
        this.salaryDeductionModel.edit.type = 'Jumlah Tetap';
        this.salaryDeductionModel.edit.pph = 0;
        this.salaryDeductionModel.edit.isActive = 1;
      },
      changeSalarySetting: function(body, assignSummary) {
        const vm = this;
        vm.loadingSetting = true;
        axios.post('/setting/salary',
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
      showToast: function(title, text, type = 'success') {
        this.toastTitle = title;
        this.toastText = text;
        this.toastType = type;
        $('#liveToast').toast('show');
      },
      hideToast: function() {
        // this.toast = false;
      },
    },
    computed: {
      proporsionalFormulaMiddleOutBody: function() {
        if (this.proporsionalFormulaMiddleOut == 'hari_kerja') {
          return {
            proporsional_formula_middle_out: this.proporsionalFormulaMiddleOut,
            work_monday: this.workMonday,
            work_tuesday: this.workTuesday,
            work_wednesday: this.workWednesday,
            work_thursday: this.workThursday,
            work_friday: this.workFriday,
            work_saturday: this.workSaturday,
            work_sunday: this.workSunday,
          }
        } else if (this.proporsionalFormulaMiddleOut == 'hari_kerja_manual') {
          return {
            proporsional_formula_middle_out: this.proporsionalFormulaMiddleOut,
            work_day_per_month: this.workDayPerMonth
          }
        }
        return {
          proporsional_formula_middle_out: this.proporsionalFormulaMiddleOut
        }
      }
    }
  })
</script>

@endsection