@extends('system::template/master')
@section('title', $title)

@section('content')
<div class="row">
    <div class="col-md-10 offset-1">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
            </div>
            <!-- /.card-header -->
            <form action="{{ $action }}" method="post" id="privilege_form">
                {{ csrf_field() }}
                <input name="privilege_id" value="{{Crypt::encrypt($privilege_id)}}" type="hidden">

                <div class="card-body">
                    <div class="form-group">
                        <label>NIK Karyawan</label>
                        <input value="{{$privilege_user_nik}}" name="privilege_user_nik" type="text" class="form-control" placeholder="ex : Avanza, Terios.." readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama Karyawan</label>
                        <input value="{{$privilege_user_name}}" name="privilege_user_name" type="text" class="form-control" placeholder="ex : Avanza, Terios.." readonly>
                    </div>
                    <div class="form-group">
                        <label>Grup Akses</label>
                        <select name="privilege_group_access_id" class="form-control select2" style="width: 100%;">
                            <option value="">-- Pilih Akses --</option>
                            @foreach ($group_access_data as $ga)
                            @php
                            if (isset($privilege_group_access_id) && $privilege_group_access_id == $ga->group_access_id) {
                            $selected = 'selected';
                            }else{
                            $selected = '';
                            }
                            @endphp
                            <option value="{{ $ga->group_access_id }}" {{$selected}}>{{ $ga->group_access_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <select name="privilege_user_location[]" class="select2" multiple="multiple" data-placeholder="Pilih lokasi" style="width: 100%;">
                            @foreach ($factory_data as $data)
                            @if ($privilege_user_location != '')
                            @php ($select = in_array($data->factory_name, explode(',', $privilege_user_location)))
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
                    <div class="row align-items-center h-100">
                        <div class="col-6">
                            <div class="form-group">
                                <label>API Key</label>
                                <input id="privilege-api-key" value="{{$privilege_api_key}}" name="privilege_api_key" type="text" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <button id="btn-api" type="button" class="btn btn-primary"><i class="fas fa-key"></i> Generate Key</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ url('privilege/user_access') }}" class="btn btn-default">Kembali</a>
                    <button type="submit" class="btn btn-info float-right">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
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
