<?php

namespace App\Http\Controllers;

use App\User;
use App\Settings;
use App\Purchase_Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PurchaseOrderController extends Controller
{
    //VIEW
    public function index(){
        $po = '';

        if(Auth::check() && Auth::user()->hasAccess()){
            $po = Purchase_Order::where('processed_status','like', 0)->distinct('purchase_id')->orderBy('created_at', 'ASC');
        }else{
            $po = Purchase_Order::where('user_id','like', Auth::user()->id)->distinct('purchase_id')->orderBy('created_at', 'DESC');
        }

        return view('purchase_order.index',[
            'po' => $po->get(),
            'total' => $po->count(),
            'store_name' => Settings::firstWhere('option','store_name'),
            'store_address' => Settings::firstWhere('option','store_address'),
            'store_telephone' => Settings::firstWhere('option','store_telephone'),
            'store_email' => Settings::firstWhere('option','store_email')
        ]);
    }

    public function view($po_id){
        $po = $this->getPO($po_id);

        if($po[0]->user_id && !Auth::check()) return redirect(route('home'));
        if(Auth::check() && $po[0]->user_id != Auth::user()->id) return redirect(route('home'));

        return view('purchase_order.view',[
            'po_items' => $po,
            'po_id' => $po_id
        ]);
    }

    //FUNCTION
    public function export2pdf($po_id){
        $po = $this->getPO($po_id);

        if($po[0]->user_id){
            $shipadress = User::where('id','like',$po[0]->user_id)->value('address');
        }else{
            $shipadress = $po[0]->guest_address;
        }

        $pdf = PDF::loadView('purchase_order.pdf', [
            'po_items' => $po,
            'po_id' => $po_id,
            'store_name' => Settings::firstWhere('option','store_name'),
            'store_address' => Settings::firstWhere('option','store_address'),
            'store_telephone' => Settings::firstWhere('option','store_telephone'),
            'store_email' => Settings::firstWhere('option','store_email'),
            'shipping_address' => $shipadress
        ]);
        return $pdf->download("$po_id.pdf");
    }

    public function processed(Request $request){
        $po = Purchase_Order::where('purchase_id',$request->po_id)->update(['processed_status' => 1]);

        return redirect()->back();
    }

    public function refunded(Request $request){
        $po = Purchase_Order::where('purchase_id',$request->po_id)->update(['processed_status' => 2]);

        return redirect()->back();
    }

    //DATA
    private function getPO($po_id){
        return Purchase_Order::leftJoin('product','product.id','=','purchase_order.product_id')->where('purchase_id',"$po_id")->get();
    }
}
