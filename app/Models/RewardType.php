<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardType extends Model
{
	use SoftDeletes;
    public function products() {
        return $this->belongsTo('App\Product','reward_type_id');
    }
}
