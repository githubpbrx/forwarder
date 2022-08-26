@extends('system::login/login_master')
@section('title', $title)

@section('content')
<div class="login-box">
    <div class="login-logo">
        {{ $nama }} <br>
        
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <form action="{{ url('login/newnohripspasswordaction') }}" method="POST" >
                {{ csrf_field() }}
                <input value="{{ $nik }}" id="nik" name="nik" type="hidden">
                <input value="{{ $nama }}" id="nama" name="nama" type="hidden">
                <div class="form-group">
                    <label>New Password</label>
                    <input id="password" name="password" onkeyup="checkPassword()" type="password" class="form-control " placeholder="Enter password..." required>
                    <small><code id="warning"></code></small>
                </div>
                <div class="form-group">
                    <label>Retype Password</label>
                    <input id="password_retype" name="password_retype" onkeyup="checkPassword()" type="password" class="form-control" placeholder="Retype password..." required>
                </div>
                <button id="submit" type="submit" class="btn btn-success float-right">Change</button>
            </form>

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    });


    

    function checkPassword() {
        var password    = $('#password').val();
        var password_re = $('#password_retype').val();

        if (password != '' && password_re != '') {
            if (password != password_re) {
                $('#password_retype').addClass('is-invalid');
                $('#password_retype').removeClass('is-valid');

                $('#submit').attr('disabled', 'disabled');
            }else{
                $('#password_retype').removeClass('is-invalid');
                $('#password_retype').addClass('is-valid');
                $('#submit').removeAttr('disabled');
            }
        }

        if (password.length < 6) {
            $('#password').addClass('is-invalid');
            $('#password').removeClass('is-valid');

            $('#warning').html('*Mininum length : 6');
            $('#submit').attr('disabled', 'disabled');
        }else{
            pass_numb = password.replace(/[^0-9]/g, '').length;
            pass_char = password.replace(/[0-9]/g, '').length;
                                 
            if (pass_numb == 0) {
                $('#password').addClass('is-invalid');
                $('#password').removeClass('is-valid');

                $('#warning').html('*Must contain Number');
                $('#submit').attr('disabled', 'disabled');
            }else if(pass_char == 0){
                $('#password').addClass('is-invalid');
                $('#password').removeClass('is-valid');

                $('#warning').html('*Must contain Letter');
                $('#submit').attr('disabled', 'disabled');
            }else{
                $('#password').removeClass('is-invalid');
                $('#password').addClass('is-valid');
                $('#warning').html('');
            }
        }
    }

    // function checkAlphaAndNumeric(params) {
    $("#password,#a_1,#a_2").keypress(function(event){
        var ew = event.which;
        
        if(48 <= ew && ew <= 57)
            return true;
        if(65 <= ew && ew <= 90)
            return true;
        if(97 <= ew && ew <= 122)
            return true;
        return false;
    });
</script>
@endsection