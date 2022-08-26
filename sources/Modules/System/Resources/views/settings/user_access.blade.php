@extends('system::template/master')
@section('title')
{{$title}}
@if (RoleAccess::whereMenu(12) == 1)
    <a href="{{ url('privilege/group_access') }}" class="btn btn-warning float-right"> <i class="fas fa-users-cog"></i> Master Group Access</a>
@endif
@endsection

@section('content')
<div class="row pb-3">
    <div class="col-md-12">
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
                        <input value="{{ $nik }}" class="form-control form-control-navbar" onkeyup="changeURLSearc(this.value)" type="search" placeholder="Input NIK .." aria-label="Search" required>
                        <div class="input-group-append">
                            <a id="button_search" href="{{ isset($nik) ? url('settings/useraccess/'.$nik) : '#' }} " class="btn btn-navbar">
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                </nav>
            
            <form id="privilege_form" action="{{ $action }}" method="POST">
                {{ csrf_field() }}
                <input name="privilege_user_nik" value="{{ $nik }}" type="hidden">

                <div id="tools_access" class="row">
                    @if ($nik)
                    <table class="table">
                        <tr>
                            <th>Active</th>
                            <th>Access</th>
                            <th>Role</th>
                        </tr>
                        
                        @foreach ($tools_data as $tools)
                        <tr>
                            <td>
                                <i id="menu_{{$tools->menu_id}}" class="far fa-minus-square"></i>
                            </td>
                            <td>{{$tools->menu_name}}</td>
                            <td>
                                <select id="menu_role_{{$tools->menu_id}}" name="menu_role_{{$tools->menu_id}}" disabled>
                                    <option value="3">Read Only</option>
                                    <option value="2">Update</option>
                                    <option value="1">Full</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach

                        <tr>
                            <td colspan="3">
                                <div class="form-group">
                                    <label>Location</label>
                                    <select name="privilege_user_location[]" class="select2" multiple="multiple" data-placeholder="Pilih fasilitas" style="width: 100%;">
                                        @foreach ($factory_data as $data)
                                        @if ($location != '')
                                            @php ($select = in_array($data->factory_name, $location))
                                            @php ( $select ? $selected = 'selected' : $selected = '')
                                        @else
                                            @php ($selected = '')
                                        @endif
                                        <option value="{{$data->factory_name}}" {{$selected}}>
                                            {{$data->factory_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
            
            <div class="col-md-7">
                <nav class="navbar navbar-expand navbar-secondary navbar-dark">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i id="label_access" class="fas fa-users-cog"></i> Access</a>
                        </li>

                        <div class="form-inline ml-1">
                            <div class="input-group input-group-sm">
                                <select id="privilege_group_access_id" name="privilege_group_access_id" onchange="showSystem(this.value)" class="form-control select2" style="min-width:200px;" required {{ (RoleAccess::whereMenu(12) == 1 || RoleAccess::whereMenu(12) == 2) ? '' : 'disabled' }}>
                                    @if ($nik)
                                    @foreach ($group_access_data as $group_access)
                                        <option value="{{$group_access->group_access_id}}" {{ $group_access_id == $group_access->group_access_id ? 'selected' : '' }}>{{$group_access->group_access_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-list-alt"></i> Menu</a>
                        </li>

                        <div class="form-inline ml-1">
                            <div class="input-group input-group-sm">
                                <select name="menu_system_id" id="menu_system_id" onchange="showMenu()" class="form-control select2" style="width: 100%;">
                                    @if ($nik)
                                    <option value="">-- Show Access --</option>
                                    @foreach ($system_data as $system)
                                        <option value="{{$system->system_id}}">{{$system->system_program_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </ul>
                </nav>

                <div id="menu_access" class="row">
                    @if ($nik)
                    @foreach ($system_data as $system)
                    <table id="system_{{$system->system_id}}" class="table" style="display:none;">
                        <tr>
                            <th>Active</th>
                            <th>Access</th>
                            <th>Role</th>
                        </tr>
                        
                        @foreach ($system->menu as $menu)
                        <tr>
                            <td>
                                <i id="menu_{{$menu->menu_id}}" class="far fa-minus-square"></i>
                            </td>
                            <td>{{$menu->menu_name}}</td>
                            <td>
                                <select id="menu_role_{{$menu->menu_id}}" name="menu_role_{{$menu->menu_id}}" disabled>
                                    <option value="3">Read Only</option>
                                    <option value="2">Update</option>
                                    <option value="1">Full</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                        
                    </table>
                    @endforeach
                    @endif
                </div>
            </form>
            </div>
        </div>

        @if ($nik && (RoleAccess::whereMenu(12) == 1 || RoleAccess::whereMenu(12) == 2))
        <div class="col-md-12 mt-1">
            <a href="{{ url('settings/useraccess') }}" class="btn btn-danger">Cancel</a>
            <button class="btn btn-success float-right" type="submit" form="privilege_form">Save</button>
        </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
    function changeURLSearc(nik) {
        $('#button_search').attr('href', '{{ url("settings/useraccess") }}/' + nik);
    }

    function showSystem() {
        var system_id = $("#privilege_group_access_id option:selected").val();

        $.ajax({
            type    : 'get',
            url     : '{{ url("settings/systemdata") }}' +'/'+ system_id,
            beforeSend: function(response){
                $('#label_access').attr('class', 'fas fa-sync-alt fa-spin');
                $("i[id^='menu_']").attr('class', 'far fa-minus-square');
                $("select[name^='menu_role_']").val(3);
            },
            success: function(data){
                toast('info', 'Data ditemukan');
                
                jQuery.each(data, function(index, item) {
                    $('#menu_' + item.role_access_menu_id).attr('class', 'fas fa-check-square text-success');
                    $('#menu_role_' + item.role_access_menu_id).val(item.role_access);
                });

                $('#label_access').attr('class', 'fas fa-users-cog');
                $('#submit').removeAttr('disabled');
            },
            error: function(xhr, textStatus, errorThrown) {
                toast('error', textStatus+' : '+detail_data);
            }
        });
    }

    function showMenu(){
        var id_table = $('#menu_system_id option:selected').val();

        $('div [id^="system"]').attr('style', 'display:none');
        $('#system_' + id_table).removeAttr('style');
    }

    $(function() {
        showSystem();
    });
</script>
@endsection