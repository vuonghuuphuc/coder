@extends('templates.default')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-6 panel col-lg-offset-3">
            <div class="row panel-body">
                <p class='lead'>Sign up</p>
                <hr>
                <form class="form-vertical form-submit-normal" role="form" method="post" action="{{ route('auth.signup') }}"
                autocomplete="off">
                    <div class="form-group form-group-lg {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="email" class="control-label">Your email address</label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input required="required" value="{{ Request::old('email') ?: '' }}" name="email" type="email" class="form-control" id="email">
                        </div>
                        @if($errors->has('email'))
                            <span class="help-block">{{ $errors->first('email') }}</span>
                        @endif
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
                    <div class="form-group form-group-lg {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                        <div class="g-recaptcha" data-sitekey="{{ Config::get('site.recaptcha_key') }}"></div>
                        @if($errors->has('g-recaptcha-response'))
                            <span class="help-block">The reCAPTCHA field is required.</span>
                        @endif
                    </div>
                    <div class="form-group form-group-lg">
                        <button type="submit" class="btn btn-default btn-lg">Sign up</button>
                        <a href="{{ route('auth.signin') }}"  class="btn btn-link pull-right btn-lg">Sign in</a>
                    </div>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                </form>
                <hr>
                <p class='text-center'>Sign up or Sign in via Social Network.</p>
                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                  <a href="{{ route('auth.social', ['driver' => 'facebook']) }}" class="btn btn-lg btn-primary">Facebook</a>
                  <a href="{{ route('auth.social', ['driver' => 'google']) }}" class="btn btn-lg btn-danger">Google</a>
                  <a href="{{ route('auth.social', ['driver' => 'linkedin']) }}" class="btn btn-lg btn-info">Linkedin</a>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('javascript')
<script src='https://www.google.com/recaptcha/api.js'></script>
@parent
@stop
