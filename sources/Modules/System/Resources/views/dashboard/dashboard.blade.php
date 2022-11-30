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
                    @if ($cocexp)
                        <div class="alert alert-info" style="background-color: rgb(140, 232, 255)">
                            <h5><i class="icon fas fa-info"></i> Notification For COC</h5>
                            <p style="color:black">Your COC is Expired, Please Input Again!!
                                <span class="badge badge-info">{{ $viewdays }}</span>
                                <br>
                                <a href="{{ route('validasicoc') }}"><button type="button"
                                        class="btn btn-primary btn-xs">Update COC</button></a>
                            </p>
                        </div>
                    @endif
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
                    @if ($newuser >= 1)
                        <div class="alert alert-danger" style="background-color: rgb(247, 195, 195)">
                            <h5><i class="icon fas fa-info"></i> Notification</h5>
                            <p style="color:black">You got a new Approval User Forwarder
                                <span class="badge badge-info">{{ $newuser }}</span>
                                <br>
                                <a href="{{ route('page_newfwd') }}"><button type="button"
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

                    @if ($totalreject >= 1)
                        <form action="#" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">PO Number</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">PI Number</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="pinomor" name="pinomor"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">Supplier</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="supplier" name="supplier"
                                                readonly>
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
                                        <label class="col-sm-12 control-label">Booking Number</label>
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
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label class="control-label">Ship Mode</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $datareject[0]->shipmode }}" readonly>
                                                </div>
                                                @if ($datareject[0]->shipmode == 'fcl')
                                                    <?php
                                                    $exp = explode('-', $datareject[0]->subshipmode);
                                                    $fclexp = explode('KG', $exp[2]);
                                                    // dd($exp);
                                                    ?>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Size</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $exp[0] }}" readonly>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Volume</label>
                                                        <div class="input-group">
                                                            <input type="number" min="0" class="form-control"
                                                                autocomplete="off" value="{{ $exp[1] }}" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">M3</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Weight</label>
                                                        <div class="input-group">
                                                            <input type="number" min="0" class="form-control"
                                                                autocomplete="off" value="{{ $fclexp[0] }}" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">KG</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($datareject[0]->shipmode == 'lcl')
                                                    <?php
                                                    $exp = explode('-', $datareject[0]->subshipmode);
                                                    $expkg = explode('KG', $exp[1]);
                                                    $lclexp = explode('CBM', $exp[0]);
                                                    // dd($exp);
                                                    ?>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">LCL</label>
                                                        <div class="input-group">
                                                            <input type="number" min="0" class="form-control"
                                                                autocomplete="off" value="{{ $lclexp[0] }}" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">CBM</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Weight</label>
                                                        <div class="input-group">
                                                            <input type="number" min="0" class="form-control"
                                                                autocomplete="off" value="{{ $expkg[0] }}" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">KG</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <?php
                                                    $exp = explode('-', $datareject[0]->subshipmode);
                                                    $expkg = explode('KG', $exp[1]);
                                                    $airexp = explode('M3', $exp[0]);
                                                    // dd($exp);
                                                    ?>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Volume</label>
                                                        <div class="input-group">
                                                            <input type="number" min="0" class="form-control"
                                                                autocomplete="off" value="{{ $airexp[0] }}" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">M3</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Weight</label>
                                                        <div class="input-group">
                                                            <input type="number" min="0" class="form-control"
                                                                autocomplete="off" value="{{ $expkg[0] }}" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">KG</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
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
                    @endif
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
            var tabelreject = @JSON($datarejecttabel);
            $('#detailreject').click(function(e) {
                $('#formreject').modal({
                    show: true,
                    backdrop: 'static'
                });

                html =
                    '<table border="1" style="width:100%"><tr><th>PO Number</th><th>Material</th><th>Material Desc</th><th>HS Code</th><th>Color</th><th>Size</th><th>Qty Item</th></tr>';
                for (let index = 0; index < tabelreject.length; index++) {

                    html +=
                        '<tr><td>' + tabelreject[index].pono + '</td><td>' + tabelreject[index]
                        .matcontents + '</td><td>' + tabelreject[index].itemdesc + '</td><td>' +
                        tabelreject[index].hscode + '</td><td>' + tabelreject[index].colorcode +
                        '</td><td>' + tabelreject[index].size + '</td><td>' + tabelreject[index].qtypo +
                        '</td></tr>';
                }

                html += "</table>";
                $('#detailitem').html(html);

                var arraypo = [];
                var arraypi = [];
                var arraysup = [];
                for (let indexpo = 0; indexpo < poreject.length; indexpo++) {
                    arraypo.push(poreject[indexpo]['pono']);
                    arraypi.push(poreject[indexpo]['pino']);
                    arraysup.push(poreject[indexpo]['nama']);
                }

                let implodepo = arraypo.join(', ');
                let implodepi = arraypi.join(', ');
                let implodesup = arraysup.join(', ');

                $('#nomorpo').val(implodepo);
                $('#pinomor').val(implodepi);
                $('#supplier').val(implodesup);

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
