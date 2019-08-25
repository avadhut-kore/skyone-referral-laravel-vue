<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use File;
use Input;
use Session;
use Validator;
use App\Traits\Category;

class CategoryController extends Controller
{
    use Category;

    public function getCategories()
    {
        return $this->getAllCategories();
    }

    public function store(Request $request)
    {
         return $this->storeCategory($request);
    }

    public function edit($id)
    {
        return $this->editCategory($id);
    }

    public function update(Request $request)
    {
       return $this->updateCategory($request);
    }

	// Tempararly commented...will uncomment when it is required
    // public function destroy(Request $request)
    // {
        // return $this->destroyCategory($request);
    // }

    public function getCategoryDetails($id) {
        return $this->getCatDetails($id);
    }

    public function getSubCategoriesByCategoryId($category_id) {
        return $this->getSubCategoriesByCatId($category_id);
    }

}
