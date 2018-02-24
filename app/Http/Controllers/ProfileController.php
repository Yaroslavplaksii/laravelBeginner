<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Validation\Rule;//для валідації полів
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        $user = Auth::user();//бере дані користувача який залогінився
        return view('pages.profile',['user'=>$user]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::user()->id)//перевірка на унікальність крім поточного запису
            ],
            'avatar'  => 'nullable|image',
           // 'password' => 'required'
        ]);
        $user = Auth::user();
        $user->edit($request->all());
        $user->generatePassword($request->get('password'));
        $user->uploadAvatar($request->file('avatar'));

        return redirect()->back()->with('satus','Pofile is update');
    }
}
