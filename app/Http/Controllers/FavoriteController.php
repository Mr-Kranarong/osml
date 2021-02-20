<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    //VIEW
    public function index(){
        $favorite = Favorite::leftJoin('product','favorite.product_id','=','product.id')
            ->select('product.*',DB::raw('favorite.created_at as fdate,favorite.id as fid'))
            ->where('user_id','=',Auth::user()->id)
            ->orderByRaw("stock_amount=0, created_at DESC")->paginate(12);

        return view('favorite',[
            'favorite' => $favorite,
            'total' => $favorite->count()
        ]);
    }

    //FUNCTION
    public function add(){
        // Favorite::insert([
        //     'user_id' => Auth::user()->id,
        //     'product_id' => request()->product_id
        // ]);
        $favorite = new Favorite();
        $favorite->user_id = Auth::user()->id;
        $favorite->product_id = request()->product_id;
        $favorite->save();

        return response()->json(array('msg'=> 'operation successful'), 200);
    }
    public function remove(){
        $favorite = Favorite::where('user_id','=',Auth::user()->id)->where('product_id','=',request()->product_id);
        $favorite->delete();
        return response()->json(array('msg'=> 'operation successful'), 200);
    }

    public function delete(Request $request){
        $favorite = Favorite::where('id','=',$request->fid);
        $favorite->delete();
        return redirect()->back();
    }
}
