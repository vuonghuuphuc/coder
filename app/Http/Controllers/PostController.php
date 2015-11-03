<?php

namespace Coder\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Coder\Models\User;
use Coder\Models\Post;
use Coder\Models\Status;
use Coder\Models\File;
use Coder\Models\Skill;
use Validator;
use Input;
use Image;
use Request;
use View;
use Response;

class PostController extends Controller
{
    public function getPosts(){
        $posts = Post::orderBy('created_at', 'desc')->paginate(30);

        return view('admin.post.posts')->with(['posts' => $posts]);
    }

    public function getPost(\Illuminate\Http\Request $request)
    {
        $directory = "uploads/posts/";

        $files = File::where('directory', $directory)->orderBy('id', 'desc')->paginate(30);

        $skills_old = [];
        if($request->old('skills')){
            $skills = (array) explode(",", $request->old('skills'));
            $skills = array_filter(array_unique($skills));
            foreach ($skills as $value) {
                $skill = Skill::find($value);
                if($skill){
                    $skills_old[] = $skill->toArray();
                }
            }
        }

        $file_url = null;
        if($request->old('file_id')){

            $thisfile = File::find($request->old('file_id'));
            if($thisfile){
                $file_url = $thisfile->url();
            }
        }

        if (Request::ajax()) {

            return Response::json(View::make('admin.file.post', array('files' => $files))->render());
        }

        return view('admin.post.post')->with('skills', json_encode($skills_old))
                                        ->with('files', $files)
                                        ->with('file_url', $file_url);

    }

    public function postPost(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            "title" => "required|min:5|max:255",
            "title_url" => "required|alpha_dash|max:255|unique:posts",
            "description" => "min:5|max:1000",
            "keywords" => "max:255",
            "body" => "required|min:100",
            "image" => "image|max:300",
            "file_id" => "required|numeric",
        ]);

        $skills = (array) explode(",", $request->input('skills'));
        $skills = array_filter(array_unique($skills));

        $validator = Validator::make(['skills' => count($skills)], [
           "skills" => "max:5",
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }

        $arrayCreate = [
            'hash_id' => "p-" . uniqid(),
            'title' => $request->input('title'),
            'title_url' => $request->input('title_url'),
            'description' => $request->input('description'),
            'keywords' => $request->input('keywords'),
            'body' => $request->input('body'),
            'active' => false,
        ];

        if($request->input('active')){
            $arrayCreate['active'] = true;
            $arrayCreate['active_at'] = Carbon::now()->toDateTimeString();
            $arrayCreate['active_by'] = Auth::user()->id;
        }

        $posted = Auth::user()->posts()->create($arrayCreate);

        foreach ($skills as $value) {
            $skill = Skill::find($value);
            if($skill){
                $posted->skills()->attach([
                        'skill_id' => $value
                ]);
            }
        }

        if($request->input('file_id')){
            $this_file = File::find($request->input('file_id'));
            if($this_file){
                $posted->files()->attach(['file_id' => $this_file->id]);
            }
        }

        notify()->flash("Posted", 'success', [
            'text' => 'Your post is success.'
        ]);
        return redirect()->route('post.view', ['title_url' => $posted->title_url ]);
    }

    public function getView($title_url)
    {
        $validator = Validator::make(['title_url' => $title_url], [
           'title_url' => 'required|alpha_dash',
       ]);

       if ($validator->fails()) {
           abort(404);
       }

       $isAdmin = false;
       if(Auth::check()){
           if(Auth::user()->isAdmin()){
               $isAdmin = true;
           }
       }

       if(!$isAdmin){
           $post = Post::where('active', true)->where('title_url', $title_url)->first();
       }else{
           $post = Post::where('title_url', $title_url)->first();
       }


       if(!$post){
           return redirect()->route('home');
       }
       //dd($post->user->getNameOrUsername());
       return view('post.view')
               ->with('post', $post);
    }


    public function getLike($postid)
    {
        $post = Post::where('active', true)->find($postid);

        if(!$post){
            return redirect()->route('home');
        }

        if(Auth::user()->hasLikedPost($post)){
            return redirect()->back();
        }

        $like = $post->likes()->create([]);

        Auth::user()->likes()->save($like);

        return redirect()->back();
    }

    public function postReply(\Illuminate\Http\Request $request, $postId)
    {
        $this->validate($request, [
                "reply-{$postId}" => 'required|max:1000'
        ],[
                'required' => 'The reply body is required.'
        ]);

        $post = Post::find($postId);

        if(!$post){
            return redirect()->route('home');
        }

        $reply = Status::create([
                'body' => $request->input("reply-{$postId}"),
                'post_id' => $post->id
        ])->user()->associate(Auth::user());

        $post->replies()->save($reply);

        return redirect()->back();
    }

    public function getActivate($post_id)
    {
        $validator = Validator::make(['post_id' => $post_id], [
           'post_id' => 'required|numeric',
        ]);

       if ($validator->fails()) {
           abort(404);
       }

       Post::where('active', false)->find($post_id)
            ->update([
            'active' => true,
            'active_at' => Carbon::now()->toDateTimeString(),
            'active_by' => Auth::user()->id
       ]);

       return redirect()->back();
    }

    public function getDeactivate($post_id)
    {
        $validator = Validator::make(['post_id' => $post_id], [
           'post_id' => 'required|numeric',
        ]);

       if ($validator->fails()) {
           abort(404);
       }

       Post::where('active', true)->find($post_id)
            ->update([
                'active' => false,
                'active_at' => null,
                'active_by' => null
            ]);

       return redirect()->back();
    }

    public function getEdit(\Illuminate\Http\Request $request, $post_id)
    {
        $validator = Validator::make(['post_id' => $post_id], [
           'post_id' => 'required|numeric',
        ]);

       if ($validator->fails()) {
           abort(404);
       }

       $post = Post::find($post_id);
       if(!$post){
           abort(404);
       }

       $directory = "uploads/posts/";

       $files = File::where('directory', $directory)->orderBy('id', 'desc')->paginate(20);

       $skills_old = [];
       if($request->old('skills')){
           $skills = (array) explode(",", $request->old('skills'));
           $skills = array_filter(array_unique($skills));
           foreach ($skills as $value) {
               $skill = Skill::find($value);
               if($skill){
                   $skills_old[] = $skill->toArray();
               }
           }
       }else{
           $skills_old = $post->skills()->get()->toArray();
       }

       $file_url = null;
       if($request->old('file_id')){

           $thisfile = File::find($request->old('file_id'));
           if($thisfile){
               $file_url = $thisfile->url();
           }
       }

       if (Request::ajax()) {

           return Response::json(View::make('admin.file.post', array('files' => $files))->render());
       }

       return view('admin.post.edit')->with('skills', json_encode($skills_old))
                                       ->with('files', $files)
                                       ->with('file_url', $file_url)
                                       ->with('post', $post);
    }

    public function postEdit(\Illuminate\Http\Request $request, $post_id)
    {
        $validator = Validator::make(['post_id' => $post_id], [
           'post_id' => 'required|numeric',
        ]);

       if ($validator->fails()) {
           abort(404);
       }

       $post = Post::find($post_id);
       if(!$post){
           abort(404);
       }



       $this->validate($request, [
           "title" => "required|min:5|max:255",
           "title_url" => "required|alpha_dash|max:255|unique:posts,title_url,". $post->id,
           "description" => "min:5|max:1000",
           "keywords" => "max:255",
           "body" => "required|min:100",
           "image" => "image|max:300",
           "file_id" => "required|numeric",
       ]);

       $skills = (array) explode(",", $request->input('skills'));
       $skills = array_filter(array_unique($skills));

       $validator = Validator::make(['skills' => count($skills)], [
          "skills" => "max:5",
       ]);

       if ($validator->fails()) {
           return redirect()->back();
       }

       $arrayUpdate = [
           'title' => $request->input('title'),
           'title_url' => $request->input('title_url'),
           'description' => $request->input('description'),
           'keywords' => $request->input('keywords'),
           'body' => $request->input('body'),
       ];

       if($request->input('active')){
           if(!$post->active){
               $arrayUpdate['active'] = true;
               $arrayUpdate['active_at'] = Carbon::now()->toDateTimeString();
               $arrayUpdate['active_by'] = Auth::user()->id;
           }
       }else{
           $arrayUpdate['active'] = false;
           $arrayUpdate['active_at'] = null;
           $arrayUpdate['active_by'] = null;
       }

       $post->update($arrayUpdate);

       $post->files()->detach();

       if($request->input('file_id')){
           $this_file = File::find($request->input('file_id'));
           if($this_file){
               $post->files()->attach(['file_id' => $this_file->id]);
           }
       }

       $post->skills()->detach();

       foreach ($skills as $value) {
           $skill = Skill::find($value);
           if($skill){
               $post->skills()->attach([
                       'skill_id' => $value
               ]);
           }
       }

       notify()->flash("Updated", 'success', [
           'text' => 'Your post is update.'
       ]);

       return redirect()->route('post.view', ['title_url' => $post->title_url ]);
    }

    public function getBookmark($postid)
    {
        $post = Post::where('active', true)->find($postid);

        if(!$post){
             abort(404);
        }

        if(Auth::user()->hasBookmarkedPost($post)){
            return redirect()->back();
        }

        Auth::user()->bookmarked()->attach([
            'post_id' => $post->id
        ]);

        notify()->flash("Bookmarked", 'success', [
            'text' => ''
        ]);

        return redirect()->back();
    }
    public function getUnbookmarked($postid)
    {
        $post = Post::where('active', true)->find($postid);

        if(!$post){
             abort(404);
        }

        if(!Auth::user()->hasBookmarkedPost($post)){
            return redirect()->back();
        }

        Auth::user()->bookmarked()->detach([
            'post_id' => $post->id
        ]);

        return redirect()->back();
    }

    public function getUserBookmarks()
    {
        $posts = Auth::user()->bookmarked()->where('active', true)->paginate(30);

        return view('post.bookmark')->with('posts', $posts);

    }

    public function getMatched(\Illuminate\Http\Request $request)
    {
        $skills_old = [];
        if($request->old('skills')){
            $skills = (array) explode(",", $request->old('skills'));
            $skills = array_filter(array_unique($skills));
            foreach ($skills as $value) {
                $skill = Skill::find($value);
                if($skill){
                    $skills_old[] = $skill->toArray();
                }
            }
        }else{
            $skills_old = Auth::user()->skills()->get()->toArray();
        }

        $arrayId = [];
        foreach ($skills_old as $key => $value) {
            $arrayId[] = $value['id'];
        }
        $posts = Post::where('active', true)->whereHas('skills', function ($query) use($arrayId) {
            $query->whereIn('skills.id', $arrayId);
        })->orderBy('active_at', 'desc')->paginate(30);

        return view('post.matched')->with('posts', $posts)
                ->with('skills', json_encode($skills_old));
    }
}
