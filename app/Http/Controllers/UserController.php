<?php

namespace Coder\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Coder\Models\User;
use Request;
use Response;
use View;

class UserController extends Controller
{
    public function getIndex()
    {
        $users = User::whereHas('permissions', function ($query) {
            $query->where('is_superadmin', false);
        })->orderBy('created_at', 'desc')->paginate(2);

        if (Request::ajax()) {
            return Response::json(View::make('admin.user.users-ajax', array('users' => $users))->render());
        }

        return view('admin.user.users')->with(['users' => $users]);
    }
}
