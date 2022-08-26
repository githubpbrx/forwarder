<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>AdminLTE 3 | Top Navigation</title>

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <!-- SweetAlert2 -->
        <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('public/adminlte/dist/css/adminlte.min.css') }}">
    </head>
    <body class="hold-transition layout-top-nav">
        <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="index3.html" class="navbar-brand">
                    <img src="{{ url('pbrx.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-1"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light">Pan Brothers</span>
                </a>
                
                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="index3.html" class="nav-link">Beranda</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Pelayanan</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li><a href="#" class="dropdown-item">Buku Tamu Kunjungan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Kontak</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"> @yield('title')</h1>
                    </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    @yield('content')
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- Default to the left -->
            <strong>Copyright &copy; 2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="{{ asset('public/adminlte/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- SweetAlert2 -->
        <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <!-- Select2 -->
        <script src="{{ asset('public/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('public/adminlte/dist/js/adminlte.min.js') }}"></script>
        
        @yield('script')
        <script>
            @if (Session::has('alert'))
                {!! Session::get('alert') !!}
            @endif
            
            function sweetAlert(alert, desc) {
                const Alert = Swal.mixin({
                    showConfirmButton: true,
                    timer: 3000
                });
    
                Alert.fire({
                    type: alert,
                    title: desc,
                });
            }
    
            function toast(alert, desc) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
    
                Toast.fire({
                    type: alert,
                    title: desc
                })
            }
    
            </script>
    </body>
</html>
