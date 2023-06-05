@extends('system::template/master')
@section('title', $title)

@section('content')
    <div class="card card-default col-md-10 offset-md-1">
        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <form action="{{ $action }}" method="post">
            {{ csrf_field() }}
            <input name="nik" value="{{ $nik }}" type="hidden">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 offset-md-2">
                        <div class="form-group">
                            <label>New Password</label>
                            <input id="password" name="password" onkeyup="checkPassword()" type="password"
                                class="form-control " placeholder="Enter password..." required>
                            <small><code id="warning"></code></small>
                        </div>
                        <div class="form-group">
                            <label>Retype Password</label>
                            <input id="password_retype" name="password_retype" onkeyup="checkPassword()" type="password"
                                class="form-control" placeholder="Enter password..." required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button id="submit" type="submit" class="btn btn-info float-right">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        function checkPassword() {
            var password = $('#password').val();
            var password_re = $('#password_retype').val();

            if (password != '' && password_re != '') {
                if (password != password_re) {
                    $('#password_retype').addClass('is-invalid');
                    $('#password_retype').removeClass('is-valid');

                    $('#submit').attr('disabled', 'disabled');
                } else {
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
            } else {
                pass_numb = password.replace(/[^0-9]/g, '').length;
                pass_char = password.replace(/[0-9]/g, '').length;

                if (pass_numb == 0) {
                    $('#password').addClass('is-invalid');
                    $('#password').removeClass('is-valid');

                    $('#warning').html('*Must contain Number');
                    $('#submit').attr('disabled', 'disabled');
                } else if (pass_char == 0) {
                    $('#password').addClass('is-invalid');
                    $('#password').removeClass('is-valid');

                    $('#warning').html('*Must contain Letter');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#password').removeClass('is-invalid');
                    $('#password').addClass('is-valid');
                    $('#warning').html('');
                }
            }
        }

        // function checkAlphaAndNumeric(params) {
        $("#password").keypress(function(event) {
            var letters = '/^[0-9a-zA-Z]+$/';

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
