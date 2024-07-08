<form action="#" class="form-horizontal">
    <?php echo e(csrf_field()); ?>

    <div class="row">
        <?php
        $arraypo = [];
        $arraysup = [];
        foreach ($posup as $key => $lue) {
            array_push($arraypo, $lue->pono);
            array_push($arraysup, $lue->nama);
        }
        ?>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label">PO</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                        value="<?php echo e(implode(', ', $arraypo)); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-12 control-label">Supplier</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="supplier" name="supplier"
                        value="<?php echo e(implode(', ', $arraysup)); ?>" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Code Booking</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e($data[0]->kode_booking); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="row">
                        <?php if($data[0]->shipmode == 'fcl'): ?>
                            <?php $__currentLoopData = $getfcl; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $fclvol = explode('M3', $item->volume);
                                $fclweight = explode('KG', $item->weight);
                                // dd($item, $fclvol[0], $fclweight[0]);
                                ?>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label class="col-sm-12 control-label">Ship Mode</label>
                                        <input type="text" class="form-control" id="shipmode" name="shipmode"
                                            value="<?php echo e($data[0]->shipmode); ?>" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="col-sm-12 control-label">Container Size</label>
                                        <input type="text" class="form-control" value="<?php echo e($item->containernumber); ?>"
                                            readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="col-sm-12 control-label">Volume</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="<?php echo e($fclvol[0]); ?>"
                                                readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">M3</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label">Number Of Container</label>
                                        <input type="number" class="form-control"
                                            value="<?php echo e($item->numberofcontainer); ?>" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="col-sm-12 control-label">Weight</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="<?php echo e($fclweight[0]); ?>"
                                                readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php
                            $explode = explode('-', $data[0]->subshipmode);
                            $volexp = explode('M3', $explode[0]);
                            $vol = $volexp[0];
                            $expkg = explode('KG', $explode[1]);
                            $kg = $expkg[0];
                            ?>
                            <div class="col-sm-2">
                                <label class="col-sm-12 control-label">Ship Mode</label>
                                <input type="text" class="form-control" id="shipmode" name="shipmode"
                                    value="<?php echo e($data[0]->shipmode); ?>" readonly>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-12 control-label">Volume</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control"
                                        value="<?php echo e($vol); ?>" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">M3</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-12 control-label">Weight</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control"
                                        value="<?php echo e($kg); ?>" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Invoice</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e($mydata ? $mydata[0]->noinv : ''); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Vessel</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e($mydata ? $mydata[0]->vessel : ''); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Route</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="route" name="route"
                        value="<?php echo e($data[0]->route_code . ' ~ ' . $data[0]->route_desc); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Port Of Loading</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="route" name="route"
                        value="<?php echo e($data[0]->loadingcode . ' ~ ' . $data[0]->loadingname); ?>" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Port Of Destination</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="route" name="route"
                        value="<?php echo e($data[0]->destinationcode . ' ~ ' . $data[0]->destinationname); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Package</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="route" name="route"
                        value="<?php echo e($data[0]->package); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">ATD</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e($mydata ? ($mydata[0]->etdfix == null ? '' : date('d F Y', strtotime($mydata[0]->etdfix))) : ''); ?>"
                         readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">ATA</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e($mydata ? ($mydata[0]->etafix == null ? '' : date('d F Y', strtotime($mydata[0]->etafix))) : ''); ?>"
                         readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">BL Number</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e(!$mydata ? '' : $mydata[0]->nomor_bl); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Date Booking</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e(date('d F Y', strtotime($data[0]->date_booking))); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Input Data</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e($dateku->created_at == null ? '' : date('d F Y H:i:s', strtotime($dateku->created_at))); ?>"
                        readonly>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="col-sm-12 control-label">Update Data</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kodebook" name="kodebook"
                        value="<?php echo e($dateku->updated_at == null ? '' : date('d F Y H:i:s', strtotime($dateku->updated_at))); ?>"
                        readonly>
                </div>
            </div>
        </div>
    </div>
    <hr style="width: 100%; height: 0.1px; background-color:rgb(145, 139, 139);" />
    <table class="form-horizontal" border="1" style="width:100%">
        <thead>
            <tr>
                <th>Material</th>
                <th>Material Desc</th>
                <th>HS Code</th>
                <th>Color</th>
                <th>Size</th>
                <th>Qty PO</th>
                <th>Qty Ship</th>
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
                    <td><?php echo e($item->qty_shipment); ?></td>
                </tr>
            </tbody>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
</form>
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/modalreportfwd.blade.php ENDPATH**/ ?>