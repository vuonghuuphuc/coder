@extends('templates.admin')

@section('content')

<a href="{{ route('admin.post') }}" class='btn btn-default btn-lg'>Create a post</a>

@if (!$posts->count())
<div class='well'>
    Results not found.
</div>
@else
<div class="posts">
    @include('admin.post.posts-ajax')
</div>
@endif
@stop
