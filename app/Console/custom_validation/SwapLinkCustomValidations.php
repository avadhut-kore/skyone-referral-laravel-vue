<?php

namespace App\Console\custom_validation;
use Illuminate\Contracts\Validation\Rule;

class SwapLinkCustomValidations implements Rule
{   
	 public function  __construct($para=0,$comp=''){
	 	
        $this->cond = 0;
        $this->para = $para;
        $this->comp = $comp;
        //$this->tocomp = $tocomp;

	 }

    public function passes($attribute='', $value)
    {  // echo $attribute."<br>";
      /** common required validation for all attributes **/
      if($this->para==0){
       
       if($value=='' || $value==null){
            $this->cond=1;            
            return false;
        }

      } 
       // dd($attribute);
     /** validation For particular Fullname  attribute ***/
      if($attribute=='commit_id'){
        //dd('hi');
        if(!preg_match("/^\d+$/", $value)) {
             $this->cond=9;
             return false;
        }
       }/** validation For particular lastname  attribute ***/
      elseif($attribute=='req_no'){
        
        if(!preg_match("/^\d+$/", $value)) {
             $this->cond=2;
             return false;
        }
        elseif($this->comp!= '' && $this->comp==$value) {
             $this->cond=3;
             return false; 
        }else{
           return true;  
        }
       }/** validation For amount ***/
      
       elseif($attribute == 'amount'){        
         // dd(strlen($value));
        if(!preg_match("/^\d{1,4}(\.\d{1,4})?$/",$value)){
            $this->cond=3;
             return false; 
        }else{
            //dd('hi');
             return true;  
        }
        
       }
     
        
    }

    public function message()
    {    echo $this->cond;
    	 if($this->cond==1){
    	 	$this->cond=0;
            return ':attribute field is required!';
    	 }
         elseif($this->cond==2 || $this->cond==9){
            $this->cond=0;
            return ':attribute - Must be integer !';
         }
         elseif($this->cond==3){
            $this->cond=0;
            return ':attribute - Not a valid Amount!';
       
         }else{
            //do nothing
         }
         
        







        
    }
}
