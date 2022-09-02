@extends('system::template/master')
@section('title', $title)
@section('link_href')

@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="text-center">DASHBOARD</h3>
                </div>
                <div class="alert alert-danger" style="background-color: rgb(253, 181, 181)">
                    {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                    <h5><i class="icon fas fa-info"></i> Notification</h5>
                    <p style="color:black">You got a new PO
                        <span class="badge badge-info">{{ $totalpo }}</span>
                        <br>
                        <a href="{{ route('page_po') }}"><button type="button"
                                class="btn btn-primary btn-xs">Process</button></a>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_src')
@endsection

@section('script')

@endsection
