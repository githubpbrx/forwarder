<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{-- {{ dd($data['dataku'][0]) }} --}}
        <?php
        $nopo = [];
        $nopi = [];
        $supname = [];
        foreach ($data['datapo'] as $key => $value) {
            array_push($nopo, $value->pono);
            array_push($nopi, $value->pino);
            array_push($supname, $value->nama);
        }
        ?>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">PO Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                            value="{{ implode(', ', $nopo) }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">PI Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                            value="{{ implode(', ', $nopi) }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Supplier</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="supplier" name="supplier"
                            value="{{ implode(', ', $supname) }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <table border="1" width="100%">
                <thead>
                    <th><input type="checkbox" class="checkall" style="height:18px;width:18px" checked>
                    </th>
                    <th>Material</th>
                    <th>Material Description</th>
                    <th>HS Code</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Qty PO</th>
                    <th>Balance Qty</th>
                    <th>Qty Ship</th>
                </thead>
                <tbody>
                    @foreach ($data['dataku'] as $key => $item)
                        <?php
                        if ($item->qtyship == null) {
                            $remain = $item['withpo']->qtypo;
                            $inputalok = $item['withpo']->qtypo;
                            $block = '';
                            $ceked = 'checked';
                        } elseif ($item->qtyship == $item['withpo']->qtypo) {
                            $remain = 0;
                            $inputalok = '';
                            $block = 'disabled';
                            $ceked = '';
                        } else {
                            $remain = $item['withpo']->qtypo - $item->qtyship;
                            $inputalok = $item['withpo']->qtypo - $item->qtyship;
                            $block = '';
                            $ceked = 'checked';
                        }

                        if ($item['withpo']['hscode'] == null) {
                            $hscode = 'empty';
                        } else {
                            $hscode = $item['withpo']['hscode']->hscode;
                        }

                        ?>
                        <tr>
                            <td><input type="checkbox" id="check-{{ $key }}" style="height:18px;width:18px"
                                    {{ $block }}{{ $ceked }}></td>
                            <td>{{ $item['withpo']->matcontents }}</td>
                            <td>{{ $item['withpo']->itemdesc }}</td>
                            <td>{{ $hscode }}</td>
                            <td>{{ $item['withpo']->colorcode }}</td>
                            <td>{{ $item['withpo']->size }}</td>
                            <td>{{ $item['withpo']->qtypo }}</td>
                            <td>{{ $remain }}</td>
                            <td><input type="number" min="0"
                                    class="form-control trigerinput cekinput-{{ $key }}" id="qty_allocation"
                                    name="qty_allocation" value="{{ $inputalok }}" {{ $block }}></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Booking Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nobook" name="nobook"
                            value="{{ $data['dataku'][0]->kode_booking }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Date Booking</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="datebook" name="datebook"
                            value="{{ $data['dataku'][0]->date_booking }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure)</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etd" name="etd"
                            value="{{ $data['dataku'][0]->etd }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETA (Estimated Time Arrival)</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="eta" name="eta"
                            value="{{ $data['dataku'][0]->eta }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Route</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="route" name="route"
                            value="{{ $data['dataku'][0]['withroute']->route_desc }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="col-sm-12 control-label">Shipmode<code>*</code></label>
                        <select class="form-control select2" style="width: 100%;" name="shipmode" id="shipmode">
                            <option value="-1" selected disabled>-- Choose Mode --</option>
                            <option value="fcl">FCL</option>
                            <option value="lcl">LCL</option>
                            <option value="air">Air</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="row" id="datafcl" style="display: none; padding-bottom: 2.5%">
                            <div class="col-sm-2" class="radiocontainer">
                                <label class="control-label">Container Size<code>*</code></label>
                                <?php
                                $dat = $data['dataku'][0]->subshipmode;
                                $exp = explode('-', $dat);
                                $exp2 = explode('KG', $exp[1]);
                                ?>
                                <br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio1" value="20" {{ $exp[0] == '20' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio1">20"</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio2" value="40" {{ $exp[0] == '40' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio2">40"</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio2" value="40hq" {{ $exp[0] == '40hq' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio2">40 HQ</label>
                                </div>
                            </div>
                            {{-- <div class="col-sm-2">
                                <label class="control-label">Volume</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="fclvol"
                                        id="fclvol" value="{{ $exp[1] }}" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-sm-3">
                                <label class="control-label">Container Number<code>*</code></label>
                                <input type="number" min="0" class="form-control" name="noc[]"
                                    value="">
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label">Weight<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="weight[]"
                                        id="weight" value="{{ $exp2[0] }}" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">KG</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label">&nbsp;</label>
                                <br>
                                <button type="button" class="btn btn-warning btn-md" id="addfcl"><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <span id="fclinput"></span>
                        <div id="datalcl" style="display: none">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="col-sm-12 control-label">Volume<code>*</code></label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control" name="lclvol"
                                            id="lclvol" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">M3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 control-label">Weight<code>*</code></label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control" name="lclweight"
                                            id="lclweight" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="dataair" style="display: none">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="col-sm-12 control-label">Volume<code>*</code></label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control" name="airvol"
                                            id="airvol" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">M3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 control-label">Weight<code>*</code></label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control" name="airweight"
                                            id="airweight" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">BL<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="file" class="form-control" id="bl" name="bl">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Invoice</label>
                    <div class="col-sm-12">
                        <input type="file" class="form-control" id="invoicefile" name="invoicefile"
                            autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Packing List</label>
                    <div class="col-sm-12">
                        <input type="file" class="form-control" id="packlist" name="packlist"
                            autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Invoice Number<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="invoice" name="invoice"
                            autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">BL Number<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nobl" name="nobl"
                            autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Vessel<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="vessel" name="vessel"
                            autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ATD (Actual Time Departure)<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etdfix" name="etdfix"
                            value="{{ $data['dataku'][0]->etd }}" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ATA (Actual Time Arrival)<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etafix" name="etafix"
                            value="{{ $data['dataku'][0]->eta }}" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-info" id="btnsubmit">Submit</button>
</div>

<script type="text/javascript">
    var current = 1;
    var dataku = @JSON($data['dataku']);
    console.log('dataku :>> ', dataku);

    var radiovalue = $("input[name='inlineRadioOptions']:checked").val();
    var cekrad;
    $('.form-check-input').click(function(e) {
        cekrad = $(this).val();
    });

    //Initialize Select2 Elements
    $('.select2').select2()

    $('#etdfix').datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "yy-m-dd",
        yearRange: "-100:+20",
    });

    $('#etafix').datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "yy-m-dd",
        yearRange: "-100:+20",
    });

    $('.checkall').change(function(e) {
        console.log('klik :>> ', 'klik');
        if (this.checked) {
            $('.trigerinput').prop('disabled', false);
            $('input[type="checkbox"]').prop('checked', true);
        } else {
            $('.trigerinput').val('');
            $('.trigerinput').prop('disabled', true);
            $('input[type="checkbox"]').prop('checked', false);
        }
    });

    for (let index = 0; index < dataku.length; index++) {
        $('#check-' + index).change(function(e) {
            if (this.checked) {
                console.log('objectsijine :>> ', 'isChecked');
                $('.cekinput-' + index).prop('disabled', false);
                // }
            } else {
                console.log('objectsijine :>> ', 'notChecked');
                $('.cekinput-' + index).val('');
                $('.cekinput-' + index).prop('disabled', true);
            }
        });
    }

    var mode;
    $('#shipmode').on('change', function() {
        mode = $(this).val();
        console.log('training :>> ', mode);
        if (mode == 'fcl') {
            console.log('object :>> ', 'klikfcl');
            $('#datafcl').show()
            $('#datalcl').hide()
            $('#dataair').hide()
        } else if (mode == 'lcl') {
            console.log('object :>> ', 'kliklcl');
            let explcl = dataku[0].subshipmode.split("-");
            let lclcbm = explcl[0].split("M3");
            let lclkg = explcl[1].split("KG");
            $('#lclvol').val(lclcbm[0]);
            $('#lclweight').val(lclkg[0]);
            $('#fclinput').empty();
            $('#datalcl').show()
            $('#datafcl').hide()
            $('#dataair').hide()
        } else {
            console.log('object :>> ', 'kliksir');
            let expair = dataku[0].subshipmode.split("-");
            let aircbm = expair[0].split("M3");
            let airkg = expair[1].split("KG");
            $('#airvol').val(aircbm[0]);
            $('#airweight').val(airkg[0]);
            $('#fclinput').empty();
            $('#dataair').show()
            $('#datafcl').hide()
            $('#datalcl').hide()
        }
    });

    function removefield() {
        $('.deletefcl').click(function(e) {
            let idku = $(this).data('id')
            $('.pilgan-' + idku).remove();
        });
    }

    $('#shipmode').val(dataku[0].shipmode).trigger("change");

    $('#addfcl').click(function(e) {
        current++;
        $('#fclinput').append(
            `<div class="form-group pilgan-` + current + `">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" name="noc[]" value="">
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="number" min="0" class="form-control" name="weight[]"
                                    id="weight" autocomplete="off">
                                <div class="input-group-append">
                                    <span class="input-group-text">KG</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <a href="#" data-id="` + current + `" class="btn btn-danger btn-md deletefcl"><i class="fa fa-eraser"></i></a>
                        </div>
                    </div>
                </div>
            </div>`
        );
        removefield();
    });

    $('#btnsubmit').click(function(e) {
        let numbofcont = $("input[name='noc[]']")
            .map(function() {
                if ($(this).val() == '') {
                    return;
                } else {
                    return $(this).val();
                }
            }).get();
        let weight = $("input[name='weight[]']")
            .map(function() {
                if ($(this).val() == '') {
                    return;
                } else {
                    return $(this).val();
                }
            }).get();
        let fclfeet = (cekrad == null) ? radiovalue : cekrad;
        let shipmode = $('#shipmode').val();
        let lclvol = $('#lclvol').val();
        let lclweight = $('#lclweight').val();
        let airvol = $('#airvol').val();
        let airweight = $('#airweight').val();
        let nomorbl = $('#nobl').val();
        let noinv = $('#invoice').val();
        let vessel = $('#vessel').val();
        let filebl = $('#bl').prop('files')[0];
        let fileinv = $('#invoicefile').prop('files')[0];
        let filepack = $('#packlist').prop('files')[0];
        let etdfix = $('#etdfix').val();
        let etafix = $('#etafix').val();

        var arrayku = [];
        for (let index = 0; index < dataku.length; index++) {
            let val = $('.cekinput-' + index).val();

            if (val) {
                let data = {
                    'idpo': dataku[index].idpo,
                    'idformpo': dataku[index].id_formpo,
                    'value': val,
                };
                arrayku.push(data);
            }
        }

        var vol;
        var updateweight;
        if (shipmode == 'lcl') {
            vol = lclvol;
            updateweight = lclweight;
        } else if (shipmode == 'air') {
            vol = airvol;
            updateweight = airweight;
        }

        let form_data = new FormData();
        form_data.append('dataid', JSON.stringify(arrayku));
        form_data.append('datacontainer', JSON.stringify(numbofcont));
        form_data.append('dataweight', JSON.stringify(weight));
        form_data.append('shipmode', shipmode);
        form_data.append('volume', vol);
        form_data.append('updateweight', updateweight);
        form_data.append('fclfeet', fclfeet);
        form_data.append('nomorbl', nomorbl);
        form_data.append('noinv', noinv);
        form_data.append('vessel', vessel);
        form_data.append('filebl', filebl);
        form_data.append('fileinv', fileinv);
        form_data.append('filepack', filepack);
        form_data.append('etdfix', etdfix);
        form_data.append('etafix', etafix);

        if (arrayku == null || arrayku == '') {
            notifalert('Quantity Allocation');
        } else if (mode == 'fcl' && (numbofcont == null || numbofcont == '')) {
            notifalert('Number Of Container');
        } else if (mode == 'fcl' && (weight == null || weight == '')) {
            notifalert('Weight');
        } else if (filebl == null || filebl == '') {
            notifalert('File BL');
        } else if (noinv == null || noinv == '') {
            notifalert('Invoice');
        } else if (nomorbl == null || nomorbl == '') {
            notifalert('BL Number');
        } else if (vessel == null || vessel == '') {
            notifalert('Vessel');
        } else if (etdfix == null || etdfix == '') {
            notifalert('ETD Fix');
        } else if (etafix == null || etafix == '') {
            notifalert('ETA Fix');
        } else {
            $.ajax({
                type: "post",
                url: "{{ route('saveshipmentprocess') }}",
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
                            .replace("{{ route('process_shipment') }}"):
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

    function notifalert(params) {
        Swal.fire({
            title: 'Information',
            text: params + ' Can not be empty',
            type: 'warning'
        });
        return;
    }
</script>
