@extends('system::template/masteremail')
@section('link_href')
 
@endsection

@section('content')

<br>
<p>Hi <b><i>,</i> {{$nama}}</b></p>

<p>Berikut adalah LINK untuk Aktifasi User <b>Web Forwarder</b> atau masukkan kode Token dibawah:
<center>
<a href="{{$link}}"><button style="background-color:  #6495ED; color: white; font-weight: bold; width:140px; height: 34px; border-radius: 11px"> LINK AKTIFASI </button></a>
<br><br> atau <br><b style="font-size:30pt">{{$token}}</b>
</center>
<br>
<p>Silahkan melakuakan aktifasi user sebelum menggunakan Web forwarder.
</p>
<b>Jika anda tidak request dari Web FORWARDER silahkan abaikan email ini</b>
@endsection

@section('script_src')
@endsection

@section('script')

@endsection