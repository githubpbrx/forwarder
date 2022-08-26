@extends('system::template/master')
@section('title', $title)

@section('content')
<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#adduser">
            Add User
        </button>
        <table id="serverside" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Grup Akses</th>
                    <th>Akses Faktori</th>
                    <th>API Key</th>
                    <th>Reset</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('privilegcreatesave')}}" method="post" accept-charset="utf-8">
                    @csrf
                    <div class="form-group">
                        <label>NIK Karyawan</label>
                        <input value="" name="nik" type="text" class="form-control" placeholder="Nik" required="" id="nik">
                    </div>

                    <div class="form-group">
                        <label>Nama Karyawan</label>
                        <input value="" name="nama" type="text" class="form-control" id="nama" placeholder="Isi field NIK Karyawan" readonly required="">
                    </div>
                    <div class="form-group">
                        <label>Grup Akses</label>
                        <select name="akses" class="form-control select2" style="width: 100%;" required="">
                            <option value="">-- Pilih Akses --</option>
                            @foreach ($group_access_data as $ga)
                            <option value="{{ $ga->group_access_id }}">{{ $ga->group_access_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <select name="lokasi[]" class="select2" multiple="multiple" data-placeholder="Pilih lokasi" style="width: 100%;" required="">
                            @foreach ($factory_data as $data)
                            <option value="{{$data->factory_name}}">
                                {{$data->factory_name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row align-items-center h-100">
                        <div class="col-6">
                            <div class="form-group">
                                <label>API Key</label>
                                <input id="privilege-api-key" name="privilege_api_key" type="text" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <button id="btn-api" type="button" class="btn btn-primary"><i class="fas fa-key"></i> Generate Key</button>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

            </form>
        </div>
    </div>
</div>


{{-- ----------------- modal content ----------------- --}}
<div class="modal fade" id="modal_request">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="request_title" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="request_content" class="row"></div>
            </div>
        </div>
    </div>
</div>
{{-- ----------------- /.modal content ----------------- --}}
@endsection

@section('script')
<script type="text/javascript">
    $("#nik").change(function() {
        var nik = $("#nik").val();
        var token = $('meta[name=csrf-token]').attr('content');
        console.log('nik :' + nik);
        $.ajax({
            url: "<?php echo route('privileggetnama') ?>",
            method: 'POST',
            data: {
                nik: nik,
                _token: token
            },
            success: function(data) {
                console.log(data);
                if (data == '-') {
                    alert('Nik Not Found');
                    $("#nik").val('');
                    $("#nama").val('');
                } else {
                    $("#nama").val(data);
                }
                cache: false
            }
        });

    });


    var oTable = $('#serverside').DataTable({
        order: [],
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("privilege/user_access/privilegedata") }}'
        },

        "fnCreatedRow": function(row, data, index) {
            $('td', row).eq(0).html(index + 1);
        },

        columns: [
            // data is for view, name is for real value
            {
                data: 'privilege_id',
                name: 'privilege_id'
            },
            {
                data: 'privilege_user_nik',
                name: 'privilege_user_nik'
            },
            {
                data: 'privilege_user_name',
                name: 'privilege_user_name'
            },
            {
                data: 'group_access',
                name: 'group_access'
            },
            {
                data: 'user_location',
                name: 'user_location',
                orderable: false
            },
            {
                data: 'privilege_api_key',
                name: 'privilege_api_key'
            },
            {
                data: 'reset',
                name: 'reset',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
    });

    $('#serverside').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
<script>
    $(document).ready(function(){

        load_event()

        function load_event(){
            btnapi_event()
        }

        function btnapi_event(){
            $('#btn-api').click(function(e){
                let key = generate_token()

                $('#privilege-api-key').val(key)
            })
        }

        function generate_token(){
            let unixtimestamps = Date.now().toString().substr(-4)

            let token = Math.random().toString(36).substr(2)

            let key = unixtimestamps + token

            return key
        }
    })
</script>
@endsection