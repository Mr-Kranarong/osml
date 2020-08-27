<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view('admin.user',[
            'users' => User::paginate(10),
            'total' => User::all()->count()
        ]);
    }

    public function search(Request $request){
        return view('admin.user',[
            'users' => User::where('name','like','%'.$request->SearchUser.'%')->paginate(10),
            'total' => User::where('name','like','%'.$request->SearchUser.'%')->count(),
            'query' => $request->SearchUser
        ]);
    }
}
