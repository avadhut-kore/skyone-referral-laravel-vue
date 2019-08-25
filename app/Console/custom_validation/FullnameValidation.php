<?php

namespace App\Console\custom_validation;
use Illuminate\Contracts\Validation\Rule;

class FullnameValidation implements Rule
{   
	 public function __construct(){
	 	$this->cond=0;
	 }

    public function passes($attribute, $value)
    { 

        if($value=='' || $value==null){
        	$this->cond=1;
        	//dd($this->cond);
        	return false;
        }
        
        if(preg_match("/^[a-zA-Z]+$/", $value)) {
             return true;
        }
        
    }

    public function message()
    { 
    	 if($this->cond==1){
    	 	return ':attribute field is required!';
    	 }
        return ':attribute - You can enter only alphabets!';
    }
}
