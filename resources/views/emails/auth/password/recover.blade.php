@extends('emails.templates.default')

@section('content')
    <p>
        You requested a password change.
    </p>
    <p>
        Click this link to reset your password: {{ route('password.reset', ['email' => $user->email, 'identifier' => urlencode($identifier)]) }}
    </p>
@stop
