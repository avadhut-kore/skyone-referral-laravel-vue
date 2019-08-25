<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;

class CategoryController extends Controller
{
    protected $category;
    public function __construct(Category $cat) {
    	$this->category = $cat;
    }

    public function getCategories()
    {
        $categories = $this->category->where('is_active',1)->get();

    	if($users->count() == 0) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Categories not found',
				'data' => [],
			],200);
    	}

        $categories_data = [];
        foreach ($categories as $key => $category) {
            array_push($categories_data, [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'parent_category_id' => $category->parent_category_id,
                'is_active' => $category->is_active,
                'logo' => ($category->logo != '') ? 
                            base_url().'assets/images/categories/'.$category->logo : ''
            ]);
        }

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Categories found',
			'data' => $categories
		],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
    		'name' => 'required|min:2',
    		'description' => 'required|min:5',
    		'parent_category_id' => 'nullable',
    		'is_active' => 'required',
    		'logo' => 'nullable|image'
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
	    	$this->category->name = $request->Input('name');
	    	$this->category->description = $request->Input('description');
	    	$this->category->parent_category_id = $request->Input('parent_category_id');
	    	$this->category->is_active = $request->Input('is_active');
        
            // Determining If A File Was Uploaded
            if (Input::hasFile('logo'))
            {
                $file = Input::file('logo');
                $imageName = date('Y-m-d H:i:s').'.'.$file->getClientOriginalExtension();
                $file->move(base_path('assets/images/categories'),$imageName);

                $this->category->logo = $imageName;
            }
	    	
	    	if(!$this->category->save()) {
	    		return response()->json([
	    			'status' => 'error',
	    			'code' => 404,
	    			'msg' => 'Error occurred adding category..!please try again',
	    			'data' => []
	    		],200);
	    	}

	    	return response()->json([
				'status' => 'success',
				'code' => 200,
				'msg' => 'category added successfully',
				'data' => [
                    'id' => $this->category->id,
                    'name' =>$this->category->name,
                    'description' =>$this->category->description,
                    'parent_category_id' =>$this->category->parent_category_id,
                    'is_active' =>$this->category->is_active,
                    'logo' => ($this->category->logo != '') ? 
                            base_url().'assets/images/categories/'.$this->category->logo : ''
                ]
			],200);
	 	}
    }

    public function edit($id)
    {
       $category = $this->category->where('id',$id)->first();

    	if(isset($category->id) == 0) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Category not found',
				'data' => [],
			],200);
    	}

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Category found',
			'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'parent_category_id' => $category->parent_category_id,
                'is_active' => $category->is_active,
                'logo' => ($category->logo != '') ? 
                            base_url().'assets/images/categories/'.$category->logo : ''
            ]
		],200);
    }

    public function update(Request $request)
    {
        $id = $request->Input('id');
    	$validator = Validator::make($request->all(), [
    		'name' => 'required|min:2',
    		'description' => 'required|min:6',
    		'parent_category_id' => 'required',
    		'is_active' => 'required',
    		'logo' => 'nullable|image'
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
				'parent_category_id' => $request->Input('parent_category_id'),
				'is_active' => $request->Input('is_active')
            ];
            
            // Determining If A File Was Uploaded
            if (Input::hasFile('logo'))
            {
                $cat = $this->category->where('id',$id)->first();

                if($cat->count() > 0) {
                    if(File::exists(base_path('assets/images/categories/'.$cat->logo))){
                        File::delete(base_path('assets/images/categories/'.$cat->logo));
                    }
                }

                $file = Input::file('logo');
                $imageName = date('Y-m-d H:i:s').'.'.$file->getClientOriginalExtension();
                $file->move(base_path('assets/images/categories'),$imageName);

                $arr['logo'] = $imageName;
            }
	    	
	    	if(!$this->category->where('id',$id)->update($arr)) {
	    		return response()->json([
	    			'status' => 'error',
	    			'code' => 404,
	    			'msg' => 'Error occurred while updating category..!please try again',
	    			'data' => [],
	    		],200);
	    	}

            $category = $this->category->where('id',$id)->first();

	    	return response()->json([
				'status' => 'success',
				'code' => 200,
				'msg' => 'Category updated successfully',
				'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'parent_category_id' => $category->parent_category_id,
                    'is_active' => $category->is_active,
                    'logo' => ($category->logo != '') ? 
                                base_url().'assets/images/categories/'.$category->logo : ''
                ],
				'errors' => []
			],200);
	 	}
    }

	// Tempararly commented...will uncomment when it is required
    // public function destroy()
    // {
    //     $id = $request->Input('id');
		
	// 	if(!$this->category->where('id',$id)->delete()) {
	// 		return response()->json([
	// 			'status' => 'error',
	// 			'code' => 404,
	// 			'msg' => 'Error occurred while deleting category..!please try again',
	// 			'data' => [],
	// 		],200);
	// 	}

	// 	return response()->json([
	// 		'status' => 'success',
	// 		'code' => 200,
	// 		'msg' => 'Category deleted successfully'
	// 	],200);
    // }

    public function getCategoryDetails($id) {
        $category = Category::with('product')->where('id',$id)->first();
        $sub_cats = Category::where('parent_category_id',$id)->get();
        
        if(isset($category->id) == 0) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Data not found',
				'data' => [],
			],200);
    	}

        $category_data = [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'parent_category_id' => $category->parent_category_id,
            'is_active' => $category->is_active,
            'logo' => ( $category->logo != '') ? base_path().
                     'assets/images/categories/'.$category->logo : '' ,
        ];

        $products = [];
        if($category->product->count() > 0)
        {
            foreach($category->product as $key => $product) {
                array_push($products, [
                     'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'is_active' => $product->is_active,
                    'reward_type_id' => $product->reward_type_id,
                    'image' => ( $product->image != '') ? base_path().
                                'assets/images/products/'.$product->image : ''
                ]);
            }
        }

        $sub_categories = [];
        if($sub_cats->count() > 0)
        {
            foreach($sub_cats as $key => $cat) {
                array_push($sub_categories, [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'description' => $cat->description,
                    'parent_category_id' => $cat->parent_category_id,
                    'is_active' => $cat->is_active,
                    'logo' => ( $cat->logo != '') ? base_path().'assets/images/categories/'.$cat->logo : ''
                ]);
            }
        }

        $category_data['sub_categories'] = $sub_categories;
        $category_data['products'] = $products;

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Data found',
			'data' => $category_data
		],200);
    }

    public function getSubCategoriesByCategoryId($category_id) {

        $category = $this->category->where('id',$category_id)->first();
        $sub_cats = $this->category->where('parent_category_id',$category_id)->get();
        
        $category_data = [
            'name' => $category->name,
            'description' => $category->description,
            'parent_category_id' => $category->parent_category_id,
            'is_active' => $category->is_active,
            'logo' => ( $category->logo != '') ? base_path().
                      'assets/images/categories/'.$category->logo : '' ,
        ];

        $category_data['sub_categories'] = [];

        if($sub_cats->count() == 0) {
    		return response()->json([
				'status' => 'error',
				'code' => 400,
				'msg' => 'Sub Categories not found',
				'data' => $category_data,
			],200);
        }

        $sub_categories = [];
        if($sub_cats->count() > 0)
        {
            foreach($sub_cats as $key => $cat) {
                array_push($sub_categories, [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'description' => $cat->description,
                    'parent_category_id' => $cat->parent_category_id,
                    'is_active' => $cat->is_active,
                    'logo' => ( $cat->logo != '') ? base_path().
                             'assets/images/categories/'.$cat->logo : ''
                ]);
            }
        }

        $category_data['sub_categories'] = $sub_categories;

    	return response()->json([
			'status' => 'success',
			'code' => 200,
			'msg' => 'Data found',
			'data' => $category_data
		],200);
    }

}
