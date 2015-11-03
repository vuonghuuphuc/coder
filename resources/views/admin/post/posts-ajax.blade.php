{!! str_replace('/?', '?', $posts->render()) !!}
<table class='table'>
    <thead>
        <tr>
            <th>
                Title
            </th>
            <th>
                Created at
            </th>
            <th>
                Activated
            </th>
            <th>
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr>
            <td>
                <a href="{{ route('post.view', ['title_url' => $post->title_url ]) }}">
                    {{ str_limit($post->title, 30) }}
                </a>
            </td>
            <td>
                {{ \Carbon\Carbon::createFromTimeStampUTC(strtotime($post->created_at))->tz(Auth::user()->timezone)->formatLocalized(Config::get('site.datetime_format')) }}
            </td>
            <td>
                @if($post->active)
                <i class="text-success glyphicon glyphicon-ok"></i>
                @endif
            </td>
            <td>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Action
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="{{ route('admin.post.edit', ['post_id' => $post->id]) }}">Edit</a></li>
                        @if($post->active)
                            <li><a href="{{ route('admin.post.deactivate', ['post_id' => $post->id]) }}">Deactivate</a></li>
                        @else
                            <li><a href="{{ route('admin.post.activate', ['post_id' => $post->id]) }}">Activate</a></li>
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! str_replace('/?', '?', $posts->render()) !!}
