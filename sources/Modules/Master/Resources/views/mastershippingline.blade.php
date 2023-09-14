@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <button class="btn btn-primary pull-right" id="adddata">Add Data</button>
                </div>
                <div class="card-body">
                    <table id="serverside" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name Country</th>
                                <th>POL City</th>
                                <th>POD City</th>
                                <th>Shipping Line</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    {{-- <a href="{{ route('dashcam') }}" type="button" class="btn btn-primary">Back</a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalshipping">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add Data Shipping Line</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        <input type="hidden" id="idku">
                        {{ csrf_field() }}
                        <div class="col-auto">
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
                                <input type="text" class="form-control" id="shipping" name="shipping"
                                    placeholder="Name Shipping Line" autocomplete="off">
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
                    url: "{{ route('list_shipping') }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'namecountry',
                        name: 'namecountry'
                    },
                    {
                        data: 'namepolcity',
                        name: 'namepolcity'
                    },
                    {
                        data: 'namepodcity',
                        name: 'namepodcity'
                    },
                    {
                        data: 'nameshipping',
                        name: 'nameshipping'
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
                $('#modalshipping').modal({
                    show: true,
                    backdrop: 'static'
                });

                $('#idku').val('');
                $('#country').empty();
                $("#polcity").empty().prop('disabled', true);
                $("#podcity").empty().prop('disabled', true);
                $('#shipping').val('');
                $('#modaltitle').html('Add Data Shipping Line');
            });

            $('#country').select2({
                placeholder: '-- Choose Country --',
                ajax: {
                    url: "{!! route('getcountry') !!}",
                    dataType: 'json',
                    delay: 500,
                    type: 'post',
                    data: function(params) {
                        var query = {
                            q: params.term,
                            page: params.page || 1,
                            _token: $('meta[name=csrf-token]').attr('content')
                        }
                        return query;
                    },
                    processResults: function(data, params) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.country,
                                    id: item.id,
                                    selected: true,
                                }
                            }),
                            pagination: {
                                more: data.to < data.total
                            }
                        };
                    },
                    cache: true
                }
            });

            $('#country').change(function(e) {
                let idcountry = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{!! route('getpolcity') !!}",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        idcountry: idcountry,
                    },
                    dataType: "JSON",
                    success: function(data) {
                        let local = localStorage.getItem("action");
                        $("#polcity").empty().prop('disabled', true);
                        let html = '<option selected disabled>-- Choose POL City --</option>'
                        for (i = 0; i < data.length; i++) {
                            html += '<option value="' + data[i].id + '">' + data[i]
                                .city + '</option>'
                        }
                        $("#polcity").html(html).select2();
                        $("#polcity").prop('disabled', false);

                        if (local) {
                            pol_city(data[0]);
                        }
                    }
                });
            });

            $('#polcity').change(function(e) {
                let idpol = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{!! route('getpodcity') !!}",
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        idpol: idpol,
                    },
                    dataType: "JSON",
                    success: function(data) {
                        let local = localStorage.getItem("action");
                        $("#podcity").empty().prop('disabled', true);
                        let html = '<option selected disabled>-- Choose POD City --</option>'
                        for (i = 0; i < data.length; i++) {
                            html += '<option value="' + data[i].id + '">' + data[i]
                                .city + '</option>'
                        }
                        $("#podcity").html(html).select2();
                        $("#podcity").prop('disabled', false);

                        if (local) {
                            pod_city(data[0]);
                        }
                        localStorage.clear();
                    }
                });
            });

            $('body').on('click', '#editdata', function() {
                $('#modalshipping').modal({
                    show: true,
                    backdrop: 'static'
                });
                let idku = $(this).attr('data-id');
                $.ajax({
                    url: "{!! route('mastershipping_edit') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: idku,
                    },
                }).done(function(data) {
                    localStorage.setItem("action", "edit");
                    let dataku = data.data;
                    $('#idku').val(dataku.id);
                    $('#country').empty().html('<option value="' + dataku.id_country + '">' + dataku
                        .country.country + '</option>').val(dataku.id_country).trigger("change");
                    $('#shipping').val(dataku.name);
                    $('#modaltitle').html('Edit Data Shipping Line');
                })
            });

            function pol_city(dataku) {
                $('#polcity').empty().html('<option value="' + dataku.id + '">' + dataku
                    .city + '</option>').val(dataku.id).trigger("change");
            }

            function pod_city(dataku) {
                $('#podcity').empty().html('<option value="' + dataku.id + '">' + dataku
                    .city + '</option>').val(dataku.id).trigger("change");
            }

            $('#submit').click(function() {
                let id = $('#idku').val();
                let country = $('#country').val();
                let polcity = $('#polcity').val();
                let podcity = $('#podcity').val();
                let nameshipping = $('#shipping').val();

                $.ajax({
                    url: (id == null || id == '') ? "{!! route('mastershipping_add') !!}" :
                        "{!! route('mastershipping_update') !!}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        id: id,
                        idcountry: country,
                        idpol: polcity,
                        idpod: podcity,
                        nameshipping: nameshipping,
                    },
                    success: function(response) {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            $('#modalshipping').modal('hide');
                            oTable.ajax.reload();
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
                            url: "{!! url('master/shipping/deleteshipping') !!}" + "/" + idku,
                            dataType: "JSON",
                            success: function(response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    type: (response.status != 'error') ?
                                        'success' : 'error'
                                }).then(() => {
                                    oTable.ajax.reload();
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
        });
    </script>
@endsection
