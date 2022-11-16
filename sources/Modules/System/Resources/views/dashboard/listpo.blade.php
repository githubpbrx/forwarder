@extends('system::template/master')
@section('title', $title)
@section('link_href')
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.min.css"> --}}
    {{-- <link type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
    rel="stylesheet" /> --}}
@endsection

@section('content')
    <div class="card" style="font-size: 10pt;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Choose PI Delivery Date :</label>
                    <div class="col-sm-12">
                        {{-- <select class="select2" style="width: 100%;" name="pidate" id="pidate">
                            <option value=""></option>
                        </select> --}}
                        <input type="text" class="form-control" id="selectdate" name="selectdate" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="control-label"> &nbsp; </label>
                    <div class="col-sm-12">
                        <a href="#" type="button" id="search" class="btn btn-info form-control"
                            data-value="klik">Search</a>
                    </div>
                </div>
            </div>
            <br>
            {{-- <form id="form-save"> --}}
            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>
                            <center><input type="checkbox" name="checkall" id="checkall"></center>
                        </th>
                        <th>
                            <center>PO Number</center>
                        </th>
                        <th>
                            <center>PI Number</center>
                        </th>
                        <th>
                            <center>PI Delivery</center>
                        </th>
                        <th>
                            <center>Supplier</center>
                        </th>
                    </tr>
                </thead>
            </table>
            <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a>
            <button type="button" class="btn btn-info" id="btnprocess">Process</button>
            {{-- </form> --}}
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="formulir_po">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Booking Detail</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="bodymodaldetail"></div>
            </div>
        </div>
    </div>
    {{-- ----------------- /.modal content ----------------- --}}
@endsection

@section('script')
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script> --}}\
    {{-- <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#selectdate').datepicker({
                changeYear: true,
                changeMonth: true,
                // minDate: 0,
                dateFormat: "yy-m-dd",
                yearRange: "-100:+20",
            });

            // var repo = {};
            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('listpo') }}",
                    data: function(d) {
                        d.pidate = $('#selectdate').val()
                    }
                },
                columns: [{
                        data: 'cekbok',
                        name: 'cekbok',
                        orderable: false,
                        orderable: false,
                        // data: 'select-checkbox',
                        // className: 'select-checkbox',
                        // targets: 0
                        // 'targets': 0,
                        // 'checkboxes': {
                        //     'selectRow': true,
                        //     'selectCallback': function(cellNodes, isSelected) {
                        //         console.log('isSelected :>> ', isSelected);
                        //         var rowData = oTable.row($(cellNodes[0]).parent()).data();
                        //         // console.log('rowID :>> ', rowData);
                        //         //var rowPk = cellNodes.settings().rowId;
                        //         var rowID = rowData['action']; // rowData[rowPk];

                        //         if (isSelected === false) {
                        //             delete repo[rowID];
                        //         }

                        //         if (isSelected === true) {
                        //             repo[rowID] = rowData;
                        //         }
                        //     },
                        // }
                    },
                    {
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'pinomor',
                        name: 'pinomor'
                    },
                    {
                        data: 'pidel',
                        name: 'pidel'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ],
                // select: {
                //     style: 'multi',
                //     selector: 'td:first-child'
                // },
                // 'order': [
                //     [1, 'asc']
                // ]
            });

            $('#serverside').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('#search').click(function(e) {
                oTable.draw();
                // table.ajax.reload();
            });

            $('#pidate').select2({
                placeholder: '-- Choose PI Date --',
                ajax: {
                    url: "{!! url('getpidate') !!}",
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
                                    text: item.pideldate,
                                    id: item.pideldate,
                                    selected: true,
                                }
                            }),
                        };
                    },
                    cache: true
                }
            });

            var multi_id = [];
            $('#checkall').change(function(e) {
                $('#mycekbok').prop('checked', false);
                singleid = [];
                if (this.checked) {
                    $('input[type="checkbox"]').prop('checked', true);
                    let lengthtable = oTable.data().count();
                    let data = oTable.data();

                    for (let index = 0; index < Number(lengthtable); index++) {
                        multi_id.push(data[index]['pino']);
                    }
                } else {
                    $('input[type="checkbox"]').prop('checked', false);
                    multi_id = [];
                }
            });

            var singleid = [];
            $('body').on('change', '#mycekbok', function() {
                if (multi_id.length != 0) {
                    $('#checkall').prop('checked', false);
                    var removeItem1 = this.value;
                    singleid = jQuery.grep(multi_id, function(value) {
                        return value != removeItem1;
                    });
                    multi_id = [];
                }

                if (this.checked) {
                    singleid.push(this.value)
                } else {
                    var removeItem = this.value;
                    singleid = jQuery.grep(singleid, function(value) {
                        return value != removeItem;
                    });
                }
            });

            $('#btnprocess').click(function(e) {
                let data;
                if (singleid.length == 0) {
                    data = multi_id;
                } else {
                    data = singleid;
                }

                if (singleid.length == 0 && multi_id.length == 0) {
                    Swal.fire({
                        title: 'Information',
                        text: 'Please Select PO Number',
                        type: 'warning'
                    });
                } else {
                    $.ajax({
                        type: "post",
                        url: "{!! route('form_po') !!}",
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            dataku: data,
                        },
                        // dataType: "json",
                        success: function(response) {
                            $('#formulir_po').modal({
                                show: true,
                                backdrop: 'static'
                            });
                            $('#bodymodaldetail').html(response);
                        }
                    });
                }
            });

            var length;
            $('body').on('click', '#formpo', function() {
                // $('#formulir_po').modal({
                //     show: true,
                //     backdrop: 'static'
                // });

                let idku = $(this).attr('data-id');
                console.log('idku :>> ', idku);
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
                    // let forwarderku = data.data.dataforwarder;
                    // console.log('forwarderku :>> ', forwarderku);
                    console.log('poku :>> ', poku);

                    length = poku.length;
                    $('#detailitem').empty();

                    html =
                        '<table border="0" style="width:100%"><tr><th>Material</th><th>Material Description</th><th>HS Code</th><th>Color Code</th><th>Size</th><th>Quantity Item</th><th>Status</th></tr>';
                    for (let index = 0; index < poku.length; index++) {
                        html +=
                            '<tr><td>' + poku[index].matcontents + '</td><td>' + poku[index]
                            .itemdesc + '</td><td>' + 'kosong' + '</td><td>' +
                            poku[index].colorcode + '</td><td>' + poku[index].size + '</td><td>' +
                            poku[index].qtypo + '</td><td>' + poku[index].statusforwarder +
                            '</td><td><input type="hidden" id="idall-' + index + '" data-id="' +
                            poku[index].id + '" data-idfwd="' + poku[index].id_forwarder +
                            '" data-idmasterfwd="' + poku[index].idmasterfwd +
                            '"></td></tr>';
                    }

                    html += "</table>";
                    $('#detailitem').html(html);

                    $('#nomorpo').val(poku[0].pono);
                    $('#supplier').val(poku[0].nama);
                })
            });
        });
    </script>
@endsection
