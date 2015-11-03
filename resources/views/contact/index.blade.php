@extends('templates.default')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-6 panel col-lg-offset-3">
            <div class="row panel-body">
                <p class='lead'>Contact us</p>
                <hr>
                <form class="form-vertical form-submit-normal" role="form" method="post" action="{{ route('contact') }}"
                autocomplete="off">
                    @if(Auth::check())
                        <div class="form-group form-group-lg {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email" class="control-label">Your email address</label>
                            <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input required="required" value="{{ Auth::user()->email }}" readonly='readonly' name="email" type="email" class="form-control" id="email">
                            </div>
                            @if($errors->has('email'))
                            <span class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    @else
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
                    @endif

                    <div class="form-group form-group-lg {{ $errors->has('message') ? 'has-error' : '' }}">
                        <label for="message" class="control-label">Your message:</label>
                        <textarea required='required' name="message" class="form-control" rows="3">{{ Request::old('message') ?: '' }}</textarea>
                        @if($errors->has('message'))
                        <span class="help-block">{{ $errors->first('message') }}</span>
                        @endif
                    </div>

                    @if(!Auth::check())
                        <div class="form-group form-group-lg {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                            <div class="g-recaptcha" data-sitekey="{{ Config::get('site.recaptcha_key') }}"></div>
                            @if($errors->has('g-recaptcha-response'))
                                <span class="help-block">The reCAPTCHA field is required.</span>
                            @endif
                        </div>
                    @endif

                    <button type="submit" class="btn btn-default btn-lg">Send</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('javascript')
<script src='https://www.google.com/recaptcha/api.js'></script>
@parent
@stop
