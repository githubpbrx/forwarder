@extends('system::template/master')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="row" style="font-size: 10pt;">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="text-center">REKAPITULASI FORM FCL RATE PB</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <div class="col-auto">
                            <label>Periode:</label>
                            <select class="form-control select2" style="width: 100%;" name="periode" id="periode">
                                <option value="" selected disabled>-- Select Periode --</option>
                                @foreach ($periode as $per)
                                    @php
                                        $perawal = date('d M Y', strtotime($per->periodeawal));
                                        $perakhir = date('d M Y', strtotime($per->periodeakhir));
                                    @endphp
                                    <option value="{{ $per->periodeawal . '/' . $per->periodeakhir }}">
                                        {{ $perawal . ' - ' . $perakhir }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto mt-4">
                            <button class="btn btn-success form-control mt-1" type="button" id="btnview">View</button>
                        </div>
                        <div id="download" class="col-auto ml-auto mt-4 d-none">
                            <a href="{{ url('report/bestratefcl/getexcel') }}" type="button"
                                class="btn btn-warning form-control">Download Excel</a>
                        </div>
                    </div>
                    {{-- <div class="d-flex justify-content-between mb-3">
                        <label for="">Priode : 01 September 2023 - 30 September 2023</label>
                        <a href="{{ route('getexcelresult') }}" type="button" class="btn btn-success" id="getexcel"
                            target="_BLANK">
                            <i class="fas fa-file-excel"> Download Excel </i>
                        </a href="#">
                    </div> --}}
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

            $('#periode').select2({
                dropdownAutoWidth: true,
                width: 'auto'
            });

            $('#btnview').click(function(e) {
                let periode = $('#periode').val();
                $.ajax({
                    type: "POST",
                    url: "{!! route('getbestrate') !!}",
                    data: {
                        periode: periode
                    },
                    // dataType: "json",
                    success: function(response) {
                        $('#kontent').html(response);
                        $('#download').removeClass('d-none');
                    }
                });
            });

        });
    </script>
@endsection
