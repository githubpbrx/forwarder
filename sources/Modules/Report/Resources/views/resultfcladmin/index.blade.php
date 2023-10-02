@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-auto">
                            <label>Year:</label>
                            <select class="form-control select2" style="width: 100%;" name="year" id="year">
                                <option value="" selected disabled>-- Select Year --</option>
                                @foreach ($year as $yr)
                                    <option value="{{ $yr }}">{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <label>Month:</label>
                            <select class="form-control select2" style="width: 100%;" name="month" id="month">
                                <option value="" selected disabled>-- Select Month --</option>
                                @foreach ($month as $key => $mt)
                                    <option value="{{ $key }}">{{ $mt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto mt-4">
                            <button class="btn btn-success form-control mt-1" type="button" id="btnview">View</button>
                        </div>
                    </div>
                    <div id="kontent"></div>
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

            $('#year').select2();
            $('#month').select2();

            $('#btnview').click(function(e) {
                let year = $('#year').val();
                let month = $('#month').val();
                console.log('year :>> ', year);
                console.log('month :>> ', month);
                $.ajax({
                    type: "POST",
                    url: "{!! route('getreport') !!}",
                    data: {
                        year: year,
                        month: month
                    },
                    // dataType: "json",
                    success: function(response) {
                        $('#kontent').html(response);
                        console.log('response :>> ', response);
                    }
                });
            });
        });
    </script>
@endsection
