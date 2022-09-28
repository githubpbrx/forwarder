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
                            <form action="#" class="form-horizontal" enctype="multipart/form-data" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="col-sm-12 control-label">Choose Suplier :</label>
                                        <div class="col-sm-12">
                                            <select class="select2" style="width: 100%;" name="supplier" id="supplier">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-sm-12 control-label">Periode</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <input type="date" class="form-control" name="tanggal1"
                                                        id="tanggal1">
                                                </div>
                                                <div class="col-sm-1">
                                                    <b>To</b>
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="date" class="form-control" name="tanggal2"
                                                        id="tanggal2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="col-sm-12 control-label">Status Forwarder :</label>
                                        <div class="col-sm-12">
                                            <select class="select2" style="width: 100%;" name="status" id="status">
                                                <option value="all">All</option>
                                                <option value="waiting">Waiting</option>
                                                <option value="partial_allocated">Partial Allocated</option>
                                                <option value="full_allocated">Full Alocated</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-sm-12 control-label">&nbsp;</label>
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-info form-control"
                                                id="searchdata">Search</button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <div class="table-responsive" style="padding-top: 20px;">
                                <table id="dataTables" class="table table-bordered table-striped table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <center>PO#</center>
                                            </th>
                                            <th>
                                                <center>Date</center>
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

    <!-- Modal Detail Allocation -->
    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle"></span> Data Detail Allocation Forwarder</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt">
                    <form class="form-horizontal" id="form-detail">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" value="#" id="po"
                                            name="po" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label class="col-sm-8 control-label">Quantity PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" value="#" id="qtypo"
                                            name="qtypo" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Supplier</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" value="#" id="detailsup"
                                            name="detailsup" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="detailhtml"></div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div id="detailstyle"></div>
                            </div> --}}
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />

                        {{-- <label class="col-sm-12 control-label">Please input allocation</label>
                        <hr> --}}
                        <div class="row">
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Quantity Allocation</label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="checkbox" class="" name="" id="">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="qty_allocation"
                                                    name="qty_allocation" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div id="qtyall"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Forwarder</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="forwarder" id="forwarder">
                                            <option value=""></option>
                                        </select>
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

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#dataTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('transaksi/allocation/search') }}",
                    data: function(d) {
                        d.supplier = $('#supplier').val(),
                            d.tanggal1 = $('#tanggal1').val(),
                            d.tanggal2 = $('#tanggal2').val(),
                            d.status = $('#status').val()
                    }
                },
                columns: [{
                        data: 'poku',
                        name: 'poku'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            // $(".searchEmail").keyup(function() {
            $('#searchdata').click(function(e) {
                table.draw();
            });

            $('#supplier').select2({
                placeholder: '-- Choose Supplier --',
                ajax: {
                    url: "{!! route('allocation_getsupplier') !!}",
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
                                    text: item.nama,
                                    id: item.id,
                                    selected: true,
                                }
                            }),
                        };
                    },
                    cache: true
                }
            });

            var qtypoku;
            var poid;
            var ponumb;
            var length;
            $('body').on('click', '#detailbtn', function() {
                $('#modal-detail').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('detail_allocation') !!}",
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
                    // let fwdku = dataku.datafwd;
                    // console.log('fwdku :>> ', fwdku);
                    console.log('poku :>> ', poku[0].supplier.nama);
                    $('#detailhtml').empty();
                    $('#detailstyle').empty();

                    var tot = 0;
                    length = poku.length;
                    html =
                        '<table border="0" style="width:100%"><tr><th style="text-align:center"><input type="checkbox" class="checkall" style="height:18px;width:18px"></th><th>Material</th><th>Style</th><th>Qty Item</th><th>Remaining Qty</th><th>Qty Allocation</th></tr>';
                    for (let index = 0; index < poku.length; index++) {
                        let nullku;

                        if (poku[index].qtyall == null) {
                            nullku = '0';
                        } else {
                            nullku = poku[index].qtyall;
                        }

                        // $('#qtypo').val();
                        console.log('poku.qtypo :>> ', poku.qtypo);
                        tot = tot + Number(poku[index].qtypo);
                        html +=
                            '<tr><td style="text-align:center"><input type="checkbox" class="check-' +
                            index + '" style="height:18px;width:18px"></td><td>' + poku[index]
                            .matcontents + '</td><td>' + poku[index]
                            .style + '</td><td>' + poku[index].qtypo +
                            '</td><td>' + nullku +
                            '</td><td><input type="number" min="0" id="qty_allocation" name="qty_allocation" class="form-control trigerinput cekinput-' +
                            index + '" data-id="' + poku[index].id + '" data-pono="' + poku[index]
                            .pono + '" data-qty="' + poku[index].qtypo + '" disabled></td></tr>';

                        // $('#detailhtml').append(
                        //     `<table style="width:100%" border="1"><tr style="width:100%"><td></td><td>
                    //         <label>Material</label><i>` + poku[index].matcontents +
                        //     `</i></td><td><label>Style</label><i>` + poku[index]
                        //     .style +
                        //     `</i></td><td></td></tr></table>`
                        // );

                    }
                    html += "</table>";
                    $('#detailhtml').html(html);
                    checkqtyall();

                    qtypoku = poku.qtypo;
                    poid = poku.id;
                    ponumb = poku.pono;

                    $('#po').val(poku[0].pono);
                    $('#qtypo').val(tot);
                    $('#detailsup').val(poku[0].supplier.nama);
                })
            });

            function checkqtyall() {

                $('.checkall').change(function(e) {
                    if (this.checked) {
                        $('.trigerinput').prop('disabled', false);
                        $('input[type="checkbox"]').prop('checked', true);
                    } else {
                        $('.trigerinput').val('');
                        $('.trigerinput').prop('disabled', true);
                        $('input[type="checkbox"]').prop('checked', false);
                    }
                });

                for (let index = 0; index < Number(length); index++) {
                    $('.check-' + index).change(function(e) {
                        if (this.checked) {
                            console.log('objectsijine :>> ', 'isChecked');
                            $('.cekinput-' + index).prop('disabled', false);
                            // }
                        } else {
                            console.log('objectsijine :>> ', 'notChecked');
                            $('.cekinput-' + index).val('');
                            $('.cekinput-' + index).prop('disabled', true);
                        }
                    });
                }
            }

            $('#forwarder').select2({
                placeholder: '-- Choose Forwarder --',
                ajax: {
                    url: "{!! route('get_forwarder') !!}",
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
                                    text: item.name,
                                    id: item.id,
                                    selected: true,
                                }
                            }),
                        };
                    },
                    cache: true
                }
            });

            $('#btnsubmit').click(function(e) {
                let idku = poid;
                let fwd = $('#forwarder').val();

                var arrayqty = [];
                for (let index = 0; index < Number(length); index++) {
                    let dataqtypo = $('.cekinput-' + index).attr('data-qty');
                    let val = $('.cekinput-' + index).val();

                    if (Number(dataqtypo) < Number(val)) {
                        Swal.fire({
                            title: 'Informasi',
                            text: 'Data Quantity Allocation Over Quantity PO',
                            type: 'warning'
                        });
                    }

                    if (val) {
                        let data = {
                            'id': $('.cekinput-' + index).attr('data-id'),
                            'pono': $('.cekinput-' + index).attr('data-pono'),
                            'value': val,
                        };

                        arrayqty.push(data)
                    }
                }
                console.log('dataaray :>> ', arrayqty);

                $.ajax({
                    type: "post",
                    url: "{{ route('detailaction') }}",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        forwarder: fwd,
                        arrayqty: arrayqty
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == "error") {
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            });
                            return;
                        }

                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {

                            $('#modal-detail').modal('hide');
                            table.ajax.reload();
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
            });
        });
    </script>
@endsection
