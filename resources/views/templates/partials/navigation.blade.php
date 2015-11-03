<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">{{ Config::get('site.name') }}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="{{ Route::is('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Bài viết</a></li>
                <li><a href="{{ route('friend.index') }}">Friends</a></li>
            </ul>
            <form class="navbar-form navbar-left" role="search" action="{{ route('search.results') }}">
                <div class="form-group">
                    <div class="input-group">
                        <input required='required' type="text" name="keyword" class="form-control" placeholder="Tìm kiếm">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class='glyphicon glyphicon-search'></i></button>
                        </span>
                    </div>
                </div>

            </form>
            <ul class="nav navbar-nav navbar-right">
                <li class="{{ Route::is('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">Contact us</a></li>
                @if(Auth::check())
                <li class="dropdown">
                    <a href="#" title="{{Auth::user()->getNameOrUsername()}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ str_limit(Auth::user()->getNameOrUsername(), 30) }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->isAdmin())
                            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">Admin dashboard</a></li>
                        @endif
                        <li class="{{ Route::is('bookmark') ? 'active' : '' }}">
                            <a href="{{ route('bookmark') }}">
                                Bookmark
                                <span class="badge pull-right">
                                {{  Auth::user()->bookmarked()->where('active', true)->count() }}
                                </span>
                            </a>
                        </li>
                        <li class="{{ Route::is('post.matched') ? 'active' : '' }}">
                            <a href="{{ route('post.matched') }}">
                                For you
                                <span class="badge pull-right">
                                <?php
                                $skills_old = Auth::user()->skills()->get()->toArray();
                                $arrayId = [];
                                foreach ($skills_old as $key => $value) {
                                    $arrayId[] = $value['id'];
                                }
                                ?>
                                {{ \Coder\Models\Post::where('active', true)->whereHas('skills', function ($query) use($arrayId) {
                                    $query->whereIn('skills.id', $arrayId);
                                })->count() }}
                                </span>
                            </a>
                        </li>
                        <li class="{{ Route::is('profile.index') ? 'active' : '' }}"><a href="{{ route('profile.index', ['username' => Auth::user()->username]) }}">Profile</a></li>
                        <li class="{{ Route::is('profile.edit') ? 'active' : '' }}"><a href="{{ route('profile.edit') }}">Update profile</a></li>
                        <li class="{{ Route::is('profile.change-password') ? 'active' : '' }}"><a href="{{ route('profile.change-password') }}">Change password</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('auth.signout') }}">Sign out</a></li>
                    </ul>
                </li>
                @else
                <li class="{{ Route::is('auth.signup') ? 'active' : '' }}"><a href="{{route('auth.signup')}}">Sign up</a></li>
                <li class="{{ Route::is('auth.signin') ? 'active' : '' }}"><a href="{{route('auth.signin')}}">Sign in</a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
