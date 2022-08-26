@extends('system::template/master')
@section('title', $title)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Log Activities</h3>
    </div>
    
    <div class="card-body">
        <table id="serverside" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Activity</th>
                    <th>Date Time</th>
                    <th>Duration</th>
                    <th>IP User</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>User ID</th>
                    <th>Activity</th>
                    <th>Date Time</th>
                    <th>Duration</th>
                    <th>IP User</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function() {
        var oTable = $('#serverside').DataTable({
            order: [],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ url("settings/logactivities") }}'
            },
            
            columns: [
                // data is for view, name is for real value
                {data: 'userid', name:'userid', orderable: false},
                {data: 'activity', name: 'activity', orderable: false},
                {data: 'datetime', name: 'datetime'},
                {data: 'microtime', name: 'microtime'},
                {data: 'ipcom', name: 'ipcom', orderable: false},
            ],
        });
    });
</script>
@endsection