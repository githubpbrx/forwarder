@extends('system::login/login_master')
@section('title', $title)

@section('content')
    <div class="login-box">
        <div class="login-logo">
            Validasi KYC <bR>
        </div>
        <!-- /.login-logo -->
        <form action="#" method="POST">
            {{ csrf_field() }}
            <input value="{{ $nik }}" id="nik" name="nik" type="hidden">
            <input value="{{ $nama }}" id="nama" name="nama" type="hidden">
            <div class="form-group">
                <input id="filekyc" name="filekyc" type="file" class="form-control " placeholder="Enter File..."
                    required>
            </div>
            <button id="submit" type="submit" class="btn btn-success">Upload</button>
        </form>
        <br>
        <a href="{{ url('logout') }}"><i class="btn btn-danger float-left">Exit</i></a>
        &nbsp;
        <a href="{{ url('sources\storage\public\file_kyc.xlsx') }}" target="_BLANK" class="btn"
            style="background-color: #AED6F1;">DOWNLOAD FILE KYC</a>

    </div>
@endsection

@section('script')
    <script>
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
