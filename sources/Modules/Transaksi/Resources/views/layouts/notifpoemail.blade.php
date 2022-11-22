@extends('transaksi::layouts/masteremail')
@section('link_href')
@endsection

@section('content')
    <br>
    <p>Hi <b><i>,</i> {{ $nama }}</b></p>

    <p>You Got New PO <br> Please Check In <b>Web Forwarder</b></p>
    <center>
        <a href="{{ $link }}"><button
                style="background-color:  #6495ED; color: white; font-weight: bold; width:140px; height: 34px; border-radius: 11px">
                Web Forwarder </button></a>
        {{-- <br><br> or <br><b style="font-size:30pt">{{ $token }}</b> --}}
    </center>
    <br>
    {{-- <p>Please activate the user before using the Web forwarder.</p> --}}
    <b>If you don't request from Web FORWARDER, please ignore this email</b>
@endsection

@section('script_src')
@endsection

@section('script')
@endsection
