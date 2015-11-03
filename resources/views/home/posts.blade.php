@extends('templates.content')

@section('left-content')
@foreach($posts as $post)
    @include('post.postblock')
@endforeach
<div class="col-lg-12">
    {!! str_replace('/?', '?', $posts->render()) !!}
</div>
@stop
