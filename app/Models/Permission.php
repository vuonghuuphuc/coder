<?php

namespace Coder\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'is_admin',
        'is_superadmin'
    ];
}
