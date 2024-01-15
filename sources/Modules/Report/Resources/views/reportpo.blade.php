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
                            <div class="row justify-content-between col-md-12 ">
                                <div class="row col-md-6">
                                    <div class="col-md-4">
                                        <label class="control-label">Choose PO :</label>
                                        <div class="col-sm-12">
                                            <select class="select2" style="width: 100%;" name="datapo" id="datapo">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label"> &nbsp; </label>
                                        <div class="col-sm-12">
                                            <a href="#" type="button" id="search"
                                                class="btn btn-info form-control" data-value="klik">Search</a>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-2">
                                    <label class="control-label"> &nbsp; </label>
                                    <a href="{{ url('report/po/getexcelpoall') }}" type="button"
                                        class="btn btn-warning form-control">Download Excel</a>
                                </div> --}}
                            </div>
                            <br>
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
                                                <center>Date</center>
                                            </th>
                                            <th>
                                                <center>Amount</center>
                                            </th>
                                            <th>
                                                <center>Supplier</center>
                                            </th>
                                            <th>
                                                <center>Shipmode</center>
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

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="detailpomodal">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail PO</span></h4>
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
                                    <label class="col-sm-12 control-label">Supplier</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="supplier" name="supplier" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="formdetailpo"></div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
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
                    url: "{{ url('report/po/search') }}",
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function(d) {
                        d.po = $('#datapo').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex'
                    }, {
                        data: 'po',
                        name: 'po'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'shipmode',
                        name: 'shipmode'
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
                    url: "{!! url('report/po/getpo') !!}",
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

            $('body').on('click', '#detailpo', function() {
                $('#detailpomodal').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('report_detailpo') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('dataku :>> ', data.data);
                    let datapo = data.data;

                    $('#formdetailpo').empty();

                    html =
                        '<table border="1" style="width:100%"><tr><th>Material</th><th>Material Desc</th><th>Color</th><th>Size</th><th>Qty PO</th><th>Status</th></tr>';
                    for (let index = 0; index < datapo.length; index++) {
                        let status;
                        if (datapo[index].statusconfirm == 'confirm') {
                            status = 'Confirmed';
                        } else if (datapo[index].statusconfirm == 'reject') {
                            status = 'Rejected';
                        } else {
                            status = 'Unprocessed';
                        }

                        html +=
                            '<tr><td>' + datapo[index].matcontents + '</td><td>' + datapo[index]
                            .itemdesc + '</td><td>' + datapo[index].colorcode +
                            '</td><td>' + datapo[index].size + '</td><td>' + datapo[index].qtypo +
                            '</td><td>' + status +
                            '</td></tr>';
                    }

                    html += "</table>";
                    $('#formdetailpo').html(html);

                    $('#nomorpo').val(datapo[0].pono);
                    // $('#material').val(datapo.matcontents);
                    // $('#matdesc').val(datapo.itemdesc);
                    // $('#qtypo').val(datapo.qtypo);
                    // let curr = datapo.curr;
                    // let pr = datapo.price;
                    // $('#price').val(pr + ' ' + curr);
                    $('#supplier').val(datapo[0].nama);
                    // $('#plant').val(datapo.plant);
                    // $('#style').val(datapo.style);
                    // $('#buyer').val(datapo.buyer);
                })
            });
        });
    </script>
@endsection
