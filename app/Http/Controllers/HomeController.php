<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Product_Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'categories' => Product_Category::all()
        ]);
    }

    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);

        if(Auth::check()){
            Auth::user()->updateLanguage($locale);
        }

        return redirect()->back();
    }
}
