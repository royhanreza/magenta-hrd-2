@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link href="{{ asset('vendor/select2/css/select2.css') }}" rel="stylesheet" />
@endsection

@section('title', 'Magenta HRD')

@section('pagestyle')
<style>
    .btn-sm {
        padding: 4px 12px;
    }

    .input-group-text {
        line-height: 0;
        padding: 8px;
    }

    .shift-form-item {
        padding: 1em;
        border-radius: 5px;
        margin: 30px 0;
        background-color: #fafafa;
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
                        <h2 class="pageheader-title">Office Shift </h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/office-shift" class="breadcrumb-link">Office Shift</a></li>
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
                    <form autocomplete="off" @submit.prevent="submitForm">
                        <div class="card">
                            <h5 class="card-header">Tambah Shift Baru</h5>
                            <div class="card-body">
                                <!-- <div class="alert alert-info" role="alert">
                                    <span title="Kosongkan field waktu jika libur"><i class="fa fa-fw fa-exclamation-circle"></i> Leave the time field (In & Out time) blank if it is a holiday</span>
                                </div> -->
                                <div class="form-row">
                                    <!-- <div class="form-group col-md-6">
                                        <label for="company">Company<sup class="text-danger">*</sup></label>
                                        <select2 v-model="company" :options="companies" id="company" class="form-control form-control-sm" required>
                                        </select2>
                                    </div> -->
                                    <div class="form-group col-md-4">
                                        <label for="shift-name">Nama Shift<sup class="text-danger">*</sup></label>
                                        <input v-model="name" type="text" class="form-control form-control-sm" id="shift-name" required>
                                    </div>
                                </div>
                                <div class="shift-form-item">
                                    <strong>Senin</strong>
                                    <div class="form-row">
                                        <!-- <div class="form-group col-md-4">
                                        <label for="">Status Hari<sup class="text-danger">*</sup></label>
                                        <div class="input-group mb-3">
                                            <select v-model="mondayStatus" class="form-control form-control-sm">
                                                <option value="workday">Hari Kerja</option>
                                                <option value="holiday">Hari Libur</option>
                                            </select>
                                        </div>
                                    </div> -->
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Masuk<sup class="text-danger">*</sup></label>
                                            <input v-model="mondayInTime" type="time" @change="onChangeClock('mondayInTime', 'mondayOutTime', 'mondayWorkingHoursEditable', 'mondayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Keluar<sup class="text-danger">*</sup></label>
                                            <input v-model="mondayOutTime" type="time" @change="onChangeClock('mondayInTime', 'mondayOutTime', 'mondayWorkingHoursEditable', 'mondayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Status Hari<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <select v-model="mondayStatus" class="form-control form-control-sm">
                                                    <option value="workday">Hari Kerja</option>
                                                    <option value="holiday">Hari Libur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Kerja<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" v-model="mondayWorkingHoursEditable" @change="onChangeClock('mondayInTime', 'mondayOutTime', 'mondayWorkingHoursEditable', 'mondayWorkingHours')" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <input v-model="mondayWorkingHours" type="number" class="form-control form-control-sm" :readonly="!mondayWorkingHoursEditable" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <input type="checkbox" v-model="mondayLateness" id="mondayLateness">
                                        <label for="mondayLateness">Terapkan <strong>keterlambatan</strong> untuk hari <strong>senin</strong></label>
                                    </div> -->
                                    <div>
                                        <input type="checkbox" v-model="mondayWorkAsOvertime" id="mondayWorkAsOvertime">
                                        <label for="mondayWorkAsOvertime">Hitung jam kerja sebagai lembur</label>
                                    </div>
                                    <div v-if="mondayWorkAsOvertime || mondayWorkAsOvertime == 1" class="pl-3">
                                        <div class="row align-items-center">
                                            <label for="mondayMaxOvertime" class="col-sm-2">Maksimal Jam Kerja</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="mondayMaxOvertime" class="form-control form-control-sm" id="mondayMaxOvertime" required max="24" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="shift-form-item">
                                    <strong>Selasa</strong>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Masuk<sup class="text-danger">*</sup></label>
                                            <input v-model="tuesdayInTime" type="time" @change="onChangeClock('tuesdayInTime', 'tuesdayOutTime', 'tuesdayWorkingHoursEditable', 'tuesdayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Keluar<sup class="text-danger">*</sup></label>
                                            <input v-model="tuesdayOutTime" type="time" @change="onChangeClock('tuesdayInTime', 'tuesdayOutTime', 'tuesdayWorkingHoursEditable', 'tuesdayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Status Hari<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <select v-model="tuesdayStatus" class="form-control form-control-sm">
                                                    <option value="workday">Hari Kerja</option>
                                                    <option value="holiday">Hari Libur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Kerja<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" v-model="tuesdayWorkingHoursEditable" @change="onChangeClock('tuesdayInTime', 'tuesdayOutTime', 'tuesdayWorkingHoursEditable', 'tuesdayWorkingHours')" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <input v-model="tuesdayWorkingHours" type="number" class="form-control form-control-sm" :readonly="!tuesdayWorkingHoursEditable" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <input type="checkbox" v-model="tuesdayLateness" id="tuesdayLateness">
                                        <label for="tuesdayLateness">Terapkan <strong>keterlambatan</strong> untuk hari <strong>selasa</strong></label>
                                    </div> -->
                                    <div>
                                        <input type="checkbox" v-model="tuesdayWorkAsOvertime" id="tuesdayWorkAsOvertime">
                                        <label for="tuesdayWorkAsOvertime">Hitung jam kerja sebagai lembur</label>
                                    </div>
                                    <div v-if="tuesdayWorkAsOvertime || tuesdayWorkAsOvertime == 1" class="pl-3">
                                        <div class="row align-items-center">
                                            <label for="tuesdayMaxOvertime" class="col-sm-2">Maksimal Jam Kerja</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="tuesdayMaxOvertime" class="form-control form-control-sm" id="tuesdayMaxOvertime" required max="24" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="shift-form-item">
                                    <strong>Rabu</strong>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Masuk<sup class="text-danger">*</sup></label>
                                            <input v-model="wednesdayInTime" type="time" @change="onChangeClock('wednesdayInTime', 'wednesdayOutTime', 'wednesdayWorkingHoursEditable', 'wednesdayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Keluar<sup class="text-danger">*</sup></label>
                                            <input v-model="wednesdayOutTime" type="time" @change="onChangeClock('wednesdayInTime', 'wednesdayOutTime', 'wednesdayWorkingHoursEditable', 'wednesdayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Status Hari<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <select v-model="wednesdayStatus" class="form-control form-control-sm">
                                                    <option value="workday">Hari Kerja</option>
                                                    <option value="holiday">Hari Libur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Kerja<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" v-model="wednesdayWorkingHoursEditable" @change="onChangeClock('wednesdayInTime', 'wednesdayOutTime', 'wednesdayWorkingHoursEditable', 'wednesdayWorkingHours')" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <input v-model="wednesdayWorkingHours" type="number" class="form-control form-control-sm" :readonly="!wednesdayWorkingHoursEditable" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <input type="checkbox" v-model="wednesdayLateness" id="wednesdayLateness">
                                        <label for="wednesdayLateness">Terapkan <strong>keterlambatan</strong> untuk hari <strong>rabu</strong></label>
                                    </div> -->
                                    <div>
                                        <input type="checkbox" v-model="wednesdayWorkAsOvertime" id="wednesdayWorkAsOvertime">
                                        <label for="wednesdayWorkAsOvertime">Hitung jam kerja sebagai lembur</label>
                                    </div>
                                    <div v-if="wednesdayWorkAsOvertime || wednesdayWorkAsOvertime == 1" class="pl-3">
                                        <div class="row align-items-center">
                                            <label for="wednesdayMaxOvertime" class="col-sm-2">Maksimal Jam Kerja</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="wednesdayMaxOvertime" class="form-control form-control-sm" id="wednesdayMaxOvertime" required max="24" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="shift-form-item">
                                    <strong>Kamis</strong>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Masuk<sup class="text-danger">*</sup></label>
                                            <input v-model="thursdayInTime" type="time" @change="onChangeClock('thursdayInTime', 'thursdayOutTime', 'thursdayWorkingHoursEditable', 'thursdayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Keluar<sup class="text-danger">*</sup></label>
                                            <input v-model="thursdayOutTime" type="time" @change="onChangeClock('thursdayInTime', 'thursdayOutTime', 'thursdayWorkingHoursEditable', 'thursdayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Status Hari<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <select v-model="thursdayStatus" class="form-control form-control-sm">
                                                    <option value="workday">Hari Kerja</option>
                                                    <option value="holiday">Hari Libur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Kerja<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" v-model="thursdayWorkingHoursEditable" @change="onChangeClock('thursdayInTime', 'thursdayOutTime', 'thursdayWorkingHoursEditable', 'thursdayWorkingHours')" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <input v-model="thursdayWorkingHours" :readonly="!thursdayWorkingHoursEditable" type="number" class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <input type="checkbox" v-model="thursdayLateness" id="thursdayLateness">
                                        <label for="thursdayLateness">Terapkan <strong>keterlambatan</strong> untuk hari <strong>kamis</strong></label>
                                    </div> -->
                                    <div>
                                        <input type="checkbox" v-model="thursdayWorkAsOvertime" id="thursdayWorkAsOvertime">
                                        <label for="thursdayWorkAsOvertime">Hitung jam kerja sebagai lembur</label>
                                    </div>
                                    <div v-if="thursdayWorkAsOvertime || thursdayWorkAsOvertime == 1" class="pl-3">
                                        <div class="row align-items-center">
                                            <label for="thursdayMaxOvertime" class="col-sm-2">Maksimal Jam Kerja</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="thursdayMaxOvertime" class="form-control form-control-sm" id="thursdayMaxOvertime" required max="24" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="shift-form-item">
                                    <strong>Jumat</strong>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Masuk<sup class="text-danger">*</sup></label>
                                            <input v-model="fridayInTime" type="time" @change="onChangeClock('fridayInTime', 'fridayOutTime', 'fridayWorkingHoursEditable', 'fridayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Keluar<sup class="text-danger">*</sup></label>
                                            <input v-model="fridayOutTime" type="time" @change="onChangeClock('fridayInTime', 'fridayOutTime', 'fridayWorkingHoursEditable', 'fridayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Status Hari<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <select v-model="fridayStatus" class="form-control form-control-sm">
                                                    <option value="workday">Hari Kerja</option>
                                                    <option value="holiday">Hari Libur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Kerja<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" v-model="fridayWorkingHoursEditable" @change="onChangeClock('fridayInTime', 'fridayOutTime', 'fridayWorkingHoursEditable', 'fridayWorkingHours')" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <input v-model="fridayWorkingHours" type="number" class="form-control form-control-sm" :readonly="!fridayWorkingHoursEditable" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <input type="checkbox" v-model="fridayLateness" id="fridayLateness">
                                        <label for="fridayLateness">Terapkan <strong>keterlambatan</strong> untuk hari <strong>Jumat</strong></label>
                                    </div> -->
                                    <div>
                                        <input type="checkbox" v-model="fridayWorkAsOvertime" id="fridayWorkAsOvertime">
                                        <label for="fridayWorkAsOvertime">Hitung jam kerja sebagai lembur</label>
                                    </div>
                                    <div v-if="fridayWorkAsOvertime || fridayWorkAsOvertime == 1" class="pl-3">
                                        <div class="row align-items-center">
                                            <label for="fridayMaxOvertime" class="col-sm-2">Maksimal Jam Kerja</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="fridayMaxOvertime" class="form-control form-control-sm" id="fridayMaxOvertime" required max="24" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="shift-form-item">
                                    <strong>Sabtu</strong>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Masuk<sup class="text-danger">*</sup></label>
                                            <input v-model="saturdayInTime" type="time" @change="onChangeClock('saturdayInTime', 'saturdayOutTime', 'saturdayWorkingHoursEditable', 'saturdayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Keluar<sup class="text-danger">*</sup></label>
                                            <input v-model="saturdayOutTime" type="time" @change="onChangeClock('saturdayInTime', 'saturdayOutTime', 'saturdayWorkingHoursEditable', 'saturdayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Status Hari<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <select v-model="saturdayStatus" class="form-control form-control-sm">
                                                    <option value="workday">Hari Kerja</option>
                                                    <option value="holiday">Hari Libur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Kerja<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" v-model="saturdayWorkingHoursEditable" @change="onChangeClock('saturdayInTime', 'saturdayOutTime', 'saturdayWorkingHoursEditable', 'saturdayWorkingHours')" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <input v-model="saturdayWorkingHours" type="number" class="form-control form-control-sm" :readonly="!saturdayWorkingHoursEditable" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <input type="checkbox" v-model="saturdayLateness" id="saturdayLateness">
                                        <label for="saturdayLateness">Terapkan <strong>keterlambatan</strong> untuk hari <strong>sabtu</strong></label>
                                    </div> -->
                                    <div>
                                        <input type="checkbox" v-model="saturdayWorkAsOvertime" id="saturdayWorkAsOvertime">
                                        <label for="saturdayWorkAsOvertime">Hitung jam kerja sebagai lembur</label>
                                    </div>
                                    <div v-if="saturdayWorkAsOvertime || saturdayWorkAsOvertime == 1" class="pl-3">
                                        <div class="row align-items-center">
                                            <label for="saturdayMaxOvertime" class="col-sm-2">Maksimal Jam Kerja</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="saturdayMaxOvertime" class="form-control form-control-sm" id="saturdayMaxOvertime" required max="24" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="shift-form-item">
                                    <strong>Minggu</strong>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Masuk<sup class="text-danger">*</sup></label>
                                            <input v-model="sundayInTime" type="time" @change="onChangeClock('sundayInTime', 'sundayOutTime', 'sundayWorkingHoursEditable', 'sundayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Keluar<sup class="text-danger">*</sup></label>
                                            <input v-model="sundayOutTime" type="time" @change="onChangeClock('sundayInTime', 'sundayOutTime', 'sundayWorkingHoursEditable', 'sundayWorkingHours')" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="">Status Hari<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <select v-model="sundayStatus" class="form-control form-control-sm">
                                                    <option value="workday">Hari Kerja</option>
                                                    <option value="holiday">Hari Libur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Jam Kerja<sup class="text-danger">*</sup></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" v-model="sundayWorkingHoursEditable" @change="onChangeClock('sundayInTime', 'sundayOutTime', 'sundayWorkingHoursEditable', 'sundayWorkingHours')" aria-label="Checkbox for following text input">
                                                    </div>
                                                </div>
                                                <input v-model="sundayWorkingHours" type="number" class="form-control form-control-sm" :readonly="!sundayWorkingHoursEditable" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <input type="checkbox" v-model="sundayLateness" id="sundayLateness">
                                        <label for="sundayLateness">Terapkan <strong>keterlambatan</strong> untuk hari <strong>minggu</strong></label>
                                    </div> -->
                                    <div>
                                        <input type="checkbox" v-model="sundayWorkAsOvertime" id="sundayWorkAsOvertime">
                                        <label for="sundayWorkAsOvertime">Hitung jam kerja sebagai lembur</label>
                                    </div>
                                    <div v-if="sundayWorkAsOvertime || sundayWorkAsOvertime == 1" class="pl-3">
                                        <div class="row align-items-center">
                                            <label for="sundayMaxOvertime" class="col-sm-2">Maksimal Jam Kerja</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="sundayMaxOvertime" class="form-control form-control-sm" id="sundayMaxOvertime" required max="24" min="0">
                                            </div>
                                        </div>
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
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('vendor/slimscroll/jquery.slimscroll.js') }}"></script>
<!-- additional script -->
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
    function jsonEscape(str) {
        return str.replace(/\n/g, "\\\\n").replace(/\r/g, "\\\\r").replace(/\t/g, "\\\\t");
    }

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
                    vm.$emit("input", this.value);
                });
        },
        watch: {
            value: function(value) {
                // update value
                $(this.$el)
                    .val(value)
                    .trigger("change");
            },
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
            companies: JSON.parse(jsonEscape('{!! $companies !!}')),
            company: '',
            name: '{{ $shift->name }}',
            mondayInTime: '{{ $shift->monday_in_time }}',
            // mondayInTimeDisabled: false,
            mondayOutTime: '{{ $shift->monday_out_time }}',
            mondayLateness: Number('{{ $shift->monday_lateness }}'),
            mondayStatus: '{{ $shift->monday_status }}',
            mondayWorkingHours: '{{ $shift->monday_working_hours }}',
            mondayWorkingHoursEditable: parseInt('{{ $shift->monday_working_hours_editable }}'),
            mondayWorkAsOvertime: Number('{{ $shift->monday_work_as_overtime }}'),
            mondayMaxOvertime: Number('{{ $shift->monday_max_overtime }}'),

            tuesdayInTime: '{{ $shift->tuesday_in_time }}',
            tuesdayOutTime: '{{ $shift->tuesday_out_time }}',
            tuesdayLateness: Number('{{ $shift->tuesday_lateness }}'),
            tuesdayStatus: '{{ $shift->tuesday_status }}',
            tuesdayWorkingHours: '{{ $shift->tuesday_working_hours }}',
            tuesdayWorkingHoursEditable: parseInt('{{ $shift->tuesday_working_hours_editable }}'),
            tuesdayWorkAsOvertime: Number('{{ $shift->tuesday_work_as_overtime }}'),
            tuesdayMaxOvertime: Number('{{ $shift->tuesday_max_overtime }}'),

            wednesdayInTime: '{{ $shift->wednesday_in_time }}',
            wednesdayOutTime: '{{ $shift->wednesday_out_time }}',
            wednesdayLateness: Number('{{ $shift->wednesday_lateness }}'),
            wednesdayStatus: '{{ $shift->wednesday_status }}',
            wednesdayWorkingHours: '{{ $shift->wednesday_working_hours }}',
            wednesdayWorkingHoursEditable: parseInt('{{ $shift->wednesday_working_hours_editable }}'),
            wednesdayWorkAsOvertime: Number('{{ $shift->wednesday_work_as_overtime }}'),
            wednesdayMaxOvertime: Number('{{ $shift->wednesday_max_overtime }}'),

            thursdayInTime: '{{ $shift->thursday_in_time }}',
            thursdayOutTime: '{{ $shift->thursday_out_time }}',
            thursdayLateness: Number('{{ $shift->thursday_lateness }}'),
            thursdayStatus: '{{ $shift->thursday_status }}',
            thursdayWorkingHours: '{{ $shift->thursday_working_hours }}',
            thursdayWorkingHoursEditable: parseInt('{{ $shift->thursday_working_hours_editable }}'),
            thursdayWorkAsOvertime: Number('{{ $shift->thursday_work_as_overtime }}'),
            thursdayMaxOvertime: Number('{{ $shift->thursday_max_overtime }}'),

            fridayInTime: '{{ $shift->friday_in_time }}',
            fridayOutTime: '{{ $shift->friday_out_time }}',
            fridayLateness: Number('{{ $shift->friday_lateness }}'),
            fridayStatus: '{{ $shift->friday_status }}',
            fridayWorkingHours: '{{ $shift->friday_working_hours }}',
            fridayWorkingHoursEditable: parseInt('{{ $shift->friday_working_hours_editable }}'),
            fridayWorkAsOvertime: Number('{{ $shift->friday_work_as_overtime }}'),
            fridayMaxOvertime: Number('{{ $shift->friday_max_overtime }}'),

            saturdayInTime: '{{ $shift->saturday_in_time }}',
            saturdayOutTime: '{{ $shift->saturday_out_time }}',
            saturdayLateness: Number('{{ $shift->saturday_lateness }}'),
            saturdayStatus: '{{ $shift->saturday_status }}',
            saturdayWorkingHours: '{{ $shift->saturday_working_hours }}',
            saturdayWorkingHoursEditable: parseInt('{{ $shift->saturday_working_hours_editable }}'),
            saturdayWorkAsOvertime: Number('{{ $shift->saturday_work_as_overtime }}'),
            saturdayMaxOvertime: Number('{{ $shift->saturday_max_overtime }}'),

            sundayInTime: '{{ $shift->sunday_in_time }}',
            sundayOutTime: '{{ $shift->sunday_out_time }}',
            sundayLateness: Number('{{ $shift->sunday_lateness }}'),
            sundayStatus: '{{ $shift->sunday_status }}',
            sundayWorkingHours: '{{ $shift->sunday_working_hours }}',
            sundayWorkingHoursEditable: parseInt('{{ $shift->sunday_working_hours_editable }}'),
            sundayWorkAsOvertime: Number('{{ $shift->sunday_work_as_overtime }}'),
            sundayMaxOvertime: Number('{{ $shift->sunday_max_overtime }}'),

            loading: false,
            url: '/office-shift'
        },
        methods: {
            submitForm: function() {
                // console.log('submitted');
                let vm = this;
                vm.loading = true;
                axios.patch('/office-shift/{{ $shift->id }}', {
                        // company: this.company,
                        name: this.name,
                        monday_in_time: this.mondayInTime,
                        monday_out_time: this.mondayOutTime,
                        monday_lateness: this.mondayLateness,
                        monday_status: this.mondayStatus,
                        monday_working_hours: this.mondayWorkingHours,
                        monday_working_hours_editable: this.mondayWorkingHoursEditable,
                        monday_work_as_overtime: this.mondayWorkAsOvertime,
                        monday_max_overtime: this.mondayMaxOvertime,

                        tuesday_in_time: this.tuesdayInTime,
                        tuesday_out_time: this.tuesdayOutTime,
                        tuesday_lateness: this.tuesdayLateness,
                        tuesday_status: this.tuesdayStatus,
                        tuesday_working_hours: this.tuesdayWorkingHours,
                        tuesday_working_hours_editable: this.tuesdayWorkingHoursEditable,
                        tuesday_work_as_overtime: this.tuesdayWorkAsOvertime,
                        tuesday_max_overtime: this.tuesdayMaxOvertime,

                        wednesday_in_time: this.wednesdayInTime,
                        wednesday_out_time: this.wednesdayOutTime,
                        wednesday_lateness: this.wednesdayLateness,
                        wednesday_status: this.wednesdayStatus,
                        wednesday_working_hours: this.wednesdayWorkingHours,
                        wednesday_working_hours_editable: this.wednesdayWorkingHoursEditable,
                        wednesday_work_as_overtime: this.wednesdayWorkAsOvertime,
                        wednesday_max_overtime: this.wednesdayMaxOvertime,

                        thursday_in_time: this.thursdayInTime,
                        thursday_out_time: this.thursdayOutTime,
                        thursday_lateness: this.thursdayLateness,
                        thursday_status: this.thursdayStatus,
                        thursday_working_hours: this.thursdayWorkingHours,
                        thursday_working_hours_editable: this.thursdayWorkingHoursEditable,
                        thursday_work_as_overtime: this.thursdayWorkAsOvertime,
                        thursday_max_overtime: this.thursdayMaxOvertime,

                        friday_in_time: this.fridayInTime,
                        friday_out_time: this.fridayOutTime,
                        friday_lateness: this.fridayLateness,
                        friday_status: this.fridayStatus,
                        friday_working_hours: this.fridayWorkingHours,
                        friday_working_hours_editable: this.fridayWorkingHoursEditable,
                        friday_work_as_overtime: this.fridayWorkAsOvertime,
                        friday_max_overtime: this.fridayMaxOvertime,

                        saturday_in_time: this.saturdayInTime,
                        saturday_out_time: this.saturdayOutTime,
                        saturday_lateness: this.saturdayLateness,
                        saturday_status: this.saturdayStatus,
                        saturday_working_hours: this.saturdayWorkingHours,
                        saturday_working_hours_editable: this.saturdayWorkingHoursEditable,
                        saturday_work_as_overtime: this.saturdayWorkAsOvertime,
                        saturday_max_overtime: this.saturdayMaxOvertime,

                        sunday_in_time: this.sundayInTime,
                        sunday_out_time: this.sundayOutTime,
                        sunday_lateness: this.sundayLateness,
                        sunday_status: this.sundayStatus,
                        sunday_working_hours: this.sundayWorkingHours,
                        sunday_working_hours_editable: this.sundayWorkingHoursEditable,
                        sunday_work_as_overtime: this.sundayWorkAsOvertime,
                        sunday_max_overtime: this.sundayMaxOvertime,
                    })
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
                        console.log(error);
                        Swal.fire(
                            'Oops!',
                            'Something wrong',
                            'error'
                        )

                    });
            },
            resetClock: function(clock, disable) {
                // clock = 'asdasd';
                console.log(clock);
                disable = true;
                clock = '14:44';
            },
            onChangeClock: function(clockIn = '', clockOut = '', editable = '', workHours = '') {
                let vm = this;
                if (!vm[editable]) {
                    if (vm[clockIn] && vm[clockOut]) {
                        let timeStart = new Date("01/01/2007 " + vm[clockIn]).getHours();
                        let timeEnd = new Date("01/01/2007 " + vm[clockOut]).getHours();

                        let hourDiff = timeEnd - timeStart;

                        if (hourDiff < 0) {
                            hourDiff = 24 + hourDiff;
                        }
                        // return hourDiff;
                        vm[workHours] = hourDiff;
                    }
                }

                // return 'empty';
            }
        }
    })
</script>
@endsection