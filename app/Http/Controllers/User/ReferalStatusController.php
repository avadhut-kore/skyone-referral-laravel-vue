<?php

namespace App\Http\Controllers;

use App\Referal;
use App\ReferalStatus;
use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;

class ReferalStatusController extends Controller
{
    protected $referal_status;
    public function __construct(ReferalStatus $ref) {
    	$this->referal_status = $ref;
    }

    public function getReferalStatusList() {

		$referal_status = $this->referal_status->with(['referal' => function($query) {
            $query->with('product','user')->first();
        }])->get();
        
        if($referal_status->count() == 0) {
            return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Referal Status data not found',
				'data' => [],
			],200);
        }

        $referal_data = [];
		foreach($referal_status as $key => $referal) {
            $arr = [
                'id' => $referal->id,
                'referal_id' => $referal->referal_id,
                'is_contacted' => $referal->is_contacted,
                'is_interested' => $referal->is_interested,
                'is_purchased' => $referal->is_purchased,
                'is_referal_rewarded' => $referal->is_referal_rewarded,
                'is_refered_by_rewarded' => $referal->is_refered_by_rewarded,
                'referal_reward_type' => $referal->referal_reward_type,
                'refered_by_reward_type' => $referal->refered_by_reward_type,
                'referal_reward_amount' => $referal->referal_reward_amount,
                'refered_by_reward_amount' => $referal->refered_by_reward_amount,
                'referal' => [
                        'id' => $referal->referal->id,
                        'first_name' => $referal->referal->first_name,
                        'last_name' => $referal->referal->last_name,
                        'email' => $referal->referal->email,
                        'mobile_no' => $referal->referal->mobile_no,
                        'refered_by' => [
                            'id' => $referal->referal->user->id,
                            'first_name' => $referal->referal->user->first_name,
                            'last_name' => $referal->referal->user->last_name,
                            'email' => $referal->referal->user->email,
                            'city' => $referal->referal->user->city,
                            'mobile_no' => $referal->referal->user->mobile_no,
                            'profession' => $referal->referal->user->profession
                        ],
                        'product' => [
                            'id' => $referal->referal->product->id,
                            'name' => $referal->referal->product->name,
                            'description' => $referal->referal->product->description,
                            'category_id' => $referal->referal->product->category_id,
                            'is_active' => $referal->referal->product->is_active,
                            'reward_type_id' => $referal->referal->product->reward_type_id,
                            'image' => ( $referal->referal->product->image != '') ? base_path().'assets/images/products/'.$referal->referal->product->image : ''
                        ]
                    ]
                ];

            array_push($referal_data,$arr);
        }
        
    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Referal Status Data found',
			'data' => $referal_data
		],200);
    }

    public function store(Request $request) {
 		
 		$validator = Validator::make($request->all(), [
    		'referal_reward_type' => 'required',
            'referal_reward_amount' => 'required',
    		'refered_by_reward_type' => 'required',
            'refered_by_reward_amount' => 'required'
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

            $this->referal_status->referal_id = $request->Input('referal_id');
	    	$this->referal_status->is_contacted = $request->Input('is_contacted');
	    	$this->referal_status->is_interested = $request->Input('is_interested');
	    	$this->referal_status->is_purchased = $request->Input('is_purchased');
	    	$this->referal_status->is_referal_rewarded = $request->Input('is_referal_rewarded');
	    	$this->referal_status->is_refered_by_rewarded = $request->Input('is_refered_by_rewarded');
            $this->referal_status->referal_reward_type = $request->Input('referal_reward_type');
	    	$this->referal_status->refered_by_reward_type = $request->Input('refered_by_reward_type');
	    	$this->referal_status->referal_reward_amount = $request->Input('referal_reward_amount');
	    	$this->referal_status->refered_by_reward_amount = $request->Input('refered_by_reward_amount');
	    	
	    	if(!$this->referal_status->save()) {
	    		return response()->json([
	    			'status' => 'error',
	    			'code' => 404,
	    			'msg' => 'Error occurred while saving referal status..!please try again',
	    			'data' => []
	    		],200);
	    	}

	    	return response()->json([
				'status' => 'success',
				'code' => 200,
				'msg' => 'Referal Status saved successfully',
				'data' => [
                    'id' => $this->referal_status->id,
                    'referal_id' => $this->referal_status->referal_id,
                    'is_contacted' => $this->referal_status->is_contacted,
                    'is_interested' => $this->referal_status->is_interested,
                    'is_purchased' => $this->referal_status->is_purchased,
                    'is_referal_rewarded' => $this->referal_status->is_referal_rewarded,
                    'is_refered_by_rewarded' => $this->referal_status->is_refered_by_rewarded,
                    'referal_reward_type' => $this->referal_status->referal_reward_type,
                    'refered_by_reward_type' => $this->referal_status->refered_by_reward_type,
                    'referal_reward_amount' => $this->referal_status->referal_reward_amount,
                    'refered_by_reward_amount' => $this->referal_status->refered_by_reward_amount
				]
			],200);
	 	}	
    }

    public function edit($id) {

    	$referal_status = $this->referal_status->where('id',$id)->first();

    	if(!isset($referal_status->id)) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Referal Status not found',
				'data' => [],
			],200);
    	}
        
    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Referal Status found',
			'data' => [
                'id' => $referal_status->id,
                'referal_id' => $referal_status->referal_id,
                'is_contacted' => $referal_status->is_contacted,
                'is_interested' => $referal_status->is_interested,
                'is_purchased' => $referal_status->is_purchased,
                'is_referal_rewarded' => $referal_status->is_referal_rewarded,
                'is_refered_by_rewarded' => $referal_status->is_refered_by_rewarded,
                'referal_reward_type' => $referal_status->referal_reward_type,
                'refered_by_reward_type' => $referal_status->refered_by_reward_type,
                'referal_reward_amount' => $referal_status->referal_reward_amount,
                'refered_by_reward_amount' => $referal_status->refered_by_reward_amount,
			]
		],200);
    }

    public function update(Request $request) {
        
		$id = $request->Input('id');
    	$validator = Validator::make($request->all(), [
    		'referal_reward_type' => 'required',
            'referal_reward_amount' => 'required',
    		'refered_by_reward_type' => 'required',
            'refered_by_reward_amount' => 'required'
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
				'is_contacted' => $request->Input('is_contacted'),
				'is_interested' => $request->Input('is_interested'),
				'is_purchased' => $request->Input('is_purchased'),
				'is_referal_rewarded' => $request->Input('is_referal_rewarded'),
				'is_refered_by_rewarded' => $request->Input('is_refered_by_rewarded'),
                'referal_reward_type' => $request->Input('referal_reward_type'),
				'refered_by_reward_type' => $request->Input('refered_by_reward_type'),
				'referal_reward_amount' => $request->Input('referal_reward_amount'),
				'refered_by_reward_amount' => $request->Input('refered_by_reward_amount')
			];
	    	
	    	if(!$this->referal_status->where('id',$id)->update($arr)) {
	    		return response()->json([
	    			'status' => 'error',
	    			'code' => 404,
	    			'msg' => 'Error occurred while updating referal status..!please try again',
	    			'data' => [],
	    		],200);
	    	}

            $referal_status = $this->referal_status->where('id',$id)->first();

	    	return response()->json([
				'status' => 'success',
				'code' => 200,
				'msg' => 'Referal Status updated successfully',
				'data' => [
					'id' => $referal_status->id,
                    'is_contacted' => $referal_status->is_contacted,
                    'is_interested' => $referal_status->is_interested,
                    'is_purchased' => $referal_status->is_purchased,
                    'is_referal_rewarded' => $referal_status->is_referal_rewarded,
                    'is_refered_by_rewarded' => $referal_status->is_refered_by_rewarded,
                    'referal_reward_type' => $referal_status->referal_reward_type,
                    'refered_by_reward_type' => $referal_status->refered_by_reward_type,
                    'referal_reward_amount' => $referal_status->referal_reward_amount,
                    'refered_by_reward_amount' => $referal_status->refered_by_reward_amount
				]
			],200);
	 	}
    }

    // Tempararly commented...will uncomment when it is required
    // public function destroy(Request $request) {
		
	// 	$id = $request->Input('id');
		
	// 	if(!$this->referal_status->where('id',$id)->delete()) {
    //         return response()->json([
	// 			'status' => 'error',
	// 			'code' => 404,
	// 			'msg' => 'Error occurred while deleting referal status..!please try again',
	// 			'data' => [],
	// 		],200);
	// 	}

    //     $this->referal_status->referal()->detach();

	// 	return response()->json([
	// 		'status' => 'success',
	// 		'code' => 200,
	// 		'msg' => 'Referal Status deleted successfully'
	// 	],200);
	// }
	
	public function getReferalStatusDetails($id) {

        $referal_status = $this->referal_status->where('id',$id)
                        ->with(['referal' => function ($query) {
                            $query->with('product','user')->first();
                        }])->first();
        
        if(!isset($referal_status->id)) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Referal Status not found',
				'data' => [],
			],200);
    	}

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Referal Status details found',
			'data' => [
                'id' => $referal_status->id,
                'referal_id' => $referal_status->referal_id,
                'is_contacted' => $referal_status->is_contacted,
                'is_interested' => $referal_status->is_interested,
                'is_purchased' => $referal_status->is_purchased,
                'is_referal_rewarded' => $referal_status->is_referal_rewarded,
                'is_refered_by_rewarded' => $referal_status->is_refered_by_rewarded,
                'referal_reward_type' => $referal_status->referal_reward_type,
                'refered_by_reward_type' => $referal_status->refered_by_reward_type,
                'referal_reward_amount' => $referal_status->referal_reward_amount,
                'refered_by_reward_amount' => $referal_status->refered_by_reward_amount,
                'referal' => [
                        'id' => $referal_status->referal->id,
                        'first_name' => $referal_status->referal->first_name,
                        'last_name' => $referal_status->referal->last_name,
                        'email' => $referal_status->referal->email,
                        'mobile_no' => $referal_status->referal->mobile_no,
                        'refered_by' => [
                            'id' => $referal_status->referal->user->id,
                            'first_name' => $referal_status->referal->user->first_name,
                            'last_name' => $referal_status->referal->user->last_name,
                            'email' => $referal_status->referal->user->email,
                            'city' => $referal_status->referal->user->city,
                            'mobile_no' => $referal_status->referal->user->mobile_no,
                            'profession' => $referal_status->referal->user->profession
                        ],
                        'product' => [
                            'id' => $referal_status->referal->product->id,
                            'name' => $referal_status->referal->product->name,
                            'description' => $referal_status->referal->product->description,
                            'category_id' => $referal_status->referal->product->category_id,
                            'is_active' => $referal_status->referal->product->is_active,
                            'reward_type_id' => $referal_status->referal->product->reward_type_id,
                            'image' => ( $referal_status->referal->product->image != '') ? base_path().
                                        'assets/images/products/'.$referal_status->referal->product->image : ''
                        ]
                ]
            ]
		],200);
    }
}
