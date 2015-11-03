<?php

namespace Coder\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';

    protected $fillable = [
        'body',
    ];

    public function user()
    {
        return $this->belongsTo('Coder\Models\User', 'user_id');
    }

    public function scopeNotReply($query)
    {
        return $query->whereNull('parent_id')->whereNull('post_id');
    }

    public function replies()
    {
        return $this->hasMany('Coder\Models\Status', 'parent_id');
    }


    public function likes()
    {
        return  $this->morphMany('Coder\Models\Like', 'likeable');
    }
}
