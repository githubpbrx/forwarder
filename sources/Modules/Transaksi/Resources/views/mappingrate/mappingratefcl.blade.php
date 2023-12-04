@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <button class="btn btn-primary pull-right" id="adddata">Add Data</button>
                </div>
                <div class="card-body">
                    <table id="serverside" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                {{-- <th>Country</th>
                                <th>POL City</th>
                                <th>POD City</th>
                                <th>Shipping Line</th> --}}
                                <th>Periode</th>
                                <th>Expired Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    {{-- <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalmapping">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add Data Mapping Rate FCL</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        <input type="hidden" id="idku">
                        {{ csrf_field() }}
                        {{-- <div class="col-auto">
                            <div class="form-group">
                                <label class="control-label">Name Country</label>
                                <select class="form-control select2" name="country" id="country">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-group">
                                <label class="control-label">Name POL (City)</label>
                                <select class="form-control select2" name="polcity" id="polcity" disabled>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-group">
                                <label class="control-label">Name POD (City)</label>
                                <select class="form-control select2" name="podcity" id="podcity" disabled>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-group">
                                <label class="control-label">Shipping Line</label>
                                <select class="form-control select2" name="shipping" id="shipping" disabled>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-auto" id="periodeku">
                            <div class="form-group">
                                <label class="control-label">Periode</label>
                                <select class="form-control select2" style="width: 100%;" name="periode" id="periode">
                                    <option value=""></option>
                                    @foreach ($periode as $per)
                                        <option value="{{ $per }}">{{ $per }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-group">
                                <label class="control-label">Set Date</label>
                                <input type="date" name="setdate" id="setdate" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary pull-left" id="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalinfo">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Data Mapping Rate FCL</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalinfomapping"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
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
                    url: "{{ route('list_mappingratefcl') }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'periode',
                        name: 'periode'
                    },
                    {
                        data: 'expireddate',
                        name: 'expireddate'
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

            $('#adddata').click(function(e) {
                $('#modalmapping').modal({
                    show: true,
                    backdrop: 'static'
                });
                $('#idku').val('');
                $('#country').empty();
                $("#polcity").empty().prop('disabled', true);
                $("#podcity").empty().prop('disabled', true);
                $('#shipping').empty().prop('disabled', true);
                $('#setdate').val('');
                $('#modaltitle').html('Add Data Mapping Rate FCL');
                $('#periodeku').removeClass('d-none');
            });

            $('body').on('click', '#editdata', function() {
                $('#modalmapping').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('mappingratefcl_edit') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    let dataku = data.data;
                    $('#idku').val(dataku);
                    $('#modaltitle').html('Edit Data Mapping Rate FCL');
                    $('#periodeku').addClass('d-none');
                })
            });

            $('#submit').click(function() {
                let id = $('#idku').val();
                let setdate = $('#setdate').val();

                $.ajax({
                    url: (id == null || id == '') ? "{!! route('mappingratefcl_add') !!}" :
                        "{!! route('mappingratefcl_update') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: id,
                        setdate: setdate,
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Inserting Data ...',
                            html: 'Please wait',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            backdrop: true,
                            onOpen: () => {
                                Swal.showLoading()
                            }
                        })
                    },
                    success: function(response) {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            $('#modalmapping').modal('hide');
                            // oTable.ajax.reload();
                            location.reload();
                            Swal.close();
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
            });

            $('body').on('click', '#delbtn', function() {
                let idku = $(this).attr('data-id');
                Swal.fire({
                    title: 'Validation delete data!',
                    text: 'Are you sure you want to delete the data  ?',
                    type: 'question',
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "GET",
                            url: "{!! url('transaksi/mappingratefcl/deletemappingratefcl') !!}" + "/" + idku,
                            dataType: "JSON",
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Deleting Data ...',
                                    html: 'Please wait',
                                    allowEscapeKey: false,
                                    allowOutsideClick: false,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    backdrop: true,
                                    onOpen: () => {
                                        Swal.showLoading()
                                    }
                                })
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    type: (response.status != 'error') ?
                                        'success' : 'error'
                                }).then(() => {
                                    oTable.ajax.reload();
                                    Swal.close();
                                })
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
                        return false;
                    }
                })
            });

            $('body').on('click', '#infodata', function() {
                console.log('klik :>> ', 'klik');
                $('#modalinfo').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('mappingratefcl_info') !!}",
                    type: 'POST',
                    // dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    console.log('data :>> ', data);
                    $('#modalinfomapping').html(data);
                })
            });

            $("#periode").select2({
                placeholder: "Choose Periode",
                dropdownAutoWidth: true
            });
        });
    </script>
@endsection
