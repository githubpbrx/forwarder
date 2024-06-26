<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal" enctype="multipart/form-data">
        <?php echo e(csrf_field()); ?>

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
                            value="<?php echo e(implode(', ', $nopo)); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">PI Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                            value="<?php echo e(implode(', ', $nopi)); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Supplier</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="supplier" name="supplier"
                            value="<?php echo e(implode(', ', $supname)); ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <table border="1" width="100%" style="text-align: center">
                <thead>
                    
                    <th>Material</th>
                    <th>Material Description</th>
                    <th>HS Code</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Qty PO</th>
                    
                    <th>Qty Booking</th>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['dataku']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        if ($item->qty_booking == null) {
                            $remain = $item['withpo']->qtypo;
                            $inputalok = $item['withpo']->qtypo;
                            $block = '';
                            $ceked = 'checked';
                        } elseif ($item->qty_booking == $item['withpo']->qtypo) {
                            $remain = 0;
                            $inputalok = '';
                            $block = 'disabled';
                            $ceked = '';
                        } else {
                            $remain = $item['withpo']->qtypo - $item->qty_booking;
                            $inputalok = $item['withpo']->qtypo - $item->qty_booking;
                            $block = '';
                            $ceked = 'checked';
                        }
                        
                        if ($item['withpo']['hscode'] == null) {
                            $hscode = '';
                        } else {
                            $hscode = $item['withpo']['hscode']->hscode;
                        }
                        
                        ?>
                        <tr>
                            
                            <td data-name="mat[]"><?php echo e($item['withpo']->matcontents); ?></td>
                            <td><?php echo e($item['withpo']->itemdesc); ?></td>
                            <td><?php echo e($hscode); ?></td>
                            <td><?php echo e($item['withpo']->colorcode); ?></td>
                            <td><?php echo e($item['withpo']->size); ?></td>
                            <td><?php echo e($item['withpo']->qtypo); ?></td>
                            
                            <td><?php echo e($item->qty_booking); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            value="<?php echo e($data['dataku'][0]->kode_booking); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Date Booking</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="datebook" name="datebook"
                            value="<?php echo e($data['dataku'][0]->date_booking); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure)</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etd" name="etd"
                            value="<?php echo e($data['dataku'][0]->etd); ?>" readonly>
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
                            value="<?php echo e($data['dataku'][0]->eta); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Route</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="route" name="route"
                            value="<?php echo e($data['dataku'][0]['withroute']->route_desc); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Shipmode</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="shipmode" name="shipmode"
                            value="<?php echo e($data['dataku'][0]['shipmode']); ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $dat = $data['dataku'][0]->subshipmode;
            $exp = explode('-', $dat);
            if (count($exp) > 2) {
                $cont = $exp[0];
                $vol = $exp[1];
                $weight = explode('KG', $exp[2]);
            } else {
                $expm3 = explode('M3', $exp[0]);
                $expkg = explode('KG', $exp[1]);
            }
            ?>
            <?php if($data['dataku'][0]['shipmode'] == 'fcl'): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Container Size</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="container" id="container"
                                value="<?php echo e($cont); ?>" autocomplete="off" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Volume</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="fclvol" id="fclvol"
                                value="<?php echo e($vol); ?>" autocomplete="off" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">M3</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Weight</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="fclweight" id="fclweight"
                                value="<?php echo e($weight[0]); ?>" autocomplete="off" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">KG</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['dataku'][0]['shipmode'] == 'lcl'): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Volume</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="lclvol" id="lclvol"
                                value="<?php echo e($expm3[0]); ?>" autocomplete="off" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">M3</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Weight</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="lclweight" id="lclweight"
                                value="<?php echo e($expkg[0]); ?>" autocomplete="off" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['dataku'][0]['shipmode'] == 'air'): ?>
                <div class="col-md-4">
                    <div class="form-group">

                        <label class="control-label">Volume</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="airvol" id="airvol"
                                value="<?php echo e($expm3[0]); ?>" autocomplete="off" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">M3</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Weight</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="airweight" id="airweight"
                                value="<?php echo e($expkg[0]); ?>" autocomplete="off" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($data['dataku'][0]['shipmode'] == 'csfy'): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Volume</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="cfscyvol" id="cfscyvol"
                                value="<?php echo e($expm3[0]); ?>" autocomplete="off" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">M3</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Weight</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="cfscyweight" id="cfscyweight"
                                value="<?php echo e($expkg[0]); ?>" autocomplete="off" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">KG</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Port Of Loading</label>
                    <div class="col-sm-12">
                        <input type="text"
                            data-idpol="<?php echo e($data['dataku'][0]['withportloading']->id_portloading); ?>"
                            class="form-control" id="portloading" name="portloading"
                            value="<?php echo e($data['dataku'][0]['withportloading']->code_port . '-' . $data['dataku'][0]['withportloading']->name_port); ?>"
                            readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Port Of Destination</label>
                    <div class="col-sm-12">
                        <input type="text"
                            data-idpod="<?php echo e($data['dataku'][0]['withportdestination']->id_portdestination); ?>"
                            class="form-control" id="portdestination" name="portdestination"
                            value="<?php echo e($data['dataku'][0]['withportdestination']->code_port . '-' . $data['dataku'][0]['withportdestination']->name_port); ?>"
                            readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Package</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="package" name="package"
                            value="<?php echo e($data['dataku'][0]->package); ?>" autocomplete="off" readonly>
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
                            value="<?php echo e($data['dataku'][0]->etd); ?>" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ATA (Actual Time Arrival)<code>*</code></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etafix" name="etafix"
                            value="<?php echo e($data['dataku'][0]->eta); ?>" autocomplete="off">
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
    var dataku = <?php echo json_encode($data['dataku'], 15, 512) ?>;

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

    $('#btnsubmit').click(function(e) {
        let fclfeet = $('#container').val();
        let shipmode = $('#shipmode').val();
        let fclvol = $('#fclvol').val();
        let fclweight = $('#fclweight').val();
        let lclvol = $('#lclvol').val();
        let lclweight = $('#lclweight').val();
        let airvol = $('#airvol').val();
        let airweight = $('#airweight').val();
        let volcfscy = $('#cfscyvol').val();
        let weightcfscy = $('#cfscyweight').val();
        let pol = $('#portloading').attr('data-idpol');
        let pod = $('#portdestination').attr('data-idpod');
        let package = $('#package').val();
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
            let data = {
                'idpo': dataku[index].idpo,
                'idformpo': dataku[index].id_formpo,
                'idfwd': dataku[index].idforwarder,
                'idmasterfwd': dataku[index].idmasterfwd,
                'qty': dataku[index].qty_booking,
            };
            arrayku.push(data);
        }

        var vol;
        var updateweight;
        if (shipmode == 'fcl') {
            vol = fclvol;
            updateweight = fclweight;
        } else if (shipmode == 'lcl') {
            vol = lclvol;
            updateweight = lclweight;
        } else if (shipmode == 'air') {
            vol = airvol;
            updateweight = airweight;
        } else if (shipmode == 'cfscy') {
            vol = volcfscy;
            updateweight = weightcfscy;
        }

        let form_data = new FormData();
        form_data.append('dataid', JSON.stringify(arrayku));
        form_data.append('shipmode', shipmode);
        form_data.append('volume', vol);
        form_data.append('updateweight', updateweight);
        form_data.append('fclfeet', fclfeet);
        form_data.append('portloading', pol);
        form_data.append('portdestination', pod);
        form_data.append('package', package);
        form_data.append('nomorbl', nomorbl);
        form_data.append('noinv', noinv);
        form_data.append('vessel', vessel);
        form_data.append('filebl', filebl);
        form_data.append('fileinv', fileinv);
        form_data.append('filepack', filepack);
        form_data.append('etdfix', etdfix);
        form_data.append('etafix', etafix);

        if (filebl == null || filebl == '') {
            notifalert('File BL');
        } else if (noinv == null || noinv == '') {
            notifalert('Invoice Number');
        } else if (nomorbl == null || nomorbl == '') {
            notifalert('BL Number');
        } else if (vessel == null || vessel == '') {
            notifalert('Vessel');
        } else if (etdfix == null || etdfix == '') {
            notifalert('ATD Fix');
        } else if (etafix == null || etafix == '') {
            notifalert('ATA Fix');
        } else {
            $.ajax({
                type: "post",
                url: "<?php echo e(route('saveshipmentprocess')); ?>",
                processData: false,
                contentType: false,
                data: form_data,
                dataType: "json",
                beforeSend: function(param) {
                    Swal.fire({
                        title: 'Saving Shipment',
                        html: 'Please Wait Data Will Be Save',
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
                        swal.close();
                        (response.status == 'success') ? window.location
                            .replace("<?php echo e(route('process_shipment')); ?>"):
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
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/Transaksi\Resources/views/outstandingshipment/modalshipment.blade.php ENDPATH**/ ?>