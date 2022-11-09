@extends('system::template/master')
@section('title', $title)
@section('link_href')
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="card" style="font-size: 10pt;">
        <div class="card-body">
            <table id="serverside" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>
                            <center>List PO#</center>
                        </th>
                        <th>
                            <center>Booking Number</center>
                        </th>
                        <th>
                            <center>Quantity PO</center>
                        </th>
                        <th>
                            <center>Status</center>
                        </th>
                        <th>
                            <center>Action</center>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- ----------------- modal content ----------------- --}}
    <div class="modal fade" id="updateshipment">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Allocation Shipment</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalkushipment"></div>
            </div>
        </div>
    </div>
    {{-- ----------------- /.modal content ----------------- --}}
@endsection

@section('script')
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var oTable = $('#serverside').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('list_shipmentprocess') }}"
                },
                columns: [{
                        data: 'listpo',
                        name: 'listpo'
                    },
                    {
                        data: 'nobook',
                        name: 'nobook'
                    },
                    {
                        data: 'qtypo',
                        name: 'qtypo'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

            // var length;
            $('body').on('click', '#updateship', function() {
                $('#updateshipment').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('form_shipmentprocess') !!}",
                    type: 'POST',
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    $('#modalkushipment').html(data);
                    // let dataku = data.data.dataku;
                    // console.log('dataku :>> ', dataku);
                    // let forwarderku = data.data.dataforwarder;

                    // length = dataku.length;
                    // $('#detailitem').empty();
                    // $('#datashipmode').empty();

                    // html =
                    //     '<table border="0" style="width:100%"><tr><th style="text-align:center"><input type="checkbox" class="checkall" style="height:18px;width:18px" checked></th><th>Material</th><th>Material Description</th><th>HS Code</th><th>Color Code</th><th>Size</th><th>Quantity Item</th><th>Remaining Quantity</th><th>Quantity Allocation</th></tr>';
                    // for (let index = 0; index < dataku.length; index++) {
                    //     let remain;
                    //     let block;
                    //     let inputalokasi;
                    //     let ceked;
                    //     // let qtypo = dataku[index].qtypo;
                    //     // let newqtypo = qtypo.replace(".", "");

                    //     if (dataku[index].qtyship == null) {
                    //         remain = dataku[index].qtypo;
                    //         inputalokasi = dataku[index].qtypo;
                    //         block = '';
                    //         ceked = 'checked';
                    //     } else if (dataku[index].qtyship == dataku[index].qtypo) {
                    //         remain = '0';
                    //         inputalokasi = '';
                    //         block = 'disabled';
                    //         ceked = '';
                    //     } else {
                    //         remain = dataku[index].qtypo - dataku[index].qtyship;
                    //         inputalokasi = dataku[index].qtypo - dataku[index].qtyship;
                    //         block = '';
                    //         ceked = 'checked';
                    //     }

                    //     html +=
                    //         '<tr><td style="text-align:center"><input type="checkbox" class="check-' +
                    //         index + '" style="height:18px;width:18px" ' + block + ' ' + ceked +
                    //         '></td><td>' +
                    //         dataku[index].matcontents + '</td><td>' + dataku[index].itemdesc +
                    //         '</td><td>' + '(kosong)' + '</td><td>' + dataku[index].colorcode +
                    //         '</td><td>' + dataku[index]
                    //         .size +
                    //         '</td><td>' + dataku[index].qtypo + '</td><td>' + remain +
                    //         '</td><td><input type="number" min="0" id="qty_allocation" name="qty_allocation" value="' +
                    //         inputalokasi + '" class="form-control trigerinput cekinput-' +
                    //         index + '" data-idpo="' + dataku[index].idpo + '"  data-idformpo="' +
                    //         dataku[index].id_formpo + '" ' + block + '></td></tr>';
                    // }

                    // html += "</table>";
                    // $('#detailitem').html(html);
                    // checkqtyall();

                    // if ((dataku[0].shipmode == 'fcl')) {
                    //     let exp = dataku[0].subshipmode.split("-");
                    //     $('#datashipmode').append(
                    //         '<div class="row"><div class="col-sm-3"><label class="control-label">Ship Mode</label><input type="text" class="form-control" value="' +
                    //         dataku[0].shipmode +
                    //         '" readonly></div><div class="col-sm-3"><label class="control-label">SubShipmode</label><input type="text" class="form-control" value="' +
                    //         exp[0] +
                    //         '&Prime;" readonly></div><div class="col-sm-3"><label class="control-label">Amount</label><input type="text" class="form-control" value="' +
                    //         exp[1] +
                    //         '" readonly></div><div class="col-sm-3"><label class="control-label">Weight</label><input type="text" class="form-control" value="' +
                    //         exp[2] + '" readonly></div></div>'
                    //     );
                    // } else {
                    //     $('#datashipmode').append(
                    //         '<div class="row"><div class="col-sm-6"><label class="control-label">Ship Mode</label><input type="text" class="form-control" value="' +
                    //         dataku[0].shipmode +
                    //         '" readonly></div><div class="col-sm-6"><label class="control-label">SubShipmode</label><input type="text" class="form-control" value="' +
                    //         dataku[0].subshipmode +
                    //         '" readonly></div></div>'
                    //     );
                    // }

                    // $('#nomorpo').val(dataku[0].pono);
                    // $('#supplier').val(dataku[0].nama);
                    // $('#nobook').val(dataku[0].kode_booking);
                    // $('#datebook').val(dataku[0].date_booking);
                    // $('#etd').val(dataku[0].etd);
                    // $('#eta').val(dataku[0].eta);
                    // $('#etdfix').val(dataku[0].etd);
                    // $('#etafix').val(dataku[0].eta);
                })
            });

            // function checkqtyall() {
            //     $('.checkall').change(function(e) {
            //         if (this.checked) {
            //             $('.trigerinput').prop('disabled', false);
            //             $('input[type="checkbox"]').prop('checked', true);
            //         } else {
            //             $('.trigerinput').val('');
            //             $('.trigerinput').prop('disabled', true);
            //             $('input[type="checkbox"]').prop('checked', false);
            //         }
            //     });

            //     for (let index = 0; index < Number(length); index++) {
            //         $('.check-' + index).change(function(e) {
            //             if (this.checked) {
            //                 console.log('objectsijine :>> ', 'isChecked');
            //                 $('.cekinput-' + index).prop('disabled', false);
            //                 // }
            //             } else {
            //                 console.log('objectsijine :>> ', 'notChecked');
            //                 $('.cekinput-' + index).val('');
            //                 $('.cekinput-' + index).prop('disabled', true);
            //             }
            //         });
            //     }
            // }
        });
    </script>
@endsection
