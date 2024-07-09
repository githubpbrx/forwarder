<form action="#" class="form-horizontal">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label">PO</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="nomorpo" name="nomorpo" value="{{ $data[0]->pono }}"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label">Supplier</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="supplier" name="supplier"
                        value="{{ $data[0]->nama }}" readonly>
                </div>
            </div>
        </div>
    </div>
    <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
    <table class="form-horizontal" border="1" style="width:100%; text-align: center">
        <thead>
            <tr>
                <th>Material</th>
                <th>Material Desc</th>
                <th>Color</th>
                <th>Size</th>
                <th>Qty PO</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                @php
                    if ($item->statusconfirm == 'confirm') {
                        $status = 'Confirmed';
                    } elseif ($item->statusconfirm == 'reject') {
                        $status = 'Rejected';
                    } else {
                        $status = 'Unprocessed';
                    }
                @endphp
                <tr>
                    <td>{{ $item->matcontents }}</td>
                    <td>{{ $item->itemdesc }}</td>
                    <td>{{ $item->colorcode }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ $item->qtypo }}</td>
                    <td>{{ $status }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>
</form>
