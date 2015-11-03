@extends('templates.content')

@section('left-content')
    <div class="col-md-12">
        <p class='lead'>
            Your search for <mark class=''>{{ $tag_name }}</mark>
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
