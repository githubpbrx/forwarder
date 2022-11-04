<form action="#" class="form-horizontal">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">PO</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="nomorpo" name="nomorpo" value="{{ $data[0]->pono }}"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">Supplier</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="supplier" name="supplier"
                        value="{{ $data[0]->nama }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">Code Booking</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $data[0]->kode_booking }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">Ship Mode</label>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="shipmode" name="shipmode"
                                value="{{ $data[0]->shipmode }}" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subshipmode" name="subshipmode"
                                value="{{ $data[0]->subshipmode }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">Invoice</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $data[0]->noinv }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">Vessel</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $data[0]->vessel }}" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">ETD</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $data[0]->etdfix == null ? '' : date('d F Y', strtotime($data[0]->etdfix)) }}"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">ETA</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $data[0]->etafix == null ? '' : date('d F Y', strtotime($data[0]->etafix)) }}"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="col-sm-12 control-label">BL Number</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $data[0]->nomor_bl }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Input Data</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $dateku->created_at == null ? '' : date('d F Y H:i:s', strtotime($dateku->created_at)) }}"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Update Data</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $dateku->updated_at == null ? '' : date('d F Y H:i:s', strtotime($dateku->updated_at)) }}"
                        readonly>
                </div>
            </div>
        </div>
    </div>
    <hr style="width: 100%; height: 0.1px; background-color:rgb(145, 139, 139);" />
    <table class="form-horizontal" border="0" style="width:100%">
        <thead>
            <tr>
                <th>Material</th>
                <th>Style</th>
                <th>Color Code</th>
                <th>Size</th>
                <th>Quantity Item</th>
                <th>Quantity Shipment</th>
            </tr>
        </thead>
        @foreach ($data as $item)
            <tbody>
                <tr>
                    <td>{{ $item->matcontents }}</td>
                    <td>{{ $item->style }}</td>
                    <td>{{ $item->colorcode }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ $item->qtypo }}</td>
                    <td>{{ $item->qty_shipment }}</td>
                </tr>
            </tbody>
        @endforeach
    </table>
</form>
