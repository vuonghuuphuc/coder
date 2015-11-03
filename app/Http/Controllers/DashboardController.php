<?php

namespace Coder\Http\Controllers;

use DB;
use Coder\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getIndex()
    {
        return view('admin.dashboard.index');
    }
}
