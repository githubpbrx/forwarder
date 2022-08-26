@extends('system::template/master')
@section('title', $title)

@section('content')

<div class="row pb-3">
    <div class="col-md-12">
        <form id="privilege_form" action="{{ url('privilege/group_access/'.$action) }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" value="{{ Crypt::encrypt($group_access_id) }}" name="group_access_id">
        <div class="row">
            <div class="col-md-5">
                <nav class="navbar navbar-expand navbar-info navbar-dark">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#" disabled><i class="fas fa-bars"></i></a>
                        </li>
                        <li class="nav-item dropdown show">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="nav-link dropdown-toggle"><i class="fas fa-cogs"></i> Tools</a>
                        </li>
                    </ul>

                    <div class="form-inline ml-3">
                        <div class="input-group input-group-sm">
                        <input id="group_access_name" name="group_access_name" value="{{ $group_access_name }}" class="form-control form-control-navbar" type="search" placeholder="Nama Group Access" aria-label="Search" onkeyup="checkGroupAccess()" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="label_group_access_name"><i class="fas fa-cog"></i></span>
                        </div>
                    </div>
                </nav>
                
                <table class="table">
                    <tr>
                        <th>Active</th>
                        <th>Access</th>
                        <th>Role</th>
                    </tr>

                    @foreach ($tools_data as $tools)
                    <tr>
                        <td><input type="checkbox" name="menu[{{$tools->menu_id}}]" {{ isset($role_access[$tools->menu_id]['role_access_menu_id']) ? 'checked' : '' }} value="1"></td>
                        <td>{{$tools->menu_name}}</td>
                        <input type="hidden" value="{{ isset($role_access[$tools->menu_id]['role_access_id']) ? $role_access[$tools->menu_id]['role_access_id'] : '' }}" name="role_access_id[{{$tools->menu_id}}]">
                        <td>
                            <select name="menu_role[{{$tools->menu_id}}]">
                                <option value="3" {{ isset($role_access[$tools->menu_id]['role_access']) ? ($role_access[$tools->menu_id]['role_access'] == '3' ? 'selected' : '') : '' }}>Read Only</option>
                                <option value="2" {{ isset($role_access[$tools->menu_id]['role_access']) ? ($role_access[$tools->menu_id]['role_access'] == '2' ? 'selected' : '') : '' }}>Update</option>
                                <option value="1" {{ isset($role_access[$tools->menu_id]['role_access']) ? ($role_access[$tools->menu_id]['role_access'] == '1' ? 'selected' : '') : '' }}>Full</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            
            <div class="col-md-7">
                <nav class="navbar navbar-expand navbar-secondary navbar-dark">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Menu</a>
                        </li>
                    </ul>
                    
                    <div class="col-md-7 ml-1">
                        <div class="input-group input-group-sm" style="width: 100%;">
                            {{-- <input name="system_id" type="hidden" value="{{ isset($role_access['system_id']) ? $role_access['system_id'] : '' }}"> --}}
                            <select name="menu_system_id" id="menu_system_id" onchange="showMenu()" class="form-control select2" style="width: 100%;">
                                <option value="">-- Choose Application --</option>
                                @foreach ($system_data as $system)
                                    <option value="{{$system->system_id}}">{{$system->system_program_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </nav>

                @foreach ($system_data as $system)
                <table id="system_{{$system->system_id}}" class="table" style="display:none;">
                    <tr>
                        <th>Active</th>
                        <th>Access</th>
                        <th>Role</th>
                    </tr>
                    
                    @if (!$system->menu->isEmpty())
                    @foreach ($system->menu as $menu)
                    <tr>
                        <td><input type="checkbox" name="menu[{{$menu->menu_id}}]" {{ isset($role_access[$menu->menu_id]['role_access_menu_id']) ? 'checked' : '' }} value="1"></td>
                        <td>{{$menu->menu_name}}</td>
                        <input type="hidden" value="{{ isset($role_access[$menu->menu_id]['role_access_id']) ? $role_access[$menu->menu_id]['role_access_id'] : '' }}" name="role_access_id[{{$menu->menu_id}}]">
                        <td>
                            <select name="menu_role[{{$menu->menu_id}}]">
                                <option value="3" {{ isset($role_access[$menu->menu_id]['role_access']) ? ($role_access[$menu->menu_id]['role_access'] == '3' ? 'selected' : '') : '' }}>Read Only</option>
                                <option value="2" {{ isset($role_access[$menu->menu_id]['role_access']) ? ($role_access[$menu->menu_id]['role_access'] == '2' ? 'selected' : '') : '' }}>Update</option>
                                <option value="1" {{ isset($role_access[$menu->menu_id]['role_access']) ? ($role_access[$menu->menu_id]['role_access'] == '1' ? 'selected' : '') : '' }}>Full</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    
                </table>
                @endforeach
            </div>
        </div>
        </form>

        <div class="col-md-12 mt-5">
            <a href="{{ url('privilege/group_access') }}" class="btn btn-default">Kembali</a>
            <button id="submit" class="btn btn-success float-right" type="submit" form="privilege_form">Save</button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function checkGroupAccess() {
        // $('#privilege_form').serialize()
        var name = $('#group_access_name').val();
        // console.log(name);
        $.ajax({
            url : '{{url("privilege/group_access/checkgroupaccess")}}',
            type: 'get',
            data: { group_access_name : name, },
            beforeSend: function(response){
                $('#label_group_access_name').html('<i class="fas fa-cog fa-spin"></i>');
            },
            success: function(response){
                if (response == 1) {
                    $('#label_group_access_name').html('<i class="fas fa-cog text-danger"></i>');
                    $('#submit').attr('disabled', true);
                }else{
                    $('#label_group_access_name').html('<i class="fas fa-cog text-success"></i>');
                    $('#submit').removeAttr('disabled');
                }
            },
        });
    }
    $("#privilege_form").submit(function(e){
        if($(':checkbox:checked').length == 0){
            e.preventDefault();
            sweetAlert('info', 'Akses tidak boleh kosong');
        }
    });

    function showMenu(){
        var id_table = $('#menu_system_id option:selected').val();

        $('div [id^="system"]').attr('style', 'display:none');
        $('#system_' + id_table).removeAttr('style');
    }

    $(function(){
        @if ($action == 'updateaction')
        showMenu();
        @endif
    })
</script>
@endsection