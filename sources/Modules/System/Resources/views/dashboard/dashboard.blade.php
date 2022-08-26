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
      <div class="card-body p-0">
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script_src')
@endsection

@section('script')

@endsection