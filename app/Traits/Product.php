<?php

namespace App\Traits;
use Exception;
use PDOException;
use DB;
use Auth;
use Illuminate\Http\Response as Response;
use Hash;
use Crypt;
use Config;
// use model
use App\Models\Product as ProductModel;

use File;
use Input;
use Session;
use Validator;

trait Product {

    protected $product;
    public function __construct(ProductModel $prod) {
        $this->product = $prod;
    }

    public function getAllProducts()
    {
        $products = $this->product->where('is_active',1)->get();
        // print_r($products->count()); exit;
        if($products->count() == 0) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'Products not found',
                'data' => [],
            ],200);
        }

        $products_data = [];
        foreach($products as $key => $product) {
            array_push($products_data, [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category_id' => $product->category_id,
                'is_active' => $product->is_active,
                'reward_type_id' => $product->reward_type_id,
                'image' => ( $product->image != '') ? base_path().'assets/images/products/'.$product->image : ''
            ]);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'msg' => 'Products found',
            'data' => $products_data
        ],200);
    }

    public function storeProduct($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'description' => 'required|min:5',
            'category' => 'required',
            'is_active' => 'required',
            'reward_type' => '',
            'image' => 'nullable|image'
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

            $this->product->name = $request->Input('name');
            $this->product->description = $request->Input('description');
            $this->product->category_id = $request->Input('category');
            $this->product->is_active = $request->Input('is_active');
            $this->product->reward_type_id = $request->Input('reward_type');
        
            // Determining If A File Was Uploaded
            if (Input::hasFile('image'))
            {
                $file = Input::file('image');
                $imageName = date('Y-m-d H:i:s').'.'.$file->getClientOriginalExtension();
                $file->move(base_path('assets/images/products'),$imageName);

                $this->product->image = $imageName;
            }
            
            if(!$this->product->save()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'msg' => 'Error occurred white saving product..!please try again',
                    'data' => []
                ],200);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'msg' => 'product saved successfully',
                'data' => [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'description' => $this->product->description,
                    'category_id' => $this->product->category_id,
                    'is_active' => $this->product->is_active,
                    'reward_type_id' => $this->product->reward_type_id,
                    'image' => ( $this->product->image != '') ? base_path().
                                'assets/images/products/'.$this->product->image : ''
                ]
            ],200);
        }
    }

    public function editProduct($id)
    {
        $product = $this->product->where('id',$id)->first();

        if(!isset($product->id)) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'Product not found',
                'data' => [],
            ],200);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'msg' => 'Product found',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category_id' => $product->category_id,
                'is_active' => $product->is_active,
                'reward_type_id' => $product->reward_type_id,
                'image' => ( $product->image != '') ? base_path().
                           'assets/images/products/'.$product->image : ''
            ]
        ],200);
    }

    public function updateProduct($request)
    {
        $id = $request->Input('id');
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'description' => 'required|min:5',
            'category' => 'required',
            'is_active' => 'required',
            'reward_type' => '',
            'image' => 'nullable|image'
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
                'description' => $request->Input('description'),
                'category_id' => $request->Input('category'),
                'is_active' => $request->Input('is_active'),
                'reward_type_id' => $request->Input('reward_type')
            ];
            
            // Determining If A File Was Uploaded
            if (Input::hasFile('image'))
            {
                $prod = $this->product->where('id',$id)->first();

                if(isset($prod->id)) {
                    if(File::exists(base_path('assets/images/products/'.$prod->image))){
                        File::delete(base_path('assets/images/products/'.$prod->image));
                    }
                }

                $file = Input::file('image');
                $imageName = date('Y-m-d H:i:s').'.'.$file->getClientOriginalExtension();
                $file->move(base_path('assets/images/products'),$imageName);

                $arr['image'] = $imageName;
            }
            
            if(!$this->product->where('id',$id)->update($arr)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'msg' => 'Error occurred while updating product..!please try again',
                    'data' => [],
                ],200);
            }

            $product = $this->product->where('id',$id)->first();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'msg' => 'Product updated successfully',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'is_active' => $product->is_active,
                    'reward_type_id' => $product->reward_type_id,
                    'image' => ( $product->image != '') ? base_path().
                                'assets/images/products/'.$product->image : ''
                ]
            ],200);
        }
    }
    
    // Tempararly commented...will uncomment when it is required
    // public function destroyProduct($request)
    // {
    //     $id = $request->Input('id');
        
    //  if(!$this->product->where('id',$id)->delete()) {
    //      return response()->json([
    //          'status' => 'error',
    //          'code' => 404,
    //          'msg' => 'Error occurred while deleting product..!please try again',
    //          'data' => [],
    //      ],200);
    //  }

    //  return response()->json([
    //      'status' => 'success',
    //      'code' => 200,
    //      'msg' => 'Product deleted successfully'
    //  ],200);
    // }

    public function getProductInfo($id) {

        $product = $this->product->with('category','reward_type')->where('id',$id)->first();

        if(!isset($product->id)) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'Data not found',
                'data' => [],
            ],200);
        }

        $product_data = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'category_id' => $product->category_id,
            'is_active' => $product->is_active,
            'reward_type_id' => $product->reward_type_id,
            'image' => ( $product->image != '') ? base_path().
                        'assets/images/products/'.$product->image : '',
            'category' => [
                'name' => $product->category->name,
                'description' => $product->category->description,
                'parent_category_id' => $product->category->parent_category_id,
                'is_active' => $product->category->is_active,
                'logo' => ( $product->category->logo != '') ? base_path().'assets/images/categories/'.$product->category->logo : '' ,
            ]
        ];

        $reward_data = [];
        if(isset($product->reward_data->id)) {
            foreach($category->reward_data as $key => $reward) {
                array_push($reward_data, [
                    'id' => $reward->id,
                    'name' => $reward->name,
                    'unit_of_measurement' => $reward->unit_of_measurement,
                    'is_variable' => $reward->is_variable
                ]);
            }
        }

        $product_data['reward_type'] = $reward_data; 

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'msg' => 'Data found',
            'data' => $product_data
        ],200);
    }
}