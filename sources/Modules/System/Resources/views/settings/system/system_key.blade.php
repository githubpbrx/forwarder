@extends('system::login/login_master')
@section('title', 'PT. Pan Brothers Tbk')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <b>Authentication</b>Panel
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Insert Key to Access</p>

            <form action="{{ url('app/settings/panel/auth')}}" method="post">
                {{ csrf_field() }}
                <div class="input-group mb-3">
                    <input name="authkey" type="password" class="form-control" placeholder=".." required>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-warning">Access</button>
                    </span>
                </div>
            </form>
            <a href="{{url('login')}}" class="btn btn-default">Back to Login</a>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection