<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;

use App\Traits\Referal;

class ReferalController extends Controller
{
    use Referal;

    public function getReferals() {
        return $this->getAllReferals();		
    }

    public function store(Request $request) {
 	  return $this->storeReferal($request);     
    }

    public function edit($id) {
    	return $this->editReferal($id);
    }

    public function update(Request $request) {
        return $this->updateReferal($request);
    }

    // Tempararly commented...will uncomment when it is required
    //    public function destroy(Request $request) {
	   // return $this->destroyReferal($request);	
	// }
	
	public function getReferalDetails($id) {
        return $this->getReferalInfo($id);
    }
}