<?php

namespace App\Http\Controllers;

use Auth; //клас роботи з користувачем (при логуванні)
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registerForm(){
        return view('pages.register');
    }

    public function register(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ]);
        $user = User::add($request->all());
        $user->generatePassword($request->get('password'));

        return redirect('/login');
    }

    public function loginForm(){
        return view('pages.login');
    }
    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if(Auth::attempt([//клас який спробує порівняти значення імайла і пароля
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ])){
            return redirect('/');
        }
        return redirect()->back()->with('status','Невірний логін або пароль');//при не вдалій спробі поверне назад і передасть значенння флеш status це сесія
        //Auth:check(); //провірка чи залогінений користувач
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
