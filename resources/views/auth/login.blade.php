<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
  <link href="{{ asset('vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('libs/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
  <style>
    html,
    body {
      height: 100%;
    }

    body {
      display: -ms-flexbox;
      display: flex;
      -ms-flex-align: center;
      align-items: center;
      padding-top: 40px;
      padding-bottom: 40px;
    }
  </style>
  <meta name="robots" content="noindex, nofollow">
</head>

<body>
  <!-- ============================================================== -->
  <!-- login page  -->
  <!-- ============================================================== -->
  <!-- <div style="height: 100vh; width: 100%; display: flex; justify-content: center; align-items: center;">
    <div class="text-center">
      <h1>MAINTENANCE</h1>
      <p>Sorry, you can comeback later</p>
    </div>
  </div> -->
  <div class="splash-container" id="app">
    <div class="card ">
      <div class="card-header text-center">
        <h3>MAGENTA HRD</h3>
        <span class="splash-description">Please enter your user information.</span>
      </div>
      <div class="card-body">
        <form autocomplete="off" @submit.prevent="login">
          <div class="form-group">
            <input class="form-control form-control-lg" v-model="username" id="username" type="text" placeholder="Username" autocomplete="off">
          </div>
          <div class="form-group">
            <div class="input-group mb-3">
              <input class="form-control form-control-lg" v-model="password" id="password" :type="passwordVisible ? 'text' : 'password'" placeholder="Password">
              <div class="input-group-append">
                <button class="btn btn-outline-light" type="button" @click="togglePasswordVisibility" id="button-addon2"><i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i></button>
              </div>
            </div>

          </div>
          <p v-if="incorrectCredential" class="text-danger text-center">Username atau password salah</p>
          <button type="submit" class="btn btn-primary btn-lg btn-block" v-bind:disabled="loading || disable"><span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sign in</button>
        </form>
      </div>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- end login page  -->
  <!-- ============================================================== -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
  <!-- <script src="{{ asset('js/adminlte.min.js') }}"></script> -->
  <script>
    let app = new Vue({
      el: '#app',
      data: {
        username: '',
        password: '',
        loading: false,
        passwordVisible: false,
        incorrectCredential: false,
        disable: false,
      },
      methods: {
        login: function(e) {
          e.preventDefault();
          this.loading = true;

          let vm = this;
          axios.post('/login/action/authenticate', {
              username: this.username,
              password: this.password,
            })
            .then(function(response) {
              vm.loading = false;
              vm.incorrectCredential = false;
              vm.disable = true;
              window.location.href = '/';
              // console.log(response.data);
              // Swal.fire({
              //   icon: 'success',
              //   title: 'Berhasil',
              //   text: 'Login berhasil',
              // })
              // setTimeout(() => {
              //   window.location.href = response.data.redirect;
              // }, 1000)
              // window.location.href = response.data.redirect;
            })
            .catch(function(error) {
              vm.loading = false;
              vm.incorrectCredential = true;
              // console.log(error.data);
              // Swal.fire({
              //   icon: 'error',
              //   title: 'Gagal',
              //   text: 'Username atau password salah',
              // })
            });
        },
        togglePasswordVisibility: function() {
          this.passwordVisible = !this.passwordVisible;
        }
      }
    })
  </script>
</body>

</html>