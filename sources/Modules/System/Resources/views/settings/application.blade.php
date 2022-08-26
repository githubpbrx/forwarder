@extends('system::template/master')
@section('title', $title)

@section('content')
@php

    if (RoleAccess::whereMenu(13) == 1 || RoleAccess::whereMenu(13) == 2) {
        $role = '';
    }else{
        $role = 'disabled';
    }
    
@endphp
<div class="row">
    <div class="col-md-3">
        <div class="sticky-top mb-3">
            <div class="card">
                <div class="card-header">
                <h4 class="card-title">Application</h4>
                </div>
                <div class="card-body">
                    @foreach ($system_data as $system)
                        <a href="#" id="{{ $system->system_id }}" onclick="showdata('{{ $system->system_id }}')" class="btn btn-info btn-block"><b>{{ $system->system_program_name}}</b></a>
                    @endforeach
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-7">
        <form action="{{ $action }}" class="form-horizontal" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="system_id" id="system_id" value="">
            <div class="form-group row">
                <label>Application Title</label>
                <input id="system_program_name" name="system_program_name" type="text" class="form-control" {{$role}} placeholder="Application title" required>
            </div>
            <div class="form-group row">
                <label>Sidebar Title</label>
                <input id="system_sidebar_title" name="system_sidebar_title" type="text" class="form-control" {{$role}} placeholder="Sidebar Title" required>
            </div>
            <div class="form-group row">
                <label>Footer Copyright</label>
                <input id="system_copyright" name="system_copyright" type="text" class="form-control" {{$role}} placeholder="Copyright" required>
            </div>
            <div class="form-group row">
                <label>
                    <p id="label_login">Login Notify </p>
                    <input type="checkbox" id="system_login_notify" name="system_login_notify" data-bootstrap-switch data-off-color="danger" data-on-color="success" value="1" {{$role}}>
                </label>
                <textarea id="system_login_description" name="system_login_description"class="form-control" placeholder="Notif Description" required {{$role}}></textarea>
            </div>
            
            {{-- <label>Language</label>
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                    <input value="indonesian" type="radio" id="radioPrimary1" name="system_default_language" checked {{$role}}>
                    <label for="radioPrimary1">
                        Indonesia
                    </label>
                </div>
                <div class="icheck-primary d-inline">
                    <input value="english" type="radio" id="radioPrimary2" name="system_default_language" {{$role}}>
                    <label for="radioPrimary2">
                        English
                    </label>
                </div>
            </div> --}}
            
            <div class="form-group row">
                <div class="col-md-10">
                    <label>Email Notify</label>
                    <input id="system_email" type="text" class="form-control" {{$role}} placeholder="Email">
                </div>
                <div class="col-md-2">
                    <label>Add Email</label>
                    <button onclick="addEmail()" type="button" class="btn btn-block btn-success float-right" {{$role}}><i class="fas fa-plus"></i></button>
                    <input type="hidden" id="system_email_notify" name="system_email_notify">
                </div>

                <div id="email_penerima" class="col-md-12 mt-2">
                    <span class="text-muted">Tidak ada email..</span>
                </div>
            </div>
            
            @if ($role == '')
            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button id="submit" type="submit" class="btn btn-success float-right" disabled>Simpan</button>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection

@section('script')
<!-- Bootstrap Switch -->
<script src="{{url('public/adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<script>
    $(function(){
        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        jQuery('#system_email').keypress().off();
        jQuery('#system_email').keypress(function(event) {
            var ew = event.which;
            if(ew == 64 || ew == 46)
                return true;
            if(48 <= ew && ew <= 57)
                return true;
            if(65 <= ew && ew <= 90)
                return true;
            if(97 <= ew && ew <= 122)
                return true;
            return false;
        });
    })

    function showEmail() {
        var email       = JSON.parse($('#system_email_notify').val());

        $('#email_penerima').html('')
        if (email.length > 0) {
            jQuery.each(email, function(index, item) {
                $('#email_penerima').append(
                    '<h6 class="d-inline mr-1">' +
                        '<span class="badge badge-success p-2 mb-1">' +
                            item +
                            '<a onclick="deleteEmail('+ index +')" class="float-right ml-4 text-light" href="http://"><i class="fas fa-times"></i></a>' +
                        '</span>' +
                    '</h6>'
                )
            });
        }else{
            $('#email_penerima').html('<span class="text-muted">Tidak ada email..</span>');
        }
    }

    function addEmail() {
        var new_email       = $('#system_email').val();
        var input_kosong    = 0;
        var check = true;

        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        if (testEmail.test(new_email)){
            $('#system_email').each(function() {
                if($(this).val() == "" || $(this).val() == null){
                    $(this).popover({
                        trigger: 'manual',
                        content: 'Please fill out this field',
                        placement: 'top'
                    });
                    $(this).addClass('is-invalid');
                    if (check) {
                        $(this).popover('show');
                        setTimeout(function(){$('#system_email').popover('hide')}, 3000);
                        check = false;
                    }
                    input_kosong = 1;
                }else{
                    $(this).removeClass('is-invalid');
                }
            })

            if (input_kosong == 0) {
                var email       = JSON.parse($('#system_email_notify').val());

                email.push(new_email);
                $('#system_email_notify').val(JSON.stringify(email));
                showEmail()
                $('#system_email').val('');
            }
        }else{
            $('#system_email').each(function() {
                // if($(this).val() == "" || $(this).val() == null){
                    $(this).popover({
                        trigger: 'manual',
                        content: 'Email not valid',
                        placement: 'top'
                    });
                    $(this).addClass('is-invalid');
                    $(this).popover('show');
                    setTimeout(function(){$('#system_email').popover('hide')}, 3000);
                // }else{
                //     $(this).removeClass('is-invalid');
                // }
            })
        }
    }

    function deleteEmail(index) {
        var email       = JSON.parse($('#system_email_notify').val());
        if (email.length > 0) {
            email.splice(index, 1)
        }
        $('#system_email_notify').val(JSON.stringify(email));
        showEmail()
    }

    function showdata(id) {
        $.ajax({
            type : 'get',
            url: '{{url("settings/applicationdata")}}' +'/'+ id,
            beforeSend: function(response){
                $('#'+id).html('<i class="fas fa-sync-alt fa-spin"></i>');
            },
            success: function(response){
                toast('info', 'Data ditemukan');
                $('#' + id).html('<b>'+response['system_program_name']+'</b>');
                $('#system_id').val(response['system_id']);
                $('#system_program_name').val(response['system_program_name']);
                $('#system_sidebar_title').val(response['system_sidebar_title']);
                $('#system_copyright').val(response['system_copyright']);
                $('#system_login_description').html(response['system_login_description']);

                $('#system_email_notify').val(response['system_email_notify']);
                showEmail()

                if (id == '1') {
                    $("#label_login").html("Before Login Notify");
                } else {
                    $("#label_login").html("After Login Notify");
                }

                var status = response['system_login_notify'];
                
                if (status == 1) {
                    $('#system_login_notify').prop('checked', true).change();
                }else{
                    $('#system_login_notify').prop('checked', false).change()
                }

                $('#submit').removeAttr('disabled');
            },
            error: function(xhr, textStatus, errorThrown) {
                toast('error', textStatus+' : '+detail_data);
            }
        });
    }
</script>
@endsection