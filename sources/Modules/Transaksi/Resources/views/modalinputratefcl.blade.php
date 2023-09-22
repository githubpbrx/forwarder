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
                                            @foreach ($data as $key => $dat)
                                                <tr>
                                                    <td>{{ $dat->country->country }}</td>
                                                    <td>{{ $dat->polcity->city }}</td>
                                                    <td>{{ $dat->podcity->city }}</td>
                                                    <td>{{ $dat->shipping->name }}</td>
                                                    <td>{{ $awal }}</td>
                                                    <td>{{ $akhir }}</td>
                                                    <td><input type="number" min="0"
                                                            class="form-control 20of-{{ $key }}"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control 40of-{{ $key }}"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control 40hcof-{{ $key }}"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control 20lb-{{ $key }}"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control 40lb-{{ $key }}"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control 40hclb-{{ $key }}"></td>
                                                </tr>
                                            @endforeach
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
    console.log('dataku :>> ', dataku);

    $('#savedata').click(function(e) {
        var arrayku = [];
        for (let index = 0; index < dataku.length; index++) {
            let of20 = $('.20of-' + index).val();
            let of40 = $('.40of-' + index).val();
            let of40hc = $('.40hcof-' + index).val();
            let lb20 = $('.20lb-' + index).val();
            let lb40 = $('.40lb-' + index).val();
            let lb40hc = $('.40hclb-' + index).val();

            // if (val) {
            let data = {
                'idmappingrate': dataku[index].id,
                'of20': of20,
                'of40': of40,
                'of40hc': of40hc,
                'lb20': lb20,
                'lb40': lb40,
                'lb40hc': lb40hc,
            };
            arrayku.push(data);
            // }
        }

        $.ajax({
            type: "post",
            url: "{{ route('inputratefcl_add') }}",
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                mydata: JSON.stringify(arrayku)
            },
            dataType: "json",
            beforeSend: function() {
                Swal.fire({
                    title: 'Inserting ...',
                    html: 'Please wait data was inserting!',
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
                console.log('response :>> ', response);
                Swal.fire({
                    title: response.title,
                    text: response.message,
                    type: (response.status != 'error') ? 'success' : 'error'
                }).then((result) => {
                    (response.status == 'success') ? window.location
                        .replace("{{ route('inputratefcl') }}"):
                        '';
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
</script>
