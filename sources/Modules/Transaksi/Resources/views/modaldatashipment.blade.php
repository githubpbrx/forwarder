<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal">
        {{ csrf_field() }}
        {{-- {{ dd($data['remaining']) }} --}}
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-12">
                @foreach ($data['shipment'] as $key1 => $item)
                    <div class="card card-default collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title"> {{ $item[0]->nomor_bl }} </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <table>
                                            <thead>
                                                <?php
                                                foreach ($item as $key2 => $val) {
                                                    if ($val->lock == 1) {
                                                        $locked = 'disabled';
                                                    } else {
                                                        $locked = '';
                                                    }
                                                }
                                                ?>
                                                <th style="text-align:center"><input type="checkbox"
                                                        class="checkall-{{ $key1 }}"
                                                        style="height:18px;width:18px" checked {{ $locked }}>
                                                </th>
                                                <th>Material</th>
                                                <th>Material Desc</th>
                                                <th>Color Code</th>
                                                <th>Size</th>
                                                <th>Qty Item</th>
                                                <th>Remaining Qty</th>
                                                <th>Qty Shipment</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($item as $key2 => $val)
                                                    <?php
                                                    if ($data['remaining'][$key1][$key2]->qtyshipment == null) {
                                                        $remain = $data['shipment']->qtypo;
                                                    } elseif ($data['remaining'][$key1][$key2]->qtyshipment == $val->qtypo) {
                                                        $remain = '0';
                                                    } else {
                                                        $remain = $val->qtypo - $data['remaining'][$key1][$key2]->qtyshipment;
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td style="text-align:center">
                                                            <input type="checkbox"
                                                                class="check-{{ $key1 }}{{ $key2 }}"
                                                                style="height:18px;width:18px" {{ $locked }}
                                                                checked>
                                                        </td>
                                                        <td>{{ $val->matcontents }}</td>
                                                        <td>{{ $val->itemdesc }}</td>
                                                        <td>{{ $val->colorcode }}</td>
                                                        <td>{{ $val->size }}</td>
                                                        <td>{{ $val->qtypo }}</td>
                                                        <td>{{ $remain }}</td>
                                                        <td>
                                                            <input type="number" min="0"
                                                                id="qtyship-{{ $key1 }}{{ $key2 }}"
                                                                name="qtyship" value="{{ $remain }}"
                                                                class="form-control trigerinput cekinput-{{ $key1 }}{{ $key2 }}"
                                                                data-idpo="{{ $val->idpo }}"
                                                                data-blku="{{ $val->nomor_bl }}"
                                                                data-idformshipment="{{ $val->id_shipment }}"
                                                                data-idformpo="{{ $val->idformpo }}"
                                                                {{ $locked }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">Invoice</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control"
                                                            id="invoice-{{ $key1 }}" name="invoice"
                                                            value="{{ $item[0]->noinv }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">BL Number</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control"
                                                            id="nomorbl-{{ $key1 }}" name="nomorbl"
                                                            value="{{ $item[0]->nomor_bl }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">ATD (Actual Time Departure)
                                                        Fix</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"
                                                            class="form-control etd-{{ $key1 }}" name="etd"
                                                            value="{{ $item[0]->etdfix }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">ATA (Actual Time Arrival)
                                                        Fix</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"
                                                            class="form-control eta-{{ $key1 }}" name="eta"
                                                            value="{{ $item[0]->etafix }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">Vessel</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control"
                                                            id="vessel-{{ $key1 }}" name="vessel"
                                                            value="{{ $item[0]->vessel }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">File BL</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control"
                                                            id="filebl-{{ $key1 }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">File Invoice</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control"
                                                            id="fileinv-{{ $key1 }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">File Packing List</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control"
                                                            id="filepack-{{ $key1 }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row float-right">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">&nbsp;</label>
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-info"
                                                            id="btnupdate-{{ $key1 }}"
                                                            {{ $locked }}>Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        {{-- <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Invoice</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="invoice" name="invoice" autocomplete="off"
                            readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">BL Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nobl" name="nobl" autocomplete="off">
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ATD (Actual Time Departure) Fix</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etd" name="etd" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ATA (Actual Time Arrival) Fix</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="eta" name="eta" value=""
                            autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Vessel</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="vessel" name="vessel" autocomplete="off">
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">File BL</label>
                    <div class="col-sm-12">
                        <input type="file" class="form-control" id="filebl">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">File Invoice</label>
                    <div class="col-sm-12">
                        <input type="file" class="form-control" id="fileinv">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">File Packing List</label>
                    <div class="col-sm-12">
                        <input type="file" class="form-control" id="filepack">
                    </div>
                </div>
            </div>
        </div> --}}
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
</div>

<script type="text/javascript">
    var dataku = @JSON($data['shipment']);
    console.log('dataku :>> ', dataku);

    submit();

    $('.etd').datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "yy-m-dd",
        yearRange: "-100:+20",
    });

    $('.eta').datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "yy-m-dd",
        yearRange: "-100:+20",
    });

    for (let index = 0; index < dataku.length; index++) {
        for (let index2 = 0; index2 < dataku[index].length; index2++) {
            $('.checkall-' + index).change(function(e) {
                if (this.checked) {
                    $('.check-' + index + index2).prop('checked', true);
                    // $('input[type="checkbox"]').prop('checked', true);
                    $('.cekinput-' + index + index2).prop('disabled', false);
                } else {
                    // $('.trigerinput').val('');
                    // $('.trigerinput').prop('disabled', true);
                    // $('input[type="checkbox"]').prop('checked', false);
                    $('.check-' + index + index2).prop('checked', false);
                    $('.cekinput-' + index + index2).prop('disabled', true);

                }
            });
        }
    }

    for (let index = 0; index < dataku.length; index++) {
        for (let index2 = 0; index2 < dataku[index].length; index2++) {
            $('.check-' + index + index2).change(function(e) {
                if (this.checked) {
                    console.log('objectsijine :>> ', 'isChecked');
                    $('.cekinput-' + index + index2).prop('disabled', false);
                    // }
                } else {
                    console.log('objectsijine :>> ', 'notChecked');
                    // $('.cekinput-' + index).val('');
                    $('.cekinput-' + index + index2).prop('disabled', true);
                }
            });
        }
    }

    function submit() {
        for (let index = 0; index < dataku.length; index++) {
            $('#btnupdate-' + index).click(function(e) {
                console.log('object :>> ', 'klik-' + index);
                let etd = $('.etd-' + index).val();
                let eta = $('.eta-' + index).val();
                let nomorbl = $('#nomorbl-' + index).val();
                let invoice = $('#invoice-' + index).val();
                let vessel = $('#vessel-' + index).val();
                let filebl = $('#filebl-' + index).prop('files')[0];
                let fileinv = $('#fileinv-' + index).prop('files')[0];
                let filepacking = $('#filepack-' + index).prop('files')[0];

                var arrayku = [];
                for (let index2 = 0; index2 < dataku[index].length; index2++) {
                    let val = $('.cekinput-' + index + index2).val();

                    let data = {
                        'idpo': $('.cekinput-' + index + index2).attr('data-idpo'),
                        'idbl': $('.cekinput-' + index + index2).attr('data-blku'),
                        'idshipment': $('.cekinput-' + index + index2).attr('data-idformshipment'),
                        'idformpo': $('.cekinput-' + index + index2).attr('data-idformpo'),
                        'value': val,
                    };
                    arrayku.push(data);
                }
                console.log('arrayku :>> ', arrayku);

                let form_data = new FormData();
                form_data.append('dataform', JSON.stringify(arrayku));
                form_data.append('etd', etd);
                form_data.append('eta', eta);
                form_data.append('nomorbl', nomorbl);
                form_data.append('inv', invoice);
                form_data.append('vessel', vessel);
                form_data.append('filebl', filebl);
                form_data.append('fileinv', fileinv);
                form_data.append('filepacking', filepacking);

                if (nomorbl == null || nomorbl == '') {
                    notifalert('BL Number');
                } else if (vessel == null || vessel == '') {
                    notifalert('Vessel');
                } else if (etd == null || etd == '') {
                    notifalert('ETD Fix');
                } else if (eta == null || eta == '') {
                    notifalert('ETA Fix');
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('updateshipment') }}",
                        processData: false,
                        contentType: false,
                        data: form_data,
                        // data: {
                        //     _token: $('meta[name=csrf-token]').attr('content'),
                        //     'idpo': idku,
                        //     'idformpo': formpo,
                        //     'file': form_data,
                        //     'nomorbl': nomorbl,
                        //     'vessel': vessel,
                        // },
                        dataType: "json",
                        success: function(response) {
                            console.log('response :>> ', response);
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                type: (response.status != 'error') ? 'success' : 'error'
                            }).then((result) => {
                                (response.status == 'success') ? window.location
                                    .replace("{{ route('datashipment') }}"):
                                    ''
                            });
                            return;
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Unsuccessfully Saved Data',
                                text: 'Check Your Data',
                                type: 'error'
                            });
                            return;
                        }
                    });
                }

            });

        }
    }

    // $('#btnupdate').click(function(e) {
    //     let etd = $('.etd').val();
    //     let eta = $('.eta').val();
    //     let nomorbl = $('#nomorbl').val();
    //     let vessel = $('#vessel').val();
    //     let filebl = $('#filebl').prop('files')[0];
    //     let fileinv = $('#fileinv').prop('files')[0];
    //     let filepacking = $('#filepack').prop('files')[0];

    //     var arrayku = [];
    //     for (let index = 0; index < dataku.length; index++) {
    //         for (let index2 = 0; index2 < dataku[index].length; index2++) {
    //             let val = $('.cekinput-' + index + index2).val();

    //             // if (val) {
    //             let data = {
    //                 'idpo': $('.cekinput-' + index + index2).attr('data-idformshipment'),
    //                 'idshipment': $('.cekinput-' + index + index2).attr('data-idformshipment'),
    //                 'idformpo': $('.cekinput-' + index + index2).attr('data-idformpo'),
    //                 'value': val,
    //             };

    //             arrayku.push(data);
    //             // }
    //         }
    //     }
    //     console.log('objectwew :>> ', JSON.stringify(arrayku));

    //     let form_data = new FormData();
    //     form_data.append('dataform', JSON.stringify(arrayku));
    //     form_data.append('etd', etd);
    //     form_data.append('eta', eta);
    //     form_data.append('nomorbl', nomorbl);
    //     form_data.append('vessel', vessel);
    //     form_data.append('filebl', filebl);
    //     form_data.append('fileinv', fileinv);
    //     form_data.append('filepacking', filepacking);

    //     if (nomorbl == null || nomorbl == '') {
    //         notifalert('BL Number');
    //     } else if (vessel == null || vessel == '') {
    //         notifalert('Vessel');
    //     } else if (etd == null || etd == '') {
    //         notifalert('ETD Fix');
    //     } else if (eta == null || eta == '') {
    //         notifalert('ETA Fix');
    //     } else {
    //         $.ajax({
    //             type: "post",
    //             url: "{{ route('updateshipment') }}",
    //             processData: false,
    //             contentType: false,
    //             data: form_data,
    //             // data: {
    //             //     _token: $('meta[name=csrf-token]').attr('content'),
    //             //     'idpo': idku,
    //             //     'idformpo': formpo,
    //             //     'file': form_data,
    //             //     'nomorbl': nomorbl,
    //             //     'vessel': vessel,
    //             // },
    //             dataType: "json",
    //             success: function(response) {
    //                 console.log('response :>> ', response);
    //                 Swal.fire({
    //                     title: response.title,
    //                     text: response.message,
    //                     type: (response.status != 'error') ? 'success' : 'error'
    //                 }).then((result) => {
    //                     (response.status == 'success') ? window.location
    //                         .replace("{{ route('datashipment') }}"):
    //                         ''
    //                 });
    //                 return;
    //             },
    //             error: function(xhr, status, error) {
    //                 Swal.fire({
    //                     title: 'Unsuccessfully Saved Data',
    //                     text: 'Check Your Data',
    //                     type: 'error'
    //                 });
    //                 return;
    //             }
    //         });
    //     }
    // });

    function notifalert(params) {
        Swal.fire({
            title: 'Information',
            text: params + ' Can not be empty',
            type: 'warning'
        });
        return;
    }
</script>
