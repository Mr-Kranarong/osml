<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Coupon;
use App\Product;
use App\Product_Category;
use App\Product_Promotion;
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
        $this->removeExpire();

        $product = Product::leftJoin('product_review','product_review.product_id','=','product.id')
            ->select('product.*',DB::raw('AVG(product_review.rating) as rating'))
            ->groupBy('product.id')->orderByRaw("stock_amount=0, created_at DESC")->paginate(12);

        return view('home', [
            'products' => $product,
            'total' => Product::all()->count(),
            'categories' => Product_Category::all(),
            'promotion' => Product_Promotion::all()
        ]);
    }

    //FUCNTION
    public function filter(Request $request){
        //name (name,desc), category, rating, min, max
        $product = Product::leftJoin('product_review','product_review.product_id','=','product.id')
        ->select('product.*',DB::raw('AVG(CAST(product_review.rating as Float)) as rating'))
        ->groupBy('product.id')->orderByRaw("stock_amount=0, product.created_at DESC");

        if($request->name) $product->where(function ($query) use ($request) { $query->where('name','like','%'.$request->name.'%')->orWhere('product.description','like','%'.$request->name.'%');});//MATCHN AGAINST DESCRIPT PRONE TO ERROR
        if($request->category) $product->where('category_id','=',$request->category);
        if($request->rating) $product->where('rating', '>=', (float) $request->rating); //BUUUUUUUUUUUUUUUUUUUUUUG
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

    public function removeExpire(){
        $today = Carbon::now();
        $expired_coupon = Coupon::where('expire_date','<', $today->format('Y-m-d'));
        $expired_promotion = Product_Promotion::where('expire_date','<', $today->format('Y-m-d'));

        $x = $expired_promotion->get();

        foreach($x as $y) {
            $product_promotion = Product::where('promotion_id','like',$y->id);
            $product_promotion->update([
                'promotion_id' => null
            ]);
        }

        $expired_coupon->delete();
        $expired_promotion->delete();
    }
}
