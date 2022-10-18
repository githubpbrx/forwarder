<form action="#" class="form-horizontal">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">PO</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="nomorpo" name="nomorpo" value="{{ $data[0]->pono }}"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Supplier</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="supplier" name="supplier"
                        value="{{ $data[0]->nama }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Code Booking</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="{{ $data[0]->kode_booking }}" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
    </div>
    <hr style="width: 100%; height: 0.8px; background-color:rgb(145, 139, 139);" />
    @foreach ($data as $item)
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Material</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="invoice" name="invoice"
                            value="{{ $item->matcontents }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Quantity Shipment</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="invoice" name="invoice"
                            value="{{ $item->qty_shipment }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Invoice</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="invoice" name="invoice"
                            value="{{ $item->noinv }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETD</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etd" name="etd"
                            value="{{ $item->etdfix }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETA</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="eta" name="eta"
                            value="{{ $item->etafix }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">No BL</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nobl" name="nobl"
                            value="{{ $item->nomor_bl }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Vessel</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="vessel" name="vessel"
                            value="{{ $item->vessel }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <hr style="width: 100%; height: 0.8px; background-color:rgb(192, 192, 192);" />
    @endforeach
</form>
