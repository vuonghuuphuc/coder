<?php

namespace Coder\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'skills';

    public $timestamps = false;

    protected $fillable = [
        'text',
    ];

    protected $hidden = [
        'pivot'
    ];

    public function posts()
    {
        return $this->belongsToMany('Coder\Models\Post', 'posts_skills', 'skill_id', 'post_id');
    }
}
