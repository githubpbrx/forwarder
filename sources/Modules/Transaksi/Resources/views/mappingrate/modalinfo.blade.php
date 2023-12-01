<div class="modal-body" style="font-size: 10pt;">
    <form action="#" class="form-horizontal">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12">
                {{-- <div class="card card-default"> --}}
                {{-- <div class="card-body"> --}}
                {{-- <div class="row"> --}}
                {{-- <div class="col-12"> --}}
                {{-- <div class="form-group"> --}}
                <table width="100%" id="datatables" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Country</th>
                            <th>POL City</th>
                            <th>POD City</th>
                            <th>Shipping Line</th>
                            <th>Periode</th>
                            <th>Expired Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $dat)
                            <tr>
                                <td>{{ $dat->country->country }}</td>
                                <td>{{ $dat->polcity->city }}</td>
                                <td>{{ $dat->podcity->city }}</td>
                                <td>{{ $dat->shipping->name }}</td>
                                @php
                                    $periodeawal = date('d M Y', strtotime($dat->periodeawal));
                                    $periodeakhir = date('d M Y', strtotime($dat->periodeakhir));
                                    $expired = date('d M Y', strtotime($dat->expired_date));
                                @endphp
                                <td>{{ $periodeawal }} - {{ $periodeakhir }}</td>
                                <td>{{ $expired }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </form>
</div>

{{-- <div class="modal-footer" style="justify-content: space-between">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
</div> --}}

<script type="text/javascript">
    $('#datatables').DataTable({
        "ordering": false,
        "paging": false,
        "info": false,
        "searching": false,
        "scrollY": '40vh',
        "scrollX": false,
        "scrollCollapse": true,
    });
</script>
