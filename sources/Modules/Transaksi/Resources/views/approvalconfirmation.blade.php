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
                    <h4>APPROVAL CONFIRMATION</h4>
                    <h5 style="color: grey">List Waiting For Confirmation</h5>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="fullscreen-container" class="card-body" style="overflow-y: auto;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="col-sm-5 control-label">Choose Suplier :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="supplier" id="supplier">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-sm-5 control-label">Periode</label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" name="" id="">
                                            </div>
                                            <div class="col-sm-1">
                                                <b>To</b>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="date" class="form-control" name="" id="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-sm-5 control-label">Buyer :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="buyer" id="buyer">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="col-sm-5 control-label">Status Forwarder :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="statusfwd" id="statusfwd">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-sm-5 control-label">Book#</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="text" name="book">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-sm-5 control-label"> &nbsp; </label>
                                    <div class="col-sm-12">
                                        <a href="" type="button" class="btn btn-info form-control">Search</a>
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
                                                <center>Book#</center>
                                            </th>
                                            <th>
                                                <center>Date</center>
                                            </th>
                                            <th>
                                                <center>Forwarder</center>
                                            </th>
                                            <th>
                                                <center>Status</center>
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

        });
    </script>
@endsection
