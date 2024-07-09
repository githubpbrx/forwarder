<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" style="width:100%">
        <thead>
            <tr>
                <th>
                    <center>NO</center>
                </th>
                <th>
                    <center>PO</center>
                </th>
                <th>
                    <center>Date</center>
                </th>
                <th>
                    <center>Amount</center>
                </th>
                <th>
                    <center>Supplier</center>
                </th>
                <th>
                    <center>Status</center>
                </th>
                <th>
                    <center>Action</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
                $no = 1;
            ?>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    if ($item->postatus == null) {
                        $stat = '';
                    } else {
                        $dt = $item->postatus->pluck('statusconfirm');
                        // dd($dt);
                        $col = collect($dt);
                        $arr = $col->toArray();
                        $uq = array_unique($arr);
                        if (count($uq) == 1) {
                            if ($uq[0] == null) {
                                $stat = 'Un Processed';
                            } else {
                                $stat = $uq[0];
                            }
                        } else {
                            $stat = 'On Going';
                        }
                    }
                ?>
                <tr>
                    <td><?php echo e($no++); ?></td>
                    <td><?php echo e($item->pono); ?></td>
                    <td><?php echo e(date('Y/m/d', strtotime($item->podate))); ?></td>
                    <td><?php echo e(round($item->amount, 3) . ' ' . $item->curr); ?></td>
                    <td><?php echo e($item->nama); ?></td>
                    <td><?php echo e($stat); ?></td>
                    <td>a</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH D:\laragon\www\forwarder\sources\Modules/Report\Resources/views/outstandingpo/datatable.blade.php ENDPATH**/ ?>