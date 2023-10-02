@php
    $system = Session::get('system');
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{!! $system['program_name'] !!} - @yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('public/pbrx.ico') }}" type="image/x-icon">

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="{{ asset('public/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    @yield('link_href')
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('public/adminlte/dist/css/adminlte.min.css') }}">
    {{-- fixedcolumn --}}
    <link rel="stylesheet"
        href="{{ asset('public/adminlte/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">

        @include('system::template/navbar')

        @include('system::template/sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-dark">@yield('title')</h1>
                        </div><!-- /.col -->

                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    @yield('content')

                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; <?php echo date('Y'); ?> <a
                    href="{{ url('') }}">{!! $system['copyright'] !!}</a></strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('public/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('public/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/adminlte/plugins/popper/umd/popper.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('public/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('public/adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('public/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- JsBarcode -->
    <script src="{{ asset('public/adminlte/plugins/JsBarcode/dist/JsBarcode.all.min.js') }}"></script>

    <!-- overlayScrollbars -->
    <script src="{{ asset('public/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('public/adminlte/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('public/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    @yield('script_src')
    <!-- AdminLTE App -->
    <script src="{{ asset('public/adminlte/dist/js/adminlte.js') }}"></script>
    {{-- fixedcolumn --}}
    <script src="{{ asset('public/adminlte/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js') }}">
    </script>
    <script src="{{ asset('public/adminlte/plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.min.js') }}">
        < script >
            function spin(id) {
                $('#' + id).html('<span class="dropdown-header"><i class="fas fa-sync fa-spin"></i></span>');
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


        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
            @if (Session::get('menu') == 'car' && Roleaccess::whereMenuIn([6, 7, 8]) > 0)
                carNotify();
            @endif

            @if (Session::get('menu') == 'sisbook') // && (Roleaccess::whereMenuIn([24,25,26, 27]) > 0)
                sisbookNotify();
            @endif

            //Initialize Select2 Elements
            $('.select2').select2()

            @if (Session::has('toast'))
                {!! Session::get('toast') !!}
            @endif
        })
    </script>
    @if (Session::has('notify'))
        @php
            $notify = Session::get('notify');
        @endphp
        <div class="modal fade" id="modal_notify">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Pengumuman</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-footer p-1 mb-2">
                            <code>
                                {!! $notify['desc'] !!}
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(function() {
                //Initialize Select2 Elements
                $('#modal_notify').modal('show');
            });
        </script>
    @endif
    @yield('script')
</body>

</html>
