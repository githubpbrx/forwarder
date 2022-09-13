@extends('system::login/login_master')
@section('title', $title)

@section('content')
    <form action="#" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="login-box">
            <div class="login-logo">
                Validation KYC <bR>
            </div>
            <div class="card">
                <div class="card-header bg-primary">Validation KYC</div>
                <div class="card-body" style="background-color: #A5F1E9">

                    <input value="{{ $nik }}" id="nik" name="nik" type="hidden">
                    <input value="{{ $nama }}" id="nama" name="nama" type="hidden">
                    @if ($statuskyc != null)
                        <div class="form-group">
                            <center>
                                <h3>
                                    PLEASE WAIT!
                                    <br>
                                    YOUR DATA IS CURRENTLY PROCESSING
                                </h3>
                            </center>
                        </div>
                    @else
                        <div class="form-group">
                            <input id="filekyc" name="filekyc" type="file" class="form-control "
                                placeholder="Enter File..." required>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-primary">
                    <div class="float-left">
                        <a href="{{ url('logout') }}"><i class="btn btn-danger float-left">Exit</i></a>
                    </div>
                    @if ($statuskyc == null)
                        <div class="float-right">
                            <button id="upload" type="button" class="btn btn-success float-right">Upload</button>
                        </div>
                    @endif
                </div>
                @if ($statuskyc == null)
                    <a href="{{ url('sources\storage\public\file_kyc.xlsx') }}" target="_BLANK" class="btn"
                        style="background-color: #82a1f5;">DOWNLOAD FILE KYC</a>
                @endif

            </div>
        </div>
    </form>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#upload').click(function(e) {
                console.log('klik :>> ', 'klik');
                let nikku = $('#nik').val();
                let namaku = $('#nama').val();
                let fileku = $('#filekyc').prop('files')[0];
                // let token = $('input[name="_token"]').val();
                let token = $('meta[name=csrf-token]').attr('content');
                let form_data = new FormData();
                form_data.append('nik', nikku);
                form_data.append('nama', namaku);
                form_data.append('file', fileku);
                form_data.append('_token', token);

                console.log('form :>> ', form_data);

                if (fileku == null || fileku == '') {
                    Swal.fire({
                        title: 'Information',
                        text: ' File KYC Can not be empty',
                        type: 'warning'
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('validasikycaction') }}",
                        processData: false,
                        contentType: false,
                        data: form_data,
                        dataType: "json",
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                (response.status == 'success') ? window.location
                                    .replace("{{ route('dashcam') }}"):
                                    ''
                            });
                            return;
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Unsuccessfully Saved Data',
                                text: 'Check Your Data',
                                type: 'error'
                            });
                            return;
                        }
                    });
                }
            });

        });
    </script>
@endsection
