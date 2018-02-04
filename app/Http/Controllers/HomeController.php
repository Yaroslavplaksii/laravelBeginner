<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index(){
        $posts = Post::paginate(2);
        return view('pages.index',['posts'=>$posts]);
    }

    public function show($slug){
        $post = Post::where('slug',$slug)->firstOrFail();//Обєкт або помилка
        return view('pages.show',['post'=>$post]);
    }
}
