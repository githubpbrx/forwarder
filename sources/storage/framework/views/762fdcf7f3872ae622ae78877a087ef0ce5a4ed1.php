<form action="#" class="form-horizontal">
    <?php echo e(csrf_field()); ?>

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
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tbody>
                <tr>
                    <td><?php echo e($item->matcontents); ?></td>
                    <td><?php echo e($item->itemdesc); ?></td>
                    <td><?php echo e($item->hscode); ?></td>
                    <td><?php echo e($item->colorcode); ?></td>
                    <td><?php echo e($item->size); ?></td>
                    <td><?php echo e($item->qtypo); ?></td>
                    <td><?php echo e($item->qtybook == null ? '' : $item->qtybook); ?></td>
                </tr>
            </tbody>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
    <hr style="width: 100%; height: 0.5px; background-color:rgb(192, 192, 192);" />
    <?php $__currentLoopData = $getbooking; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title"><?php echo e($item->kode_booking); ?></h3>
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
                                <input type="text" class="form-control" value="<?php echo e($item->pono); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Supplier</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="<?php echo e($item->nama); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Forwarder</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="<?php echo e($item->name); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Code Booking</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="<?php echo e($item->kode_booking); ?>" readonly>
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
                                    value="<?php echo e(date('d F Y', strtotime($item->date_booking))); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Route</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="<?php echo e($item->route_code . ' ~ ' . $item->route_desc); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Port Of Loading</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="<?php echo e($item->code_port . ' ~ ' . $item->name_port); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Port Of Destination</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="<?php echo e($item->code_port . ' ~ ' . $item->name_port); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Package</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" value="<?php echo e($item->package); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">ETD</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="<?php echo e(date('d F Y', strtotime($item->etd))); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">ETA</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="<?php echo e(date('d F Y', strtotime($item->eta))); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">Input Data</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"
                                    value="<?php echo e(date('d F Y', strtotime($item->created_at))); ?>" readonly>
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
                                    value="<?php echo e($item->updated_at == null ? '' : date('d F Y', strtotime($item->updated_at))); ?>"
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
                                        <input type="text" class="form-control" value="<?php echo e($item->shipmode); ?>"
                                            readonly>
                                    </div>
                                    <?php if($item): ?>
                                        <?php if($item->shipmode == 'fcl'): ?>
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
                                                    value="<?php echo e($fclcont); ?>" readonly>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-sm-12 control-label">Volume</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                        value="<?php echo e($fclvol); ?>" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">M3</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-sm-12 control-label">Weight</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control"
                                                        value="<?php echo e($fclkg); ?>" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Kg</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php elseif($item->shipmode == 'lcl'): ?>
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
                        <?php $__currentLoopData = $getperbooking[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tbody>
                                <tr>
                                    <td><?php echo e($item2->matcontents); ?></td>
                                    <td><?php echo e($item2->itemdesc); ?></td>
                                    <td><?php echo e($item2->hscode); ?></td>
                                    <td><?php echo e($item2->colorcode); ?></td>
                                    <td><?php echo e($item2->size); ?></td>
                                    <td><?php echo e($item2->qtypo); ?></td>
                                    <td><?php echo e($item2->qty_booking); ?></td>
                                </tr>
                            </tbody>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</form>
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/readyallocation/modalreportalokasi.blade.php ENDPATH**/ ?>