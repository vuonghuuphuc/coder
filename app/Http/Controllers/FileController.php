<?php

namespace Coder\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Input;
use Image;
use Auth;
use File;
use Coder\Models\Post;

class FileController extends Controller
{
    public function postUpload($dir){
        if($dir == "posts"){
            $validator = Validator::make(['file' => Input::file('file_upload')], [
               "file" => "image|max:500",
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => $validator->messages()
                ];
            }else{
                $image = Input::file('file_upload');

                $extension = $image->getClientOriginalExtension();
                $original_name = $image->getClientOriginalName();
                $size = $image->getSize();
                $directory = "uploads/posts/";

                $file_name  = time() . '.' . $image->getClientOriginalExtension();
                $thumbnail = "thumbnail_" . time() . '.' . $image->getClientOriginalExtension();

                $path = public_path($directory . $file_name);
                Image::make($image->getRealPath())->save($path);

                $path = public_path($directory . $thumbnail);
                Image::make($image->getRealPath())->resize(150, 100)->save($path);

                Auth::user()->files()->create([
                    'directory' => $directory,
                    'file_name' => $file_name,
                    'thumbnail' => $thumbnail,
                    'original_name' => $original_name,
                    'extension' => $extension,
                    'size' => $size,
                ]);

                return [
                    'success' => true
                ];
            }
        }
    }

    public function postDelete(Request $request)
    {
        $this->validate($request, [
            "id" => "required|numeric",
        ]);

        $file_id = $request->input('id');

        $file = \Coder\Models\File::find($file_id);


        if($file){

            $posts = $file->posts()->get();
            foreach($posts as $post){
                $post->files()->detach(['file_id' => $file->id]);
            }

            $dir = public_path($file->directory . $file->file_name);
            $dir_thumb = public_path($file->directory . $file->thumbnail);

            File::delete($dir, $dir_thumb);

            Auth::user()->files()->find($file_id)->delete();

            $file->delete();
        }
    }
}
