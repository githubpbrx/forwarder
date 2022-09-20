@extends('system::login/login_master')
@section('title', $title)

@section('content')
    <div class="login-box">
        <div class="login-logo">
            Account Activation <bR>

        </div>
        <!-- /.login-logo -->
        <p>Please check your e-mail<b>{{ $nik }}</b>
        <form action="{{ url('login/validasiaktifasi') }}" method="POST">
            {{ csrf_field() }}
            <input value="{{ $nik }}" id="nik" name="nik" type="hidden">
            <input value="{{ $nama }}" id="nama" name="nama" type="hidden">
            <div class="form-group">
                <input id="password" name="password" type="text" class="form-control " placeholder="Enter Token..."
                    required>
                <small><code id="warning"></code></small>
            </div>

            <button id="submit" type="submit" class="btn btn-success float-right">Activation</button>
        </form>
        <i>If you haven't received an email, please <a href="{{ route('resendemail') }}"><i class=" float-left">Resend
                    Email..</i></a></i> <br><br>

        <a href="{{ url('logout') }}"><i class="btn btn-danger btn-xs float-left">Exit</i></a>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });

        // function checkAlphaAndNumeric(params) {
        $("#password,#a_1,#a_2").keypress(function(event) {
            var ew = event.which;

            if (48 <= ew && ew <= 57)
                return true;
            if (65 <= ew && ew <= 90)
                return true;
            if (97 <= ew && ew <= 122)
                return true;
            return false;
        });
    </script>
@endsection
