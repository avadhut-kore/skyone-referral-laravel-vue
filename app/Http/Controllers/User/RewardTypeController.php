<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;

use App\Traits\RewardType;

class RewardTypeController extends Controller
{

	use RewardType;

    public function getRewardTypes()
    {
        return $this->getAllRewardTypes();
    }

    public function store(Request $request)
    {
        return $this->storeRewardType($request);
    }

    public function edit($id)
    {
        return $this->editRewardType($id);
    }

    public function update($request)
    {
    	return $this->updateRewardType($request);
    }
    
	// Tempararly commented...will uncomment when it is required
    // public function destroy(Request $request)
    // {
    	// return $this->destroyRewardType($request);
    // }

    public function getRewardTypeDetails($id) {
    	return $this->getRewardTypeInfo($id);
    }
}
