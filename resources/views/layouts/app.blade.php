<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="icon" href="{{ asset('images/magenta-hrd-icon.png') }}" type="image/png" sizes="16x16">
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
  <link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('libs/css/style.css') }}">
  <!-- jQuery -->
  <script src="{{ asset('vendor/jquery/jquery-3.3.1.min.js') }}"></script>
  @yield('head')
  <title>@yield('title')</title>
  @yield('pagestyle')
</head>

<body>
  @yield('bodyscript')
  <div class="dashboard-main-wrapper" id="app">
    @include('layouts.navbar')
    @include('layouts.sidebar')
    @yield('content')
  </div>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
  @yield('script')
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
  @yield('pagescript')
</body>

</html>