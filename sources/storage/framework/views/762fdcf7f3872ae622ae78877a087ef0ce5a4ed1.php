<form action="#" class="form-horizontal">
    <?php echo e(csrf_field()); ?>

    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">PO</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="<?php echo e($data[0]->pono); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Supplier</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="<?php echo e($data[0]->nama); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Forwarder</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="<?php echo e($data[0]->name); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Code Booking</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="<?php echo e($data[0]['formpo'] == null ? '' : $data[0]['formpo']->kode_booking); ?>" readonly>
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
                        value="<?php echo e($data[0]['formpo'] == null ? '' : date('d F Y', strtotime($data[0]['formpo']->date_booking))); ?>"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Route</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="<?php echo e($data[0]['formpo'] == null ? '' : $data[0]['formpo']['route']->route_code . ' ~ ' . $data[0]['formpo']['route']->route_desc); ?>"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Port Of Loading</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="<?php echo e($data[0]['formpo'] == null ? '' : $data[0]['formpo']['loading']->code_port . ' ~ ' . $data[0]['formpo']['loading']->name_port); ?>"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Port Of Destination</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="<?php echo e($data[0]['formpo'] == null ? '' : $data[0]['formpo']['destination']->code_port . ' ~ ' . $data[0]['formpo']['destination']->name_port); ?>"
                        readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Package</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="<?php echo e($data[0]['formpo'] == null ? '' : $data[0]['formpo']->package); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">ETD</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="<?php echo e($data[0]['formpo'] == null ? '' : date('d F Y', strtotime($data[0]['formpo']->etd))); ?>"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">ETA</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="<?php echo e($data[0]['formpo'] == null ? '' : date('d F Y', strtotime($data[0]['formpo']->eta))); ?>"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Input Data</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control"
                        value="<?php echo e($data[0]['formpo'] == null ? '' : date('d F Y', strtotime($data[0]['formpo']->created_at))); ?>"
                        readonly>
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
                        value="<?php echo e($data[0]['formpo'] == null || $data[0]['formpo']->updated_at == null ? '' : date('d F Y', strtotime($data[0]['formpo']->updated_at))); ?>"
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
                            <input type="text" class="form-control"
                                value="<?php echo e($data[0]['formpo'] == null ? '' : $data[0]['formpo']->shipmode); ?>" readonly>
                        </div>
                        <?php if($data[0]['formpo']): ?>
                            <?php if($data[0]['formpo']->shipmode == 'fcl'): ?>
                                <?php
                                $exp = explode('-', $data[0]['formpo']->subshipmode);
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
                                    <input type="text" class="form-control" value="<?php echo e($fclcont); ?>" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 control-label">Volume</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" value="<?php echo e($fclvol); ?>"
                                            readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">M3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12 control-label">Weight</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" value="<?php echo e($fclkg); ?>"
                                            readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif($data[0]['formpo']->shipmode == 'lcl'): ?>
                                <?php
                                $explcl = explode('-', $data[0]['formpo']->subshipmode);
                                $lclvolexp = explode('M3', $explcl[0]);
                                $lclvol = $lclvolexp[0];
                                $explclkg = explode('KG', $explcl[1]);
                                $lclkg = $explclkg[0];
                                ?>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 control-label">Volume</label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control"
                                            value="<?php echo e($lclvol); ?>" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">M3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 control-label">Weight</label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control"
                                            value="<?php echo e($lclkg); ?>" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php
                                $expair = explode('-', $data[0]['formpo']->subshipmode);
                                $airvolexp = explode('M3', $expair[0]);
                                $airvol = $airvolexp[0];
                                $expairkg = explode('KG', $expair[1]);
                                $airkg = $expairkg[0];
                                ?>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 control-label">Volume</label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control"
                                            value="<?php echo e($airvol); ?>" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">M3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 control-label">Weight</label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control"
                                            value="<?php echo e($airkg); ?>" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
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
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tbody>
                <tr>
                    <td><?php echo e($item->matcontents); ?></td>
                    <td><?php echo e($item->itemdesc); ?></td>
                    <td><?php echo e($item->hscode); ?></td>
                    <td><?php echo e($item->colorcode); ?></td>
                    <td><?php echo e($item->size); ?></td>
                    <td><?php echo e($item->qtypo); ?></td>
                </tr>
            </tbody>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
</form>
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/readyallocation/modalreportalokasi.blade.php ENDPATH**/ ?>