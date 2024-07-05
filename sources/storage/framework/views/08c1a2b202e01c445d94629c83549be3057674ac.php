<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal">
        <?php echo e(csrf_field()); ?>

        
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
                    <label class="col-sm-12 control-label">PI Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                            value=" <?php echo e($listpi[0]); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">PO Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                            value=" <?php echo e(implode(', ', $listpo)); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Supplier</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="supplier" name="supplier"
                            value="<?php echo e($namasup[0]); ?>" readonly>
                    </div>
                </div>
            </div>
            
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-12">
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e($item[0]->pino); ?></h3>
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
                                        <table border="1" width="100%" style="text-align:center">
                                            <thead>
                                                <th style="text-align:center"><input type="checkbox"
                                                        class="checkall-<?php echo e($key); ?>"
                                                        style="height:18px;width:18px" checked>
                                                </th>
                                                <th>PO Nomor</th>
                                                <th>Material</th>
                                                <th>Material Description</th>
                                                <th>HS Code</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Qty PO</th>
                                                <th>Balance Qty</th>
                                                <th>Qty Booking</th>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2 => $dat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                    if ($dat->hscode == null) {
                                                        $hscode = '';
                                                    } else {
                                                        $hscode = $dat->hscode;
                                                    }
                                                    
                                                    $qtybook = 0;
                                                    if ($dat->qtybook != null) {
                                                        $qtybook = $dat->qtybook;
                                                    }
                                                    
                                                    if ($dat['withformpo'] == null) {
                                                        $remain = $dat->qtypo;
                                                        $block = '';
                                                        $ceked = 'checked';
                                                    } elseif ($qtybook == $dat->qtypo) {
                                                        $remain = 0;
                                                        $block = 'disabled';
                                                        $ceked = '';
                                                    } else {
                                                        $remain = $dat->qtypo - $qtybook;
                                                        $block = '';
                                                        $ceked = 'checked';
                                                    }
                                                    
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox"
                                                                class="check-<?php echo e($key); ?><?php echo e($key2); ?>"
                                                                style="height:18px;width:18px"
                                                                <?php echo e($block); ?><?php echo e($ceked); ?>>
                                                        </td>
                                                        <td><?php echo e($dat->po_nomor); ?></td>
                                                        <td data-name="mat[]"><?php echo e($dat->matcontents); ?></td>
                                                        <td><?php echo e($dat->itemdesc); ?></td>
                                                        <td> <input type="text" class="form-control"
                                                                value="<?php echo e($hscode); ?>" id="inputhscode[]"
                                                                name="inputhscode[]" autocomplete="off">
                                                        </td>
                                                        <td><?php echo e($dat->colorcode); ?></td>
                                                        <td><?php echo e($dat->size); ?></td>
                                                        <td><?php echo e($dat->qtypo); ?></td>
                                                        <td><?php echo e($remain); ?></td>
                                                        <td>
                                                            <input type="number" min="0"
                                                                id="qtybook-<?php echo e($key); ?><?php echo e($key2); ?>"
                                                                name="qtybook"
                                                                class="form-control trigerinput cekinput-<?php echo e($key); ?><?php echo e($key2); ?>"
                                                                data-remain="<?php echo e($remain); ?>" <?php echo e($block); ?>>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <input type="text" class="form-control" id="eta" name="eta"
                            autocomplete="off">
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
        var dataku = <?php echo json_encode($data, 15, 512) ?>;

        //Initialize Select2 Elements
        $('.select2').select2();

        for (let index = 0; index < dataku.length; index++) {
            for (let index2 = 0; index2 < dataku[index].length; index2++) {
                $('.checkall-' + index).change(function(e) {
                    let getqty = dataku[index][index2];
                    if (this.checked) {
                        if (getqty['qtybook'] == getqty['qtypo']) {
                            $('.check-' + index + index2).prop('checked', false);
                            $('.cekinput-' + index + index2).prop('disabled', true);
                            $('.cekinput-' + index + index2).val('');
                        } else {
                            $('.check-' + index + index2).prop('checked', true);
                            $('.cekinput-' + index + index2).prop('disabled', false);
                        }
                    } else {
                        $('.check-' + index + index2).prop('checked', false);
                        $('.cekinput-' + index + index2).val('');
                        $('.cekinput-' + index + index2).prop('disabled', true);
                    }
                });
            }
        }

        for (let index = 0; index < dataku.length; index++) {
            for (let index2 = 0; index2 < dataku[index].length; index2++) {
                $('.check-' + index + index2).change(function(e) {
                    if (this.checked) {
                        $('.cekinput-' + index + index2).prop('disabled', false);
                    } else {
                        $('.cekinput-' + index + index2).val('');
                        $('.cekinput-' + index + index2).prop('disabled', true);
                    }
                });
            }
        }

        // memvalidasi inputan supaya tidak bisa lebih dari balance
        for (let index = 0; index < dataku.length; index++) {
            for (let index2 = 0; index2 < dataku[index].length; index2++) {
                $('.cekinput-' + index + index2).keyup(function(e) {
                    let valinput = $('.cekinput-' + index + index2).val();
                    let rem = $('.cekinput-' + index + index2).attr('data-remain');

                    if (Number(valinput) >= Number(rem)) {
                        $('.cekinput-' + index + index2).val(rem);
                    }
                });
            }
        }

        $('#etd').prop('disabled', true);
        $('#eta').prop('disabled', true);
        $('#datebook').datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: "yy-m-dd",
            yearRange: "-100:+20",
        });

        $('#datebook').change(function() {
            date1 = $('#datebook').val();
            $('#etd').prop('disabled', false);

            $('#etd').datepicker({
                changeYear: true,
                changeMonth: true,
                dateFormat: "yy-m-dd",
                yearRange: "-100:+20",
            });

            $('#etd').change(function(e) {
                date2 = $('#etd').val();
                $('#eta').prop('disabled', false);

                $('#eta').datepicker({
                    changeYear: true,
                    changeMonth: true,
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
                    var cekdisabled = $('.cekinput-' + index + index2).prop('disabled');
                    let val = $('.cekinput-' + index + index2).val();

                    if (!cekdisabled && val != 0) {
                        let data = {
                            'idforwarder': dataku[index][index2].id_forwarder,
                            'idpo': dataku[index][index2].idpo,
                            'idmasterfwd': dataku[index][index2].idmasterfwd,
                            'pono': dataku[index][index2].po_nomor,
                            'qtybook': val,
                        };

                        arraysave.push(data)
                    }
                }
            }

            if (nobook == null || nobook == '') {
                notifalert('Nomor Booking');
            } else if (datebook == null || datebook == '') {
                notifalert('Date Booking');
            } else if (myetd == null || myetd == '') {
                notifalert('ETD (Estimated Time Departure)');
            } else if (myeta == null || myeta == '') {
                notifalert('ETA (Estimated Time Arrival)');
            } else if (mode == null || mode == '') {
                notifalert('Ship Mode');
            } else if (mode == 'fcl' && myfcl == '') {
                notifalert('FCL');
            } else if (mode == 'fcl' && myweight == '') {
                notifalert('Weight');
            } else if (mode == 'fcl' && fclvol == '') {
                notifalert('Volume');
            } else if (mode == 'lcl' && mylcl == '') {
                notifalert('LCL');
            } else if (mode == 'lcl' && lclweight == '') {
                notifalert('LCL Weight');
            } else if (mode == 'air' && myair == '') {
                notifalert('AIR');
            } else if (mode == 'air' && airweight == '') {
                notifalert('AIR Weight');
            } else if (mode == 'cfscy' && cfscyvol == '') {
                notifalert('CSF/CY Volume');
            } else if (mode == 'cfscy' && cfscyweight == '') {
                notifalert('CSF/CY Weight');
            } else if (myroute == null || myroute == '') {
                notifalert('Route');
            } else if (portloading == null || portloading == '') {
                notifalert('Port Of Loading');
            } else if (portdestination == null || portdestination == '') {
                notifalert('Port Of Destination');
            } else if (package == null || package == '') {
                notifalert('Package');
            } else {
                $.ajax({
                    type: "post",
                    url: "<?php echo e(route('formposave')); ?>",
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
                    beforeSend: function(param) {
                        Swal.fire({
                            title: 'Saving ...',
                            html: 'Please Wait Data Was Saving',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            onOpen: () => {
                                swal.showLoading();
                            }
                        })
                    },
                    success: function(response) {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            (response.status == 'success') ? window.location
                                .replace("<?php echo e(route('page_po')); ?>"):
                                ''
                            $('#btnsubmit').html('Submit')
                            $('#btnsubmit').prop('disabled', false)
                            swal.close();
                        });
                        return;
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Unsuccessfully Saved Data',
                            text: 'Check Your Data',
                            type: 'error'
                        });
                        $('#btnsubmit').html('Submit')
                        $('#btnsubmit').prop('disabled', false)
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
            $('#btnsubmit').html('Submit')
            $('#btnsubmit').prop('disabled', false)
            return;
        }
    });
</script>
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/dashboard/modal_listpo.blade.php ENDPATH**/ ?>