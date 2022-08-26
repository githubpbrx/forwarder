<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>PB Assets | Settings</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ url('public/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ url('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ url('public/adminlte/dist/css/adminlte.min.css') }}">
    </head>

    <body class="hold-transition" style="height: 100vh; background: #e9ecef;">
        <div class="wrapper">
        <section class="content">
            <div class="container-fluid">
                @yield('content')  
            </div>
        </section>
        </div>
        <!-- jQuery -->
        <script src="{{ url('public/adminlte/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ url('public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- SweetAlert2 -->
        <script src="{{ url('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ url('public/adminlte/dist/js/adminlte.min.js') }}"></script>

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
