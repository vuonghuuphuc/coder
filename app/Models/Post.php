<?php

namespace Coder\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'hash_id',
        'title',
        'title_url',
        'description',
        'keywords',
        'active',
        'active_at',
        'active_by',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo('Coder\Models\User', 'user_id');
    }

    public function activator()
    {
        return $this->belongsTo('Coder\Models\User', 'active_by');
    }

    public function skills()
    {
        return $this->belongsToMany('Coder\Models\Skill', 'posts_skills', 'post_id', 'skill_id');
    }

    public function users_bookmarked()
    {
        return $this->belongsToMany('Coder\Models\User', 'bookmarks', 'post_id', 'user_id');
    }

    public function files()
    {
        return $this->belongsToMany('Coder\Models\File', 'posts_files', 'post_id', 'file_id');
    }

    public function likes()
    {
        return  $this->morphMany('Coder\Models\Like', 'likeable');
    }
    public function replies()
    {
        return $this->hasMany('Coder\Models\Status', 'post_id');
    }
}
