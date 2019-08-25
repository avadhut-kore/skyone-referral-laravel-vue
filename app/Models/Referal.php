<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referal extends Model
{
	use SoftDeletes;

    public function product() {
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function user() {
        return $this->belongsTo('App\User','refered_by','id');
    }

    public function referal_status() {
        return $this->hasOne('App\Models\ReferalStatus','referal_id');
    }
}
