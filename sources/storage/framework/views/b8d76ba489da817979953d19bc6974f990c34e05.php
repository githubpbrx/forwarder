<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-body">
            <a href="<?php echo e(route('user_add')); ?>"><button type="button" class="btn btn-primary">Add User
                </button></a>

            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Grup Akses</th>
                        <th>Nama Finance</th>
                        <th>Nik Finance</th>
                        <th>Email Finance</th>
                        <th>Reset</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    
    <div class="modal fade" id="modal_request">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="request_title" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="request_content" class="row"></div>
                </div>
            </div>
        </div>
    </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $("#nik").change(function() {
            var nik = $("#nik").val();
            var token = $('meta[name=csrf-token]').attr('content');
            console.log('nik :' + nik);
            $.ajax({
                url: "<?php echo route('privileggetnama'); ?>",
                method: 'POST',
                data: {
                    nik: nik,
                    _token: token
                },
                success: function(data) {
                    console.log(data);
                    if (data == '-') {
                        alert('Nik Not Found');
                        $("#nik").val('');
                        $("#nama").val('');
                    } else {
                        $("#nama").val(data);
                    }
                    cache: false
                }
            });

        });


        var oTable = $('#serverside').DataTable({
            order: [],
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo e(url('privilege/user_access/privilegedata')); ?>',
                type: 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            },

            "fnCreatedRow": function(row, data, index) {
                $('td', row).eq(0).html(index + 1);
            },

            columns: [
                // data is for view, name is for real value
                {
                    data: 'privilege_id',
                    name: 'privilege_id'
                },
                {
                    data: 'privilege_user_nik',
                    name: 'privilege_user_nik'
                },
                {
                    data: 'privilege_user_name',
                    name: 'privilege_user_name'
                },
                {
                    data: 'jenis',
                    name: 'jenis'
                },
                {
                    data: 'group_access',
                    name: 'group_access'
                },
                {
                    data: 'nama_finance',
                    name: 'nama_finance'
                },
                {
                    data: 'nik_finance',
                    name: 'nik_finance'
                },
                {
                    data: 'email_finance',
                    name: 'email_finance'
                },
                {
                    data: 'reset',
                    name: 'reset',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });

        $('#serverside').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        })
    </script>
    <script>
        $(document).ready(function() {

            load_event()

            function load_event() {
                btnapi_event()
            }

            function btnapi_event() {
                $('#btn-api').click(function(e) {
                    let key = generate_token()

                    $('#privilege-api-key').val(key)
                })
            }

            function generate_token() {
                let unixtimestamps = Date.now().toString().substr(-4)

                let token = Math.random().toString(36).substr(2)

                let key = unixtimestamps + token

                return key
            }
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system::template/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\forwarder\sources\Modules/System\Resources/views/settings/privileges/user_access_list_serverside.blade.php ENDPATH**/ ?>