<table width="100%" id="datatables" class="table table-bordered table-hover table-striped">
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
            <th colspan="6" class="text-center">Best Rate</th>
        </tr>
        <tr>
            <th colspan="3" class="text-center">Ocean Freight</th>
            <th colspan="3" class="text-center">LSS/Banker</th>
        </tr>
        <tr>
            <th class="text-center">20'</th>
            <th class="text-center">40'</th>
            <th class="text-center">40'HC</th>
            <th class="text-center">20'</th>
            <th class="text-center">40'</th>
            <th class="text-center">40'HC</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mapping as $key => $item)
            <tr>
                <td>{{ $item->country->country }}</td>
                <td>{{ $item->polcity->city }}</td>
                <td>{{ $item->podcity->city }}</td>
                <td>{{ $item->shipping->name }}</td>
                <td>{{ $data[$key]['bestof_20']['bestof20'] }}
                </td>
                <td>{{ $data[$key]['bestof_40']['bestof40'] }}
                </td>
                <td>{{ $data[$key]['bestof_40hc']['bestof40hc'] }}
                </td>
                <td>{{ $data[$key]['bestlb_20']['bestlb20'] }}
                </td>
                <td>{{ $data[$key]['bestlb_40']['bestlb40'] }}
                </td>
                <td>{{ $data[$key]['bestlb_40hc']['bestlb40hc'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $('#datatables').DataTable({
        // order: [],
        serverSide: false,
        processing: true,
        ordering: false,
        paging: false,
        scrollX: true,
        scrollCollapse: true,
        lengthChange: true,
        searching: false,
        autoWidth: true,
        // info: false
        scrollY: screen.height * 0.55,
    });
</script>
