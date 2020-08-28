<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
}
