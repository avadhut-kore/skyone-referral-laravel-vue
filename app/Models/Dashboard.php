<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
  
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_dashboard';
    protected $primaryKey = 'srno';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'srno','id', 'coin','btc','total_investment','active_investment','direct_income','direct_income_withdraw','level_income','  level_income_withdraw','level_income_ico','roi_income','roi_income_withdraw','total_withdraw','total_profit'
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
