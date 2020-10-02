<?php

namespace App\Http\Controllers;

use App\Product;
use App\Product_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //VIEW
    public function index()
    {
        $product = Product::leftJoin('product_review','product_review.product_id','=','product.id')
            ->select('*',DB::raw('AVG(product_review.rating) as rating'))
            ->groupBy('product.id')->select('product.*','rating')->paginate(12);

        return view('home', [
            'products' => $product,
            'total' => Product::all()->count(),
            'categories' => Product_Category::all()
        ]);
    }

    //FUCNTION
    public function filter(Request $request){
        //name (name,desc), category, rating, min, max
        $product = Product::leftJoin('product_review','product_review.product_id','=','product.id')
        ->select('*',DB::raw('AVG(product_review.rating) as rating'))
        ->groupBy('product.id')->select('product.*','rating');

        if($request->name) $product->where(function ($query) use ($request) { $query->where('name','like','%'.$request->name.'%')->orWhere('product.description','like','%'.$request->name.'%');});//MATCHN AGAINST DESCRIPT PRONE TO ERROR
        if($request->category) $product->where('category_id','=',$request->category);
        if($request->rating) $product->where('rating','>=',$request->rating);
        if($request->min_price) $product->where('price','>=',$request->min_price);
        if($request->max_price) $product->where('price','<=',$request->max_price);

        return view('home', [
            'products' => $product->paginate(12),
            'total' => $product->count(),
            'categories' => Product_Category::all()
        ]);
    }

    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);

        if(Auth::check()){
            Auth::user()->updateLanguage($locale);
        }

        return redirect()->back();
    }
}
