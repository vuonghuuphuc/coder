@extends('templates.default')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            @include('user.partials.userblock')
            <hr>

            @if (!$statuses->count())
                <p>
                    {{ $user->getFirstNameOrUsername() }} hasn't posted anything yet.
                </p>
            @else
                @foreach ($statuses as $status)
                    <div class="media">
                        <a href="{{ route('profile.index', ['username' => $status->user->username]) }}"
                            class="pull-left" title="{{$status->user->getNameOrUsername()}}">
                            <img src="{{ $status->user->getAvatarUrl() }}" class="media-object img-rounded"
                             alt="{{ $status->user->getNameOrUsername() }}" />
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading break-word">
                                <a title="{{$status->user->getNameOrUsername()}}"
                                    href="{{ route('profile.index', ['username' => $status->user->username]) }}">
                                    {{ str_limit($status->user->getNameOrUsername(), 30) }}
                                </a>
                            </h4>
                            <p class="break-word">
                                {{ $status->body }}
                            </p>
                            <ul class="list-inline">
                                <li>{{ $status->created_at->diffForHumans() }}</li>
                                @if(Auth::check())
                                    @if($status->user->id !== Auth::user()->id)
                                    <li><a href="{{ route('status.like', ['statusId' => $status->id]) }}">Like</a></li>
                                    @endif
                                @endif
                                <li>{{ $status->likes->count() }} {{ str_plural('like', $status->likes->count()) }}</li>
                            </ul>

                            @foreach ($status->replies as $reply)
                            <div class="media">
                                <a href="{{ route('profile.index', ['username' => $reply->user->username]) }}"
                                    class="pull-left" title="{{$reply->user->getNameOrUsername()}}">
                                    <img src="{{ $reply->user->getAvatarUrl() }}" class="media-object img-rounded"
                                    alt="{{ $reply->user->getNameOrUsername() }}" />
                                </a>
                                <div class="media-body">
                                    <h5 class="media-heading break-word">
                                        <a title="{{$reply->user->getNameOrUsername()}}"
                                            href="{{ route('profile.index', ['username' => $reply->user->username]) }}">
                                            {{ str_limit($reply->user->getNameOrUsername(), 30) }}
                                        </a>
                                    </h5>
                                    <p class="break-word">
                                        {{ $reply->body }}
                                    </p>
                                    <ul class="list-inline">
                                        <li>{{ $reply->created_at->diffForHumans() }}</li>
                                        @if (Auth::check())
                                            @if($reply->user->id !== Auth::user()->id)
                                            <li><a href="{{ route('status.like', ['statusId' => $reply->id]) }}">Like</a></li>
                                            @endif
                                        @endif
                                        <li>{{ $reply->likes->count() }} {{ str_plural('like', $reply->likes->count()) }}</li>
                                    </ul>
                                </div>
                            </div>
                            @endforeach

                            @if($authUserIsFriend)
                            <form class="" role="form" action="{{ route('status.reply', ['statusId' => $status->id]) }}"
                             method="post">
                                <div class="form-group  {{ $errors->has("reply-{$status->id}") ? 'has-error' : '' }}">
                                    <textarea name="reply-{{ $status->id }}" class="form-control"
                                     rows="1" placeholder="Reply to this status"></textarea>
                                     @if($errors->has("reply-{$status->id}"))
                                     <span class="help-block">{{ $errors->first("reply-{$status->id}") }}</span>
                                     @endif
                                </div>
                                <input type="submit"  value="Reply" class="btn btn-default btn-sm">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                            </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="col-lg-4 col-lg-offset-2">

            @if(Auth::check())
                @if (Auth::user()->id !== $user->id)
                    @if (Auth::user()->hasFriendRequestPending($user))
                        <p>Waiting for {{ $user->getFirstNameOrUsername() }} to accept your request.</p>
                    @elseif (Auth::user()->hasFriendRequestReceived($user))
                        <a href="{{ route('friend.accept', ['username' => $user->username]) }}" class="btn btn-primary">
                            Accept friend request
                        </a>
                    @elseif (Auth::user()->isFriendsWith($user))
                        <p>You and {{ $user->getFirstNameOrUsername() }} are friends.</p>
                    @else
                        <a href="{{ route('friend.add', ['username' => $user->username]) }}" class="btn btn-primary">
                            Add as friend
                        </a>
                    @endif
                @endif
            @endif

            <h4 class="break-word">{{ $user->getFirstNameOrUsername() }}'s friends.</h4>

            @if(!$user->friends()->count())
                <p>{{ $user->getFirstNameOrUsername() }} has no friends.</p>
            @else
                @foreach($user->friends() as $user)
                    @include('user/partials/userblock')
                @endforeach
            @endif
        </div>
    </div>
@stop
