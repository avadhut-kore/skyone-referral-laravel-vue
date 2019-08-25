<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{	
	use SoftDeletes;

    public function product() {
        return $this->hasMany('App\Product','category_id');
    }

    // public function childrens() {
    //     return $this->hasMany('App\Category','category_parent_id');
    // }
}
