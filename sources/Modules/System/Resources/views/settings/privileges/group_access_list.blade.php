@extends('system::template/master')
@section('title', $title)

@section('content')

<div class="card">
    <div class="card-header">
        <a href="{{ url('privilege/group_access/create') }}" class="btn btn-success"><i class="fas fa-users-cog"></i> Create Group Access</a>
        <a href="{{ url('privilege/menu') }}" class="btn btn-secondary float-right"><i class="fas fa-list"></i> Master Menu</a>
        {{-- <a href="{{ url('settings/useraccess') }}" class="btn btn-info float-right mr-5"><i class="fas fa-user-shield"></i> User Access</a> --}}
    </div>
    
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Group Access</th>
                <th>Privilege</th>
                <th>Pengguna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php ($no = 0)
            @foreach ($group_access_data as $group_access)
            
            <tr>
                <td>{{++$no}}</td>
                <td>{{$group_access->group_access_name}}</td>
                <td>{{($group_access->role_access)->count()}} Menu</td>
                <td>{{($group_access->privilege)->count()}} User</td>
                <th>
                    <a href="{{ url('privilege/group_access/update/'.Crypt::encrypt($group_access->group_access_id)) }}"><i class="fas fa-edit text-orange"></i></a>
                    <a href="{{ url('privilege/group_access/delete/'.Crypt::encrypt($group_access->group_access_id)) }}" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="fas fa-trash text-danger"></i></a>
                </th>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(function () {
            $("#example1").DataTable();
        });
    </script>
@endsection