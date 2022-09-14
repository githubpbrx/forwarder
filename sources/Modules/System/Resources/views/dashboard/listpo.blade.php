@extends('system::template/master')
@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-body">

            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>List PO#</th>
                        <th>Items PO</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="formulir_po">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Formulir PO</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-sm-7 control-label">Nomor PO</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobook" name="nobook"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Date Booking</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" id="datebook" name="datebook">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimate Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" id="etd" name="etd">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimate Acutal Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" id="eta" name="eta">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Ship Mode</label>
                                    <div class="col-sm-12">
                                        <select class="form-control select2" style="width: 100%;" name="shipmode"
                                            id="shipmode">
                                            <option value="-1" selected disabled>-- Choose Mode --</option>
                                            <option value="fcl">FCL</option>
                                            <option value="lcl">LCL</option>
                                            <option value="air">Air</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="datafcl" style="display: none">
                                    <label class="col-sm-12 control-label">FCL</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="fclku" id="fclku">
                                            <option value="20">20"</option>
                                            <option value="40">40"</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="datalcl" style="display: none">
                                    <label class="col-sm-12 control-label">LCL</label>
                                    <div class="col-sm-12">
                                        {{-- <select class="select2" style="width: 100%;" name="lclku" id="lclku">
                                            <option value="cbm">CBM</option>
                                        </select> --}}
                                        <div class="input-group">
                                            <input type="number" min="0" class="form-control" name="lclku"
                                                id="lclku" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text">CBM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="dataair" style="display: none">
                                    <label class="col-sm-12 control-label">AIR</label>
                                    <div class="col-sm-12">
                                        {{-- <select class="select2" style="width: 100%;" name="airku" id="airku">
                                            <option value="kg">KG</option>
                                        </select> --}}
                                        <div class="input-group">
                                            <input type="number" min="0" class="form-control" name="airku"
                                                id="airku" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text">KG</span>
                                            </div>
                                        </div>
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

            $('#shipmode').on('change', function() {
                let mode = $(this).val();
                console.log('training :>> ', mode);
                console.log('object :>> ', 'klik');
                if (mode == 'fcl') {
                    $('#datafcl').show()
                    $('#datalcl').hide()
                    $('#dataair').hide()

                } else if (mode == 'lcl') {
                    $('#datalcl').show()
                    $('#datafcl').hide()
                    $('#dataair').hide()
                } else {
                    $('#dataair').show()
                    $('#datafcl').hide()
                    $('#datalcl').hide()
                }
            });

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('list_po') }}"
                },
                columns: [{
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'itempo',
                        name: 'itempo'
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
            var idfwd;
            $('body').on('click', '#formpo', function() {
                $('#formulir_po').modal({
                    show: true,
                    backdrop: 'static'
                });

                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('form_po') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    let poku = data.data.datapo;

                    idpo = poku.id;
                    idfwd = poku.idmasterfwd;

                    $('#nomorpo').val(poku.pono);
                })
            });

            $('#btnsubmit').click(function(e) {
                let idku = idpo;
                let nobook = $('#nobook').val();
                let datebook = $('#datebook').val();
                let myetd = $('#etd').val();
                let myeta = $('#eta').val();
                let mode = $('#shipmode').val();
                let myfcl = $('#fclku').val();
                let mylcl = $('#lclku').val();
                let myair = $('#airku').val();
                console.log('mode :>> ', mode);
                console.log('mylcl :>> ', mylcl);
                console.log('myair :>> ', myair);

                if (nobook == null || nobook == '') {
                    notifalert('Nomor Booking');
                } else if (datebook == null || datebook == '') {
                    notifalert('Date Booking');
                } else if (myetd == null || myetd == '') {
                    notifalert('ETD (Estimate Delivery Date)');
                } else if (myeta == null || myeta == '') {
                    notifalert('ETA (Estimate Acutal Delivery Date)');
                } else if (mode == null || mode == '') {
                    notifalert('Ship Mode');
                } else if (mode == 'lcl' && mylcl == '') {
                    notifalert('LCL');
                } else if (mode == 'air' && myair == '') {
                    notifalert('AIR');
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('formposave') }}",
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            'idpo': idku,
                            'idfwd': idfwd,
                            'nobooking': nobook,
                            'datebooking': datebook,
                            'etd': myetd,
                            'eta': myeta,
                            'shipmode': mode,
                            'fcl': myfcl,
                            'lcl': mylcl,
                            'air': myair
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                (response.status == 'success') ? window.location
                                    .replace("{{ route('page_po') }}"):
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
