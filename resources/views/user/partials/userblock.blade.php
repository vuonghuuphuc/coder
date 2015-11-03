<div class="media">
    <a title="{{$user->getNameOrUsername()}}" class="pull-left" href="{{ route('profile.index', ['username' => $user->username]) }}">
        <img class="media-object img-rounded" src="{{$user->getAvatarUrl()}}" alt="{{$user->getNameOrUsername()}}">
    </a>
    <div class="media-body">
        <h4 class="media-heading break-word">
            <a title="{{$user->getNameOrUsername()}}" href="{{ route('profile.index', ['username' => $user->username]) }}">
            {{ $user->getNameOrUsername() }}
            </a>
        </h4>
        @if($user->location)
            <p class="break-word">{{$user->location}}</p>
        @endif
    </div>
</div>
