<?php

namespace Coder\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('auth.signin');
            }
        }else{
            $user = $this->auth->user();
            if($user->ban){
                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    $this->auth->logout();
                    notify()->flash('Banned', 'error', [
                        'text' => $user->ban_reason
                    ]);
                    return redirect()->route('auth.signin');
                }
            }
        }

        /*$ipInfo = getIpInfo($request->getClientIp());
        if($ipInfo){
            if(isset($ipInfo['timezone'])){
                if($ipInfo['timezone'] != $this->auth->user()->timezone){
                    $this->auth->user()->update([
                        'timezone' => $ipInfo['timezone']
                    ]);
                }
            }
        }*/
        return $next($request);
    }
}
