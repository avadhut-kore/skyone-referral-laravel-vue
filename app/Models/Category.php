<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Product;
class Category extends Model
{	
	use SoftDeletes;

    public function product() {
        return $this->hasMany('App\Models\Product','category_id');
    }

    // public function childrens() {
    //     return $this->hasMany('App\Category','category_parent_id');
    // }
}
