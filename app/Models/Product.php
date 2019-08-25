<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
	
    public function referals() {
        return $this->belongsTo('App\Models\Referal','product_id');
    }

    public function category() {
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function reward_type() {
        return $this->belongsTo('App\Models\RewardType','reward_type_id');
    }
}
