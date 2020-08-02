<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'price', 'is_published'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function categories()
    {
        return $this->belongsToMany('App\Categories', 'goods_categories_assignments')
                    ->as('categories');
    }

    /**
     * Updates categories for good model
     * @param array $categories
     */
    public function updateCategories(array $categories = null)
    {
        //delete old categories
        $this->categories()->detach();
        //add new
        $this->categories()->attach($categories);
    }

}
