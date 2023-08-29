<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal">
        {{ csrf_field() }}
        {{-- {{ dd($data) }} --}}
        <?php
        $listpo = [];
        $namasup = [];
        $listpi = [];
        foreach ($mypo as $po) {
            array_push($listpo, $po->pono);
            array_push($namasup, $po->nama);
            array_push($listpi, $po->pino);
        }
        ?>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">PO Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                            value=" {{ implode(', ', $listpo) }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">PI Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                            value=" {{ implode(', ', $listpi) }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Supplier</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="supplier" name="supplier"
                            value="{{ implode(', ', $namasup) }}" readonly>
                    </div>
                </div>
            </div>
            {{-- <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly> --}}
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-12">
                @foreach ($data as $key => $item)
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">{{ $item[0]['poku']->pino }}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <table border="1" width="100%">
                                            <thead>
                                                <th>PO Nomor</th>
                                                <th>Material</th>
                                                <th>Material Description</th>
                                                <th>HS Code</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Qty PO</th>
                                                <th>Status</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($item as $key2 => $dat)
                                                    <?php
                                                    // dd($dat['poku']->matcontents);
                                                    if ($dat['poku']['hscode'] == null) {
                                                        $hscode = '';
                                                    } else {
                                                        $hscode = $dat['poku']['hscode']->hscode;
                                                    }
                                                    
                                                    ?>
                                                    <tr>
                                                        <td>{{ $dat->po_nomor }}</td>
                                                        <td data-name="mat[]">{{ $dat['poku']->matcontents }}</td>
                                                        <td>{{ $dat['poku']->itemdesc }}</td>
                                                        <td> <input type="text" class="form-control"
                                                                value="{{ $hscode }}" id="inputhscode[]"
                                                                name="inputhscode[]" autocomplete="off"></td>
                                                        <td>{{ $dat['poku']->colorcode }}</td>
                                                        <td>{{ $dat['poku']->size }}</td>
                                                        <td>{{ $dat['poku']->qtypo }}</td>
                                                        <td>{{ $dat->statusforwarder }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Booking Number<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nobook" name="nobook" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Date Booking<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="datebook" name="datebook" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure)<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etd" name="etd" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETA (Estimated Time Arrival)<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="eta" name="eta" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Ship Mode<code>*</code></label>
                    <div class="col-sm-12">
                        <select class="form-control select2" style="width: 100%;" name="shipmode" id="shipmode">
                            <option value="-1" selected disabled>-- Choose Mode --</option>
                            <option value="fcl">FCL</option>
                            <option value="lcl">LCL</option>
                            <option value="air">Air</option>
                            <option value="cfscy">CFS/CY</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="datafcl" style="display: none">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="control-label">Size<code>*</code></label>
                                <select class="form-control select2" style="width: 100%;" name="fclku"
                                    id="fclku">
                                    <option value="20">20"</option>
                                    <option value="40">40"</option>
                                    <option value="40hq">40HQ</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Volume<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="fclvol"
                                        id="fclvol" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Weight<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="fclweight"
                                        id="fclweight" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">KG</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="datalcl" style="display: none">
                    <div class="col-sm-12">
                        {{-- <select class="select2" style="width: 100%;" name="lclku" id="lclku">
                        <option value="cbm">CBM</option>
                    </select> --}}
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">Volume<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="lclku"
                                        id="lclku" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Weight<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="lclweight"
                                        id="lclweight" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">KG</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="dataair" style="display: none">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">Volume<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="airku"
                                        id="airku" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Weight<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="airweight"
                                        id="airweight" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">KG</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="datacfscy" style="display: none">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">Volume<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="cfscyvol"
                                        id="cfscyvol" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Weight<code>*</code></label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" name="cfscyweight"
                                        id="cfscyweight" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">KG</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Route<code>*</code></label>
                    <div class="col-sm-12">
                        <select class="form-control select2" name="route" id="route">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Port Of Loading<code>*</code></label>
                    <div class="col-sm-12">
                        <select class="form-control select2" name="portloading" id="portloading">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Port Of Destination<code>*</code></label>
                    <div class="col-sm-12">
                        <select class="form-control select2" name="portdestination" id="portdestination">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Package<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="package" id="package"
                            autocomplete="off">
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
    $(document).ready(function() {
        var dataku = @JSON($data);

        //Initialize Select2 Elements
        $('.select2').select2();

        $('#etd').prop('disabled', true);
        $('#eta').prop('disabled', true);
        $('#datebook').datepicker({
            changeYear: true,
            changeMonth: true,
            // minDate: -14,
            dateFormat: "yy-m-dd",
            yearRange: "-100:+20",
        });

        $('#datebook').change(function() {
            date1 = $('#datebook').val();
            $('#etd').prop('disabled', false);

            $('#etd').datepicker({
                changeYear: true,
                changeMonth: true,
                // minDate: date1,
                dateFormat: "yy-m-dd",
                yearRange: "-100:+20",
            });

            $('#etd').change(function(e) {
                date2 = $('#etd').val();
                $('#eta').prop('disabled', false);

                $('#eta').datepicker({
                    changeYear: true,
                    changeMonth: true,
                    // minDate: date2,
                    dateFormat: "yy-m-dd",
                    yearRange: "-100:+20",
                });

                $('#eta').change(function(e) {
                    date3 = $('#eta').val();
                });

            });
        });

        $('#shipmode').on('change', function() {
            let mode = $(this).val();
            console.log('training :>> ', mode);
            console.log('object :>> ', 'klik');
            if (mode == 'fcl') {
                $('#datafcl').show()
                $('#datalcl').hide()
                $('#dataair').hide()
                $('#datacfscy').hide()
            } else if (mode == 'lcl') {
                $('#datalcl').show()
                $('#datafcl').hide()
                $('#dataair').hide()
                $('#datacfscy').hide()
            } else if (mode == 'air') {
                $('#dataair').show()
                $('#datafcl').hide()
                $('#datalcl').hide()
                $('#datacfscy').hide()
            } else {
                $('#datacfscy').show()
                $('#dataair').hide()
                $('#datafcl').hide()
                $('#datalcl').hide()
            }
        });

        $('#route').select2({
            placeholder: '-- Choose Route --',
            ajax: {
                url: "{!! route('get_route') !!}",
                dataType: 'json',
                delay: 500,
                type: 'post',
                data: function(params) {
                    var query = {
                        q: params.term,
                        page: params.page || 1,
                        _token: $('meta[name=csrf-token]').attr('content')
                    }
                    return query;
                },
                processResults: function(data, params) {
                    console.log('data :>> ', data);
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.route_code + '-' + item.route_desc,
                                id: item.id_route,
                                selected: true,
                            }
                        }),
                        pagination: {
                            more: data.to < data.total
                        }
                    };
                },
                cache: true
            }
        });

        $('#portloading').select2({
            placeholder: '-- Choose Port Of Loading --',
            ajax: {
                url: "{!! route('get_portloading') !!}",
                dataType: 'json',
                delay: 500,
                type: 'post',
                data: function(params) {
                    var query = {
                        q: params.term,
                        page: params.page || 1,
                        _token: $('meta[name=csrf-token]').attr('content')
                    }
                    return query;
                },
                processResults: function(data, params) {
                    console.log('data :>> ', data);
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.code_port + '-' + item.name_port,
                                id: item.id_portloading,
                                selected: true,
                            }
                        }),
                        pagination: {
                            more: data.to < data.total
                        }
                    };
                },
                cache: true
            }
        });

        $('#portdestination').select2({
            placeholder: '-- Choose Port Of Destination --',
            ajax: {
                url: "{!! route('get_portdestination') !!}",
                dataType: 'json',
                delay: 500,
                type: 'post',
                data: function(params) {
                    var query = {
                        q: params.term,
                        page: params.page || 1,
                        _token: $('meta[name=csrf-token]').attr('content')
                    }
                    return query;
                },
                processResults: function(data, params) {
                    console.log('data :>> ', data);
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.code_port + '-' + item.name_port,
                                id: item.id_portdestination,
                                selected: true,
                            }
                        }),
                        pagination: {
                            more: data.to < data.total
                        }
                    };
                },
                cache: true
            }
        });

        $('#btnsubmit').click(function(e) {
            $('#btnsubmit').html('<i class="fas fa-hourglass"></i> Please Wait')
            $('#btnsubmit').prop('disabled', true)
            var matcontent = $('td[data-name^=mat]').map(function(idx, elem) {
                return $(elem).html();
            }).get();
            var hscode = $('input[name^=inputhscode]').map(function(idx, elem) {
                return $(elem).val();
            }).get();
            let nobook = $('#nobook').val();
            let datebook = $('#datebook').val();
            let myetd = $('#etd').val();
            let myeta = $('#eta').val();
            let mode = $('#shipmode').val();
            let myfcl = $('#fclku').val();
            let myweight = $('#fclweight').val();
            let fclvol = $('#fclvol').val();
            let mylcl = $('#lclku').val();
            let lclweight = $('#lclweight').val();
            let myair = $('#airku').val();
            let airweight = $('#airweight').val();
            let cfscyvol = $('#cfscyvol').val();
            let cfscyweight = $('#cfscyweight').val();
            let myroute = $('#route').val();
            let portloading = $('#portloading').val();
            let portdestination = $('#portdestination').val();
            let package = $('#package').val();

            var arraysave = [];
            for (let index = 0; index < dataku.length; index++) {
                for (let index2 = 0; index2 < dataku[index].length; index2++) {
                    let val = {
                        'idforwarder': dataku[index][index2].id_forwarder,
                        'idpo': dataku[index][index2].idpo,
                        'idmasterfwd': dataku[index][index2].idmasterfwd,
                        'pono': dataku[index][index2].po_nomor,
                    };
                    arraysave.push(val)
                }
            }


            if (nobook == null || nobook == '') {
                notifalert('Nomor Booking');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (datebook == null || datebook == '') {
                notifalert('Date Booking');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (myetd == null || myetd == '') {
                notifalert('ETD (Estimated Time Departure)');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (myeta == null || myeta == '') {
                notifalert('ETA (Estimated Time Arrival)');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == null || mode == '') {
                notifalert('Ship Mode');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'fcl' && myfcl == '') {
                notifalert('FCL');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'fcl' && myweight == '') {
                notifalert('Weight');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'fcl' && fclvol == '') {
                notifalert('Volume');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'lcl' && mylcl == '') {
                notifalert('LCL');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'lcl' && lclweight == '') {
                notifalert('LCL Weight');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'air' && myair == '') {
                notifalert('AIR');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'air' && airweight == '') {
                notifalert('AIR Weight');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'cfscy' && cfscyvol == '') {
                notifalert('CSF/CY Volume');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (mode == 'cfscy' && cfscyweight == '') {
                notifalert('CSF/CY Weight');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (myroute == null || myroute == '') {
                notifalert('Route');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (portloading == null || portloading == '') {
                notifalert('Port Of Loading');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (portdestination == null || portdestination == '') {
                notifalert('Port Of Destination');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else if (package == null || package == '') {
                notifalert('Package');
                $('#btnsubmit').html('Submit')
                $('#btnsubmit').prop('disabled', false)
            } else {
                $.ajax({
                    type: "post",
                    url: "{{ route('formposave') }}",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        'dataid': arraysave,
                        'hscode': hscode,
                        'matcontent': matcontent,
                        'nobooking': nobook,
                        'datebooking': datebook,
                        'etd': myetd,
                        'eta': myeta,
                        'shipmode': mode,
                        'fcl': myfcl,
                        'fclweight': myweight,
                        'fclvol': fclvol,
                        'lcl': mylcl,
                        'lclweight': lclweight,
                        'air': myair,
                        'airweight': airweight,
                        'cfscyvol': cfscyvol,
                        'cfscyweight': cfscyweight,
                        'route': myroute,
                        'portloading': portloading,
                        'portdestination': portdestination,
                        'package': package
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log('response :>> ', response);
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            (response.status == 'success') ? window.location
                                .replace("{{ route('page_po') }}"):
                                ''
                            $('#btnsubmit').html('Submit')
                            $('#btnsubmit').prop('disabled', false)
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
    });
</script>
