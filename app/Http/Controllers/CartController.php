<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use App\Product_Promotion;
use App\Coupon;
use App\Used_Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    //VIEW
    public function index(){
        return view('cart');
    }

    //FUNCTION
    public function add(){
        if(!Auth::check()){
            //GUEST
            $cart = Session::get('cart',[]);
            foreach($cart as $id => $item) {
                if ($item['product_id'] == request()->product_id){
                    $item['amount'] +=  (int) request()->buy_amount;
                    Session::put('cart.'.$id, $item);
                    Session::save();
                    return redirect(route('cart.index'));
                }
            }
            Session::push('cart', array('product_id' => (int) request()->product_id, 'amount' => (int) request()->buy_amount ));
            Session::save();
            return redirect(route('cart.index'));
        }else{
            //USER
            $current = Cart::where('user_id', Auth::user()->id)->where('product_id',request()->product_id)->first();
            if($current){
                $current->update([
                    'amount' => $current->amount+request()->buy_amount
                ]);
            }else{
                $cart = new Cart();
                $cart->user_id = Auth::user()->id;
                $cart->product_id = request()->product_id;
                $cart->amount = request()->buy_amount;
                $cart->save();
            }
        }
        return redirect(route('cart.index'));
    }

    public function remove(Request $request){
        if(!Auth::check()){
            //GUEST
            $targets = (array) $request->chk_id;
            $cart = Session::get('cart',[]);
            foreach ($targets as $pro_id) {
                foreach($cart as $id => $item) {
                    if ($item['product_id'] == $pro_id){
                        Session::forget('cart.'.$id);
                        break;
                    }
                }
            }
        }else{
            //USER
            Cart::where('user_id',Auth::user()->id)->whereIn('product_id', (array) $request->chk_id)->delete();
        }
        return redirect()->back();
    }

    public function update(){
        if(!Auth::check()){
            //GUEST
            $product = Product::where('id', request()->product_id)->first();
            $cart = Session::get('cart',[]);
            foreach($cart as $id => $item) {
                if ($item['product_id'] == request()->product_id){
                    $item['amount'] =  ((int) request()->buy_amount > $product->stock_amount) ? $product->stock_amount : (int) request()->buy_amount;
                    $current_amount = (int) $item['amount'];
                    Session::put('cart.'.$id, $item);
                    Session::save();
                    break;
                }
            }
        }else{
            //USER
            $product = Product::where('id', request()->product_id)->first();
            $cart = Cart::where('user_id',Auth::user()->id)->where('product_id', request()->product_id)->first();
            $cart->update([
                'amount' => request()->buy_amount
            ]);
            $current_amount = $cart->amount;
        }
        return response()->json(array('current_amount'=> $current_amount, 'max_amount'=> $product->stock_amount), 200);
    }

    public function finalize(){
        $coupon_status = false;
        if(!Auth::check()){
            //GUEST
            $product_id_array = array();
            $cart = Session::get('cart',[]);
            foreach($cart as $id => $item) {
                $product_id_array[] = $item['product_id'];
            }
            $products = Product::whereIn('id',$product_id_array)->orderBy('id', 'asc')->get();
            $promotion_applied = array();
            $total = 0;
            $prevtotal = 0;
            $calculated = array();
            foreach($products as $product) {
                if(in_array($product->id, $calculated)) continue;

                if($product->promotion_id){
                    $applicableProducts = Product::where('promotion_id',$product->promotion_id)->orderBy('id', 'asc')->get();
                    $selectedProducts = $products->where('promotion_id',$product->promotion_id);
                    $promotion = Product_Promotion::firstWhere('id',$product->promotion_id);
                    $apply = true;
                    $lowest = 0;
                    $first = true;
                    foreach($applicableProducts as $ap){
                        if($apply){
                            foreach($selectedProducts as $sp){
                                if($first){
                                    foreach($cart as $id => $item) {
                                        if($sp->id == $item['product_id']){
                                            $lowest = $item['amount'];
                                            $first = false;
                                        }
                                    }
                                }else{
                                    foreach($cart as $id => $item) {
                                        if($sp->id == $item['product_id']){
                                            if($lowest > $item['amount']) $lowest = $item['amount'];
                                        }
                                    }
                                }
                                if($ap->id == $sp->id){
                                    $apply = true;
                                    break;
                                }
                                $apply = false;
                            }
                        }else{break;}
                    }

                    if($apply){
                        $affected = array();
                        foreach($cart as $id => $item) {
                            if($selectedProducts->contains('id',$item['product_id'])){
                                $y = Product::firstWhere('id',$item['product_id']);
                                $total += ($item['amount'] - $lowest) * $y->price;
                                $prevtotal += $item['amount'] * $y->price;
                                $calculated[] = $item['product_id'];
                                $affected[] = $y->name;
                            }
                        }
                        $total += $promotion->discounted_price * $lowest;
                        $promotion_applied[] = array(
                            'promotion_id' => $promotion->id, 
                            'promotion_name' => $promotion->name,
                            'count' => $lowest,
                            'products' => $affected
                        );
                        continue;
                    }
                }

                foreach($cart as $id => $item) {
                    if($product->id == $item['product_id']){
                        $total += $product->price * $item['amount'];
                        $prevtotal += $product->price * $item['amount'];
                        $calculated[] = $item['product_id'];
                        break;
                    }
                }
            }
        }else{
            //USER
            $cart = Cart::where('user_id',Auth::user()->id)->orderBy('id', 'asc')->get();
            $product_id_array = Cart::where('user_id',Auth::user()->id)->pluck('product_id')->toArray();
            $products = Product::whereIn('id',$product_id_array)->orderBy('id', 'asc')->get();
            $promotion_applied = array();
            $total = 0;
            $prevtotal = 0;
            $calculated = array();
            foreach($products as $product) {
                if(in_array($product->id, $calculated)) continue;

                if($product->promotion_id){
                    $applicableProducts = Product::where('promotion_id',$product->promotion_id)->orderBy('id', 'asc')->get();
                    $selectedProducts = $products->where('promotion_id',$product->promotion_id);
                    $promotion = Product_Promotion::firstWhere('id',$product->promotion_id);
                    $apply = true;
                    $lowest = 0;
                    $first = true;
                    foreach($applicableProducts as $ap){
                        if($apply){
                            foreach($selectedProducts as $sp){
                                if($first){
                                    foreach($cart as $item) {
                                        if($sp->id == $item->product_id){
                                            $lowest = $item->amount;
                                            $first = false;
                                        }
                                    }
                                }else{
                                    foreach($cart as $item) {
                                        if($sp->id == $item->product_id){
                                            if($lowest > $item->amount) $lowest = $item->amount;
                                        }
                                    }
                                }
                                if($ap->id == $sp->id){
                                    $apply = true;
                                    break;
                                }
                                $apply = false;
                            }
                        }else{break;}
                    }

                    if($apply){
                        $affected = array();
                        foreach($cart as $item) {
                            if($selectedProducts->contains('id',$item->product_id)){
                                $y = Product::firstWhere('id',$item->product_id);
                                $total += ($item->amount - $lowest) * $y->price;
                                $prevtotal += $item->amount * $y->price;
                                $calculated[] = $item->product_id;
                                $affected[] = $y->name;
                            }
                        }
                        $total += $promotion->discounted_price * $lowest;
                        $promotion_applied[] = array(
                            'promotion_id' => $promotion->id, 
                            'promotion_name' => $promotion->name,
                            'count' => $lowest,
                            'products' => $affected
                        );
                        continue;
                    }
                }

                foreach($cart as $id => $item) {
                    if($product->id == $item->product_id){
                        $total += $product->price * $item->amount;
                        $prevtotal += $product->price * $item->amount;
                        $calculated[] = $item->product_id;
                        break;
                    }
                }
            }

            if(request()->session()->has('coupon')){
                $coupon_status = true;
                $coupon = Coupon::firstWhere('code', request()->session()->get('coupon'));
                if($coupon->category_id){
                    $pass = true;
                    foreach($products as $product){
                        if($product->category_id != $coupon->category_id) {
                            $pass = false;
                            break;
                        }
                    }
                    if($pass == false) $coupon_status = false;
                }
                if($coupon->min_total_price){
                    if($coupon->min_total_price > $total) $coupon_status = false;
                }
                if($coupon->max_total_price){
                    if($coupon->max_total_price < $total) $coupon_status = false;
                }

                if($coupon_status == true){
                    if($coupon->discount_percentage) $total *= ((100-$coupon->discount_percentage)/100);
                    if($coupon->discount_amount) $total -= $coupon->discount_amount;
                    if($total < 0) $total = 0;
                }
            }
        }

        return response()->json(array(
            'total'=> $total, 
            'prevtotal' => $prevtotal,
            'promotion_applied' => $promotion_applied,
            'coupon_status' => $coupon_status,
        ), 200);
    }

    public function transaction_completed(){

    }

    public function coupon_session(){
        session(['coupon' => request()->coupon]);
        if($this->coupon_validation()){
            return response()->json(array('valid'=> true), 200);
        }else{
            request()->session()->forget('coupon');
            return response()->json(array('valid'=> false), 200);
        }
    }

    //VALIDATION
    protected function coupon_validation(){
        $coupon = Coupon::firstWhere('code', request()->session()->get('coupon'));
        $valid = false;
        if($coupon) {
            $used_coupon = Used_Coupon::where('user_id',Auth::user()->id)->where('coupon_id', $coupon->id)->first();
            if(!$used_coupon) $valid = true;
        }
        return ($valid) ? true : false; 
    }
}
