@extends('system::template/master')
@section('title', $title)
@section('link_href')

@endsection

@section('content')
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-primary">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body" style="overflow-y: auto;">
                            <div class="row mb-2">
                                <div>
                                    <label class="control-label">PO :</label>
                                    <select class="select2" style="width: 100%;" name="datapo" id="datapo">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="ml-2">
                                    <label class="control-label">Forwarder :</label>
                                    <select class="select2" style="width: 100%;" name="datafwd" id="datafwd">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="ml-2">
                                    <label class="control-label">Supplier :</label>
                                    <select class="select2" style="width: 100%;" name="datasupp" id="datasupp"
                                        multiple="multiple">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="ml-2">
                                    <label class="control-label">Periode :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="periode" class="form-control float-right"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <label class="control-label">&nbsp;</label>
                                    <a href="#" type="button" id="search" class="btn btn-info form-control"
                                        data-value="klik">Search</a>
                                </div>
                                <div class="ml-auto">
                                    <label class="control-label">&nbsp;</label>
                                    <button id="btndownload" class="btn btn-warning form-control" type="button">Download
                                        Excel</button>
                                </div>
                            </div>
                            <div class="row mt-3 mb-2">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body overflow-auto">
                                            <div id="mychartpo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="dataTables" class="table table-bordered table-striped table-hover"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center>NO</center>
                                                </th>
                                                <th>
                                                    <center>PO</center>
                                                </th>
                                                <th>
                                                    <center>Supplier</center>
                                                </th>
                                                <th>
                                                    <center>Forwarder</center>
                                                </th>
                                                <th>
                                                    <center>Code Booking</center>
                                                </th>
                                                <th>
                                                    <center>Invoice</center>
                                                </th>
                                                <th>
                                                    <center>Date Submit</center>
                                                </th>
                                                <th>
                                                    <center>ATD</center>
                                                </th>
                                                <th>
                                                    <center>ATA</center>
                                                </th>
                                                <th>
                                                    <center>BL Number</center>
                                                </th>
                                                <th>
                                                    <center>Action</center>
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
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="detailall">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Report Ready Shipment</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <div id="formdetail"></div>
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

            $('#periode').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#periode').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#periode').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $.ajax({
                type: "POST",
                url: "{!! route('report_getchartshipment') !!}",
                beforeSend: function(response) {
                    $('#mychartpo').html(
                        '<h6 class="text-center mt-5"><span class="fa-stack text-info"><i class="fas fa-circle fa-stack-2x"></i><i class="fas fa-hourglass-half fa-spin fa-stack-1x fa-inverse"></i></span> Please wait! Checking Chart Shipment...</h6>'
                    );
                },
                success: function(response) {
                    $('#mychartpo').html(response);
                }
            });

            var tabel = $('#dataTables').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('report/shipment/search') }}",
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function(d) {
                        d.pono = $('#datapo').val(),
                            d.idmasterfwd = $('#datafwd').val(),
                            d.idsupplier = $('#datasupp').val(),
                            d.periode = $('#periode').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'po',
                        name: 'po'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'forwarder',
                        name: 'forwarder'
                    },
                    {
                        data: 'codebook',
                        name: 'codebook'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'datesubmit',
                        name: 'datesubmit'
                    },
                    {
                        data: 'atd',
                        name: 'atd'
                    },
                    {
                        data: 'ata',
                        name: 'ata'
                    },
                    {
                        data: 'blnumber',
                        name: 'blnumber'
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
                $.ajax({
                    type: "POST",
                    url: "{!! route('report_getchartshipment') !!}",
                    data: {
                        pono: $('#datapo').val(),
                        idmasterfwd: $('#datafwd').val(),
                        idsupplier: $('#datasupp').val(),
                        periode: $('#periode').val()
                    },
                    beforeSend: function(response) {
                        $('#mychartpo').html(
                            '<h6 class="text-center mt-5"><span class="fa-stack text-info"><i class="fas fa-circle fa-stack-2x"></i><i class="fas fa-hourglass-half fa-spin fa-stack-1x fa-inverse"></i></span> Please wait! Checking Chart Shipment...</h6>'
                        );
                    },
                    success: function(response) {
                        $('#mychartpo').html(response);
                    }
                });
                // table.ajax.reload();
            });

            $('#datapo').select2({
                placeholder: '-- Choose PO --',
                ajax: {
                    url: "{!! route('report_getposhipment') !!}",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.pono,
                                    id: item.pono,
                                    selected: true,
                                }
                            }),
                            pagination: {
                                more: data.to < data.total
                            }
                        };
                    },
                    cache: true
                }
            });

            $('#datafwd').select2({
                placeholder: '-- Choose Forwarder --',
                ajax: {
                    url: "{!! route('report_getfwdshipment') !!}",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                    selected: true,
                                }
                            }),
                            pagination: {
                                more: data.to < data.total
                            }
                        };
                    },
                    cache: true
                }
            });

            $('#datasupp').select2({
                placeholder: '-- Choose Supplier --',
                ajax: {
                    url: "{!! route('report_getsuppshipment') !!}",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.id,
                                    selected: true,
                                }
                            }),
                            pagination: {
                                more: data.to < data.total
                            }
                        };
                    },
                    cache: true
                }
            });

            $('body').on('click', '#detailshipment', function() {
                $('#detailall').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('report_detailshipment') !!}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    $('#formdetail').html(data);
                })
            });

            $("#btndownload").click(function(e) {
                let datapo = $('#datapo').val();
                let datafwd = $('#datafwd').val();
                let datasupp = $('#datasupp').val();
                let dataper = $('#periode').val();

                var query = {
                    'pono': datapo,
                    'idmasterfwd': datafwd,
                    'idsupplier': datasupp,
                    'periode': dataper,
                }
                var url = "{{ url('report/shipment/getexcelshipmentall') }}?" + $.param(query);
                window.open(url, '_blank');
            });
        });
    </script>
@endsection
