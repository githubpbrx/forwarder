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
                    <input name="privilege_id" value="{{ Crypt::encrypt($privilege_id) }}" type="hidden">

                    <div class="card-body">
                        <div class="form-group">
                            <label>Pilih Jenis</label>
                            <select name="jenis" id="jenis" class="form-control">
                                <option value="internal" {{ $jenisku == 'internal' ? 'selected' : '' }}>Internal</option>
                                <option value="external" {{ $jenisku == 'external' ? 'selected' : '' }}>External</option>
                            </select>
                        </div>


                        <div id="internalku">
                            <div class="form-group">
                                <label>NIK Karyawan</label>
                                <input value="{{ $privilege_user_nik }}" name="internalnik" type="text"
                                    class="form-control" id="internalnik" placeholder="masukkan nik">
                            </div>

                            <div class="form-group">
                                <label>Nama Karyawan</label>
                                <input value="{{ $privilege_user_name }}" name="internalnama" type="text"
                                    class="form-control" id="internalnama" placeholder="nama" readonly>
                            </div>
                        </div>

                        <div id="externalku">
                            <div class="form-group">
                                <label>Email</label>
                                <input value="{{ $privilege_user_nik }}" name="externalemail" type="text"
                                    class="form-control" id="externalemail" placeholder="masukkan email">
                            </div>

                            <div class="form-group">
                                <label>Nama</label>
                                <input value="{{ $privilege_user_name }}" name="externalnama" type="text"
                                    class="form-control" id="externalnama" placeholder="masukkan nama">
                            </div>

                            <div class="form-group">
                                <label>NIK Validasi Finance</label>
                                <input value="{{ $nik_finance }}" name="nikfinance" type="text" class="form-control"
                                    id="nikfinance" placeholder="NIK Finance">
                            </div>
                            <div class="form-group">
                                <label>Nama Finance</label>
                                <input value="{{ $nama_finance }}" name="namafinance" type="text" class="form-control"
                                    id="namafinance" placeholder="nama Finance" readonly>
                            </div>
                            <div class="form-group">
                                <label>Email Finance</label>
                                <input value="{{ $email_finance }}" name="emailfinance" type="email" class="form-control"
                                    id="emailfinance" placeholder="Email Finance">
                            </div>
                        </div>


                        <div class="form-group">
                            <label>Grup Akses</label>
                            <select name="privilege_group_access_id" class="form-control select2" style="width: 100%;">
                                <option value="" disabled>-- Pilih Akses --</option>
                                @foreach ($group_access_data as $ga)
                                    @php
                                        if (isset($privilege_group_access_id) && $privilege_group_access_id == $ga->group_access_id) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                    @endphp
                                    <option value="{{ $ga->group_access_id }}" {{ $selected }}>
                                        {{ $ga->group_access_name }}</option>
                                @endforeach
                            </select>
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
        $(document).ready(function() {
            var jenisku = @JSON($jenisku);
            console.log('jenisku :>> ', jenisku);
            if (jenisku == 'internal') {
                $("#internalku").show();
                $("#externalku").hide();
            } else {
                $("#externalku").show();
                $("#internalku").hide();
            }

            // $("#internalku").show();
            // $("#externalku").hide();
            $("#internalnik").attr('required', true);
            $("#internalnama").attr('required', true);
            $("#externalnik").attr('required', false);
            $("#externalnama").attr('required', false);
            $("#nikfinance").attr('required', false);
            $("#namafinance").attr('required', false)
            $("#emailfinance").attr('required', false)
            $("#jenis").change(function() {
                var jenis = $("#jenis").val();
                if (jenis == "internal") {
                    $("#internalku").show();
                    $("#externalku").hide();
                    $("#internalnik").attr('required', true);
                    $("#internalnama").attr('required', true);
                    $("#externalnik").attr('required', false);
                    $("#externalnama").attr('required', false);
                    $("#nikfinance").attr('required', false);
                    $("#namafinance").attr('required', false)
                    $("#emailfinance").attr('required', false)
                } else if (jenis == 'external') {
                    $("#internalku").hide();
                    $("#externalku").show();
                    $("#internalnik").attr('required', false);
                    $("#internalnama").attr('required', false);
                    $("#externalnik").attr('required', true);
                    $("#externalnama").attr('required', true);
                    $("#nikfinance").attr('required', true);
                    $("#namafinance").attr('required', true);
                    $("#emailfinance").attr('required', true)
                } else {
                    $("#internalku").hide();
                    $("#externalku").hide();
                    $("#internalnik").attr('required', false);
                    $("#internalnama").attr('required', false);
                    $("#externalnik").attr('required', false);
                    $("#externalnama").attr('required', false);
                    $("#nikfinance").attr('required', false);
                    $("#namafinance").attr('required', false)
                    $("#emailfinance").attr('required', false)
                }
            });

            $("#internalnik").change(function() {
                var nik = $("#internalnik").val();
                var token = $('meta[name=csrf-token]').attr('content');
                console.log('nik :' + nik);
                $.ajax({
                    url: "<?php echo route('privileggetnama'); ?>",
                    method: 'POST',
                    data: {
                        nik: nik,
                        _token: token
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == '-') {
                            alert('Nik Not Found');
                            $("#internalnik").val('');
                            $("#internalnama").val('');
                        } else {
                            $("#internalnama").val(data);
                        }
                        cache: false
                    }
                });

            });


            $("#nikfinance").change(function() {
                var nik = $("#nikfinance").val();
                var token = $('meta[name=csrf-token]').attr('content');
                console.log('nik :' + nik);
                $.ajax({
                    url: "<?php echo route('privileggetnama'); ?>",
                    method: 'POST',
                    data: {
                        nik: nik,
                        _token: token
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == '-') {
                            alert('Nik Not Found');
                            $("#nikfinance").val('');
                            $("#namafinance").val('');
                        } else {
                            $("#namafinance").val(data);
                        }
                        cache: false
                    }
                });

            });

        })
    </script>
@endsection
