<?php

namespace Coder\Http\Controllers;

use DB;
use Auth;
use Mail;
use Config;
use Coder\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function getIndex()
    {
        return view('contact.index');
    }
    
    public function postSend(Request $request)
    {
        if(Auth::check()){
            $this->validate($request, [
            "message" => "required|min:5|max:1000",
            ]);
            
            $email = Auth::user()->email;
        }else{
            $this->validate($request, [
            "email" => "required|email|max:255",
            "message" => "required|min:5|max:1000",
            'g-recaptcha-response' => 'required'
            ]);
            
            $recaptcha = new \ReCaptcha\ReCaptcha(Config::get('site.recaptcha_secret'));
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'));
            if (!$resp->isSuccess()) {
                return redirect()->back();
            }
            
            $email = $request->input('email');
        }
        
        $mess = $request->input('message');
        
        $admins = User::whereHas('permissions', function ($query) {
            $query->where('is_admin', true);
        })->get();
        
        $from = $email;
        foreach($admins as $admin){
            $to = $admin->email;
            Mail::send('emails.contact.send', ['mess' => $mess, 'from' => $from], function ($message) use ($to){
                $message->subject('Contact us')
                ->to($to);
            });
        }
        
        
        
        notify()->flash('Thank you', 'success', [
        'text' => 'Your message sent to our inbox.'
        ]);
        return redirect()->route('contact');
    }
}