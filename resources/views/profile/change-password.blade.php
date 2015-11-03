@extends('templates.default')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-6 panel col-lg-offset-3">
            <div class="panel-body row">
                <p class='lead'>Change password</p>
                <hr>
                <form class="form-vertical form-submit-normal" role="form" method="post" action="{{ route('profile.change-password') }}"
                autocomplete="off">
                    <div class="form-group form-group-lg">
                        <label for="email" class="control-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input disabled='disabled' value="{{ Auth::user()->email }}" name="email" type="email" class="form-control" id="email">
                        </div>
                    </div>
                    <div class="form-group form-group-lg {{ $errors->has('current_password') ? 'has-error' : '' }}">
                        <label for="current_password" class="control-label">Current password</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class='glyphicon glyphicon-lock'></i></span>
                            <input required="required" name="current_password" type="password" class="form-control" id="current_password">
                        </div>
                        @if($errors->has('current_password'))
                            <span class="help-block">{{ $errors->first('current_password') }}</span>
                        @endif
                    </div>
                    <div class="form-group form-group-lg {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="password" class="control-label">New password</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class='glyphicon glyphicon-lock'></i></span>
                            <input required="required" name="password" type="password" class="form-control" id="password">
                        </div>
                        @if($errors->has('password'))
                            <span class="help-block">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group form-group-lg {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                        <label for="password_confirmation" class="control-label">New password confirmation</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class='glyphicon glyphicon-lock'></i></span>
                            <input required="required" name="password_confirmation" type="password" class="form-control" id="password_confirmation">
                        </div>
                        @if($errors->has('password_confirmation'))
                            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-default btn-lg">Change password</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                </form>
            </div>
        </div>
    </div>
</div>

@stop
