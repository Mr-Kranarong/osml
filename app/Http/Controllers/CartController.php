<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use App\Product_Promotion;
use App\Coupon;
use App\Used_Coupon;
use App\PayPalClient;
use App\Purchase_Order;
use App\Settings;
use Phpml\Association\Apriori;
use Exception;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    //VIEW
    public function index(){
        if(!Auth::check()){
            //GUEST
            $product_id_a = array();
            $cart = Session::get('cart',[]);
            foreach($cart as $id => $item) {
                $product_id_a[] = $item['product_id'];
            }
        }else{
            //USER
            $product_id_a = Cart::where('user_id', Auth::user()->id)->pluck('product_id')->toArray();
        }

        if($product_id_a != null){ 
            //FIND RELEVANT PRODUCTS
            $recommends = $this->apriori_recommendation($product_id_a); 
            if($recommends == null) goto end;
            foreach($recommends as $array){
                $product_id_b = array();
                foreach($array as $id){
                    $product_id_b[] = $id;
                }
            }
            $recommends = Product::find($product_id_b);
        }else{ 
            $recommends = '';
        };
        end:


        
        return view('cart', [
            'recommends' => $recommends
        ]);
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

    public function transaction_completed($orderID){
        $client = PayPalClient::client();

        try {
            $response = $client->execute(new OrdersGetRequest($orderID));
        } catch (Exception $e) {
            $response = null;
        }

        if(!$response == null){
            $po_exist = Purchase_Order::firstWhere('purchase_id',$orderID);
            if(!$po_exist){
                //po not exist, add item to db, REDIRECT to po page
                //See Authorize payment for order for more parameters
                //https://developer.paypal.com/docs/api/orders/v2/#orders_get

                $orderid = $response->result->id;
                $status = $response->result->status;
                $email = $response->result->payer->email_address;
                // return view('test',[
                //     'test' => $response
                // ]);
                $finalprice = $response->result->purchase_units[0]->amount->value;
                if($status == "COMPLETED"){
                    if(!Auth::check()){
                        //GUEST
                        $cart = Session::get('cart',[]);
                        foreach($cart as $id => $item) {
                            $new_po_item = new Purchase_Order();
                            $new_po_item->purchase_id = $orderid;
                            $new_po_item->product_id = $item['product_id'];
                            $new_po_item->processed_status = false;
                            $new_po_item->amount = $item['amount'];
                            $new_po_item->final_price = $finalprice;
                            $new_po_item->payer_email = $email;
                            $new_po_item->guest_address = session()->get('address');
                            $new_po_item->save();
                            
                            $update_product = Product::firstWhere('id',$item['product_id']);
                            $update_product->update([
                                'stock_amount' => $update_product->stock_amount - $item['amount']
                            ]);
                            
                            Session::forget('cart.'.$id);
                        }
                    }else{
                        //USER
                        $targets =Cart::where('user_id',Auth::user()->id);
                        $cart = $targets->get();
                        foreach($cart as $item) {
                            $new_po_item = new Purchase_Order();
                            $new_po_item->purchase_id = $orderid;
                            $new_po_item->product_id = $item->product_id;
                            $new_po_item->user_id = Auth::user()->id;
                            $new_po_item->processed_status = false;
                            $new_po_item->amount = $item->amount;
                            $new_po_item->final_price = $finalprice;
                            $new_po_item->payer_email = $email;
                            $new_po_item->save();

                            $update_product = Product::firstWhere('id',$item['product_id']);
                            $update_product->update([
                                'stock_amount' => $update_product->stock_amount - $item->amount
                            ]);
                        }

                        $targets->delete();
                    }
                    //redirect to po
                    request()->session()->forget('coupon');
                    return redirect(route('po.view', [
                        'po_id' => $orderid
                    ]));
                }else{
                    return redirect()->back();
                }
            }
            //po already exists - Change to PO index once created
            return redirect(route('home'));
        }else{
            //order id invalid
            return redirect(route('cart.index'));
        }
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

    public function address_session(){
        session(['address' => request()->guestaddress]);
        return response()->json(array('valid'=> true), 200);
    }

    public function apriori_recommendation($productID_array){
        $result = Purchase_Order::all();

        $samples = array();
        $subarray = array();
        $first = true;
        $purchaseID = '';
        foreach ($result as $purchaseLog) {
            if ($purchaseLog->purchase_id != $purchaseID && $first == false) {
                $purchaseID = $purchaseLog->purchase_id;
                array_push($samples, $subarray);
                $subarray = array();
            } else {
                $purchaseID = $purchaseLog->purchase_id;
                $first = false;
            }
            array_push($subarray, $purchaseLog->product_id);
        }

        $labels  = [];

        $support = Settings::firstWhere('option','apriori_support');
        $confidence = Settings::firstWhere('option','apriori_confidence');

        $reg = new Apriori((float) $support->value, (float) $confidence->value);
        $reg->train($samples, $labels);
        $res = $reg->predict($productID_array);

        return $res;
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
