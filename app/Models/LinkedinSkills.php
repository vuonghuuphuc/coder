<?php

namespace Coder\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedinSkills extends Model
{
    protected $table = 'linkedin_skills';

    protected $fillable = [
        'name',
    ];
}
