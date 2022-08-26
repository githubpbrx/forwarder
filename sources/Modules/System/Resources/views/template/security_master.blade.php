<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>PT. Pan Brothers Tbk</title>
        <link rel="shortcut icon" href="{{ url('public/pbrx.ico') }}" type="image/x-icon">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
        @yield('link_href')
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('public/adminlte/dist/css/adminlte.min.css') }}">

    </head>

    <body class="hold-transition" style="background: #e9ecef;">

        @yield('content')        

        <!-- jQuery -->
        <script src="{{ asset('public/adminlte/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- SweetAlert2 -->
        <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <!-- daterangepicker -->
        <script src="{{ asset('public/adminlte/plugins/moment/moment.min.js') }}"></script>
        <!-- Select2 -->
        <script src="{{ asset('public/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
        <!-- DataTables -->
        <script src="{{ asset('public/adminlte/plugins/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('public/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
        @yield('script_src')

        <!-- AdminLTE App -->
        <script src="{{ asset('public/adminlte/dist/js/adminlte.min.js') }}"></script>

        

        @yield('script')
        <script>
            @if (Session::has('alert'))
                {!! Session::get('alert') !!}
            @endif
        
        function sweetAlert(alert, desc, text) {
            const Alert = Swal.mixin({
                showConfirmButton: true
                // timer: 3000
            });

            Alert.fire({
                type: alert,
                title: desc,
                text: text,
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
        jQuery('input[type="text"], textarea').keypress(function(event) {
            var ew = event.which;

            if(ew == 32) // space char
                return true;
            if(48 <= ew && ew <= 57)
                return true;
            if(65 <= ew && ew <= 90)
                return true;
            if(97 <= ew && ew <= 122)
                return true;
            return false;
        });
        </script>
    </body>
</html>
