<?php

namespace Coder\Http\Controllers;

use Auth;
use Mail;
use Hash;
use Config;
use Socialite;
use Coder\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AuthController extends Controller
{


    public function getSignup()
    {
        return view('auth.signup');
    }

    public function postSignup(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required|min:6|max:255|confirmed',
            'password_confirmation' => 'required|min:6|max:255',
            'g-recaptcha-response' => 'required'
        ]);

        $recaptcha = new \ReCaptcha\ReCaptcha(Config::get('site.recaptcha_secret'));
        $resp = $recaptcha->verify($request->input('g-recaptcha-response'));
        if (!$resp->isSuccess()) {
            return redirect()->back();
        }

        $ipInfo = getIpInfo($request->getClientIp());

        $identifier = str_random(128);

        $arrayUser = [
            'email' => $request->input('email'),
            'username' => "u-" . uniqid(),
            'password' => bcrypt($request->input('password')),
            'active' => false,
            'active_hash' => Hash::make($identifier),
            'ban' => false
        ];

        if($ipInfo){
            if(isset($ipInfo['city'])) $arrayUser['city'] = $ipInfo['city'];
            if(isset($ipInfo['city'])) $arrayUser['location'] = $ipInfo['city'];
            if(isset($ipInfo['country'])) $arrayUser['country'] = $ipInfo['country'];
            if(isset($ipInfo['countryCode'])) $arrayUser['country_code'] = $ipInfo['countryCode'];
            if(isset($ipInfo['lat'])) $arrayUser['lat'] = $ipInfo['lat'];
            if(isset($ipInfo['lon'])) $arrayUser['lon'] = $ipInfo['lon'];
            if(isset($ipInfo['region'])) $arrayUser['region'] = $ipInfo['region'];
            if(isset($ipInfo['regionName'])) $arrayUser['regionName'] = $ipInfo['regionName'];
            if(isset($ipInfo['timezone'])) $arrayUser['timezone'] = $ipInfo['timezone'];
        }else{
            $arrayUser['timezone'] = "UTC";
        }

        $user = User::create($arrayUser);
        $user->permissions()->create([
                'is_admin' => false,
                'is_superadmin' => false,
        ]);

        Mail::send('emails.auth.signup', ['user' => $user, 'identifier' => $identifier], function ($message) use ($user){
          $message->subject('Thanks for registering.')
                  ->to($user->email);
        });

        notify()->flash($request->input('email'), 'success', [
            'text' => 'Please check your email to active account before login.'
        ]);
        return redirect()->route('auth.signin');
    }

    public function getSignin()
    {
        return view('auth.signin');
    }

    public function postSignin(Request $request)
    {
        $this->validate($request,[
                'email' => 'required|email|max:255',
                'password' => 'required|min:6',
            ]);

        if(!Auth::attempt($request->only(['email', 'password']), $request->has('remember'))){
            notify()->flash('Oops...', 'error', [
                'text' => 'Email or password wrong.'
            ]);
            return redirect()->back();
        }

        if(!Auth::user()->active){
            $user = Auth::user();
            Auth::logout();
            notify()->flash($user->email, 'error', [
                'text' => 'Your account not yet active, please check your email.'
            ]);
            return redirect()->back();
        }

        if(Auth::user()->ban){
            $user = Auth::user();
            Auth::logout();
            notify()->flash('Banned', 'error', [
                'text' => $user->ban_reason
            ]);
            return redirect()->back();
        }

        return redirect()->route('home');
    }

    public function  getSignout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    public function getActivate(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email|max:255',
            'identifier' => 'required',
        ]);

        $identifier = $request->input('identifier');

        $user = User::where('email', $request->input('email'))
                    ->where('active', false)->first();

        if(!$user || !Hash::check($identifier, $user->active_hash)){
            notify()->flash('Oops...', 'error', [
                'text' => 'There was a problem activating your account.'
            ]);
            return redirect()->route('home');
        }

        $user->update([
            "active" => true,
            "active_hash" => null,
            'active_at' => Carbon::now()->toDateTimeString()
        ]);

        Auth::login($user);

        notify()->flash('Welcome', 'success', [
            'text' => 'Your account has been activated.'
        ]);

        return redirect()->route('home');
    }

    public function getRedirectToProvider($driver)
   {
       return Socialite::driver($driver)->redirect();
   }

   public function getHandleProviderCallback($driver, Request $request)
   {

       if($request->input('error')){
           return redirect()->route('auth.signup');
       }

       $user = Socialite::driver($driver)->user();

       $id = $user->getId();
       $nick_name = $user->getNickname();
       $first_name = $user->getName();
       $last_name = null;
       $email = $user->getEmail();
       $avatar = $user->getAvatar();

       if(!$email){
           notify()->flash('Sorry '. $first_name . '!', 'error', [
               'text' => 'Your ' .$driver. ' account have not email. Please login your social network with email address.'
           ]);
           return redirect()->route('auth.signup');
       }

       $url = null;
       if($driver == "linkedin"){
           if(isset($user->user['publicProfileUrl'])) $url = $user->user['publicProfileUrl'];
           if(isset($user->user['firstName'])) $first_name = $user->user['firstName'];
           if(isset($user->user['lastName'])) $last_name = $user->user['lastName'];
       }
       if($driver == "google"){
           if(isset($user->user['url'])) $url = $user->user['url'];
           if(isset($user->user['name']['givenName'])) $first_name = $user->user['name']['givenName'];
           if(isset($user->user['name']['familyName'])) $last_name = $user->user['name']['familyName'];
       }
       if($driver == "facebook"){
           $url = "http://www.facebook.com/" . $user->getId();
           if(isset($user->user['first_name'])) $first_name = $user->user['first_name'];
           if(isset($user->user['last_name'])) $last_name = $user->user['last_name'];
       }

        $user = User::where('email', $email)->first();

        if(!$user){
            //register new account

            $random_password = str_random(6);
            $ipInfo = getIpInfo($request->getClientIp());

            $arrayUser = [
                'email' => $email,
                'username' => "u-" . uniqid(),
                'first_name' => $first_name,
                'last_name' => $last_name,
                'password' => bcrypt($random_password),
                'active' => true,
                $driver . '_id' => $id,
                $driver . '_url' => $url,
                'ban' => false
            ];
            if($ipInfo){
                if(isset($ipInfo['city'])) $arrayUser['city'] = $ipInfo['city'];
                if(isset($ipInfo['city'])) $arrayUser['location'] = $ipInfo['city'];
                if(isset($ipInfo['country'])) $arrayUser['country'] = $ipInfo['country'];
                if(isset($ipInfo['countryCode'])) $arrayUser['country_code'] = $ipInfo['countryCode'];
                if(isset($ipInfo['lat'])) $arrayUser['lat'] = $ipInfo['lat'];
                if(isset($ipInfo['lon'])) $arrayUser['lon'] = $ipInfo['lon'];
                if(isset($ipInfo['region'])) $arrayUser['region'] = $ipInfo['region'];
                if(isset($ipInfo['regionName'])) $arrayUser['regionName'] = $ipInfo['regionName'];
                if(isset($ipInfo['timezone'])) $arrayUser['timezone'] = $ipInfo['timezone'];
            }else{
                $arrayUser['timezone'] = "UTC";
            }

            $user = User::create($arrayUser);
            $user->permissions()->create([
                    'is_admin' => false,
                    'is_superadmin' => false,
            ]);
            Mail::send('emails.auth.signup', ['user' => $user, 'password' => $random_password], function ($message) use ($user){
              $message->subject('Thanks for registering.')
                      ->to($user->email);
            });

            notify()->flash('Welcome', 'success', [
                'text' => 'You are now signed in.'
            ]);
        }

        Auth::login($user, true);

        if(!Auth::user()->getSocialId($driver)){
            Auth::user()->update([
                $driver . '_id' => $id,
                $driver . '_url' => $url,
            ]);
        }

        if(!Auth::user()->active){
            Auth::user()->update([
                'active' => true,
                'active_hash' => null,
                'active_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        return redirect()->route('home');
   }
   public function getRecoverPassword()
   {
       return view('auth.password.recover');
   }
   public function postRecoverPassword(Request $request)
   {
       $this->validate($request,[
           'email' => 'required|email|max:255',
           'g-recaptcha-response' => 'required'
       ]);

       $recaptcha = new \ReCaptcha\ReCaptcha(Config::get('site.recaptcha_secret'));
       $resp = $recaptcha->verify($request->input('g-recaptcha-response'));
       if (!$resp->isSuccess()) {
           return redirect()->back();
       }

       $user = User::where('email', $request->input('email'))->first();

       if(!$user){
           notify()->flash($request->input('email'), 'success', [
               'text' => 'We have emailed you instruction to reset your password.'
           ]);
       }else{
           $identifier = str_random(128);
           $user->update([
                'recover_hash' => Hash::make($identifier)
            ]);

            Mail::send('emails.auth.password.recover', ['user' => $user, 'identifier' => $identifier], function ($message) use ($user){
              $message->subject('Recover your password.')
                      ->to($user->email);
            });

            notify()->flash($request->input('email'), 'success', [
                'text' => 'We have emailed you instruction to reset your password.'
            ]);
       }

       return redirect()->back();
   }

   public function getPasswordReset(Request $request)
   {
       $this->validate($request,[
           'email' => 'required|email|max:255',
           'identifier' => 'required',
       ]);

       $identifier = $request->input('identifier');

       $user = User::where('email', $request->input('email'))
                   ->where('active', false)->first();

       if(!$user || !Hash::check($identifier, $user->recover_hash)){
           return redirect()->route('home');
       }

       return view('auth.password.reset', [
           'email' => $user->email,
           'identifier' => $identifier
        ]);

   }
   public function postPasswordReset(Request $request)
   {
       $this->validate($request,[
           'email' => 'required|email|max:255',
           'identifier' => 'required',
           'password' => 'required|min:6|confirmed',
           'password_confirmation' => 'required|min:6',
       ]);

       $identifier = $request->input('identifier');

       $user = User::where('email', $request->input('email'))
                   ->where('active', false)->first();

       if(!$user || !Hash::check($identifier, $user->recover_hash)){
           return redirect()->route('home');
       }

       $user->update([
           'password' => Hash::make($request->input('password')),
           'recover_hash' => null
       ]);

       notify()->flash('Success', 'success', [
           'text' => 'Your password has been reset and you can now sign in.'
       ]);

       return redirect()->route('auth.signin');
   }
}
