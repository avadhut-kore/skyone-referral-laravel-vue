<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masterpwd extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'master_password';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','password','datetime','tran_password','master_otp'
    ];
    public $timestamps = false; 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}