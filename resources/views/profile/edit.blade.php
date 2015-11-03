@extends('templates.default')

@section('style')
<link rel="stylesheet" href="{{ asset('select2-3.5.4/select2.css') }}">
<link rel="stylesheet" href="{{ asset('select2-3.5.4/select2-bootstrap.css') }}">
@parent
@stop

@section('content')
<div class="row">

    <form class="form-vertical form-submit-normal" role="form" action="{{ route('profile.edit') }}" method="post">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label class="control-label">Avatar</label>
                        <div>
                            <a href="{{ Config::get('site.gravatar_url') }}" target="_blank">
                                <img class='img-rounded' src="{{Auth::user()->getAvatarUrl(34)}}" alt="{{Auth::user()->getNameOrUsername()}}">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="form-group">
                        <label class="control-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="email" readonly="readonly" class="form-control" value="{{ Auth::user()->email }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Public profile url</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class='glyphicon glyphicon-link'></i></span>
                    <input type="text" readonly="readonly" class="form-control" value="{{ route('profile.index', ['username' => Auth::user()->username]) }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                        <label for="first_name" class="control-label">First name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ Request::old('first_name') ?: Auth::user()->first_name }}">
                        @if($errors->has('first_name'))
                        <span class="help-block">{{ $errors->first('first_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                        <label for="last_name" class="control-label">Last name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ Request::old('last_name') ?: Auth::user()->last_name }}">
                        @if($errors->has('last_name'))
                        <span class="help-block">{{ $errors->first('last_name') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                <label for="location" class="control-label">Location</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class='glyphicon glyphicon-globe'></i></span>
                    <input type="text"  name="location" class="form-control" value="{{ Request::old('location') ?: Auth::user()->location }}">
                </div>
                @if($errors->has('location'))
                <span class="help-block">{{ $errors->first('location') }}</span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
                <label for="timezone" class="control-label">Timezone</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class='glyphicon glyphicon-time'></i></span>
                    <select class="form-control" name="timezone">
                        @foreach(timezone_identifiers_list() as $timezone)
                        @if($timezone == Auth::user()->timezone)
                        <option value="{{ $timezone }}" selected="selected">{{ $timezone }}</option>
                        @else
                        <option value="{{ $timezone }}">{{ $timezone }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <small>
                    Current datetime: {{ \Carbon\Carbon::now()->tz(Auth::user()->timezone)->formatLocalized(Config::get('site.datetime_format')) }} -
                    {{ Auth::user()->timezone }}
                </small>
            </div>
            <div class="form-group {{ $errors->has('skills') ? 'has-error' : '' }}">
                <label for="skills" class="control-label">Skills</label>
                <input type="text"  name="skills" id='skills' class="form-control" value="">
                @if($errors->has('skills'))
                <span class="help-block">{{ $errors->first('skills') }}</span>
                @endif
            </div>

        </div>
        <div class="col-lg-6">
            <div class="form-group {{ $errors->has('facebook_url') ? 'has-error' : '' }}">
                <label for="facebook_url" class="control-label">Facebook</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class='glyphicon glyphicon-link'></i></span>
                    <input type="text"  name="facebook_url" class="form-control" value="{{ Request::old('facebook_url') ?: Auth::user()->facebook_url }}">
                </div>
                @if($errors->has('facebook_url'))
                <span class="help-block">{{ $errors->first('facebook_url') }}</span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('google_url') ? 'has-error' : '' }}">
                <label for="google_url" class="control-label">Google</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class='glyphicon glyphicon-link'></i></span>
                    <input type="text"  name="google_url" class="form-control" value="{{ Request::old('google_url') ?: Auth::user()->google_url }}">
                </div>
                @if($errors->has('google_url'))
                <span class="help-block">{{ $errors->first('google_url') }}</span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('linkedin_url') ? 'has-error' : '' }}">
                <label for="linkedin_url" class="control-label">Linkedin</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class='glyphicon glyphicon-link'></i></span>
                    <input type="text"  name="linkedin_url" class="form-control" value="{{ Request::old('linkedin_url') ?: Auth::user()->linkedin_url }}">
                </div>
                @if($errors->has('linkedin_url'))
                <span class="help-block">{{ $errors->first('linkedin_url') }}</span>
                @endif
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <button type="submit" class="btn btn-default btn-lg">Update</button>
            </div>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
        </div>
    </form>

</div>
@stop


@section('javascript')
<script src="{{ asset('select2-3.5.4/select2.min.js') }}"></script>
<script type="text/javascript">
$("#skills").select2({
    tags: true,
    minimumInputLength: 2,
    maximumInputLength: 50,
    maximumSelectionSize: 20,
    ajax: {
        url: "{{ route('remote.skills') }}",
        type: 'post',
        dataType: 'json',
        delay: 250,
        quietMillis: 600,
        data: function (term, page) {
            return {
                q: term,
                _token: '{{ Session::token() }}'
            };
        },
        results: function (data, page) {
            return { results: data };
        },
        cache: true
    },
}).select2("data", {!! Auth::user()->skills()->get()->toJson() !!});
</script>
@parent
@stop
