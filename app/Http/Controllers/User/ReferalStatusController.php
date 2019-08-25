<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;

use App\Traits\ReferalStatus;

class ReferalStatusController extends Controller
{

    use ReferalStatus;

    public function getReferalStatusList() {
        return $this->getAllReferalStatusList();
    }

    public function storeReferalStatus(Request $request) {
        return $this->storeReferalStatus($request);
    }

    public function edit($id) {
        return $this->editReferalStatus($id);
    }

    public function update(Request $request) {
      return $this->updateReferalStatus($request);
    }

    // Tempararly commented...will uncomment when it is required
    // public function destroy($request) {
        // return $this->destroyReferalStatus($request);
    // }
    
    public function getReferalStatusDetails($id) {
        return $this->getReferalStatusInfo($id);
    }
}
