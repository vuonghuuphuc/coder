<?php

namespace Coder\Http\Controllers;

use Validator;
use Coder\Models\Skill;
use Illuminate\Http\Request;

class RemoteController extends Controller
{
    public function postSkills(Request $request)
    {
        $query = $request->input('q');
        $validator = Validator::make(['query' => $query], [
            'query' => 'required|min:2|max:50',
        ]);

        if ($validator->fails()) {
            return [];
        }
        $kills = Skill::where(
            'text',
            'LIKE', "{$query}%"
        )->get()->toJson();
        return $kills;
    }
}
