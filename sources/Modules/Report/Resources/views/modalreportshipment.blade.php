<form action="#" class="form-horizontal">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">PO</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $data[0]->pono }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Supplier</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $data[0]->nama }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Forwarder</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $data[0]->name }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Code Booking</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $data[0]->kode_booking }}" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Date Booking</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="{{ date('d F Y', strtotime($data[0]->date_booking)) }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Route</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="{{ $data[0]->route_code . ' ~ ' . $data[0]->route_desc }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Port Of Loading</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="{{ $data[0]->loadingcode . ' ~ ' . $data[0]->loadingname }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Port Of Destination</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="{{ $data[0]->destinationcode . ' ~ ' . $data[0]->destinationname }}" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Package</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $data[0]->package }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Invoice</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $data[0]->noinv }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">ATD</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ date('d F Y', strtotime($data[0]->etdfix)) }}"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">ATA</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ date('d F Y', strtotime($data[0]->etafix)) }}"
                        readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">BL Number</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $data[0]->nomor_bl }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="col-sm-12">Shipmode</label>
                            <input type="text" class="form-control" value="{{ $data[0]->shipmode }}" readonly>
                        </div>
                        @if ($data[0]->shipmode == 'fcl')
                            <?php
                            $datacont = $data[0]['container'];
                            // dd($datacont);
                            // $exp = explode('-', $data[0]->subshipmode);
                            // $fclsize = $exp[0];
                            // $fclvol = $exp[1];
                            $expkg = explode('KG', $data[0]['container']->weight);
                            $fclkg = $expkg[0];
                            // if ($fclsize == '40hq') {
                            //     $fclcont = $fclsize;
                            // } else {
                            //     $fclcont = $fclsize . '"';
                            // }
                            ?>
                            <div class="col-sm-2">
                                <label class="col-sm-12 control-label">Container Size</label>
                                <input type="text" class="form-control"
                                    value="{{ $data[0]['container']->containernumber }}" readonly>
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-12 control-label">Volume</label>
                                <div class="input-group">
                                    <input type="number" class="form-control"
                                        value="{{ $data[0]['container']->volume }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-sm-12 control-label">Number Of Container</label>
                                <input type="text" class="form-control"
                                    value="{{ $data[0]['container']->numberofcontainer }}" readonly>
                            </div>
                            <div class="col-sm-2">
                                <label class="col-sm-12 control-label">Weight</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" value="{{ $fclkg }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($data[0]->shipmode == 'lcl')
                            <?php
                            $explcl = explode('-', $data[0]->subshipmode);
                            $lclvolexp = explode('M3', $explcl[0]);
                            $lclvol = $lclvolexp[0];
                            $explclkg = explode('KG', $explcl[1]);
                            $lclkg = $explclkg[0];
                            ?>
                            <div class="col-sm-4">
                                <label class="col-sm-12 control-label">Volume</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control"
                                        value="{{ $lclvol }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-12 control-label">Weight</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control"
                                        value="{{ $lclkg }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <?php
                            $expair = explode('-', $data[0]->subshipmode);
                            $airvolexp = explode('M3', $expair[0]);
                            $airvol = $airvolexp[0];
                            $expairkg = explode('KG', $expair[1]);
                            $airkg = $expairkg[0];
                            ?>
                            <div class="col-sm-4">
                                <label class="col-sm-12 control-label">Volume</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control"
                                        value="{{ $airvol }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-12 control-label">Weight</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control"
                                        value="{{ $airkg }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg</span>
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
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Vessel</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $data[0]->vessel }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Input Data</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="{{ date('d F Y H:i:s', strtotime($dateku->created_at)) }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Update Data</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="{{ $dateku->updated_at == null ? '' : date('d F Y H:i:s', strtotime($dateku->updated_at)) }}"
                        readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label">File BL</label>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $data[0]->file_bl }}"
                                id="filename" readonly>
                        </div>
                        <div class="col-sm-2">
                            <a href="#" id="downloadfile" target="_BLANK" class="btn btn-info"><i
                                    class="fa fa-download"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label">File Invoice</label>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $data[0]->file_invoice }}"
                                id="filenameinv" readonly>
                        </div>
                        <div class="col-sm-2">
                            <a href="#" id="downloadfileinv" target="_BLANK" class="btn btn-info"><i
                                    class="fa fa-download"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-sm-12 control-label">File Packing List</label>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $data[0]->file_packinglist }}"
                                id="filenamepack" readonly>
                        </div>
                        <div class="col-sm-2">
                            <a href="#" id="downloadfilepack" target="_BLANK" class="btn btn-info"><i
                                    class="fa fa-download"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr style="width: 100%; height: 0.5px; background-color:rgb(192, 192, 192);" />
    <table class="form-horizontal" border="1" style="width:100%">
        <thead>
            <tr>
                <th>Material</th>
                <th>Material Desc</th>
                <th>HS Code</th>
                <th>Color</th>
                <th>Size</th>
                <th>Qty PO</th>
                <th>Qty Ship</th>
            </tr>
        </thead>
        @foreach ($data as $item)
            <tbody>
                <tr>
                    <td>{{ $item->matcontents }}</td>
                    <td>{{ $item->itemdesc }}</td>
                    <td>{{ $item->hscode }}</td>
                    <td>{{ $item->colorcode }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ $item->qtypo }}</td>
                    <td>{{ $item->qty_shipment }}</td>
                </tr>
            </tbody>
        @endforeach
    </table>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#downloadfile').click(function(e) {
                // e.preventDefault();
                let filename = $('#filename').val();
                // console.log('klik :>> ', filename);
                var base = "{!! url('sources/storage/app') !!}" + "/" + filename;
                $('#downloadfile').attr('href', base);
            });

            $('#downloadfileinv').click(function(e) {
                // e.preventDefault();
                let filenameinv = $('#filenameinv').val();
                // console.log('klik :>> ', filename);
                var baseinv = "{!! url('sources/storage/app') !!}" + "/" + filenameinv;
                $('#downloadfileinv').attr('href', baseinv);
            });

            $('#downloadfilepack').click(function(e) {
                // e.preventDefault();
                let filenamepack = $('#filenamepack').val();
                // console.log('klik :>> ', filename);
                var basepack = "{!! url('sources/storage/app') !!}" + "/" + filenamepack;
                $('#downloadfilepack').attr('href', basepack);
            });
        });
    </script>
</form>
