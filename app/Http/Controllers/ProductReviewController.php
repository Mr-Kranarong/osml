<?php

namespace App\Http\Controllers;

use App\Product_Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function create(Request $request){
        $this->review_validation();

        $input = $request->all();

        $product_review = new Product_Review();
        $product_review->product_id = $input['product_id'];
        $product_review->user_id = Auth::user()->id;
        $product_review->rating = $input['reviewrating'];
        $product_review->description = $input['content'];
        $product_review->save();


        return redirect()->back();
    }

    //VALIDATION
    public function review_validation(){
        request()->validate([
            'content' => ['required','min:1'],
            'reviewrating' => ['integer','between:1,5']
        ]);

        return request();
    }
}
