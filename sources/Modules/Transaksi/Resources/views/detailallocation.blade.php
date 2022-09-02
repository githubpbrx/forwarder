@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>DETAIL ALLOCATION FORWARDER</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="fullscreen-container" class="card-body" style="overflow-y: auto;">
                            <form action="#" class="form-horizontal" enctype="multipart/form-data" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">PO</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datapo->pono }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">KP</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datapo->kpno }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Description</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datapo->itemdesc }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Color</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datapo->colorcode }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Size</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datapo->size }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Unit</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datapo->unit }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Currency</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datapo->curr }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Price</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datapo->price }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-sm-5 control-label">Supplier</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" value="{{ $datasup->nama }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="col-sm-12 control-label">Quality Location</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="qty_location"
                                                name="qty_location">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-sm-5 control-label">Forwarder</label>
                                        <div class="col-sm-12">
                                            <select class="select2" style="width: 100%;" name="forwarder" id="forwarder">
                                                <option value="" disabled selected>--Choose Forwarder--</option>
                                                <?php
                                                foreach ($datafw as $key => $val) {
                                                    echo '<option value="' . $val->id . '">' . $val->nama . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-sm-5 control-label">&nbsp;</label>
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-info form-control"
                                                id="btnsubmit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

            $('#btnsubmit').click(function(e) {
                $.ajax({
                    type: "post",
                    url: "{{ route('detailaction') }}",
                    data: {
                        'qtylocation': $('#qty_location').val(),
                        'forwarder': $('#forwarder').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log('response :>> ', response);
                        swal({
                            title: response.title,
                            text: response.message,
                            icon: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            (response.status == 'success') ? window.location
                                .replace("{{ route('allocationforwarder') }}"):
                                ''
                        });
                        return;
                    },
                    error: function(xhr, status, error) {
                        swal({
                            title: 'Unsuccessfully Saved Data',
                            text: 'Check Your Data',
                            icon: 'error'
                        });
                        return;
                    }
                });

            });
        });
    </script>
@endsection
