@extends('system::template/master')
@section('title', $title)
@section('link_href')

@endsection

@section('content')
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body" style="overflow-y: auto;">
                            <div class="row">
                                <div>
                                    <label class="control-label">PO :</label>
                                    <select class="select2" style="width: 100%;" name="datapo" id="datapo">
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
                                    <button type="button" class="btn btn-warning form-control" id="btndownload">Download
                                        Excel</button>
                                </div>
                            </div>
                            <div class="row mt-3">
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
                    <div id="formdetailpo"></div>
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
                        d.po = $('#datapo').val(),
                            d.supplier = $('#datasupp').val(),
                            d.periode = $('#periode').val()
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
                        data: 'status',
                        name: 'status'
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

            $('#datasupp').select2({
                placeholder: '-- Choose Supplier --',
                ajax: {
                    url: "{!! url('report/po/getsupplier') !!}",
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

            $('body').on('click', '#detailpo', function() {
                let idku = $(this).attr('data-id');
                console.log('idku :>> ', idku);
                $.ajax({
                    url: "{!! route('report_detailpo') !!}",
                    type: 'POST',
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                    beforeSend: function(param) {
                        Swal.fire({
                            title: 'Please Wait .......',
                            // html: '',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            onOpen: () => {
                                swal.showLoading();
                            }
                        })
                    },
                    success: function(data) {
                        console.log('data :>> ', data);
                        $('#detailpomodal').modal({
                            show: true,
                            backdrop: 'static'
                        });
                        $('#formdetailpo').html(data);
                        swal.close();
                    }
                })
            });

            $("#btndownload").click(function(e) {
                let datapo = $('#datapo').val();
                let datasupp = $('#datasupp').val();
                let dataper = $('#periode').val();

                var query = {
                    'po': datapo,
                    'supplier': datasupp,
                    'periode': dataper,
                }
                var url = "{{ url('report/po/getexcelpoall') }}?" + $.param(query);
                window.open(url, '_blank');
            });
        });
    </script>
@endsection
