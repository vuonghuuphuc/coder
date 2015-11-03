@extends('emails.templates.default')

@section('content')
    <p>
        You have registered.
    </p>
    <p>
        @if($user->active)
            Your password: <b>{{ $password }}</b>
        @else
            Activate you account using this link: {{ route('auth.activate', ['email' => $user->email, 'identifier' => urlencode($identifier)]) }}
        @endif
    </p>
@stop
