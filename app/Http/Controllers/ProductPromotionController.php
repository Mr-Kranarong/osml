<?php

namespace App\Http\Controllers;

use App\Product;
use Phpml\Association\Apriori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Product_Promotion;
use App\Purchase_Order;
use App\Settings;

class ProductPromotionController extends Controller
{
    //view
    public function index()
    {
        //$promotion = Product_Promotion::leftJoin('product','product_promotion.id','=','product.promotion_id')->get();
        $promotion = Product_Promotion::all();

        return view('admin.promotion.index', [
            'promotion' => $promotion
        ]);
    }
    public function create(Product $product)
    {
        return view('admin.promotion.create', [
            'product' => $product
        ]);
    }

    //function
    public function store(Request $request)
    {
        $promotion = new Product_Promotion();
        $promotion->name = $request->promotionname;
        $promotion->discounted_price = $request->promotiontotal;
        $promotion->expire_date = $request->promotiondate;
        $promotion->save();

        $product_array = [$request->one,$request->two,$request->three];
        $product = Product::whereIn('id',$product_array);
        $product->update([
            'promotion_id' => $promotion->id
        ]);

        return redirect(route('product.promotion.index'));
    }
    public function delete(Request $request)
    {
        Product_Promotion::destroy($request->promotion_id);
        return redirect()->back();
    }

    //ajax
    public function apriori()
    {
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

        if(request()->itemNo == 1){
            $p_array[] = request()->productA;
        }elseif(request()->itemNo == 2){
            $p_array[] = request()->productA;
            $p_array[] = request()->productB;
        }

        $res = $reg->predict($p_array);
        $res = Product::whereNull('promotion_id')->whereIn('id',$res)->get();

        return $res;
    }
}
