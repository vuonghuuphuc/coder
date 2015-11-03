@if(Auth::check())
    <p>Hello {{ Auth::user()->getFirstNameOrUsername() }}</p>
@else
    <p>Hello</p>
@endif

@yield('content')
