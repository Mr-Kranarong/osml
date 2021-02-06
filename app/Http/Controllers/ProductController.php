<?php

namespace App\Http\Controllers;

use App\Product;
use App\Product_Category;
use App\Product_Review;
use App\Product_Question;
use App\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    //========= PRODUCT ========================================
    //VIEW
    public function index()
    {
        return view('admin.product.index', [
            'products' => Product::paginate(10),
            'total' => Product::all()->count()
        ]);
    }

    public function search(Request $request)
    {
        return view('admin.product.index', [
            'products' => Product::where('name', 'like', '%' . $request->SearchProduct . '%')->paginate(10),
            'total' => Product::where('name', 'like', '%' . $request->SearchProduct . '%')->count(),
            'query' => $request->SearchProduct
        ]);
    }

    public function create()
    {
        return view('admin.product.create', [
            'categories' => Product_Category::all()
        ]);
    }

    public function edit(Product $product)
    {
        return view('admin.product.edit', [
            'product' => $product,
            'categories' => Product_Category::all()
        ]);
    }

    public function view(Product $product, Request $request){
        // if(Auth::check() && Auth::user()->hasAccess()){
        //     return view('admin.user.index');
        // }elseif(Auth::check()){
        //     return redirect(route('home'));
        // }else{
        //     return redirect(route('home'));
        // }

        $Product = $product->leftJoin('product_review','product_review.product_id','=','product.id')
        ->select('product.*',DB::raw('AVG(product_review.rating) as rating'))
        ->groupBy('product.id')->where('product.id',"$product->id")->first();

        $reviews = Product_Review::leftJoin('users','product_review.user_id','=','users.id')
        ->where('product_review.product_id','=',"$product->id")->orderBy('product_review.created_at', 'desc');

        $questions = Product_Question::leftJoin('users','product_question.user_id','=','users.id')
        ->where('product_question.product_id','=',"$product->id")->whereRaw('response <> ""')
        ->where('question','like','%'.$request->SearchQuestion.'%')
        ->orderBy('product_question.created_at', 'desc')->paginate(5, ['*'], 'questions');
        
        if(Auth::check()){
            $favorite = Favorite::where('user_id','=',Auth::user()->id)->where('product_id','=',"$product->id")->first();
        }

        $product->update([
            'view_count' => $product->view_count+1
        ]);

        return view('product',[
            'product' => $Product,
            'product_category' => Product_Category::find("$product->category_id"),
            'total_review' => $reviews->count(),
            'reviews' =>$reviews->paginate(2, ['*'], 'reviews'),
            'questions' => $questions,
            'query' => $request->SearchQuestion,
            'favorite' => isset($favorite) ? $favorite:null
        ]);
    }

    //FUNCTION
    public function delete(Request $request)
    {
        $targets = $request->chk_id;
        foreach ($targets as $target) {
            $product = Product::findOrFail($target);
            if($product->image_img){
                $images = explode('|', $product->image_img);
                foreach ($images as $image) {
                    if (file_exists('images/' . $image)) unlink('images/' . $image);
                }
            }
        }
        Product::destroy($request->chk_id);
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->product_validation();

        $input = $request->all();
        $images = array();
        if ($files = $request->file('image_img')) {
            foreach ($files as $file) {
                $name = Str::random(64) . '.' . $file->getClientOriginalExtension();
                $file->move('images', $name);
                $images[] = $name;
            }
        }

        // Product::insert([
        //     'name' => $input['name'],
        //     'description' => $input['description'],
        //     'category_id' => $input['category'],
        //     'price' => $input['price'],
        //     'stock_amount' => $input['stock_amount'],
        //     'image_img' =>  implode("|", $images),
        // ]);

        $product = new Product();
        $product->name = $input['name'];
        $product->description = $input['description'];
        $product->category_id = $input['category'];
        $product->price = $input['price'];
        $product->stock_amount = $input['stock_amount'];
        $product->image_img = implode("|", $images);
        $product->save();

        return redirect(route('product.index'));
    }

    public function update(Product $product){
        $this->product_validation();
        $new_images = array();

        if($files = request()->file('image_img')){
            foreach ($files as $file) {
                $name = Str::random(64) . '.' . $file->getClientOriginalExtension();
                $file->move('images', $name);
                $new_images[] = $name;
            }
        }

        if($product->image_img){
            $old_images = explode('|',$product->image_img);
        }else{
            $old_images = Array();
        }
        
        $images = array_merge($old_images,$new_images);

        $product->update([
            'name' => request()->name,
            'description' => request()->description,
            'category_id' => request()->category,
            'price' => request()->price,
            'stock_amount' => request()->stock_amount,
            'image_img' =>  implode("|", $images),
        ]);

        return redirect(route('product.index'));
    }

    public function delete_image(Product $product){
        $old_images = explode('|',$product->image_img);
        $new_images = array_diff($old_images,(array)request()->target_image);
        $product->update([
            'image_img' =>  implode("|", $new_images),
        ]);
         if (file_exists('images/' . request()->target_image)) unlink('images/' . request()->target_image);
        return response()->json(array('msg'=> 'operation successful'), 200);
    }

    //VALIDATION
    public function product_validation(){
        return request()->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:50000',
            'category' => 'nullable',
            'price' => 'numeric|required',
            'stock_amount' => 'numeric|required',
            'image_img' => 'nullable',
            'image_img.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    }

    //========= PRODUCT_CATEGORY ========================================
    //VIEW
    public function category_index()
    {
        return view('admin.product.category.index', [
            'categories' => Product_Category::paginate(20),
            'total' => Product_Category::all()->count()
        ]);
    }

    //FUNCTION
    public function category_delete(Product_Category $category)
    {
        $category->delete();
        return redirect()->back();
    }

    public function category_update(Product_Category $category)
    {
        $category->update($this->validateCategory());
        return redirect()->back();
    }

    public function category_create()
    {
        Product_Category::create($this->validateCategory());
        return redirect()->back();
    }

    //VALIDATION
    public function validateCategory()
    {
        return request()->validate([
            'name' => ['required', 'max:700']
        ]);
    }
}
