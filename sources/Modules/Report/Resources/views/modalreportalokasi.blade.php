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
                <label class="col-sm-12 control-label">ETD</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ date('d F Y', strtotime($data[0]->etd)) }}"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">ETA</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ date('d F Y', strtotime($data[0]->eta)) }}"
                        readonly>
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
        <div class="col-md-6">
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="col-sm-12">Shipmode</label>
                            <input type="text" class="form-control" value="{{ $data[0]->shipmode }}" readonly>
                        </div>
                        @if ($data[0]->shipmode == 'fcl')
                            <?php
                            $exp = explode('-', $data[0]->subshipmode);
                            $fclsize = $exp[0];
                            $fclvol = $exp[1];
                            $expkg = explode('KG', $exp[2]);
                            $fclkg = $expkg[0];
                            if ($fclsize == '40hq') {
                                $fclcont = $fclsize;
                            } else {
                                $fclcont = $fclsize . '"';
                            }
                            ?>
                            <div class="col-sm-3">
                                <label class="col-sm-12 control-label">Container Size</label>
                                <input type="text" class="form-control" value="{{ $fclcont }}" readonly>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-sm-12 control-label">Volume</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" value="{{ $fclvol }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
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
                </tr>
            </tbody>
        @endforeach
    </table>
</form>
