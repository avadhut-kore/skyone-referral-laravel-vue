<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referal extends Model
{
	use SoftDeletes;

    public function product() {
        return $this->belongsTo('App\Product','product_id');
    }

    public function user() {
        return $this->belongsTo('App\User','refered_by');
    }

    public function referal_status() {
        return $this->hasMany('App\ReferalStatus','referal_id');
    }
}
