<?php

namespace App\Http\Controllers;

use App\RewardType;
use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;

class RewardTypeController extends Controller
{
    protected $reward_type;
    public function __construct(RewardType $reward) {
    	$this->reward_type = $reward;
    }

    public function getRewardTypes()
    {
        $reward_type = $this->reward_type->get();
    	if($reward_type->count() == 0) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Reward types not found',
				'data' => [],
			],200);
    	}

        $reward_types = [];
        foreach($reward_type as $key => $reward) {
            array_push($reward_types, [
                'id' => $reward->id,
                'name' => $reward->name,
                'unit_of_measurement' => $reward->unit_of_measurement,
                'is_variable' => $reward->is_variable
            ]);
        }

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Reward types found',
			'data' => $reward_types
		],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
    		'name' => 'required',
    		'unit_of_measurement' => 'required',
    		'is_variable' => 'required'
    	]);

    	if($validator->fails()) {
    		return response()->json([
    			'status' => 'error',
    			'code' => 400,
    			'msg' => 'validation errors occured',
    			'errors' => $validator->errors(),
    			'data' => []
    		],200);
    	}
	 	else {

	    	$this->reward_type->name = $request->Input('name');
	    	$this->reward_type->unit_of_measurement = $request->Input('unit_of_measurement');
	    	$this->reward_type->is_variable = $request->Input('is_variable');
	    	
	    	if(!$this->reward_type->save()) {
	    		return response()->json([
	    			'status' => 'error',
	    			'code' => 404,
	    			'msg' => 'Error occurred white saving reward type..!please try again',
	    			'data' => []
	    		],200);
	    	}

	    	return response()->json([
				'status' => 'success',
				'code' => 200,
				'msg' => 'Reward type saved successfully',
				'data' => [
					'id' =>$this->reward_type->id,
	                'name' =>$this->reward_type->name,
	                'unit_of_measurement' =>$this->reward_type->unit_of_measurement,
	                'is_variable' =>$this->reward_type->is_variable
				]
			],200);
	 	}
    }

    public function edit($id)
    {
        $reward_type = $this->reward_type->where('id',$id)->first();

    	if(!isset($reward_type->id)) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Reward Type not found',
				'data' => [],
			],200);
    	}

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Reward Type found',
			'data' => [
				'id' =>$reward_type->id,
                'name' =>$reward_type->name,
                'unit_of_measurement' =>$reward_type->unit_of_measurement,
                'is_variable' =>$reward_type->is_variable
			]
		],200);
    }

    public function update(Request $request)
    {
        $id = $request->Input('id');
    	$validator = Validator::make($request->all(), [
    		'name' => 'required',
    		'unit_of_measurement' => 'required',
    		'is_variable' => 'required'
    	]);

    	if($validator->fails()) {
    		return response()->json([
    			'status' => 'error',
    			'code' => 400,
    			'msg' => 'validation errors occured',
    			'errors' => $validator->errors(),
    			'data' => []
    		],200);
    	}
	 	else {
			
			$arr = [
				'name' => $request->Input('name'),
				'unit_of_measurement' => $request->Input('unit_of_measurement'),
				'is_variable' => $request->Input('is_variable')
            ];
	    	
	    	if(!$this->reward_type->where('id',$id)->update($arr)) {
	    		return response()->json([
	    			'status' => 'error',
	    			'code' => 404,
	    			'msg' => 'Error occurred while updating product..!please try again',
	    			'data' => [],
	    		],200);
	    	}

	    	$reward_type = $this->reward_type->where('id',$id)->first();

	    	return response()->json([
				'status' => 'success',
				'code' => 200,
				'msg' => 'Product updated successfully',
				'data' => [
					'id' =>$reward_type->id,
	                'name' =>$reward_type->name,
	                'unit_of_measurement' =>$reward_type->unit_of_measurement,
	                'is_variable' =>$reward_type->is_variable
				],
			],200);
	 	}
    }
	// Tempararly commented...will uncomment when it is required
    // public function destroy()
    // {
    //     $id = $request->Input('id');
		
	// 	if(!$this->product->where('id',$id)->delete()) {
	// 		return response()->json([
	// 			'status' => 'error',
	// 			'code' => 404,
	// 			'msg' => 'Error occurred while deleting product..!please try again',
	// 			'data' => [],
	// 		],200);
	// 	}

	// 	return response()->json([
	// 		'status' => 'success',
	// 		'code' => 200,
	// 		'msg' => 'Product deleted successfully'
	// 	],200);
    // }

    public function getRewardTypeDetails($id) {

        $reward_type = $this->reward_type->where('id',$id)->first();
        
        if(!isset($reward_type->id)) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Reward Type not found',
				'data' => [],
			],200);
    	}

        $reward_type_data = [
            'id' => $reward_type->id,
            'name' => $reward_type->name,
            'unit_of_measurement' => $reward_type->unit_of_measurement,
            'is_variable' => $reward_type->is_variable
        ];

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Reward Type Data found',
			'data' => $reward_type_data
		],200);
    }
}
