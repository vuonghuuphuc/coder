@extends('templates.content')

@section('left-content')

    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <a title="{{Auth::user()->getNameOrUsername()}}"
                href="{{ route('profile.index', ['username' => Auth::user()->username]) }}">
                {{ str_limit(Auth::user()->getNameOrUsername(), 30) }}
                </a>
            </li>
            <li><a href="{{ route('post.matched') }}">Posts matched your skills</a></li>
        </ol>
        <form class="" action="{{ route('profile.skills') }}" method="post">
            <div class="form-group {{ $errors->has('skills') ? 'has-error' : '' }}">
                <label for="skills" class="control-label">Your Skills:</label>
                <div class="input-group">
                    <input type="text"  name="skills" id='skills' class="form-control" value="">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success">Save your skills</button>
                    </span>
                </div>
                @if($errors->has('skills'))
                <span class="help-block">{{ $errors->first('skills') }}</span>
                @endif
            </div>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
        </form>
        <p>
            <label for="">Posts matched your skills</label>
        </p>
        @if(!$posts->count())
            <div class='panel'>
                <div class="panel-body">
                    No results found, sorry.
                </div>
            </div>
        @else
        <div class="row">
                @foreach($posts as $post)
                    @include('post.postblock')
                @endforeach
        </div>
        {!! str_replace('/?', '?', $posts->appends(Input::except('page'))->render()) !!}
        @endif
    </div>
@stop

@section('style')
<link rel="stylesheet" href="{{ asset('select2-3.5.4/select2.css') }}">
<link rel="stylesheet" href="{{ asset('select2-3.5.4/select2-bootstrap.css') }}">
@parent
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
}).select2("data", {!! $skills !!});
</script>
@parent
@stop
