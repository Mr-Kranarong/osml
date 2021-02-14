<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
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
}
