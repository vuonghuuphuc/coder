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
        <li><a href="{{ route('bookmark') }}">Bookmarked</a></li>
    </ol>
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
