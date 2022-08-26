@extends('system::settings/system/system_master')
@section('title', 'PT. Pan Brothers Tbk')

@section('content')
<div class="row p-5">
    <div class="col-12 login-logo">
        <b>Setting </b>Panel
    </div>
    
    <div class="col-6">
        <form action="{{ url('app/settings/panel')}}" method="post" >
        <div class="card">
            <div class="card-body login-card-body">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">DB_HOST</label>
                    <div class="col-sm-8">
                        <input name="DB_HOST" value="{{ $DB_HOST }}" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">DB_PORT</label>
                    <div class="col-sm-8">
                        <input name="DB_PORT" value="{{ $DB_PORT }}" type="text" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">DB_USERNAME</label>
                    <div class="col-sm-8">
                        <input name="DB_USERNAME" value="{{ $DB_USERNAME }}" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">DB_PASSWORD</label>
                    <div class="col-sm-8">
                        <input name="DB_PASSWORD" value="{{ $DB_PASSWORD }}" type="text" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body login-card-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">APP DB_DATABASE</label>
                    <div class="col-sm-8">
                        <input name="DB_DATABASE" value="{{ $DB_DATABASE }}" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">LOG DB_DATABASE</label>
                    <div class="col-sm-8">
                        <input name="DB_DATABASE_LOG" value="{{ $DB_DATABASE_LOG }}" type="text" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">API IP_ADDRESS</label>
                    <div class="col-sm-8">
                        <input name="IP_ADDRESS" value="{{ $IP_ADDRESS }}" type="text" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ url('app/settings/panel/exit') }}" class="btn btn-danger">Exit</a>
        <button type="submit" class="btn btn-warning float-right">Save</button>
        </form>
    </div>
</div>
@endsection