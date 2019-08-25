<?php

namespace App\Http\Controllers;

use App\Referal;
use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;

class ReferalController extends Controller
{
    protected $referal;
    public function __construct(Referal $ref) {
    	$this->referal = $ref;
    }

    public function getReferals() {

		$referals = $this->referal->with('product','user','referal_status')->get();
        
        if($referals->count() == 0) {
            return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Referals data not found',
				'data' => [],
			],200);
        }

        $referal_data = [];
		foreach($referals as $key => $referal) {
            $arr = [
                'id' => $referal->id,
				'first_name' => $referal->first_name,
				'last_name' => $referal->last_name,
				'email' => $referal->email,
				'mobile_no' => $referal->mobile_no,
				'refered_by' => [
                    'id' => $referal->user->id,
                    'first_name' => $referal->user->first_name,
                    'last_name' => $referal->user->last_name,
                    'email' => $referal->user->email,
                    'city' => $referal->user->city,
                    'mobile_no' => $referal->user->mobile_no,
                    'profession' => $referal->user->profession
                ],
                'product' => [
                    'id' => $referal->product->id,
                    'name' => $referal->product->name,
                    'description' => $referal->product->description,
                    'category_id' => $referal->product->category_id,
                    'is_active' => $referal->product->is_active,
                    'reward_type_id' => $referal->product->reward_type_id,
                    'image' => ( $referal->product->image != '') ? base_path().'assets/images/products/'.$referal->product->image : ''
                ]
                ];
            
            if($referal->referal_status->count() > 0) {
                $arr['referal_status'] = [
                    'id' => $referal->referal_status->id,
                    'referal_id' => $referal->referal_status->referal_id,
                    'is_contacted' => $referal->referal_status->is_contacted,
                    'is_interested' => $referal->referal_status->is_interested,
                    'is_purchased' => $referal->referal_status->is_purchased,
                    'is_referal_rewarded' => $referal->referal_status->is_referal_rewarded,
                    'is_refered_by_rewarded' => $referal->referal_status->is_refered_by_rewarded,
                    'referal_rewarded_type' => $referal->referal_status->referal_rewarded_type,
                    'refered_by_rewarded_type' => $referal->referal_status->refered_by_rewarded_type,
                    'referal_reward_amount' => $referal->referal_status->referal_reward_amount,
                    'refered_by_reward_amount' => $referal->referal_status->refered_by_reward_amount,
                ];
            } else {
                $arr['referal_status'] = [];
            }

            array_push($referal_data,$arr);
        }
        
    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Referals Data found',
			'data' => $referal_data
		],200);
    }

    public function store(Request $request) {
 	
 		$validator = Validator::make($request->all(), [
    		'product' => 'required',
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
    		'email' => 'required|email',
    		'mobile_no' => 'required|numeric',
    		'refered_by' => 'required'
    	]);

    	if ($validator->fails()) {
    		return response()->json([
    			'status' => 'error',
    			'code' => 400,
    			'msg' => 'validation errors occured',
    			'errors' => $validator->errors(),
    			'data' => []
    		],200);
    	} else {
            
            $this->referal->product_id = $request->Input('product');
	    	$this->referal->first_name = $request->Input('first_name');
	    	$this->referal->last_name = $request->Input('last_name');
	    	$this->referal->email = $request->Input('email');
	    	$this->referal->mobile_no = $request->Input('mobile_no');
	    	$this->referal->refered_by = $request->Input('refered_by');
	    	
	    	if(!$this->referal->save()) {
	    		return response()->json([
	    			'status' => 'error',
	    			'code' => 404,
	    			'msg' => 'Error occurred while saving referal..!please try again',
	    			'data' => []
	    		],200);
	    	}

	    	return response()->json([
				'status' => 'success',
				'code' => 200,
				'msg' => 'Referal saved successfully',
				'data' => [
                    'id' => $this->referal->id,
                    'product_id' => $this->referal->product_id,
                    'first_name' => $this->referal->first_name,
                    'last_name' => $this->referal->last_name,
                    'email' => $this->referal->email,
                    'mobile_no' => $this->referal->mobile_no,
                    'refered_by' => $this->referal->refered_by
				]
			],200);
	 	}	
    }

    public function edit($id) {
    	$referal = $this->referal->where('id',$id)->first();

    	if($referal->count() == 0) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Referal not found',
				'data' => [],
			],200);
    	}

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Referal found',
			'data' => [
				'id' => $referal->id,
                'product_id' => $referal->product_id,
                'first_name' => $referal->first_name,
                'last_name' => $referal->last_name,
                'email' => $referal->email,
                'mobile_no' => $referal->mobile_no,
                'refered_by' => $referal->refered_by
			]
		],200);
    }

    public function update(Request $request) {
        
		$id = $request->Input('id');
    	$validator = Validator::make($request->all(), [
    		'product' => 'required',
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
    		'email' => 'required|email',
    		'mobile_no' => 'required|numeric',
    		'refered_by' => 'required'
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
				'product_id' => $request->Input('product'),
				'first_name' => $request->Input('first_name'),
				'last_name' => $request->Input('last_name'),
				'email' => $request->Input('email'),
				'mobile_no' => $request->Input('mobile_no'),
				'refered_by' => $request->Input('refered_by')
			];
	    	
	    	if(!$this->referal->where('id',$id)->update($arr)) {
	    		return response()->json([
	    			'status' => 'error',
	    			'code' => 404,
	    			'msg' => 'Error occurred while updating referal..!please try again',
	    			'data' => [],
	    		],200);
	    	}

            $referal = $this->referal->where('id',$id)->first();

	    	return response()->json([
				'status' => 'success',
				'code' => 200,
				'msg' => 'Referal updated successfully',
				'data' => [
					'id' => $referal->id,
                    'product_id' => $referal->product_id,
                    'first_name' => $referal->first_name,
                    'last_name' => $referal->last_name,
                    'email' => $referal->email,
                    'mobile_no' => $referal->mobile_no,
                    'refered_by' => $referal->refered_by
				]
			],200);
	 	}
    }

    // Tempararly commented...will uncomment when it is required
 //    public function destroy(Request $request) {
		
	// 	$id = $request->Input('id');
		
	// 	if(!$this->referal->where('id',$id)->delete()) {
 //            return response()->json([
	// 			'status' => 'error',
	// 			'code' => 404,
	// 			'msg' => 'Error occurred while deleting referal..!please try again',
	// 			'data' => [],
	// 		],200);
	// 	}

 //        $this->referal->referal_status()->detach();

	// 	return response()->json([
	// 		'status' => 'success',
	// 		'code' => 200,
	// 		'msg' => 'Referal deleted successfully'
	// 	],200);
	// }
	
	public function getReferalDetails($id) {

        $referal = $this->referal->with('product','user','referal_status')
                                 ->where('id',$id)
                                 ->first();
        
        if(!isset($referal->id)) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'Referals data not found',
                'data' => [],
            ],200);
        }

        $referal_data = [];
        $arr = [
            'id' => $referal->id,
            'first_name' => $referal->first_name,
            'last_name' => $referal->last_name,
            'email' => $referal->email,
            'mobile_no' => $referal->mobile_no,
            'refered_by' => [
                'id' => $referal->user->id,
                'first_name' => $referal->user->first_name,
                'last_name' => $referal->user->last_name,
                'email' => $referal->user->email,
                'city' => $referal->user->city,
                'mobile_no' => $referal->user->mobile_no,
                'profession' => $referal->user->profession
            ],
            'product' => [
                'id' => $referal->product->id,
                'name' => $referal->product->name,
                'description' => $referal->product->description,
                'category_id' => $referal->product->category_id,
                'is_active' => $referal->product->is_active,
                'reward_type_id' => $referal->product->reward_type_id,
                'image' => ( $referal->product->image != '') ? base_path().'assets/images/products/'.$referal->product->image : ''
            ],
        ];

        if(isset($referal->referal_status->id)) {
            $arr['referal_status'] = [
                'id' => $referal->referal_status->id,
                'referal_id' => $referal->referal_status->referal_id,
                'is_contacted' => $referal->referal_status->is_contacted,
                'is_interested' => $referal->referal_status->is_interested,
                'is_purchased' => $referal->referal_status->is_purchased,
                'is_referal_rewarded' => $referal->referal_status->is_referal_rewarded,
                'is_refered_by_rewarded' => $referal->referal_status->is_refered_by_rewarded,
                'referal_rewarded_type' => $referal->referal_status->referal_rewarded_type,
                'refered_by_rewarded_type' => $referal->referal_status->refered_by_rewarded_type,
                'referal_reward_amount' => $referal->referal_status->referal_reward_amount,
                'refered_by_reward_amount' => $referal->referal_status->refered_by_reward_amount,
            ];
        } else {
            $arr['referal_status'] = [];
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'msg' => 'Referals Data found',
            'data' => $arr
        ],200);
    }
}
