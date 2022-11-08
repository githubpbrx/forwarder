<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal">
        {{ csrf_field() }}
        {{-- {{ dd($data) }} --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">PO Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nomorpo" name="nomorpo"
                            value="@foreach ($mypo as $po) {{ $po->pono . ',' }} @endforeach" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Supplier</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="supplier" name="supplier"
                            value="@foreach ($mypo as $po) {{ $po->nama . ',' }} @endforeach" readonly>
                    </div>
                </div>
            </div>
            {{-- <input type="text" class="form-control" id="nomorpo" name="nomorpo" readonly> --}}
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-12">
                @foreach ($data as $key => $item)
                    <div class="card card-default collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">{{ date('d F Y', strtotime($item[0]->pideldate)) }}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <table border="1">
                                            <thead>
                                                <th>PO Nomor</th>
                                                <th>Material</th>
                                                <th>Material Description</th>
                                                <th>HS Code</th>
                                                <th>Color Code</th>
                                                <th>Size</th>
                                                <th>Quantity Item</th>
                                                <th>Status</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($item as $key2 => $dat)
                                                    <tr>
                                                        <td>{{ $dat->po_nomor }}</td>
                                                        <td>{{ $dat->matcontents }}</td>
                                                        <td>{{ $dat->itemdesc }}</td>
                                                        <td>{{ 'empty' }}</td>
                                                        <td>{{ $dat->colorcode }}</td>
                                                        <td>{{ $dat->size }}</td>
                                                        <td>{{ $dat->qtypo }}</td>
                                                        <td>{{ $dat->statusforwarder }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" />
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Booking Number</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nobook" name="nobook" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Date Booking</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="datebook" name="datebook" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETD (Estimated Time Departure)</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="etd" name="etd" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">ETA (Estimated Time Arrival)</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="eta" name="eta" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Ship Mode</label>
                    <div class="col-sm-12">
                        <select class="form-control select2" style="width: 100%;" name="shipmode" id="shipmode">
                            <option value="-1" selected disabled>-- Choose Mode --</option>
                            <option value="fcl">FCL</option>
                            <option value="lcl">LCL</option>
                            <option value="air">Air</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="datafcl" style="display: none">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">FCL</label>
                                <select class="form-control select2" style="width: 100%;" name="fclku"
                                    id="fclku">
                                    <option value="20">20"</option>
                                    <option value="40">40"</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Weight</label>
                                <input type="number" min="0" class="form-control" name="weight"
                                    id="weight" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="datalcl" style="display: none">
                    <label class="col-sm-12 control-label">LCL</label>
                    <div class="col-sm-12">
                        {{-- <select class="select2" style="width: 100%;" name="lclku" id="lclku">
                        <option value="cbm">CBM</option>
                    </select> --}}
                        <div class="input-group">
                            <input type="number" min="0" class="form-control" name="lclku" id="lclku"
                                autocomplete="off">
                            <div class="input-group-append">
                                <span class="input-group-text">CBM</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="dataair" style="display: none">
                    <label class="col-sm-12 control-label">AIR</label>
                    <div class="col-sm-12">
                        {{-- <select class="select2" style="width: 100%;" name="airku" id="airku">
                        <option value="kg">KG</option>
                    </select> --}}
                        <div class="input-group">
                            <input type="number" min="0" class="form-control" name="airku" id="airku"
                                autocomplete="off">
                            <div class="input-group-append">
                                <span class="input-group-text">KG</span>
                            </div>
                        </div>
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
        var dataku = @JSON($data);

        $('#etd').prop('disabled', true);
        $('#eta').prop('disabled', true);
        $('#datebook').datepicker({
            changeYear: true,
            changeMonth: true,
            minDate: 0,
            dateFormat: "yy-m-dd",
            yearRange: "-100:+20",
        });

        $('#datebook').change(function() {
            date1 = $('#datebook').val();
            $('#etd').prop('disabled', false);

            $('#etd').datepicker({
                changeYear: true,
                changeMonth: true,
                minDate: date1,
                dateFormat: "yy-m-dd",
                yearRange: "-100:+20",
            });

            $('#etd').change(function(e) {
                date2 = $('#etd').val();
                $('#eta').prop('disabled', false);

                $('#eta').datepicker({
                    changeYear: true,
                    changeMonth: true,
                    minDate: date2,
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
            console.log('training :>> ', mode);
            console.log('object :>> ', 'klik');
            if (mode == 'fcl') {
                $('#datafcl').show()
                $('#datalcl').hide()
                $('#dataair').hide()

            } else if (mode == 'lcl') {
                $('#datalcl').show()
                $('#datafcl').hide()
                $('#dataair').hide()
            } else {
                $('#dataair').show()
                $('#datafcl').hide()
                $('#datalcl').hide()
            }
        });

        $('#btnsubmit').click(function(e) {
            $('#btnsubmit').html('<i class="fas fa-hourglass"></i> Please Wait')
            $('#btnsubmit').prop('disabled', true)
            let nobook = $('#nobook').val();
            let datebook = $('#datebook').val();
            let myetd = $('#etd').val();
            let myeta = $('#eta').val();
            let mode = $('#shipmode').val();
            let myfcl = $('#fclku').val();
            let myweight = $('#weight').val();
            let mylcl = $('#lclku').val();
            let myair = $('#airku').val();

            var arraysave = [];
            for (let index = 0; index < dataku.length; index++) {
                for (let index2 = 0; index2 < dataku[index].length; index2++) {
                    let val = {
                        'idforwarder': dataku[index][index2].id_forwarder,
                        'idpo': dataku[index][index2].idpo,
                        'idmasterfwd': dataku[index][index2].idmasterfwd,
                        'pono': dataku[index][index2].po_nomor,
                    };
                    arraysave.push(val)
                }
            }

            if (nobook == null || nobook == '') {
                notifalert('Nomor Booking');
            } else if (datebook == null || datebook == '') {
                notifalert('Date Booking');
            } else if (myetd == null || myetd == '') {
                notifalert('ETD (Estimate Delivery Date)');
            } else if (myeta == null || myeta == '') {
                notifalert('ETA (Estimate Acutal Delivery Date)');
            } else if (mode == null || mode == '') {
                notifalert('Ship Mode');
            } else if (mode == 'fcl' && myfcl == '') {
                notifalert('FCL');
            } else if (mode == 'fcl' && myweight == '') {
                notifalert('Weight');
            } else if (mode == 'lcl' && mylcl == '') {
                notifalert('LCL');
            } else if (mode == 'air' && myair == '') {
                notifalert('AIR');
            } else {
                $.ajax({
                    type: "post",
                    url: "{{ route('formposave') }}",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        'dataid': arraysave,
                        'nobooking': nobook,
                        'datebooking': datebook,
                        'etd': myetd,
                        'eta': myeta,
                        'shipmode': mode,
                        'fcl': myfcl,
                        'weight': myweight,
                        'lcl': mylcl,
                        'air': myair,
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log('response :>> ', response);
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            (response.status == 'success') ? window.location
                                .replace("{{ route('page_po') }}"):
                                ''
                            $('#btnsubmit').html('Submit')
                            $('#btnsubmit').prop('disabled', false)
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
    });
</script>
