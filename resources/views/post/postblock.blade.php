<div class="col-lg-6">
    <div class='col-lg-12 panel panel-default'>
        <div class="panel-body row">
            <div class="row">
                <div class="col-xs-4">
                    @if($post->files()->first())
                    <a href="{{ route('post.view', ['title_url' => $post->title_url ]) }}">
                        <img style='height:100px;width:100%;'  class="media-object img-rounded" src="{{ $post->files()->first()->thumbnail_url() }}" alt="{{ $post->title }}">
                    </a>
                    @else
                    <a href="{{ route('post.view', ['title_url' => $post->title_url ]) }}">
                        <i class='glyphicon glyphicon-info-sign' style='font-size:50px'></i>
                    </a>
                    @endif
                </div>
                <div class="col-xs-8">
                    <p class='lead'>
                        <a href="{{ route('post.view', ['title_url' => $post->title_url ]) }}">
                            {{ $post->title }}
                        </a>
                    </p>
                    <p>
                        <small>
                        by
                        <a title="{{$post->user->getNameOrUsername()}}"
                            href="{{ route('profile.index', ['username' => $post->user->username]) }}">
                            {{ str_limit($post->user->getNameOrUsername(), 30) }}
                        </a>
                        at
                        @if(Auth::check())
                        {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->active_at))->tz(Auth::user()->timezone)->formatLocalized(Config::get('site.datetime_format')) }}
                        @else
                        {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->active_at))->formatLocalized(Config::get('site.datetime_format')) }}
                        @endif
                        </small>
                    </p>
                    <p>
                        @foreach($post->skills()->get() as $skill)
                            <a href="{{ route('search.tag', ['tag_id' => $skill->id, 'tag_name' => $skill->text]) }}"><span class="label label-primary">{{ $skill->text }}</span></a>
                        @endforeach
                    </p>
                    <p>
                        <a class='pull-right' href="{{ route('post.view', ['title_url' => $post->title_url ]) }}" role="button">
                            View details »
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
