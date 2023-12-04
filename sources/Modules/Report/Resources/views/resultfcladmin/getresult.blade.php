{{-- @php
    $arc = [];
@endphp
@foreach ($row as $k => $rs)
    @php
    $dtr[$rs->id_country] = [];
    $arc[] = $rs->id_country;
    $djml[$rs->id_country] = $rs->jml;
    @endphp
@endforeach --}}

{{-- @php
    $dtpol = [];
@endphp   --}}
{{-- @foreach ($rowpol as $rp)
    @php
    $dtp[$rp->id_polcity] = [];
    $dtpol[] = $rp->id_polcity;
    $jmlpol[$rp->id_polcity] = $rp->jml;
    @endphp
@endforeach --}}

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
            <th rowspan="2" colspan="2" style="vertical-align: middle; text-align: center">
                Efective Date
            </th>
            @foreach ($fwd as $item)
                <th colspan="6" class="text-center">Rate {{ $item->masterfwd->name }}</th>
            @endforeach
            <th colspan="6" class="text-center"> Best Rate</th>
        </tr>
        <tr>
            @foreach ($fwd as $item)
                <th colspan="3" class="text-center">Ocean Freight</th>
                <th colspan="3" class="text-center">LSS/Banker</th>
            @endforeach
            <th colspan="3" class="text-center">Ocean Freight</th>
            <th colspan="3" class="text-center">LSS/Banker</th>
        </tr>
        <tr>
            <th class="text-center">From</th>
            <th class="text-center">End</th>
            @foreach ($fwd as $item)
                <th class="text-center">20'</th>
                <th class="text-center">40'</th>
                <th class="text-center">40'HC</th>
                <th class="text-center">20'</th>
                <th class="text-center">40'</th>
                <th class="text-center">40'HC</th>
            @endforeach
            <th class="text-center">20'</th>
            <th class="text-center">40'</th>
            <th class="text-center">40'HC</th>
            <th class="text-center">20'</th>
            <th class="text-center">40'</th>
            <th class="text-center">40'HC</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($master as $key => $item)
            {{-- @php
                $rows = '';
                $rowspol = '';
                if (in_array($item->id_country, $arc)) {
                    if (count($dtr[$item->id_country]) == 0) {
                        $dtr[$item->id_country] = ['a' => 'ok', 'b' => 'ok'];
                        $rows = $djml[$item->id_country];
                    }
                }

                if (in_array($item->id_polcity, $dtpol)) {
                    if (count($dtp[$item->id_polcity]) == 0) {
                        $dtp[$item->id_polcity] = ['a' => 'ok', 'b' => 'ok'];
                        $rowspol = $jmlpol[$item->id_polcity];
                    }
                }
            @endphp --}}

            <tr>
                <td>{{ $item->country->country }}</td>
                <td>{{ $item->polcity->city }}</td>
                <td>{{ $item->podcity->city }}</td>
                <td>{{ $item->shipping->name }}</td>
                <td>{{ $item->periodeawal }}</td>
                <td>{{ $item->periodeakhir }}</td>
                @foreach ($fwd as $key2 => $item2)
                    <td>{{ $data[$key][$item2->id_forwarder][0]['of_20'] }}</td>
                    <td>{{ $data[$key][$item2->id_forwarder][0]['of_40'] }}</td>
                    <td>{{ $data[$key][$item2->id_forwarder][0]['of_40hc'] }}</td>
                    <td>{{ $data[$key][$item2->id_forwarder][0]['lb_20'] }}</td>
                    <td>{{ $data[$key][$item2->id_forwarder][0]['lb_40'] }}</td>
                    <td>{{ $data[$key][$item2->id_forwarder][0]['lb_40hc'] }}</td>
                @endforeach
                <td>{{ $min[$key]['minof_20']['minof20'] }} -
                    ({{ $min[$key]['minof_20']['masterfwd'] ? $min[$key]['minof_20']['masterfwd']['name'] : '' }})
                </td>
                <td>{{ $min[$key]['minof_40']['minof40'] }} -
                    ({{ $min[$key]['minof_40']['masterfwd'] ? $min[$key]['minof_40']['masterfwd']['name'] : '' }})
                </td>
                <td>{{ $min[$key]['minof_40hc']['minof40hc'] }} -
                    ({{ $min[$key]['minof_40hc']['masterfwd'] ? $min[$key]['minof_40hc']['masterfwd']['name'] : '' }})
                </td>
                <td>{{ $min[$key]['minlb_20']['minlb20'] }} -
                    ({{ $min[$key]['minlb_20']['masterfwd'] ? $min[$key]['minlb_20']['masterfwd']['name'] : '' }})
                </td>
                <td>{{ $min[$key]['minlb_40']['minlb40'] }} -
                    ({{ $min[$key]['minlb_40']['masterfwd'] ? $min[$key]['minlb_40']['masterfwd']['name'] : '' }})
                </td>
                <td>{{ $min[$key]['minlb_40hc']['minlb40hc'] }} -
                    ({{ $min[$key]['minlb_40hc']['masterfwd'] ? $min[$key]['minlb_40hc']['masterfwd']['name'] : '' }})
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

        fixedColumns: {
            leftColumns: 6
        },
    });
</script>
