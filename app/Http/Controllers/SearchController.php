<?php

namespace Coder\Http\Controllers;

use DB;
use Coder\Models\User;
use Coder\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function getResults(Request $request)
    {
        /*$this->validate($request,[
            'query' => 'required|max:255',
        ]);

        $query = $request->input('query');


        $users = User::where(
            DB::raw("CONCAT(first_name, ' ', last_name)"),
            'LIKE', "%{$query}%"
        )->orWhere('username', 'LIKE', "%{$query}%")
        ->orWhere('email', $query)->get();

        return view('search.results')->with('users', $users);*/
        $this->validate($request,[
            'keyword' => 'required|max:255',
        ]);

        $keyword = $request->input('keyword');


        /*$posts = Post::where('active', true)->where(
            'title', 'LIKE', "%{$keyword}%"
        )->orderBy('active_at', 'desc')->paginate(30);*/

        $posts = Post::where('active', true)->where(function($query) use($keyword){

            $query->where('title', 'LIKE', "%{$keyword}%")->orWhereHas('skills', function ($query) use($keyword) {
                $query->where('skills.text', 'LIKE', "%{$keyword}%");
            });

        })->orderBy('active_at', 'desc')->paginate(30);

        return view('search.tag')->with('posts', $posts)
                ->with('tag_name', $keyword);
    }

    public function getTag($tag_id, $tag_name){
        $posts = Post::where('active', true)->whereHas('skills', function ($query) use($tag_id) {
            $query->where('skills.id', $tag_id);
        })->orderBy('active_at', 'desc')->paginate(30);

        return view('search.tag')->with('posts', $posts)
                ->with('tag_name', $tag_name);
    }
}
