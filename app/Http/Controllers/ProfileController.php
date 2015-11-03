<?php

namespace Coder\Http\Controllers;

use Auth;
use Validator;
use Hash;
use Coder\Models\User;
use Coder\Models\Skill;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getProfile($username)
    {
        $validator = Validator::make(['username' => $username], [
           'username' => 'required|alpha_dash',
       ]);

       if ($validator->fails()) {
           abort(404);
       }

        $user = User::where('username', $username)->first();

        if(!$user){
            abort(404);
        }

        $statuses = $user->statuses()->notReply()->orderBy('created_at', 'desc')->get();

        $authUserIsFriend = false;

        if(Auth::check()){
            if(Auth::user()->id === $user->id){
                //using it to reply
                $authUserIsFriend = true;
            }else{
                $authUserIsFriend = Auth::user()->isFriendsWith($user);
            }
        }

        return view('profile.index')
                ->with('user', $user)
                ->with('statuses', $statuses)
                ->with('authUserIsFriend', $authUserIsFriend);
    }

    public function getEdit()
    {
        return view('profile.edit');
    }

    public function postSkills(Request $request)
    {
        $update = false;

        $skills = (array) explode(",", $request->input('skills'));
        $skills = array_filter(array_unique($skills));

        $validator = Validator::make(['skills' => count($skills)], [
           "skills" => "max:20",
       ]);

       if ($validator->fails()) {
           return redirect()->back();
       }

       //Detach all skill not have in new list
       $userSkills = Auth::user()->skills()->get();
       foreach($userSkills as $value){
           if(!in_array($value->id, $skills)){
               Auth::user()->skills()->detach([
                    'skill_id' => $value->id
               ]);
               $update = true;
           }
       }
       //Add new skill to user skill if that skill not yet have in user skill list
        foreach ($skills as $value) {
            $haveSkill = Auth::user()->skills()->find($value);
            if(!$haveSkill){
                $skill = Skill::find($value);
                if($skill){
                    Auth::user()->skills()->attach([
                            'skill_id' => $value
                    ]);
                    $update = true;
                }
            }
        }

        if($update){
            notify()->flash("Updated", 'success', [
                'text' => 'Your skills is updated'
            ]);
            return redirect()->route('post.matched');
        }
        return redirect()->back();
    }

    public function postEdit(Request $request)
    {
        $update = false;
        $this->validate($request, [
            "first_name" => "required|alpha|min:2|max:50",
            "last_name" => "required|alpha|min:2|max:50",
            "location" => "max:20",
            "timezone" => "required|timezone",
            "facebook_url" => "url",
            "google_url" => "url",
            "linkedin_url" => "url",
        ]);

        $skills = (array) explode(",", $request->input('skills'));
        $skills = array_filter(array_unique($skills));

        $validator = Validator::make(['skills' => count($skills)], [
           "skills" => "max:20",
       ]);

       if ($validator->fails()) {
           return redirect()->back();
       }


       //Detach all skill not have in new list
       $userSkills = Auth::user()->skills()->get();
       foreach($userSkills as $value){
           if(!in_array($value->id, $skills)){
               Auth::user()->skills()->detach([
                    'skill_id' => $value->id
               ]);
               $update = true;
           }
       }
       //Add new skill to user skill if that skill not yet have in user skill list
        foreach ($skills as $value) {
            $haveSkill = Auth::user()->skills()->find($value);
            if(!$haveSkill){
                $skill = Skill::find($value);
                if($skill){
                    Auth::user()->skills()->attach([
                            'skill_id' => $value
                    ]);
                    $update = true;
                }
            }
        }
        $arrayUpdate = null;
        if($request->input('first_name') != Auth::user()->first_name)
            $arrayUpdate['first_name'] = $request->input('first_name');

        if($request->input('last_name') != Auth::user()->last_name)
            $arrayUpdate['last_name'] = $request->input('last_name');

        if($request->input('location') != Auth::user()->location)
            $arrayUpdate['location'] = $request->input('location');

        if($request->input('timezone') != Auth::user()->timezone)
            $arrayUpdate['timezone'] = $request->input('timezone');


        if($request->input('facebook_url') != Auth::user()->facebook_url)
            $arrayUpdate['facebook_url'] = $request->input('facebook_url');

        if($request->input('google_url') != Auth::user()->google_url)
            $arrayUpdate['google_url'] = $request->input('google_url');

        if($request->input('linkedin_url') != Auth::user()->linkedin_url)
            $arrayUpdate['linkedin_url'] = $request->input('linkedin_url');


        if($arrayUpdate){

            Auth::user()->update($arrayUpdate);
            $update = true;
        }

        if($update){
            return redirect()->route('profile.edit')
            ->with('success', 'Your profile has been updated.');
        }
        return redirect()->route('profile.edit');


    }

    public function getChangePassword(){
        return view('profile.change-password');
    }

    public function postChangePassword(Request $request){

        $this->validate($request,[
            'current_password' => 'required|min:6|max:255',
            'password' => 'required|min:6|max:255|confirmed',
            'password_confirmation' => 'required|min:6|max:255',
        ]);

        if(!Hash::check($request->input('current_password'), Auth::user()->password)){
            notify()->flash('Oops...', 'error', [
                'text' => 'Your current password wrong.'
            ]);
            return redirect()->back();
        }

        if($request->input('current_password') != $request->input('password')){
            Auth::user()->update([
                'password' => bcrypt($request->input('password')),
            ]);
        }

        notify()->flash('Success', 'success', [
            'text' => 'Your password was changed.'
        ]);
        return redirect()->back();
    }
}
