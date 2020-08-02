<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'price', 'is_published'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
