<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal">
        {{ csrf_field() }}
        {{-- {{ dd($data) }} --}}
        {{-- <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" /> --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    {{-- <div class="card-header">
                        <h3 class="card-title"> kk </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div> --}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <table width="100%" id="datatables" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th rowspan="3" style="vertical-align: middle; text-align: center">
                                                    Country
                                                </th>
                                                <th rowspan="3" style="vertical-align: middle; text-align: center">
                                                    POL City
                                                </th>
                                                <th rowspan="3" style="vertical-align: middle; text-align: center">
                                                    POD City
                                                </th>
                                                <th rowspan="3" style="vertical-align: middle; text-align: center">
                                                    Shipping Line
                                                </th>
                                                <th colspan="2" rowspan="2"
                                                    style="vertical-align: middle; text-align: center">
                                                    Effective Date
                                                </th>
                                                <th colspan="6" class="text-center">Rate ({{ $nama }})</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-center">Ocean Freight</th>
                                                <th colspan="3" class="text-center">LSS/Banker</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">From</th>
                                                <th class="text-center">End</th>
                                                <th class="text-center">20'</th>
                                                <th class="text-center">40'</th>
                                                <th class="text-center">40'HC</th>
                                                <th class="text-center">20'</th>
                                                <th class="text-center">40'</th>
                                                <th class="text-center">40'HC</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $data->country->country }}</td>
                                                <td>{{ $data->polcity->city }}</td>
                                                <td>{{ $data->podcity->city }}</td>
                                                <td>{{ $data->shipping->name }}</td>
                                                <td>{{ $awal }}</td>
                                                <td>{{ $akhir }}</td>
                                                <td><input id="20of" type="number" min="0"
                                                        class="form-control"></td>
                                                <td><input id="40of" type="number" min="0"
                                                        class="form-control"></td>
                                                <td><input id="40hcof" type="number" min="0"
                                                        class="form-control"></td>
                                                <td><input id="20lb" type="number" min="0"
                                                        class="form-control"></td>
                                                <td><input id="40lb" type="number" min="0"
                                                        class="form-control"></td>
                                                <td><input id="40hclb" type="number" min="0"
                                                        class="form-control"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <hr style="width: 100%; color: rgb(192, 192, 192); height: 0.5px; background-color:rgb(192, 192, 192);" /> --}}
    </form>
</div>

<div class="modal-footer" style="justify-content: space-between">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary pull-right" id="savedata">Submit</button>
</div>

<script type="text/javascript">
    $('#datatables').DataTable();

    var dataku = @JSON($data);

    $('#savedata').click(function(e) {
        console.log('kloik :>> ', 'kloik');
        $.ajax({
            type: "post",
            url: "{{ route('inputratefcl_add') }}",
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                idmappingrate: dataku.id,
                of20: $('#20of').val(),
                of40: $('#40of').val(),
                of40hc: $('#40hcof').val(),
                lb20: $('#20lb').val(),
                lb40: $('#40lb').val(),
                lb40hc: $('#40hclb').val(),
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
                        .replace("{{ route('inputratefcl') }}"):
                        ''
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
</script>
