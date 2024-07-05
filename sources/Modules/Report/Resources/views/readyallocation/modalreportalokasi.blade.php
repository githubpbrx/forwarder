<form action="#" class="form-horizontal">
    {{ csrf_field() }}
    <table class="form-horizontal" border="1" style="width:100%; text-align: center">
        <thead>
            <tr>
                <th>Material</th>
                <th>Material Desc</th>
                <th>HS Code</th>
                <th>Color</th>
                <th>Size</th>
                <th>Qty PO</th>
                <th>Qty Booking</th>
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
                    <td>{{ $item->qtybook == null ? '' : $item->qtybook }}</td>
                </tr>
            </tbody>
        @endforeach
    </table>
    <hr style="width: 100%; height: 0.5px; background-color:rgb(192, 192, 192);" />
    @foreach ($getbooking as $key => $item)
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">{{ $item->kode_booking }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">PO</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="{{ $item->pono }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Supplier</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="{{ $item->nama }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Forwarder</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="{{ $item->name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Code Booking</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="{{ $item->kode_booking }}" readonly>
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
                                    value="{{ date('d F Y', strtotime($item->date_booking)) }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Route</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="{{ $item->route_code . ' ~ ' . $item->route_desc }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Port Of Loading</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="{{ $item->code_port . ' ~ ' . $item->name_port }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Port Of Destination</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="{{ $item->code_port . ' ~ ' . $item->name_port }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Package</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="{{ $item->package }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">ETD</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="{{ date('d F Y', strtotime($item->etd)) }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">ETA</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="{{ date('d F Y', strtotime($item->eta)) }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Input Data</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="{{ date('d F Y', strtotime($item->created_at)) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Update Data</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="{{ $item->updated_at == null ? '' : date('d F Y', strtotime($item->updated_at)) }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="col-sm-12">Shipmode</label>
                                        <input type="text" class="form-control" value="{{ $item->shipmode }}"
                                            readonly>
                                    </div>
                                    @if ($item)
                                        @if ($item->shipmode == 'fcl')
                                            <?php
                                            $exp = explode('-', $item->subshipmode);
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
                                                <input type="text" class="form-control"
                                                    value="{{ $fclcont }}" readonly>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-sm-12 control-label">Volume</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                        value="{{ $fclvol }}" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">M3</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-sm-12 control-label">Weight</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                        value="{{ $fclkg }}" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Kg</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($item->shipmode == 'lcl')
                                            <?php
                                            $explcl = explode('-', $item->subshipmode);
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
                                            $expair = explode('-', $item->subshipmode);
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
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <table class="form-horizontal" border="1" style="width:100%; text-align: center">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Material Desc</th>
                                <th>HS Code</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Qty PO</th>
                                <th>Qty Booking</th>
                            </tr>
                        </thead>
                        @foreach ($getperbooking[$key] as $item2)
                            <tbody>
                                <tr>
                                    <td>{{ $item2->matcontents }}</td>
                                    <td>{{ $item2->itemdesc }}</td>
                                    <td>{{ $item2->hscode }}</td>
                                    <td>{{ $item2->colorcode }}</td>
                                    <td>{{ $item2->size }}</td>
                                    <td>{{ $item2->qtypo }}</td>
                                    <td>{{ $item2->qty_booking }}</td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</form>
