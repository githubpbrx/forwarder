@extends('system::template/master')
@section('title', $title)
@section('link_href')

@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="text-center">ALLOCATION FORWARDER</h3> --}}
                    <h4>ALLOCATION FORWARDER</h4>
                    <h5 style="color: grey">List PO To Forwarder</h5>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="fullscreen-container" class="card-body" style="overflow-y: auto;">
                            <form action="#" class="form-horizontal" enctype="multipart/form-data" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Choose Suplier :</label>
                                        <div class="col-sm-12">
                                            <select class="select2" style="width: 100%;" name="supplier" id="supplier">
                                                <option value="" disabled selected>--Choose Supplier--</option>
                                                <?php
                                                foreach ($sup as $key => $val) {
                                                    echo '<option value="' . $val->id . '">' . $val->nama . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Periode</label>
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
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Status Forwarder :</label>
                                        <div class="col-sm-12">
                                            <select class="select2" style="width: 100%;" name="status" id="status">
                                                <option value="all">All</option>
                                                <option value="waiting">Waiting</option>
                                                <option value="partial_allocated">Partial Allocated</option>
                                                <option value="full_allocated">Full Alocated</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">&nbsp;</label>
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-info form-control"
                                                id="searchdata">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
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
                                                <center>Material</center>
                                            </th>
                                            <th>
                                                <center>Status</center>
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
                <div class="modal-body">
                    <form class="form-horizontal" id="form-detail">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">PO</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="po" name="po"
                                        readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">KP</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="kp" name="kp"
                                        readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Description</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="deskripsi"
                                        name="deskripsi" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Color</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="color"
                                        name="color" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Size</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="size"
                                        name="size" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Unit</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="unit"
                                        name="unit" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-8 control-label">Quantity PO</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="qtypo"
                                        name="qtypo" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Currency</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="curr"
                                        name="curr" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Price</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="price"
                                        name="price" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Supplier</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="#" id="detailsup"
                                        name="detailsup" readonly>
                                </div>
                            </div>
                            <hr
                                style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />

                            <div id="detailhtml"></div>
                            <hr
                                style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />

                            <label class="col-sm-12 control-label">Please input allocation</label>
                            <hr>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Quantity Allocation</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="qty_allocation" name="qty_allocation"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Forwarder</label>
                                <div class="col-sm-12">
                                    <select class="select2" style="width: 100%;" name="forwarder" id="forwarder">
                                        <option value=""></option>
                                    </select>
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
                        data: 'material',
                        name: 'material'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

            var qtypoku;
            var poid;
            $('body').on('click', '#detailbtn', function() {
                console.log('object :>> ', 'klik');
                // $('#modal-detail').modal('show');
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
                    let dataku = data.data;
                    let poku = dataku.datapo;
                    let supku = dataku.datasup;
                    qtypoku = poku.qtypo;
                    poid = poku.id;

                    $('#po').val(poku.pono);
                    $('#kp').val(poku.kpno);
                    $('#deskripsi').val(poku.itemdesc);
                    $('#color').val(poku.colorcode);
                    $('#size').val(poku.size);
                    $('#unit').val(poku.unit);
                    $('#curr').val(poku.curr);
                    $('#price').val(poku.price);
                    $('#detailsup').val(supku.nama);
                    $('#qtypo').val(poku.qtypo);
                    console.log(dataku.detail);
                    $('#detailhtml').html(dataku.detail);
                    $('#qty_allocation').val('');
                    $('#forwarder').val('').change();
                })
            });

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
                let qtyall = $('#qty_allocation').val();
                let fwd = $('#forwarder').val();

                if (qtyall > qtypoku) {
                    Swal.fire({
                        title: 'Informasi',
                        text: 'Data Quantity Allocation Over Quantity PO',
                        type: 'warning'
                    });
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('detailaction') }}",
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content'),
                            'idpo': idku,
                            'qtyallocation': qtyall,
                            'forwarder': fwd,
                            'data_qtypo': qtypoku
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status == "error") {
                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    type: (response.status != 'error') ? 'success' :
                                        'error'
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
                                // table.reload();
                                // (response.status == 'success') ? window.location
                                //     .replace("{{ route('allocationforwarder') }}"):
                                //     ''
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

        });
    </script>
@endsection
