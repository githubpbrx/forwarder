@extends('system::template/master')
@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-body">

            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>List PO#</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="formulir_po">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Formulir PO</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Nomor PO</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Nomor Booking</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="booking" name="booking">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Date Booking</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="booking" name="booking">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">ETD (Estimate Delivery Date)</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="etd" name="etd">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">ETA (Estimate Acutal Delivery Date)</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="eta" name="eta">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Ship Mode</label>
                                <div class="col-sm-12">
                                    <select class="select2" style="width: 100%;" name="shipmode" id="shipmode">
                                        <option value="fcl">FCL</option>
                                        <option value="lcl">LCL</option>
                                        <option value="air">Air</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-none" id="fcl_none">
                                <label class="col-sm-12 control-label">FCL</label>
                                <div class="col-sm-12">
                                    <select class="select2" style="width: 100%;" name="fclku" id="fclku">
                                        <option value="20">20"</option>
                                        <option value="40">40"</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- ----------------- /.modal content ----------------- --}}
@endsection

@section('script')
    <script type="text/javascript">
        // $('#shipmode').on("select2:select", function(e) {
        //     console.log('object :>> ', $this.val());
        //     console.log('object :>> ', 'klik');
        // });
        // $('#select2-shipmode-container').change(function(e) {
        // });
        var oTable = $('#serverside').DataTable({
            order: [],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list_po') }}"
            },
            columns: [{
                    data: 'listpo',
                    name: 'listpo'
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

        $('body').on('click', '#formpo', function() {
            // console.log('object :>> ', 'klik');
            // $('#modal-detail').modal('show');
            $('#formulir_po').modal({
                show: true,
                backdrop: 'static'
            });

            let idku = $(this).attr('data-id');
            $.ajax({
                url: "{!! route('form_po') !!}",
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    id: idku,
                },
            }).done(function(data) {
                let dataku = data.data;
                let poku = dataku.datapo;

                $('#nomorpo').val(poku.pono);
            })
        });
    </script>
@endsection
