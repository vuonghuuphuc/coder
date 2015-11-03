<?php

namespace Coder\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'directory',
        'file_name',
        'thumbnail',
        'original_name',
        'extension',
        'size',
    ];

    public function user()
    {
        return $this->belongsTo('Coder\Models\User', 'user_id');
    }

    public function url()
    {
        return asset($this->directory . $this->file_name);
    }

    public function thumbnail_url()
    {
        return asset($this->directory . $this->thumbnail);
    }

    public function posts()
    {
        return $this->belongsToMany('Coder\Models\Post', 'posts_files', 'file_id', 'post_id');
    }
}
