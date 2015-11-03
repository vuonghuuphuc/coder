@extends('emails.templates.admin')

@section('content')
    <p>
        From : {{ $from }}
    </p>
    <p>
        Message : {{ $mess }}
    </p>
@stop
