@extends('templates.default')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-6 panel col-lg-offset-3">
            <div class="row panel-body">
                <p class='lead'>Password recover</p>
                <hr>
                <form class="form-vertical form-submit-normal" role="form" method="post" action="{{ route('password.reset', ['email' => $email, 'identifier' => urlencode($identifier)]) }}"
                autocomplete="off">
                    <div class="form-group form-group-lg">
                        <label for="email" class="control-label">Your email address</label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input disabled='disabled' required="required" value="{{ $email }}" name="email" type="email" class="form-control" id="email">
                        </div>
                    </div>

                    <div class="form-group form-group-lg {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="password" class="control-label">Choose a password</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class='glyphicon glyphicon-lock'></i></span>
                            <input required="required" name="password" type="password" class="form-control" id="password">
                        </div>
                        @if($errors->has('password'))
                            <span class="help-block">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group form-group-lg {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                        <label for="password" class="control-label">Password confirmation</label>
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
