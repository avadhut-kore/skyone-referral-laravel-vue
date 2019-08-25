<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    public $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','city','mobile_no', 'email', 'password','profession'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function referal() {
        return $this->hasMany('App\Referal','refered_by');
    }
    
    public $timestamps  = false;

    /**
     * Get the login user_id to be used by the controller.
     *
     * @return string
     */
    public static function username() {
        return 'user_id';
    }

    /**
     * Function to define the validation rules & messages
     * 
     * @param $intId : Id which avoid the unique constraint on email while editing
     * 
     */ 
    public static function getValidationRulesMessages( $intId = NULL) {
        $arrOutputData  = [];
        $arrOutputData['arrMessage'] = array(
            'password.regex'    =>'Password contains letters and numbers only without spaces',
            'email.email'       =>'Email should be in format abc@abc.com',
            'first_name.regex'    =>'Special characters not allowed in fullname',
            'last_name.regex'     =>'Special characters not allowed in lastname',
           // 'user_id.regex'     =>'No space or special charachers allowed in user_id',
            'mobile_no.min'        =>'Mobile No. should be 10 digits',
            'mobile_no.max'        =>'Mobile No. not more than 20 digits',
            'user_id.regex'     =>'Special charachers not allowed in username',
            'user_id.min'       =>'Username requires minimum 7 charachers.',
           'user_id.max'       =>'Username not more than 15 charachers.',
            'user_id'           =>'Username Required',
            'ref_user_id'       =>'Referance user id required',
            'city'              =>'City required',
            'profession'        =>'Profession Required ',
            
        );

        $arrOutputData['arrRules'] = array(
            'first_name'      => 'required|max:30|regex:/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/',
            'last_name'      => 'required|max:30|regex:/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/',
            // 'user_id'       =>  'required|min:3|max:15|regex:/^[a-zA-Z][a-zA-Z0-9]*$/|unique:tbl_users,user_id,'.$intId,
             'email'         => 'required|email|max:30',
            // for regular password regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#])[A-Za-z\d$@$!%*?&#]{7,}/
            'password'      =>'required|confirmed',
            'mobile_no'        =>  'required|min:10|max:20',
            
            'city'               =>'required',
            'profession'        =>'required',

        );
        return $arrOutputData;
    }

    /**
     * Override the password field for the passport authentication of the user .
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->bcrypt_password;
    }
    
    /**
     * Function to overrite the findForPassport method
     * 
     */ 
    public function findForPassport($username)
    {
        return $this->where('user_id', $username)->first();
    }

    protected $appends = [];
}