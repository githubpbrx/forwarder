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
                            <div class="d-flex">
                                <div class="p-2">
                                    <label class="control-label">Choose PO :</label>
                                    <select class="select2" style="width: 100%;" name="datapo" id="datapo">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="p-2">
                                    <label class="control-label"> &nbsp; </label>
                                    <a href="#" type="button" id="search" class="btn btn-info form-control"
                                        data-value="klik">Search</a>
                                </div>
                                <div class="ml-auto p-2">
                                    <a href="{{ url('report/alokasi/getexcelalokasiall') }}" type="button"
                                        class="btn btn-warning form-control">Download Data Excel</a>
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
                    <h4 class="modal-title"><span id="modaltitle">Detail Allocation</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Supplier</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="supplier" name="supplier" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Material</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="material" name="material"
                                            autocomplete="off" readonly>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Material Desc</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="matdesc" name="matdesc"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Style</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="style" name="style"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Quantity PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="qtypo" name="qtypo"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Quantity Allocation</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="qtyall" name="qtyall"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Plant</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="plant" name="plant"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="booking" name="booking"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="forwarder" name="forwarder"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Invoice</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="invoice" name="invoice"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Shipmode</label>
                                    <div class="col-sm-12">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="shipmode"
                                                    name="shipmode" autocomplete="off" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="subshipmode"
                                                    name="subshipmode" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">No BL</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobl" name="nobl"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Vessel</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="vessel" name="vessel"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">File BL</label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="filebl" name="filebl"
                                                    autocomplete="off" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#" id="downloadfile" target="_BLANK"
                                                    class="btn btn-info">Download
                                                    File</a>
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

            var namefile;
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

                    namefile = dataku.file_bl;

                    $('#nomorpo').val(dataku.pono);
                    $('#material').val(dataku.matcontents);
                    $('#matdesc').val(dataku.itemdesc);
                    $('#qtypo').val(dataku.qtypo);
                    $('#qtyall').val(dataku.qty_allocation);
                    $('#plant').val(dataku.plant);
                    $('#style').val(dataku.style);
                    $('#invoice').val(dataku.noinv);
                    $('#booking').val(dataku.kode_booking);
                    $('#forwarder').val(dataku.name);
                    $('#etd').val(dataku.etd);
                    $('#eta').val(dataku.eta);
                    $('#shipmode').val(dataku.shipmode);
                    $('#subshipmode').val(dataku.subshipmode);
                    $('#nobl').val(dataku.nomor_bl);
                    $('#vessel').val(dataku.vessel);
                    $('#filebl').val(dataku.file_bl);
                    $('#supplier').val(dataku.nama);
                })
            });

            $('#downloadfile').click(function(e) {
                // e.preventDefault();
                // console.log('klik :>> ', 'klik');
                var base = "{!! url('sources/storage/app') !!}" + "/" + namefile;
                $('#downloadfile').attr('href', base);
            });

        });
    </script>
@endsection
