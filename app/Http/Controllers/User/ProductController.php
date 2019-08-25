<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use App\Product;
use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;

class ProductController extends Controller
{
    use Product;

    public function getProducts()
    {
       return $this->getAllProducts();
    }

    public function store(Request $request)
    {
        return $this->storeProduct($request);
    }

    public function edit($id)
    {
        return $this->editProduct($id);
    }

    public function update(Request $request)
    {
       return $this->updateProduct($request); 
    }
	// Tempararly commented...will uncomment when it is required
    // public function destroy(Request $request)
    // {
    //    
    // }

    public function getProductDetails($id) {
        return $this->getProductInfo($id); 
    }
}
