<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sistema Inventario Laravel Vue Js- TeamJS">
  <meta name="author" content="Jipson Saad">
  <meta name="keyword" content="Sistema inventario Laravel Vue Js">
  <link rel="shortcut icon" href="img/favicon.png">
  <!-- Id for Channel Notification -->
  <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">
  <title>Sistema Inventario - TeamJS</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
  <!-- Main styles for this application -->
  <link href="css/plantilla.css" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
  <div id="app">
    <header class="app-header navbar">
      <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#"></a>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
          <a class="nav-link" href="#">Escritorio</a>
        </li>
      </ul>
      <ul class="nav navbar-nav ml-auto">
        <notification :notifications="notifications" ></notification>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <img src="img/avatars/8.png" class="img-avatar" alt="admin@bootstrapmaster.com">
            <span class="d-md-down-none">{{Auth::user()->usuario}} </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
              <strong>Cuenta</strong>
            </div>
            <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="
              event.preventDefault();
              const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-success',
                  cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
              });

              swalWithBootstrapButtons
                .fire({
                  title: '¿Estás seguro de cerrar sesión?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Aceptar',
                  cancelButtonText: 'Cancelar',
                  reverseButtons: true
                })
                .then(result => {
                  if (result.value) {
                    document.getElementById('logout-form').submit();
                  }
                });
              "
              >
              <i class="fa fa-lock"></i> Cerrar sesión</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </div>

        </li>
      </ul>
    </header>


    <div class="app-body">
      @if (Auth::check())
        @if(Auth::user()->condicion == 1)
          @include('plantilla.sidebaradministrador')
        @else

        @endif
      @endif

      <!-- Contenido Principal -->
      @yield('contenido')
      <!-- /Fin del contenido principal -->
    </div>
  </div>



  <footer class="app-footer">
    <span><a href="https://www.facebook.com/codegeektech" target="_blank">CodeGT</a> &copy; 2019</span>
    <span class="ml-auto">Desarrollado por <a href="https://www.twitter.com/jipson_saad" target="_blank">CodeGT</a></span>
  </footer>

  <!-- GenesisUI main scripts -->
  <script src="js/app.js"></script>
  <script src="js/sweetalert2@8.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/Chart.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/pace.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/template.js"></script>
</body>

</html>
