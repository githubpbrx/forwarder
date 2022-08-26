@extends('system::template/master')
@section('title', $title)

@section('content')
<div class="card card-default col-md-8 offset-md-2">
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>

        <div class="card-tools">
        </div>
    </div>
    <!-- /.card-header -->
    
    <div class="card-body">
        <form id="form_factory" action="{{ $action }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input name="factory_id" value="{{Crypt::encrypt($factory_id)}}" type="hidden">
            <div class="form-group">
                <label>Kode Factory</label>
                <input value="{{ $factory_code }}" name="factory_code" type="text" class="form-control" placeholder="Kode" required>
            </div>
            <div class="form-group">
                <label>Nama Factory</label>
                <input value="{{ $factory_name }}" name="factory_name" type="text" class="form-control" placeholder="Nama" required>
            </div>
            <div class="form-group">
                <label>Nama Perusahaan</label>
                <input value="{{ $factory_company_name }}" name="factory_company_name" type="text" class="form-control" placeholder="Nama Perusahaan" required>
            </div>
            <div class="form-group">
                <label>Alamat Perusahaan</label>
                <input value="{{ $factory_company_address }}" name="factory_company_address" type="text" class="form-control" placeholder="Nama Perusahaan" required>
            </div>
            <div class="form-group">
                <label>Email Perusahaan</label>
                <input value="{{ $factory_email }}" name="factory_company_email" type="text" class="form-control" placeholder="Email Perusahaan" required>
            </div>
            <label>Logo Perusahaan</label>
            @if ($factory_logo != '')
                <code>*kosongkan jika tidak dirubah</code>
            @endif
            <div class="row">
                <div class="col-md-9">
                    <div class="custom-file">
                    <input name="factory_logo" type="file" class="custom-file-input" id="factory_logo" {{ $factory_logo != '' ? '' : 'required' }}>
                        <label class="custom-file-label" for="factory_logo">Choose file</label>
                    </div>
                    Format : <code>*.png / jpeg / jpg</code> , Max : <code>1 MB</code> <br>
                    
                </div>
                <div class="col-md-3">
                    @if ($factory_logo != '')
                        <img src="{{ url('public/uploads/images/factory/'.$factory_logo) }}" alt="" width="100%">
                    @else
                        <span class="text-muted">Belum ada foto</span>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <a href="{{ url('factory') }}" class="btn btn-default">Kembali</a>
        <button type="submit" class="btn btn-info float-right" form="form_factory">Simpan</button>
    </div>
</div>
@endsection

@section('script_src')
    <script src="{{ asset('public/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
@endsection
@section('script')
<script>
    $(function () {
        bsCustomFileInput.init();
    })

    function checkMimeType(input) {
        var validMimeType = ['89504e47', 'ffd8ffe0', 'ffd8ffe1', 'ffd8ffe2'];
        var files = $(input).get(0).files;

        if (files.length > 0) {
            var file = files[0];
            var fileReader = new FileReader();
            fileReader.onloadend = function (e) {
                var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
                var header = '';
                for (var i = 0; i < arr.length; i++) {
                    header += arr[i].toString(16);
                }

                if (!validMimeType.includes(header)) {
                    $('#factory_logo').val('');
                    $('#factory_logo').removeAttr('src');
                    $('.custom-file-label').html('Choose file')
                    sweetAlert("error", "Gambar tidak support", "MimeType doesn't match, image is invalid or unsupported");
                }
            }
            fileReader.readAsArrayBuffer(file);
        }
    }

    $('#factory_logo').bind('change', function() {
        var size = this.files[0].size/1024/1024;
        var type = this.files[0].type;
        var validType = ['image/jpeg', 'image/png'];

        if (!validType.includes(type)) {
            sweetAlert( "warning", "Pilih format Gambar", "contoh : nama_file.jpeg / .png");
            $('#factory_logo').val('');
            $('#kop_gambar_preview').removeAttr('src');
            $('.custom-file-label').html('Choose file')
        }else{
            if (size >= 1) {
                sweetAlert("warning", "Ukuran terlalu besar", "Maksimal 1 Mb");
                $('#factory_logo').val('');
                $('#factory_logo').removeAttr('src');
                $('.custom-file-label').html('Choose file')
            }else{
                checkMimeType(this);
            }
        }
    })
</script>
@endsection
