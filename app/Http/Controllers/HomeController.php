<?php

namespace Coder\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Coder\Models\Status;
use Coder\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            //echo $active_date = Carbon::createFromTimeStampUTC(strtotime(Auth::user()->active_at))->tz(Auth::user()->timezone);
            //dd(Auth::user()->permissions);
            $statuses = Status::notReply()->where(function($query){
                return $query->where('user_id', Auth::user()->id)
                            ->orWhereIn('user_id', Auth::user()->friends()->lists('id'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            return view('timeline.index')
                    ->with('statuses', $statuses);
        }

        return view('home');
    }

    public function posts()
    {
        $posts = Post::where('active', true)->orderBy('active_at', 'desc')->paginate(30);
        return view('home.posts')->with('posts', $posts);
    }
}
