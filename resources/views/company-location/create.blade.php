@extends('layouts.app')

@section('head')
<link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
<link href="{{ asset('vendor/select2/css/select2.css') }}" rel="stylesheet" />
<link href="https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.js"></script>
@endsection

@section('title', 'Magenta HRD')

@section('pagestyle')
<style>
    #map {
        width: 100%;
        height: 450px;
    }
</style>
@endsection

@section('bodyscript')
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css" type="text/css">
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
                        <h2 class="pageheader-title">Lokasi Kantor</h2>
                        <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/company-location" class="breadcrumb-link">Locations</a></li>
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
                            <h5 class="card-header">Tambah Lokasi Kantor</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <!-- <div class="form-group col-md-6">
                                        <label for="company">Company<sup class="text-danger">*</sup></label>
                                        <select v-model="company" v-on:change="onChangeCompany($event)" name="company" id="company" class="form-control form-control-sm">
                                            <option value="" disabled>Choose Company</option>
                                            <option v-for="company in companies" :key="company.id" :value="company.id">@{{ company.name }}</option>
                                        </select>
                                    </div> -->
                                    <!-- <script type="text/x-template" id="demo-template"> -->
                                    <div class="form-group col-md-6">
                                        <label for="location-name">Nama Kantor<sup class="text-danger">*</sup></label>
                                        <input v-model="locationName" type="text" class="form-control form-control-sm" id="location-name" placeholder="ex: Main Office" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="location-head">Kepala Kantor (Opsional)</label>
                                        <select2 v-model="locationHead" :options="employees" id="location-head" class="form-control form-control-sm use-select2">
                                        </select2>
                                    </div>
                                    <!-- </script> -->
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="contact-number">Telepon</label>
                                        <input v-model="contactNumber" type="text" class="form-control form-control-sm" id="contact-number">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input v-model="email" type="email" class="form-control form-control-sm" id="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="npwp">NPWP</label>
                                    <input v-model="npwp" type="text" class="form-control form-control-sm" id="npwp">
                                </div>
                                <div class="form-group">
                                    <label for="address">Alamat<sup class="text-danger">*</sup></label>
                                    <textarea v-model="address" name="address" id="address" class="form-control form-control-sm" style="white-space: pre-line;"></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="province">Provinsi<sup class="text-danger">*</sup></label>
                                        <input v-model="province" type="text" class="form-control" id="province" required>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="city">Kota<sup class="text-danger">*</sup></label>
                                        <input v-model="city" type="text" class="form-control" id="city" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="zip-code">Kode Pos<sup class="text-danger">*</sup></label>
                                        <input v-model="zipCode" type="text" class="form-control" id="zip-code" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="country">Negara<sup class="text-danger">*</sup></label>
                                    <input v-model="country" type="text" class="form-control form-control-sm" id="country" value="Indonesia" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label for="longitude">Longitude<sup class="text-danger">*</sup></label>
                                        <input v-model="longitude" type="text" class="form-control" id="longitude" required>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="latitude">Latitude<sup class="text-danger">*</sup></label>
                                        <input v-model="latitude" type="text" class="form-control" id="latitude" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label></sup></label>
                                        <button type="button" class="btn btn-sm btn-primary w-100 mt-1" data-toggle="modal" data-target="#mapModal">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Button trigger modal -->


                                <!-- Modal -->
                                <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="mapModalLabel">Choose Office Location</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="map"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Choose Location</button>
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

    function renderBreakLine(str) {
        return str.replace('\\r\\n', '\r\n');
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
        // template: "#demo-template",
        data: {
            companies: JSON.parse(jsonEscape('{!! $companies !!}')),
            companySelected: false,
            employees: JSON.parse('{!! $employees !!}'),
            company: '',
            locationHead: '',
            locationName: '',
            contactNumber: '',
            email: '',
            npwp: '',
            address: '',
            province: '',
            city: '',
            zipCode: '',
            country: 'Indonesia',
            latitude: -6.200000,
            longitude: 106.816666,
            loading: false,
            url: '/company-location',
        },
        methods: {
            submitForm: function() {
                // console.log('submitted');
                let vm = this;
                vm.loading = true;
                axios.post('/company-location', {
                        company: this.company,
                        location_head: parseInt(this.locationHead),
                        location_name: this.locationName,
                        contact_number: this.contactNumber,
                        email: this.email,
                        npwp: this.npwp,
                        address: this.address,
                        province: this.province,
                        city: this.city,
                        zip_code: this.zipCode,
                        country: this.country,
                        longitude: this.longitude,
                        latitude: this.latitude,
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
            onChangeCompany: function(event) {
                //   console.log('changed');
                //   console.log(event.target.value);
                let id = event.target.value;
                if (this.companies.length > 0 && this.companies !== null) {
                    const company = this.companies.filter(company => company.id == id);
                    this.contactNumber = company[0].contact_number;
                    this.email = company[0].email;
                    this.npwp = company[0].contact_number;
                    this.address = renderBreakLine(company[0].address);
                    this.province = company[0].province;
                    this.city = company[0].city;
                    this.zipCode = company[0].zip_code;
                    this.country = company[0].country;

                    console.log(company[0].address, renderBreakLine(company[0].address));

                    setTimeout(() => {
                        this.employees.push({
                            id: 1,
                            text: 'royhan'
                        }, {
                            id: 2,
                            text: 'faisal'
                        }, {
                            id: 3,
                            text: 'reza'
                        }, )
                        this.companySelected = true;
                    }, 200)
                }
            },
            //   resetForm: function() {
            //     this.company,
            //     this.locationHead,
            //     this.contact_number,
            //     this.email,
            //     this.website,
            //     this.npwp,
            //     this.address,
            //     this.province,
            //     this.city,
            //     this.zipCode,
            //     this.country,
            //   },

        }
    })
</script>
<script>
    // console.log(app.$data.latitude);

    // let app.$data.longitude = app.$data.longitude;
    // let app.$data.latitude = app.$data.latitude;
    // let currentLongitude = 106.816666;
    // let currentLatitude = -6.200000;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
                let currentLongitude = position.coords.longitude;
                let currentLatitude = position.coords.latitude;
                app.$data.longitude = position.coords.longitude;
                app.$data.latitude = position.coords.latitude;
                initMap(currentLongitude, currentLatitude);
            },
            (error) => {
                alert('We can\'t locate your location');
                let currentLongitude = 106.816666;
                let currentLatitude = -6.200000;
                initMap(currentLongitude, currentLatitude);
            }, {
                enableHighAccuracy: true,
            }
        );
    } else {
        alert('Your browser does not support geolocation, we can\'t locate your location');
        let currentLongitude = 106.816666;
        let currentLatitude = -6.200000;
        initMap(currentLongitude, currentLatitude);
    }

    function initMap(initLongitude, initLatitude) {
        mapboxgl.accessToken = `{{ env('MAPBOX_ACCESS_TOKEN') }}`;
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [initLongitude, initLatitude],
            // center: [106.816666, -6.200000],
            zoom: 13
        });

        // Add the control to the map.
        var geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            marker: false,
            mapboxgl: mapboxgl
        })

        var marker = new mapboxgl.Marker({
            draggable: true,
            color: "royalblue"
        })

        marker.setLngLat({
                lng: initLongitude,
                lat: initLatitude
            })
            .addTo(map);

        function onDragEnd() {
            var lngLat = marker.getLngLat();
            console.log('On Drag Longitude: ' + lngLat.lng + ' Latitude: ' + lngLat.lat);
            app.$data.longitude = lngLat.lng;
            app.$data.latitude = lngLat.lat;
        }

        marker.on('dragend', onDragEnd);

        geocoder.on('result', function(e) {
            marker.remove();
            marker.setLngLat(e.result.center).addTo(map);
            app.$data.longitude = e.result.center[0];
            app.$data.latitude = e.result.center[1];
        });

        map.on('click', function(e) {
            marker.remove();
            marker.setLngLat(e.lngLat).addTo(map);
            app.$data.longitude = e.lngLat.lng;
            app.$data.latitude = e.lngLat.lat;
        });

        map.addControl(
            geocoder
        );

        map.addControl(
            new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true
            })
        );

        $('#mapModal').on('shown.bs.modal', function() {
            map.resize();
            map.flyTo({
                center: [app.$data.longitude, app.$data.latitude]
            });
            marker.remove();
            marker.setLngLat({
                lng: app.$data.longitude,
                lat: app.$data.latitude
            }).addTo(map);
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('.use-select2').select2({
            width: '100%',
        });
    })
</script>
@endsection