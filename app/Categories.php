<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'is_published'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function goods()
    {
        return $this->belongsToMany('App\Goods', 'goods_categories_assignments')
                    ->as('goods');
    }
}
