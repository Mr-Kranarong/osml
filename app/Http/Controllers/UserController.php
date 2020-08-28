<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view('admin.user.index',[
            'users' => User::paginate(10),
            'total' => User::all()->count()
        ]);
    }

    public function search(Request $request){
        return view('admin.user.index',[
            'users' => User::where('name','like','%'.$request->SearchUser.'%')->paginate(10),
            'total' => User::where('name','like','%'.$request->SearchUser.'%')->count(),
            'query' => $request->SearchUser
        ]);
    }

    public function delete(User $user){
        $user->delete();
        return redirect(route('user.index'));
    }

    public function update(User $user){
        $user->update($this->inputValidation());
        return redirect(route('user.index'));
    }

    public function inputValidation(){
        return request()->validate([
            'name' => ['required','max:700'],
            'address' => ['max:1000','nullable'],
            'phone' => ['max:15','min:6','nullable'],
            'email' => ['required','email:rfc,dns'],
            'access' => ['boolean']
        ]);
    }
}
