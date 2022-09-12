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
                @if ($datauser->privilege_group_access_id == '1')
                    <div class="alert alert-danger" style="background-color: rgb(253, 181, 181)">
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                        <h5><i class="icon fas fa-info"></i> Notification</h5>
                        <p style="color:black">You got a new PO
                            <span class="badge badge-info">{{ $totalpo }}</span>
                            <br>
                            <a href="{{ route('page_po') }}"><button type="button"
                                    class="btn btn-primary btn-xs">Process</button></a>
                        </p>
                        <p style="color:black">You got a new Update Shipment
                            <span class="badge badge-info">{{ $totalconfirm }}</span>
                            <br>
                            <a href="{{ route('page_update') }}"><button type="button"
                                    class="btn btn-primary btn-xs">Process</button></a>
                        </p>
                    </div>
                @else
                    @if ($totalapproval >= 1)
                        <div class="alert alert-danger" style="background-color: rgb(247, 195, 195)">
                            {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                            <h5><i class="icon fas fa-info"></i> Notification</h5>
                            <p style="color:black">You got a new Approval
                                <span class="badge badge-info">{{ $totalapproval }}</span>
                                <br>
                                <a href="{{ route('page_approval') }}"><button type="button"
                                        class="btn btn-primary btn-xs">Process</button></a>
                            </p>
                        </div>
                    @endif
                    @if ($totalcoc >= 1)
                        <div class="alert alert-danger" style="background-color: rgb(247, 195, 195)">
                            <h5><i class="icon fas fa-info"></i> Notification</h5>
                            <p style="color:black">You got a new Approval CODE OF CONDUCT (CoC)
                                <span class="badge badge-info">{{ $totalcoc }}</span>
                                <br>
                                <a href="{{ route('page_coc') }}"><button type="button"
                                        class="btn btn-primary btn-xs">Process</button></a>
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

@endsection

@section('script_src')
@endsection

@section('script')

@endsection
