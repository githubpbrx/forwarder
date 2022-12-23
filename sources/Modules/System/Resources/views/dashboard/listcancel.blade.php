@extends('system::template/master')
@section('title', $title)
@section('link_href')

@endsection

@section('content')
    <div class="card" style="font-size: 10pt;">
        <div class="card-body">
            {{-- <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Choose PI Delivery Date :</label>
                    <div class="col-sm-12">
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
            </div> --}}
            <br>
            {{-- <form id="form-save"> --}}
            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>
                            <center>PO Number</center>
                        </th>
                        <th>
                            <center>Material</center>
                        </th>
                        <th>
                            <center>PI Delivery</center>
                        </th>
                        <th>
                            <center>Supplier</center>
                        </th>
                        <th>
                            <center>Status</center>
                        </th>
                    </tr>
                </thead>
            </table>
            <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a>
            {{-- <button type="button" class="btn btn-info" id="btnback">Back</button> --}}
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
                    url: "{{ url('listcancel') }}",
                    // data: function(d) {
                    //     d.pidate = $('#selectdate').val()
                    // }
                },
                columns: [{
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'material',
                        name: 'material'
                    },
                    {
                        data: 'pidel',
                        name: 'pidel'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ],
            });

            $('#serverside').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            // $('#search').click(function(e) {
            //     oTable.draw();
            //     // table.ajax.reload();
            // });

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

        });
    </script>
@endsection
