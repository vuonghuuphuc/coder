@extends('templates.content')

@section('meta')
<meta property="og:url"           content="{{ route('post.view', ['title_url' => $post->title_url]) }}" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="{{ $post->title }}" />
<meta property="og:description"   content="{{ $post->description }}" />
@if($post->files()->first())
<meta property="og:image"         content="{{ $post->files()->first()->thumbnail_url() }}" />
@endif

@stop

@section('left-content')
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-body">
                <div>
                    <p class="lead break-word">
                        @if(Auth::check() && $post->active)
                            @if(Auth::user()->hasBookmarkedPost($post))
                            <a href="{{ route('post.unbookmarked', ['postId' => $post->id]) }}" style='font-size:30px;color:#FFE800' class='pull-right'
                            data-toggle="tooltip" data-placement="bottom" title="Bookmark">
                                <i class='glyphicon glyphicon-star'></i>
                            </a>
                            @else
                            <a href="{{ route('post.bookmark', ['postId' => $post->id]) }}" style='font-size:30px;color:#FFE800' class='pull-right'
                            data-toggle="tooltip" data-placement="bottom" title="Bookmark">
                                <i class='glyphicon glyphicon-star-empty'></i>
                            </a>
                            @endif
                        @endif
                        {{ $post->title }}
                    </p>
                    <small>
                        by
                        <a title="{{$post->user->getNameOrUsername()}}"
                            href="{{ route('profile.index', ['username' => $post->user->username]) }}">
                            {{ str_limit($post->user->getNameOrUsername(), 30) }}
                        </a>
                        @if($post->active)
                            at
                            @if(Auth::check())
                            {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->active_at))->tz(Auth::user()->timezone)->formatLocalized(Config::get('site.datetime_format')) }}
                            @else
                            {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->active_at))->formatLocalized(Config::get('site.datetime_format')) }}
                            @endif
                        @endif
                    </small>
                </div>
                <hr>
                <div class='row'>
                    <div class="col-lg-12">
                        @if(Auth::check())
                            @if(Auth::user()->isAdmin())
                            @if($post->active)
                                <a class='btn btn-link pull-right' href="{{ route('admin.post.deactivate', ['post_id' => $post->id]) }}">
                                    <i class='glyphicon glyphicon-remove'></i> Deactivate
                                </a>
                            @else
                                <a class='btn btn-link pull-right' href="{{ route('admin.post.activate', ['post_id' => $post->id]) }}">
                                    <i class='glyphicon glyphicon-ok'></i> Activate
                                </a>
                            @endif
                            <a class='btn btn-link pull-right' href="{{ route('admin.post.edit', ['post_id' => $post->id]) }}">
                                <i class='glyphicon glyphicon-pencil'></i> Edit
                            </a>
                            @endif
                        @endif

                        @if($post->active)
                        <div class="fb-share-button pull-left" style="margin-right:10px" data-href="{{ route('post.view', ['title_url' => $post->title_url]) }}" data-layout="button_count"></div>

                        <div class="g-plus pull-left" data-action="share" data-annotation="bubble"></div>

                        <script type="IN/Share" data-url="{{ route('post.view', ['title_url' => $post->title_url]) }}" data-counter="right"></script>
                        @else
                        <p class='text-danger lead'>
                            This post is not active yet.
                        </p>
                        @endif
                    </div>

                </div>
                <hr>
                <p class='text-center'>
                    <a href="#" class='btn btn-danger'><i class='glyphicon glyphicon-download-alt'></i> Export PDF</a>
                    <a href="#" class='btn btn-success'><i class='glyphicon glyphicon-download-alt'></i> Source code</a>
                </p>
                <p>
                    @foreach($post->skills()->get() as $skill)
                        <a href="{{ route('search.tag', ['tag_id' => $skill->id, 'tag_name' => $skill->text]) }}"><span class="label label-primary">{{ $skill->text }}</span></a>
                    @endforeach
                </p>
                <div class='break-word'>
                    {!! $post->body !!}
                </div>
                <hr>
                <ul class="list-inline hide">
                    @if(Auth::check())
                        @if($post->user->id !== Auth::user()->id)
                            @if(Auth::user()->hasLikedPost($post))
                                <li><a href="#" class="disabled">Like</a></li>
                            @else
                                <li><a href="{{ route('post.like', ['postId' => $post->id]) }}">Like</a></li>
                            @endif
                        @endif
                    @endif
                    <li>{{ $post->likes->count() }} {{ str_plural('like', $post->likes->count()) }}</li>
                </ul>
                <div id="disqus_comment">
                    <div id="disqus_thread"></div>
                    <script type="text/javascript">
                        /* * * CONFIGURATION VARIABLES * * */
                        var disqus_shortname = 'laptrinhweb';

                        /* * * DON'T EDIT BELOW THIS LINE * * */
                        (function() {
                            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                        })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript_on_top')
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId={{ Config::get('services.facebook.client_id') }}";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
@parent
@stop


@section('javascript')
<script>
window.___gcfg = {
    lang: 'en-US',
    parsetags: 'onload'
};
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
@parent
@stop
