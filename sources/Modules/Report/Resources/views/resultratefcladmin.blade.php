@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-body">
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
                            <tr>
                                <td>q</td>
                                <td>w</td>
                                <td>e</td>
                                <td>r</td>
                                <td>t</td>
                                <td>y</td>
                                <td>u</td>
                                <td>i</td>
                                <td>o</td>
                                <td>p</td>
                            </tr>
                        </tbody>
                    </table>
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
        });
    </script>
@endsection
