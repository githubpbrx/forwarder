@extends('system::template/master')
@section('title', $title)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Menu</h3>
        <a href="{{ url('privilege/group_access') }}" class="btn btn-warning float-right"><i class="fas fa-users-cog"></i> Master Group Access</a>
    </div>
    
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode Menu</th>
                <th>System</th>
                <th>Menu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php ($no = 0)
            @foreach ($menu_data as $menu)
            
            <tr>
                <td>{{$menu->menu_id}}</td>
                <td>{!! isset($menu->system->system_id) ? $menu->system->system_program_name : '<span class="badge bg-secondary">unknown</span>' !!}</td>
                <td>
                    {{$menu->menu_name}}
                    @if ($menu->menu_is_active == 1)
                        <i class="fas fa-check-circle text-success"></i>
                    @else
                    <i class="fas fa-times-circle text-danger"></i>
                    @endif
                </td>
                
                <th>
                    <a href="#" onclick="editmenu('{{ Crypt::encrypt($menu->menu_id) }}','{{ $menu->system->system_id }}','{{ $menu->menu_name }}')" data-toggle="modal" data-target="#modal_form"><i class="fas fa-edit text-orange"></i></a>

                    @if ($menu->menu_is_active == 1)
                    <a href="{{ url('privilege/menu/delete/'.Crypt::encrypt($menu->menu_id)) }}" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="fas fa-trash text-danger"></i></a>
                    @else
                    <a href="{{ url('privilege/menu/active/'.Crypt::encrypt($menu->menu_id)) }}" onclick="return confirm('Apakah anda yakin ingin mengaktifkan?')"><i class="fas fa-plus-circle text-success"></i></a>                        
                    @endif
                </th>
            </tr>
            @endforeach
            
            <tr>
            <form action="{{ url('privilege/menu/createaction') }}" method="post">
                {{ csrf_field() }}
                <td></td>
                <td>
                    <select name="menu_system_id" class="form-control select2" style="width: 100%;" required>
                        <option value="">-- Pilih App --</option>
                        @foreach ($system_data as $system)
                            <option value="{{$system->system_id}}">{{$system->system_program_name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input name="menu_name" type="text" class="form-control" placeholder="Menu .." required>
                </td>
                <th>
                    <button type="submit" class="btn btn-xs btn-success"><i class="fas fa-plus"></i></button>
                </th>
            </form>
            </tr>
        </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_form">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form action="{{url('privilege/menu/updateaction')}}" method="post">
        {{ csrf_field() }}
        <input id="menu_id" name="menu_id" type="hidden">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Applikasi</label>
                        <select id="menu_system_id" name="menu_system_id" class="form-control select2" style="width: 100%;" required>
                            <option value="">-- Pilih App --</option>
                            @foreach ($system_data as $system)
                                <option value="{{$system->system_id}}">{{$system->system_program_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Menu</label>
                        <input id="menu_name" name="menu_name" value="" type="text" class="form-control" placeholder="Masukkan BBM" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function editmenu(menu_id, system_id, menu_name){
            $("#menu_id").val(menu_id);
            $("#menu_system_id").val(system_id).trigger('change');
            $("#menu_name").val(menu_name);
        }
        
        $(function () {
            $("#example1").DataTable();
        });
    </script>
@endsection