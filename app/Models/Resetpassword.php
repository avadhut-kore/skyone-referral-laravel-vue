<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resetpassword extends Model
{
  
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_user_reset_password';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'otp_id','id', 'request_ip_address','ip_address','reset_password_token','otp_status','out_time'
    ];
    public $timestamps = false; 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}