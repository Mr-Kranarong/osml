<?php

namespace App\Http\Controllers;

use App\Product;
use App\Product_Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //========= PRODUCT ========================================
    //VIEW
    public function index(){
        return view('admin.product.index',[
            'products' => Product::paginate(10),
            'total' => Product::all()->count()
        ]);
    }

    public function search(Request $request){
        return view('admin.product.index',[
            'products' => Product::where('name','like','%'.$request->SearchProduct.'%')->paginate(10),
            'total' => Product::where('name','like','%'.$request->SearchProduct.'%')->count(),
            'query' => $request->SearchProduct
        ]);
    }

    //FUNCTION
    public function delete(Request $request){
        Product::destroy($request->chk_id);
        //MIGHT NEED TO BE CHANGE BECAUSE IMAGES NEED TO BE DELETED TOO
        return redirect(route('product.index'));
    }

    //========= PRODUCT_CATEGORY ========================================
    //VIEW
    public function category_index(){
        return view('admin.product.category.index',[
            'categories' => Product_Category::paginate(20),
            'total' => Product_Category::all()->count()
        ]);
    }

    //FUNCTION
    public function category_delete(Product_Category $category){
        $category->delete();
        return redirect(route('product.category.index'));
    }

    public function category_update(Product_Category $category){
        $category->update($this->validateCategory());
        return redirect(route('product.category.index'));
    }

    public function category_create(){
        Product_Category::create($this->validateCategory());
        return redirect(route('product.category.index'));
    }

    public function validateCategory(){
        return request()->validate([
            'name' => ['required','max:700']
        ]);
    }

}
