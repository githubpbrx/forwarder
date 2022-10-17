@extends('system::template/master')
@section('title', $title)
@section('link_href')

@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="text-center">DASHBOARD</h3>
                </div>
                @if ($datauser->privilege_group_access_id == '1')
                    @if ($totalpo >= 1)
                        <div class="alert alert-danger" style="background-color: rgb(253, 181, 181)">
                            {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                            <h5><i class="icon fas fa-info"></i> Notification</h5>
                            <p style="color:black">You got a new PO
                                <span class="badge badge-info">{{ $totalpo }}</span>
                                <br>
                                <a href="{{ route('page_po') }}"><button type="button"
                                        class="btn btn-primary btn-xs">Process</button></a>
                            </p>
                        </div>
                    @endif
                    @if ($totalconfirm >= 1 && $totalshipment == 0)
                        <div class="alert alert-danger" style="background-color: rgb(253, 181, 181)">
                            {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                            <h5><i class="icon fas fa-info"></i> Notification</h5>
                            <p style="color:black">You got a new Update Shipment
                                {{-- <span class="badge badge-info">{{ $totalconfirm }}</span> --}}
                                <br>
                                <a href="{{ route('process_shipment') }}"><button type="button"
                                        class="btn btn-primary btn-xs">Process</button></a>
                            </p>
                        </div>
                    @endif
                    @if ($totalreject >= 1)
                        <div class="alert alert-danger" style="background-color: rgb(253, 181, 181)">
                            <h5><i class="icon fas fa-info"></i> Notification</h5>
                            <p style="color:black">Your PO is rejected, please check again!
                                <span class="badge badge-info">{{ $totalreject }}</span>
                                <br>
                                <button type="button" class="btn btn-info btn-xs" id="detailreject">Check Detail</button>
                                <a href="{{ route('page_po') }}"><button type="button"
                                        class="btn btn-primary btn-xs">Process</button></a>
                            </p>
                        </div>
                    @endif
                @else
                    @if ($totalapproval >= 1)
                        <div class="alert alert-danger" style="background-color: rgb(247, 195, 195)">
                            {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                            <h5><i class="icon fas fa-info"></i> Notification</h5>
                            <p style="color:black">You got a new Approval
                                <span class="badge badge-info">{{ $totalapproval }}</span>
                                <br>
                                <a href="{{ route('page_approval') }}"><button type="button"
                                        class="btn btn-primary btn-xs">Process</button></a>
                            </p>
                        </div>
                    @endif
                    @if ($totalkyc >= 1)
                        <div class="alert alert-danger" style="background-color: rgb(247, 195, 195)">
                            <h5><i class="icon fas fa-info"></i> Notification</h5>
                            <p style="color:black">You got a new Approval KYC
                                <span class="badge badge-info">{{ $totalkyc }}</span>
                                <br>
                                <a href="{{ route('page_kyc') }}"><button type="button"
                                        class="btn btn-primary btn-xs">Process</button></a>
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="formreject">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Detail Data Reject PO</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 10pt;">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor PO</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-12">
                                <div id="detailitem"></div>
                            </div>
                        </div>
                        <hr
                            style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nomor Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nobook" name="nobook"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Date Booking</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="datebook" name="datebook"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETD (Estimate Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="etd" name="etd"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">ETA (Estimate Acutal Delivery Date)</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="eta" name="eta"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Ship Mode</label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="shipmode" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="subshipmode" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Description</label>
                                    <div class="col-sm-12">
                                        {{-- <input type="text" class="form-control" id="deskripsi" name="deskripsi"
                                            autocomplete="off" readonly> --}}
                                        <textarea name="deskripsi" id="deskripsi" cols="104" rows="2" disabled></textarea>
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
    {{-- ----------------- /.modal content ----------------- --}}
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {

            var poreject = @JSON($datareject);
            console.log('poreject :>> ', poreject);
            $('#detailreject').click(function(e) {
                console.log('object :>> ', poreject);
                $('#formreject').modal({
                    show: true,
                    backdrop: 'static'
                });

                html =
                    '<table border="0" style="width:100%"><tr><th>Material Contents</th><th>Item Description</th></tr>';
                for (let index = 0; index < poreject.length; index++) {

                    html +=
                        '<tr><td>' +
                        poreject[index].matcontents + '</td><td>' +
                        poreject[index].itemdesc + '</td></tr>';
                }

                html += "</table>";
                $('#detailitem').html(html);

                $('#nomorpo').val(poreject[0].pono);
                $('#nobook').val(poreject[0].kode_booking);
                $('#datebook').val(poreject[0].date_booking);
                $('#etd').val(poreject[0].etd);
                $('#eta').val(poreject[0].eta);
                $('#shipmode').val(poreject[0].shipmode);
                $('#subshipmode').val(poreject[0].subshipmode);
                $('#deskripsi').val(poreject[0].ket_tolak);
            });
        });
    </script>
@endsection
