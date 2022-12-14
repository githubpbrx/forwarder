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
                                                <input type="date" class="form-control" name="tanggal1" id="tanggal1">
                                            </div>
                                            <div class="col-sm-1">
                                                <b>To</b>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" name="tanggal2" id="tanggal2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="col-sm-12 control-label">Buyer :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="buyer" id="buyer">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="col-sm-12 control-label">Status Forwarder :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="statusfwd" id="statusfwd">
                                            <option value="all">All</option>
                                            <option value="waiting">Waiting</option>
                                            <option value="confirm">Confirm</option>
                                            <option value="reject">Reject</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="col-sm-12 control-label">Book#</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="text" name="book" id="book">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="col-sm-12 control-label"> &nbsp; </label>
                                    <div class="col-sm-12">
                                        <a href="#" type="button" id="search"
                                            class="btn btn-info form-control">Search</a>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive" style="padding-top: 20px;">
                                <table id="dataTables" class="table table-bordered table-striped table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <center>PO</center>
                                            </th>
                                            <th>
                                                <center>Code Booking</center>
                                            </th>
                                            <th>
                                                <center>Material</center>
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

    {{-- Modal Approval Forwarder --}}
    <div class="modal fade" id="detailapproval">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Approval Forwarder</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">PO Number</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Material</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="material" name="material"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Color Code</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="colorcode" name="colorcode"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Size</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="size" name="size"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Booking Number</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobook" name="nobook"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Forwarder</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="forwarder" name="forwarder"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Status</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="status" name="status"
                                            readonly>
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

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // function dataTablesku() {
            var tabel = $('#dataTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('transaksi/approval/search') }}",
                    data: function(d) {
                        d.supplier = $('#supplier').val(),
                            d.tanggal1 = $('#tanggal1').val(),
                            d.tanggal2 = $('#tanggal2').val(),
                            d.buyer = $('#buyer').val(),
                            d.statusfwd = $('#statusfwd').val(),
                            d.book = $('#book').val()
                    }
                },
                columns: [{
                        data: 'nopo',
                        name: 'nopo'
                    },
                    {
                        data: 'kodebook',
                        name: 'kodebook'
                    },
                    {
                        data: 'material',
                        name: 'material'
                    },
                    {
                        data: 'forwarder',
                        name: 'forwarder'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            // }

            // $(".searchEmail").keyup(function() {
            $('#search').click(function(e) {
                tabel.draw();
            });

            $('#supplier').select2({
                placeholder: '-- Choose Supplier --',
                ajax: {
                    url: "{!! route('get_supplier') !!}",
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

            $('#buyer').select2({
                placeholder: '-- Choose Buyer --',
                ajax: {
                    url: "{!! route('get_buyer') !!}",
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
                                    text: item.nama_buyer,
                                    id: item.id_buyer,
                                    selected: true,
                                }
                            }),
                        };
                    },
                    cache: true
                }
            });

            $('body').on('click', '#detailbtn', function() {
                console.log('objectdetail :>> ', 'klik');
                // $('#modal-detail').modal('show');
                $('#detailapproval').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('getdetailapproval') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('data :>> ', data.data);
                    let dataku = data.data;

                    $('#nomorpo').val(dataku.pono);
                    $('#material').val(dataku.matcontents);
                    $('#colorcode').val(dataku.colorcode);
                    $('#size').val(dataku.size);
                    $('#nobook').val(dataku.kode_booking);
                    $('#forwarder').val(dataku.name);
                    $('#status').val(dataku.statusformpo);
                })
            });
        });
    </script>
@endsection
