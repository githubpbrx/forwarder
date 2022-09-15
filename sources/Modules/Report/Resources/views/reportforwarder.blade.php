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
                    <h4>REPORT FORWARDER</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="fullscreen-container" class="card-body" style="overflow-y: auto;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-sm-5 control-label">Choose Forwarder :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="forwarder" id="forwarder">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-sm-5 control-label"> &nbsp; </label>
                                    <div class="col-sm-12">
                                        <a href="#" type="button" id="search" class="btn btn-info form-control"
                                            data-value="klik">Search</a>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table id="dataTables" class="table table-bordered table-striped table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <center>Forwarder</center>
                                            </th>
                                            <th>
                                                <center>Status KYC</center>
                                            </th>
                                            <th>
                                                <center>Status Allocation Forwarder</center>
                                            </th>
                                            <th>
                                                <center>Status Update Shipment</center>
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
                        d.forwarder = $('#forwarder').val()
                    }
                },
                columns: [{
                        data: 'fwd',
                        name: 'fwd'
                    },
                    {
                        data: 'kyc',
                        name: 'kyc'
                    },
                    {
                        data: 'allocation',
                        name: 'allocation'
                    },
                    {
                        data: 'shipment',
                        name: 'shipment'
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

            $('#forwarder').select2({
                placeholder: '-- Choose Forwarder --',
                ajax: {
                    url: "{!! route('report_getfwd') !!}",
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

        });
    </script>
@endsection
