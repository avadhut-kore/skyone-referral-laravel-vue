<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferalStatus extends Model
{
	use SoftDeletes;

    public function referal() {
        return $this->belongsTo('App\Referal','referal_id');
    }
}
