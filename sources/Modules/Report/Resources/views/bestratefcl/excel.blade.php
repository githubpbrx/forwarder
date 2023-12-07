<?php
header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename=Report Best Rate FCL_ ' . date('d-m-Y') . '.xlsx');
header('Pragma: no-cache');
header('Expires: 0');

?>
<style type="text/css">
    table {
        margin: 20px auto;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid #3c3c3c;
        padding: 3px 8px;

    }
</style>

<table>
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
            <th colspan="6" class="text-center">Best Rate</th>
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
        @foreach ($mapping as $key => $item)
            <tr>
                <td>{{ $item->country->country }}</td>
                <td>{{ $item->polcity->city }}</td>
                <td>{{ $item->podcity->city }}</td>
                <td>{{ $item->shipping->name }}</td>
                <td>{{ $item->periodeawal }}</td>
                <td>{{ $item->periodeakhir }}</td>
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
