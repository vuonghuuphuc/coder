{!! str_replace('/?', '?', $users->render()) !!}
<table class='table'>
    <thead>
        <tr>
            <th>
                Name
            </th>
            <th>
                Created at
            </th>
            <th>
                Activated
            </th>
            <th>
                Banned
            </th>
            <th>
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>
                <a title="{{$user->getNameOrUsername()}}" href="{{ route('profile.index', ['username' => $user->username]) }}">
                    {{ str_limit($user->getNameOrUsername(), 30) }}
                </a>
            </td>
            <td>
                {{ \Carbon\Carbon::createFromTimeStampUTC(strtotime($user->created_at))->tz(Auth::user()->timezone)->formatLocalized(Config::get('site.datetime_format')) }}
            </td>
            <td>
                @if($user->active)
                <i class="text-success glyphicon glyphicon-ok"></i>
                @endif
            </td>
            <td>
                @if($user->ban)
                <i class="text-danger glyphicon glyphicon-ban-circle"></i>
                @endif
            </td>
            <td>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Action
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!! str_replace('/?', '?', $users->render()) !!}
