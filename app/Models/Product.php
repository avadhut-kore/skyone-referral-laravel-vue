<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
	
    public function referals() {
        return $this->belongsTo('App\Product','product_id');
    }

    public function category() {
        return $this->belongsTo('App\Category','category_id');
    }

    public function reward_type() {
        return $this->belongsTo('App\RewardType','reward_type_id');
    }
}
