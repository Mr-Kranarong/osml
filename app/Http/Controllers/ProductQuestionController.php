<?php

namespace App\Http\Controllers;

use App\Product_Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductQuestionController extends Controller
{
    //VIEW
    public function index()
    {
        return view('admin.question.index', [
            'questions' => Product_Question::where('response','=','')->orderBy('created_at', 'ASC')->paginate(10),
            'total' => Product_Question::where('response','=','')->count()
        ]);
    }

    //FUNCTION
    public function create(Request $request){
        $this->question_validation();

        $input = $request->all();

        $product_question = new Product_Question();
        $product_question->product_id = $input['product_id'];
        $product_question->user_id = Auth::user()->id;
        $product_question->question = $input['content'];
        $product_question->save();


        return redirect()->back();
    }

    public function answer(Request $request){
        Product_Question::where('id',$request->pq_id)->update(['response' => $request->content]);
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        Product_Question::destroy($request->pq_id);
        return redirect()->back();
    }

    //VALIDATION
    public function question_validation(){
        request()->validate([
            'content' => ['required','min:5']
        ]);

        return request();
    }
}
