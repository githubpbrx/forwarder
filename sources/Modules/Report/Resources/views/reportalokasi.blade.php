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
                                    <label class="control-label">Choose PO :</label>
                                    <select class="select2" style="width: 100%;" name="datapo" id="datapo">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="control-label"> &nbsp; </label>
                                    <a href="#" type="button" id="search" class="btn btn-info form-control"
                                        data-value="klik">Search</a>
                                </div>
                                {{-- <div class="ml-auto p-2">
                                    <a href="{{ url('report/alokasi/getexcelalokasiall') }}" type="button"
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
                                                <center>Invoice</center>
                                            </th>
                                            <th>
                                                <center>Code Booking</center>
                                            </th>
                                            <th>
                                                <center>BL Number</center>
                                            </th>
                                            <th>
                                                <center>Forwarder</center>
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
    <div class="modal fade" id="detailall">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Report Allocation</span></h4>
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
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'kodebook',
                        name: 'kodebook'
                    },
                    {
                        data: 'blnumber',
                        name: 'blnumber'
                    },
                    {
                        data: 'forwarder',
                        name: 'forwarder'
                    },
                    // {
                    //     data: 'statusallocation',
                    //     name: 'statusallocation'
                    // },
                    // {
                    //     data: 'statusconfirm',
                    //     name: 'statusconfirm'
                    // },
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
                    url: "{!! route('report_getpoalokasi') !!}",
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
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    $('#formdetail').html(data);
                    // console.log('data :>> ', data.data);
                    // let dataku = data.data;

                    // namefile = dataku.file_bl;

                    // $('#nomorpo').val(dataku.pono);
                    // $('#material').val(dataku.matcontents);
                    // $('#matdesc').val(dataku.itemdesc);
                    // $('#qtypo').val(dataku.qtypo);
                    // $('#supplier').val(dataku.nama);
                    // $('#style').val(dataku.style);
                    // $('#plant').val(dataku.plant);
                    // $('#forwarder').val(dataku.name);
                    // $('#booking').val(dataku.kode_booking);
                    // $('#qtyship').val(dataku.qty_shipment);
                    // $('#invoice').val(dataku.noinv);
                    // $('#etd').val(dataku.etdfix);
                    // $('#eta').val(dataku.etafix);
                    // $('#shipmode').val(dataku.shipmode);
                    // $('#subshipmode').val(dataku.subshipmode);
                    // $('#nobl').val(dataku.nomor_bl);
                    // $('#vessel').val(dataku.vessel);
                    // $('#filebl').val(dataku.file_bl);
                })
            });
        });
    </script>
@endsection
