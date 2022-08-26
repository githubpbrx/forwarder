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
                                    <label class="col-sm-5 control-label">Status Forwarder :</label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="status" id="status">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="col-sm-5 control-label">&nbsp;</label>
                                    <div class="col-sm-12">
                                        <a href="" type="button" class="btn btn-info form-control">Search</a>
                                    </div>
                                </div>
                            </div>
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

@endsection

@section('script_src')
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {

            //
            $('#dataTables').DataTable({
                order: [],
                processing: true,
                serverSide: false,
                ordering: true,
                paging: false,
                scrollX: true,
                scrollY: '450px',
                scrollCollapse: true,
                lengthChange: true,
                searching: true,
                autoWidth: true,
            });
        });
    </script>
@endsection
