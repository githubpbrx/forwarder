<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal">
        <?php echo e(csrf_field()); ?>

        
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title"> <?php echo e($data['booking'][0]->kode_booking); ?> </h3>
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
                                            <th>Material</th>
                                            <th>Material Desc</th>
                                            <th>Hs Code</th>
                                            <th>Color</th>
                                            <th>Size</th>
                                            <th>Qty PO</th>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $data['booking']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2 => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                if ($val['withpo']['hscode'] == null) {
                                                    $hscode = 'empty';
                                                } else {
                                                    $hscode = $val['withpo']['hscode']->hscode;
                                                }
                                                
                                                $idpo = $val->idpo;
                                                ?>
                                                <tr>
                                                    <td data-name="mat[]"><?php echo e($val['withpo']->matcontents); ?></td>
                                                    <td><?php echo e($val['withpo']->itemdesc); ?></td>
                                                    <td> <input type="text" class="form-control"
                                                            value="<?php echo e($hscode); ?>" id="edithscode[]"
                                                            name="edithscode[]" autocomplete="off"></td>
                                                    <td><?php echo e($val['withpo']->colorcode); ?></td>
                                                    <td><?php echo e($val['withpo']->size); ?></td>
                                                    <td><?php echo e($val['withpo']->qtypo); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <hr
                                        style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">Booking
                                                    Number<code>*</code></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="nobook"
                                                        name="nobook" value="<?php echo e($val->kode_booking); ?>"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">Date
                                                    Booking<code>*</code></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="datebook"
                                                        name="datebook" value="<?php echo e($val->date_booking); ?>"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">ETD (Estimated Time
                                                    Departure)<code>*</code></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="etd"
                                                        name="etd" value="<?php echo e($val->etd); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">ETA (Estimated Time
                                                    Arrival)<code>*</code></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="eta"
                                                        name="eta" value="<?php echo e($val->eta); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">Ship
                                                    Mode<code>*</code></label>
                                                <div class="col-sm-12">
                                                    <select class="form-control select2" style="width: 100%;"
                                                        name="shipmode" id="shipmode">
                                                        <option value="-1" selected disabled>-- Choose Mode --
                                                        </option>
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
                                                            <select class="form-control select2" style="width: 100%;"
                                                                name="fclku" id="fclku">
                                                                <option value="20">20"</option>
                                                                <option value="40">40"</option>
                                                                <option value="40hq">40HQ</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label class="control-label">Volume<code>*</code></label>
                                                            <div class="input-group">
                                                                <input type="number" min="0"
                                                                    class="form-control" name="fclvol"
                                                                    id="fclvol" autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">M3</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label class="control-label">Weight<code>*</code></label>
                                                            <div class="input-group">
                                                                <input type="number" min="0"
                                                                    class="form-control" name="fclweight"
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
                                                    
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label class="control-label">Volume<code>*</code></label>
                                                            <div class="input-group">
                                                                <input type="number" min="0"
                                                                    class="form-control" name="lclku"
                                                                    id="lclku" autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">M3</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label class="control-label">Weight<code>*</code></label>
                                                            <div class="input-group">
                                                                <input type="number" min="0"
                                                                    class="form-control" name="lclweight"
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
                                                                <input type="number" min="0"
                                                                    class="form-control" name="airku"
                                                                    id="airku" autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">M3</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label class="control-label">Weight<code>*</code></label>
                                                            <div class="input-group">
                                                                <input type="number" min="0"
                                                                    class="form-control" name="airweight"
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
                                                                <input type="number" min="0"
                                                                    class="form-control" name="cfscyvol"
                                                                    id="cfscyvol" autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">M3</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label class="control-label">Weight<code>*</code></label>
                                                            <div class="input-group">
                                                                <input type="number" min="0"
                                                                    class="form-control" name="cfscyweight"
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
                                                    <select class="form-control select2" name="route"
                                                        id="route">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">Port Of
                                                    Loading<code>*</code></label>
                                                <div class="col-sm-12">
                                                    <select class="form-control select2" name="portloading"
                                                        id="portloading">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">Port Of
                                                    Destination<code>*</code></label>
                                                <div class="col-sm-12">
                                                    <select class="form-control select2" name="portdestination"
                                                        id="portdestination">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">Package<code>*</code></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" name="package"
                                                        id="package" value="<?php echo e($val->package); ?>"
                                                        autocomplete="off">
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
                                                        id="btnupdate">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
</div>

<script type="text/javascript">
    var dataku = <?php echo json_encode($data['booking'], 15, 512) ?>;

    //Initialize Select2 Elements
    $('.select2').select2();

    submit();

    $('#datebook').datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "yy-m-dd",
        yearRange: "-100:+20",
    });

    $('#etd').datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "yy-m-dd",
        yearRange: "-100:+20",
    });

    $('#eta').datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "yy-m-dd",
        yearRange: "-100:+20",
    });

    var mode;
    $('#shipmode').on('change', function() {
        mode = $(this).val();
        if (mode == 'fcl') {
            let expfcl = dataku[0].subshipmode.split("-");

            let fclvol;
            let fclkg;
            let fclsize;
            if (expfcl.length > 2) {
                fclsize = expfcl[0];
                fclvol = expfcl[1];
                fclkg = expfcl[2].split("KG");
            } else {
                let splitfcl = expfcl[0].split("M3");
                fclvol = splitfcl[0];
                fclkg = expfcl[1].split("KG");
            }
            $('#fclku').val(fclsize).trigger("change");
            $('#fclweight').val(fclkg[0]);
            $('#fclvol').val(fclvol);
            $('#datafcl').show()
            $('#datalcl').hide()
            $('#dataair').hide()
            $('#datacfscy').hide()
        } else if (mode == 'lcl') {
            let explcl = dataku[0].subshipmode.split("-");

            let lclvol;
            let lclkg;
            if (explcl.length > 2) {
                lclvol = explcl[1];
                lclkg = explcl[2].split("KG");
            } else {
                let splitlcl = explcl[0].split("M3");
                lclvol = splitlcl[0];
                lclkg = explcl[1].split("KG");
            }
            $('#lclku').val(lclvol);
            $('#lclweight').val(lclkg[0]);
            $('#fclinput').empty();
            $('#datalcl').show()
            $('#datafcl').hide()
            $('#dataair').hide()
            $('#datacfscy').hide()
        } else if (mode == 'air') {
            let expair = dataku[0].subshipmode.split("-");

            let airvol;
            let airkg;
            if (expair.length > 2) {
                airvol = expair[1];
                airkg = expair[2].split("KG");
            } else {
                let splitair = expair[0].split("M3");
                airvol = splitair[0];
                airkg = expair[1].split("KG");
            }
            $('#airku').val(airvol);
            $('#airweight').val(airkg[0]);
            $('#fclinput').empty();
            $('#dataair').show()
            $('#datafcl').hide()
            $('#datalcl').hide()
            $('#datacfscy').hide()
        } else {
            let expcfscy = dataku[0].subshipmode.split("-");

            let cfscyvol;
            let cfscykg;
            if (expcfscy.length > 2) {
                cfscyvol = expcfscy[1];
                cfscykg = expcfscy[2].split("KG");
            } else {
                let splitcfscy = expcfscy[0].split("M3");
                cfscyvol = splitcfscy[0];
                cfscykg = expcfscy[1].split("KG");
            }
            $('#cfscyvol').val(cfscyvol);
            $('#cfscyweight').val(cfscykg[0]);
            $('#fclinput').empty();
            $('#datacfscy').show()
            $('#dataair').hide()
            $('#datafcl').hide()
            $('#datalcl').hide()
        }
    });

    $('#shipmode').val(dataku[0].shipmode).trigger("change");

    $('#route').select2({
        placeholder: '-- Choose Route --',
        ajax: {
            url: "<?php echo route('get_route'); ?>",
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
            url: "<?php echo route('get_portloading'); ?>",
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
            url: "<?php echo route('get_portdestination'); ?>",
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

    $('#route').empty().html('<option value="' + dataku[0].idroute + '">' + dataku[0].withroute.route_code + '-' +
        dataku[0].withroute.route_desc + '</option>').val(dataku[0].idroute).trigger('change');

    $('#portloading').empty().html('<option value="' + dataku[0].idportloading + '">' + dataku[0]
        .withportloading.code_port + '-' +
        dataku[0].withportloading.name_port + '</option>').val(dataku[0].idportloading).trigger('change');

    $('#portdestination').empty().html('<option value="' + dataku[0].idportdestination + '">' + dataku[0]
        .withportdestination.code_port + '-' +
        dataku[0].withportdestination.name_port + '</option>').val(dataku[0].idportdestination).trigger(
        'change');

    function submit() {
        $('#btnupdate').click(function(e) {
            let matcontent = $("td[data-name='mat[]']")
                .map(function() {
                    if ($(this).html() == '') {
                        return;
                    } else {
                        return $(this).html();
                    }
                }).get();
            let hscode = $("input[name='edithscode[]']")
                .map(function() {
                    if ($(this).val() == '') {
                        return;
                    } else {
                        return $(this).val();
                    }
                }).get();
            let nobook_old = dataku[0].kode_booking;
            let nobook = $('#nobook').val();
            let datebook = $('#datebook').val();
            let myetd = $('#etd').val();
            let myeta = $('#eta').val();
            let shipmode = $('#shipmode').val();
            let invoice = $('#invoice').val();
            let vessel = $('#vessel').val();
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
                    url: "<?php echo e(route('updatebooking')); ?>",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        'hscode': hscode,
                        'nobook_old': nobook_old,
                        'nobooking': nobook,
                        'datebooking': datebook,
                        'matcontent': matcontent,
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
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            (response.status == 'success') ? window.location
                                .replace("<?php echo e(route('dataupdatebooking')); ?>"):
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

    function notifalert(params) {
        Swal.fire({
            title: 'Information',
            text: params + ' Can not be empty',
            type: 'warning'
        });
        return;
    }
</script>
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/Transaksi\Resources/views/modalupdatebooking.blade.php ENDPATH**/ ?>