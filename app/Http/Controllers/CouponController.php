<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product_Category;
use App\Coupon;

class CouponController extends Controller
{
    //VIEW
    public function index(){
        return view('admin.coupon.index',[
            'coupons' => Coupon::paginate(10),
            'categories' => Product_Category::all()
        ]);
    }

    //FUNCTION
    public function create(){
        Coupon::create($this->coupon_validation());
        return redirect()->back();
    }

    public function delete(Coupon $coupon){
        $coupon->delete();
        return redirect()->back();
    }

    public function update(Coupon $coupon){
        $coupon->update([
            'discount_percentage' => NULL,
            'discount_amount' => NULL,
            'category_id' => NULL,
            'min_total_price' => NULL,
            'max_total_price' => NULL,
        ]);
        $coupon->update($this->coupon_validation());
        return redirect()->back();
    }

    //VALIDATION
    public function coupon_validation(){
        request()->validate([
            'name' => ['required','max:700'],
            'expire_date' => ['date','nullable'],
            'code' => ['max:20','min:1'],
            'discount_type' => ['required'],
            'discount_percentage' => ['required_if:discount_type,0','integer','between:1,100'],
            'discount_amount' => ['required_if:discount_type,1','integer'],
            'category_condition' => ['nullable'],
            'category_id' => ['required_with:category_condition'],
            'min_condition' => ['nullable'],
            'min_total_price' => ['required_with:min_condition'],
            'max_condition' => ['nullable'],
            'max_total_price' => ['required_with:max_condition']
        ]);

        $rem = array();
        if(request()->discount_type == 0){
            $rem[] = 'discount_amount';
        }elseif(request()->discount_type == 1){
            $rem[] = 'discount_percentage';
        }
        $rem[] = 'discount_type';

        if(!request()->category_condition){
            $rem[] = 'category_id';
        }
        $rem[] = 'category_condition';

        if(!request()->min_condition){
            $rem[] = 'min_total_price';
        }
        $rem[] = 'min_condition';

        if(!request()->max_condition){
            $rem[] = 'max_total_price';
        }
        $rem[] = 'max_condition';
        $rem[] = '_token';
        $rem[] = '_method';

        return request()->except($rem);
    }
}
