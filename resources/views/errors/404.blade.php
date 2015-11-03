@extends('templates.default')

@section('content')
<div class="panel">
    <div class="panel-body">
        <h1>Oops, that page could not be found.</h1>
        <p>...</p>
        <p><a class="btn btn-primary btn-lg" href="{{ route('home') }}" role="button">Go home</a></p>
    </div>
</div>
@stop
