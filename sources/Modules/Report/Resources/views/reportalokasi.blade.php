@extends('system::template/master')
@section('title', $title)
@section('link_href')

@endsection

@section('content')

    <div class="row" style="font-size: 10pt;">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="row">
                    <div class="col-md-12">
                        <div id="fullscreen-container" class="card-body" style="overflow-y: auto;">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="col-sm-5 control-label">Choose PO :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="datapo" id="datapo">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label class="col-sm-5 control-label"> &nbsp; </label>
                                    <div class="col-sm-12">
                                        <a href="#" type="button" id="search" class="btn btn-info form-control"
                                            data-value="klik">Search</a>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table id="dataTables" class="table table-bordered table-striped table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <center>PO</center>
                                            </th>
                                            <th>
                                                <center>QUANTITY PO</center>
                                            </th>
                                            <th>
                                                <center>QUANTITY ALLOCATION</center>
                                            </th>
                                            <th>
                                                <center>INVOICE</center>
                                            </th>
                                            <th>
                                                <center>FORWARDER</center>
                                            </th>
                                            <th>
                                                <center>STATUS ALLOCATION</center>
                                            </th>
                                            <th>
                                                <center>STATUS CONFIRM</center>
                                            </th>
                                            <th>
                                                <center>ACTION</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="detailall">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Data Reject PO</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Material</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="material" name="material" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="detailitem"></div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" /> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobook" name="nobook"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Date Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="datebook" name="datebook"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimate Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimate Acutal Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Ship Mode</label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="shipmode" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="subshipmode" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Description</label>
                                    <div class="col-sm-12">
                                        {{-- <input type="text" class="form-control" id="deskripsi" name="deskripsi"
                                            autocomplete="off" readonly> --}}
                                        <textarea name="deskripsi" id="deskripsi" cols="104" rows="2" disabled></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- /.modal content ----------------- --}}
@endsection

@section('script_src')
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var tabel = $('#dataTables').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('report/alokasi/search') }}",
                    data: function(d) {
                        d.po = $('#datapo').val()
                    }
                },
                columns: [{
                        data: 'po',
                        name: 'po'
                    },
                    {
                        data: 'qtypo',
                        name: 'qtypo'
                    },
                    {
                        data: 'qtyallocation',
                        name: 'qtyallocation'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'forwarder',
                        name: 'forwarder'
                    },
                    {
                        data: 'statusallocation',
                        name: 'statusallocation'
                    },
                    {
                        data: 'statusconfirm',
                        name: 'statusconfirm'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
                // ,
                // createdRow: function(row, data, index, cells) {
                //     $(cells[1]).css('color', 'white')

                //     if (data.kyc == 'confirm') {
                //         $(cells[1]).css('background-color', '#42ba96')
                //     } else {
                //         $(cells[1]).css('background-color', '#df4759')
                //     }
                // },
            });

            $('#dataTables').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('#search').click(function(e) {
                tabel.draw();
                // table.ajax.reload();
            });

            $('#datapo').select2({
                placeholder: '-- Choose PO --',
                ajax: {
                    url: "{!! route('report_getpo') !!}",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            // page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {

                            results: $.map(data, function(item) {
                                return {
                                    text: item.pono,
                                    id: item.pono,
                                    selected: true,
                                }
                            }),
                        };
                    },
                    cache: true
                }
            });

            $('body').on('click', '#detailalokasi', function() {
                $('#detailall').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('report_detailalokasi') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('data :>> ', data.data);
                    let dataku = data.data;
                    let poku = dataku.datapo;

                    $('#detailhtml').empty();
                    $('#detailstyle').empty();

                    var tot = 0;
                    length = poku.length;
                    html =
                        '<table border="0" style="width:100%"><tr><th style="text-align:center"><input type="checkbox" class="checkall" style="height:18px;width:18px"></th><th>Material</th><th>Style</th><th>Qty Item</th><th>Qty Allocation</th></tr>';
                    for (let index = 0; index < poku.length; index++) {

                        // $('#qtypo').val();
                        console.log('poku.qtypo :>> ', poku.qtypo);
                        tot = tot + Number(poku[index].qtypo);
                        html +=
                            '<tr><td style="text-align:center"><input type="checkbox" class="check-' +
                            index + '" style="height:18px;width:18px"></td><td>' + poku[index]
                            .matcontents + '</td><td>' + poku[index]
                            .style + '</td><td>' + poku[index].qtypo +
                            '</td><td><input type="number" min="0" id="qty_allocation" name="qty_allocation" class="form-control trigerinput cekinput-' +
                            index + '" data-id="' + poku[index].id + '" data-pono="' + poku[index]
                            .pono + '" data-qty="' + poku[index].qtypo + '" disabled></td></tr>';

                    }
                    html += "</table>";
                    $('#detailhtml').html(html);
                    checkqtyall();

                    qtypoku = poku.qtypo;
                    poid = poku.id;
                    ponumb = poku.pono;

                    $('#po').val(poku[0].pono);
                    $('#qtypo').val(tot);
                    $('#detailsup').val(poku[0].nama);
                })
            });

        });
    </script>
@endsection
