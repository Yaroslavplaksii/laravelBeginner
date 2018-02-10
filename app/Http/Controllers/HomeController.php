<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Category;
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

    public function tag($slug){
        $tag = Tag::where('slug', $slug)->firstOrFail();
//        $posts = $tag->posts; //якщо потрібно всі вибрати записи
        $posts = $tag->posts()->paginate(2);//коли використовуємо пагінацію posts => posts(), в цьому буде різниця

        return view('pages.list',['posts'=>$posts]);
    }

    public function category($slug){
        $category = Category::where('slug',$slug)->firstOrFail();
//        $posts = $category->posts;
$posts = $category->posts()->paginate(2);
        return view('pages.list',['posts'=>$posts]);
    }
}
