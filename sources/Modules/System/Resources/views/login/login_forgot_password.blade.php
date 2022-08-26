@extends('system::login/login_master')
@section('title', $title)

@section('content')
<div class="login-box">
    <div class="login-logo">
        {{ $title }}
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            @if ($new_pass == false)
                <form id="check_nik">
                    <label for="">NIK</label>
                    <div class="input-group mb-3">
                        <input name="nik" type="text" class="form-control" placeholder="NIK" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-id-badge"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <a href="{{ url('login') }}" class="btn btn-danger btn-block">Login</a>
                        </div>
                    <!-- /.col -->
                    <div class="col-8">
                        <button type="submit" class="btn btn-info btn-block">Next</button>
                    </div>
                    <!-- /.col -->
                    </div>
                </form>

                <form action="{{ url('forgotpassword') }}" method="post" id="check_qa" style="display:none;">
                    {{ csrf_field() }}
                    <input id="nik" name="nik" type="hidden">
                    <label id="q_1"></label>
                    <div class="form-group mb-3">
                        <input name="a_1" type="text" class="form-control" placeholder="Input answer" required>
                    </div>
                    <label id="q_2"></label>
                    <div class="form-group mb-3">
                        <input name="a_2" type="text" class="form-control" placeholder="Input answer" required>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <a href="{{ url('forgotpassword') }}" class="btn btn-danger btn-block">Cancel</a>
                        </div>
                    <!-- /.col -->
                    <div class="col-8">
                        <button type="submit" class="btn btn-info btn-block">Next</button>
                    </div>
                    <!-- /.col -->
                    </div>
                </form>
            @else
                <form action="{{ url('forgotpasswordaction') }}" id="new_pass" method="POST">
                    {{ csrf_field() }}
                    <input id="nik_forgot" name="nik" type="hidden" value="{{ $nik }}">
                    <div class="form-group">
                        <label>New Password</label>
                        <input id="password" name="password" onkeyup="checkPassword()" type="password" class="form-control " placeholder="Enter password..." required>
                        <small><code id="warning"></code></small>
                    </div>
                    <div class="form-group">
                        <label>Retype Password</label>
                        <input id="password_retype" name="password_retype" onkeyup="checkPassword()" type="password" class="form-control" placeholder="Enter password..." required>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <a href="{{ url('login') }}" class="btn btn-danger btn-block">Cancel</a>
                        </div>
                    <div class="col-8">
                        <button id="submit" type="submit" class="btn btn-info btn-block">Change</button>
                    </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function showNIK() {
        $('#check_nik').removeAttr('style');
        $('#check_qa').attr('style', 'display:none');
    }

    function showQA() {
        $('#check_qa').removeAttr('style');
        $('#check_nik').attr('style', 'display:none');
    }

    $('#check_nik').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '{{url("checknik")}}',
            type: 'get',
            data: $('#check_nik').serialize(),
            success: function(data){
                if (data == 0) {
                    sweetAlert('error', 'NIK not found');    
                } else {
                    $('#q_1').html(data['q_1']);
                    $('#q_2').html(data['q_2']);
                    $('#nik').val(data['nik']);
                    $('#nik_forgot').val(data['nik']);
                    
                    showQA();
                    toast('success', 'NIK found')
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                toast('error', textStatus+' : '+errorThrown);
            }
        });
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
    $("#password").keypress(function(event){
        var letters = '/^[0-9a-zA-Z]+$/';

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