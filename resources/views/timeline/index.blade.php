@extends('templates.default')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <form class="" role="form" action="{{ route('status.post') }}" method="post">
            <div class="form-group  {{ $errors->has('status') ? 'has-error' : '' }}">
                <textarea placeholder="What's up {{ Auth::user()->getFirstNameOrUsername() }}" name="status"
                class="form-control" rows="1"></textarea>
                @if($errors->has('status'))
                <span class="help-block">{{ $errors->first('status') }}</span>
                @endif
            </div>
            <button type="submit" class="btn btn-default">Update status</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
        </form>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        @if (!$statuses->count())
            <p>
                There's nothing in your timeline, yet.
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
                            <a title="{{$status->user->getNameOrUsername()}}" href="{{ route('profile.index', ['username' => $status->user->username]) }}">
                                {{ str_limit($status->user->getNameOrUsername(), 30) }}
                            </a>
                        </h4>
                        <p class="break-word">
                            {{ trim($status->body) }}
                        </p>
                        <ul class="list-inline">
                            <li>{{ $status->created_at->diffForHumans() }}</li>
                            @if ($status->user->id !== Auth::user()->id)
                            <li><a href="{{ route('status.like', ['statusId' => $status->id]) }}">Like</a></li>
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
                                    <a title="{{$reply->user->getNameOrUsername()}}" href="{{ route('profile.index', ['username' => $reply->user->username]) }}">
                                        {{ str_limit($reply->user->getNameOrUsername(), 30) }}
                                    </a>
                                </h5>
                                <p class="break-word">
                                    {{ trim($reply->body) }}
                                </p>
                                <ul class="list-inline">
                                    <li>{{ $reply->created_at->diffForHumans() }}</li>
                                    @if ($reply->user->id !== Auth::user()->id)
                                    <li><a href="{{ route('status.like', ['statusId' => $reply->id]) }}">Like</a></li>
                                    @endif
                                    <li>{{ $reply->likes->count() }} {{ str_plural('like', $reply->likes->count()) }}</li>
                                </ul>
                            </div>
                        </div>
                        @endforeach

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
                    </div>
                </div>
            @endforeach

            {!! $statuses->render() !!}
        @endif
    </div>
</div>
@stop
