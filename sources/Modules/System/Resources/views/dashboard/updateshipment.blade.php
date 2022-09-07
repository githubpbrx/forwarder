@extends('system::template/master')
@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-body">

            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>List PO#</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="updateshipment">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Formulir PO</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobook" name="nobook" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Date Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="datebook" name="datebook" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimate Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimate Acutal Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Ship Mode</label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="shipmode" name="shipmode"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="submode" name="submode"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">BL</label>
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" id="bl" name="bl">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor BL</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobl" name="nobl">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Vessel</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="vessel" name="vessel">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info" id="btnsubmit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- /.modal content ----------------- --}}
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('list_update') }}"
                },
                columns: [{
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

            var idpo;
            var idformpo;
            $('body').on('click', '#updateship', function() {
                $('#updateshipment').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('form_update') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('object :>> ', data.data);
                    let databook = data.data.databook;
                    let poku = data.data.datapo;

                    idpo = poku.id;
                    idformpo = databook.id_formpo;

                    $('#nomorpo').val(poku.pono);
                    $('#nobook').val(databook.kode_booking);
                    $('#datebook').val(databook.date_booking);
                    $('#etd').val(databook.etd);
                    $('#eta').val(databook.eta);
                    $('#shipmode').val(databook.shipmode);
                    $('#submode').val(databook.subshipmode);
                })
            });

            $('#btnsubmit').click(function(e) {
                let idku = idpo;
                let formpo = idformpo;
                let filebl = $('#bl').val();
                let nomorbl = $('#nobl').val();
                let vessel = $('#vessel').val();
                let file = $('#bl').prop('files')[0];
                let form_data = new FormData();
                form_data.append('idpo', idku);
                form_data.append('idformpo', formpo);
                form_data.append('nomorbl', nomorbl);
                form_data.append('vessel', vessel);
                form_data.append('file', file);

                console.log('form :>> ', form_data);

                if (filebl == null || filebl == '') {
                    notifalert('File BL');
                } else if (nomorbl == null || nomorbl == '') {
                    notifalert('Nomor BL');
                } else if (vessel == null || vessel == '') {
                    notifalert('Vessel');
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('saveshipment') }}",
                        processData: false,
                        contentType: false,
                        data: form_data,
                        // data: {
                        //     _token: $('meta[name=csrf-token]').attr('content'),
                        //     'idpo': idku,
                        //     'idformpo': formpo,
                        //     'file': form_data,
                        //     'nomorbl': nomorbl,
                        //     'vessel': vessel,
                        // },
                        dataType: "json",
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                (response.status == 'success') ? window.location
                                    .replace("{{ route('page_update') }}"):
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

            function notifalert(params) {
                Swal.fire({
                    title: 'Information',
                    text: params + ' Can not be empty',
                    type: 'warning'
                });
                return;
            }

        });
    </script>
@endsection
