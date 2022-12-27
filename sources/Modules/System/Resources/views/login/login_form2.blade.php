@extends('system::login/login_master')
@section('title', 'PT. Pan Brothers Tbk')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('adminlte/index2.html') }}"> <b>PB</b> Login</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                @php
                    $login_chance = Session::get('login_chance');
                    if (Session::has('login_chance')) {
                        $chance = $login_chance['chance'];
                        $time = $login_chance['time_start'];
                    } else {
                        $chance = 5;
                        $time = 0;
                    }

                    if (Session::has('time_chance')) {
                        $time_chance = date('i:s', Session::get('time_chance'));
                    } else {
                        $time_chance = '00:00';
                    }
                @endphp
                {{-- {{date('H:i:s', strtotime($time))}} --}}
                {{-- {{$time_chance}} --}}
                @if ($chance > 0)
                    <p class="login-box-msg">Login to access </p>
                    <form action="{{ url('loginitgoaction') }}" method="post">
                        {{ csrf_field() }}
                        <label for="">Username</label>
                        <div class="input-group mb-3">
                            <input name="nik" type="text" class="form-control" placeholder="Username" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>

                        <label for="">Password</label>
                        <div class="input-group mb-3">
                            <input name="password" type="password" class="form-control" placeholder="Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-4">
                                <button type="submit" class="btn btn-info btn-block">Login</button>
                            </div>
                            <div class="col-12 mb-4">
                                <a href="{{ url('forgotpassword') }}" class="btn btn-danger btn-block">Forgot Password</a>
                            </div>
                        </div>
                    </form>
                @else
                    <h1 id="time_remaining" class="text-center"></h1>
                    {{-- --}}
                @endif

            </div>
        </div>
    </div>
    @if (Session::has('notify'))
        @php
            $notify = Session::get('notify');
        @endphp
        <div class="modal fade" id="modal_notify">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Pengumuman</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-footer p-1 mb-2">
                            <code>
                                {!! $notify['desc'] !!}
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
        function chance() {
            $.ajax({
                url: '{{ url('loginChance') }}',
                success: function(data) {
                    console.log(data);
                },
            });
        }

        $(function() {
            //Initialize Select2 Elements
            $('#modal_notify').modal('show');

            @if ($chance <= 0)
                var timer2 = '{{ $time_chance }}';
                var interval = setInterval(function() {

                    var timer = timer2.split(':');
                    //by parsing integer, I avoid all extra string processing
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);
                    --seconds;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    if (minutes < 0) clearInterval(interval);
                    seconds = (seconds < 0) ? 59 : seconds;
                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                    //minutes = (minutes < 10) ?  minutes : minutes;

                    if (minutes == 0 && seconds == 0) {
                        window.location.href = "{{ url('login') }}";
                    }

                    $('#time_remaining').html(minutes + ':' + seconds);
                    timer2 = minutes + ':' + seconds;
                }, 1000);
            @endif
        });
    </script>
@endsection
