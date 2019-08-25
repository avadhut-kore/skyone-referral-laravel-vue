<?php

namespace App\Console\custom_validation;
use Illuminate\Contracts\Validation\Rule;

class test implements Rule
{   
	 public function __construct(){
	 	$this->cond=0;
	 }
    public function passes($attribute, $value)
    { 

        //return $value > 10;
        if($value=='' || $value==null){
        	$this->cond=1;
        	//dd($this->cond);
        	return false;
        }
        
        if($value==1 || $value==2){
             return true;
        }
    }

    public function message()
    { 
    	 if($this->cond==1){
    	 	return ':attribute field is required!';
    	 }
        return ':attribute value should be right or left!';
    }
}
