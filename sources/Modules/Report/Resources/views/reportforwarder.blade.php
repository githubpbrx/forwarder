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
                                {{-- <div class="p-2"> --}}
                                <div class="col-md-3">
                                    <label class="control-label">Choose PO :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="datapo" id="datapo">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                {{-- </div> --}}
                                {{-- <div class="p-2"> --}}
                                <div class="col-md-1">
                                    <label class="control-label"> &nbsp; </label>
                                    <div class="col-sm-12">
                                        <a href="#" type="button" id="search" class="btn btn-info form-control"
                                            data-value="klik">Search</a>
                                    </div>
                                </div>
                                {{-- </div> --}}
                                {{-- <div class="ml-auto p-2">
                                    <label class="control-label"> &nbsp; </label>
                                    <a href="{{ url('report/forwarder/getexcelforwarderall') }}" type="button"
                                        class="btn btn-warning form-control">Download Data Excel</a>
                                </div> --}}
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
                                                <center>Supplier</center>
                                            </th>
                                            <th>
                                                <center>Code Booking</center>
                                            </th>
                                            <th>
                                                <center>Invoice</center>
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

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="detailforwarder">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail History Forwarder</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <div id="modalreportfwd"></div>
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
                    url: "{{ url('report/forwarder/search') }}",
                    data: function(d) {
                        d.po = $('#datapo').val()
                    }
                },
                columns: [{
                        data: 'po',
                        name: 'po'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'nobook',
                        name: 'nobook'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'nobl',
                        name: 'nobl'
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
                    url: "{!! url('report/forwarder/getpo') !!}",
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

            $('body').on('click', '#detailpo', function() {
                $('#detailforwarder').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('report_detailforwarder') !!}",
                    type: 'POST',
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('data :>> ', data);
                    $('#modalreportfwd').html(data);
                    // let dataformpo = data.data.dataformpo;
                    // let datapo = data.data.datapo;
                    // let dataforwarder = data.data.dataforwarder;
                    // let mydata = data.data.alldata;

                    // $('#nomorpo').val(mydata.pono);
                    // $('#supplier').val(mydata.nama);
                    // $('#material').val(mydata.matcontents);
                    // $('#qtypo').val(mydata.qtypo);
                    // $('#qtyall').val(mydata.qty_allocation);
                    // $('#kodebook').val(mydata.kode_booking);
                    // $('#invoice').val(mydata.noinv);
                    // $('#etd').val(mydata.etdfix);
                    // $('#eta').val(mydata.etafix);
                    // $('#nobl').val(mydata.nomor_bl);
                    // $('#vessel').val(mydata.vessel);
                    // $('#shipmode').val(mydata.shipmode);
                    // $('#subshipmode').val(mydata.subshipmode);
                })
            });
        });
    </script>
@endsection
