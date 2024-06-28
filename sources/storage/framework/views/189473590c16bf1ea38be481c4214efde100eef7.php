<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal">
        <?php echo e(csrf_field()); ?>

        
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-12">
                <?php $__currentLoopData = $data['shipment']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title"> <?php echo e($item[0]->noinv); ?> </h3>
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
                                        <table border="1" style="width: 100%; text-align: center">
                                            <?php
                                            foreach ($item as $key2 => $val) {
                                                if ($val->lock == 1) {
                                                    $locked = 'disabled';
                                                } else {
                                                    $locked = '';
                                                }
                                            }
                                            ?>
                                            <thead>
                                                <th>Material</th>
                                                <th>Material Desc</th>
                                                <th>Hs Code</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Qty PO</th>
                                                <th>Qty Shipment</th>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2 => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                    if ($val['withformpo']['withpo']['hscode'] == null) {
                                                        $hscode = 'empty';
                                                    } else {
                                                        $hscode = $val['withformpo']['withpo']['hscode']->hscode;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo e($val['withformpo']['withpo']->matcontents); ?></td>
                                                        <td><?php echo e($val['withformpo']['withpo']->itemdesc); ?></td>
                                                        <td><?php echo e($hscode); ?></td>
                                                        <td><?php echo e($val['withformpo']['withpo']->colorcode); ?></td>
                                                        <td><?php echo e($val['withformpo']['withpo']->size); ?></td>
                                                        <td><?php echo e($val['withformpo']['withpo']->qtypo); ?></td>
                                                        <td><?php echo e($val->qty_shipment); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">Invoice</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control"
                                                            id="invoice-<?php echo e($key1); ?>" name="invoice"
                                                            value="<?php echo e($item[0]->noinv); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">BL Number</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control"
                                                            id="nomorbl-<?php echo e($key1); ?>" name="nomorbl"
                                                            value="<?php echo e($item[0]->nomor_bl); ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">ATD (Actual Time
                                                        Departure)</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"
                                                            class="form-control etd-<?php echo e($key1); ?>" name="etd"
                                                            id="etd" value="<?php echo e($item[0]->etdfix); ?>"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">ATA (Actual Time
                                                        Arrival)</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"
                                                            class="form-control eta-<?php echo e($key1); ?>" name="eta"
                                                            id="eta" value="<?php echo e($item[0]->etafix); ?>"
                                                            autocomplete="off">
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
                                                            id="vessel-<?php echo e($key1); ?>" name="vessel"
                                                            value="<?php echo e($item[0]->vessel); ?>" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">File BL</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control"
                                                            id="filebl-<?php echo e($key1); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">File Invoice</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control"
                                                            id="fileinv-<?php echo e($key1); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="col-sm-12 control-label">File Packing List</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" class="form-control"
                                                            id="filepack-<?php echo e($key1); ?>">
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
                                                            id="btnupdate-<?php echo e($key1); ?>"
                                                            <?php echo e($locked); ?>>Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
</div>

<script type="text/javascript">
    var dataku = <?php echo json_encode($data['shipment'], 15, 512) ?>;

    submit();

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

    function submit() {
        for (let index = 0; index < dataku.length; index++) {
            $('#btnupdate-' + index).click(function(e) {
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
                    let data = {
                        'idpo': dataku[index][index2]['withformpo']['idpo'],
                        'idbl': dataku[index][index2]['nomor_bl'],
                        'idshipment': dataku[index][index2]['id_shipment'],
                        'idformpo': dataku[index][index2]['idformpo'],
                    };
                    arrayku.push(data);
                }

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
                        url: "<?php echo e(route('updateshipment')); ?>",
                        processData: false,
                        contentType: false,
                        data: form_data,
                        dataType: "json",
                        beforeSend: function(param) {
                            Swal.fire({
                                title: 'Updating ...',
                                html: 'Please Wait Data Shipment Will Be Updating',
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
                                    .replace("<?php echo e(route('datashipment')); ?>"):
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

    function notifalert(params) {
        Swal.fire({
            title: 'Information',
            text: params + ' Can not be empty',
            type: 'warning'
        });
        return;
    }
</script>
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/Transaksi\Resources/views/updateshipment/modaldatashipment.blade.php ENDPATH**/ ?>