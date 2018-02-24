<?php

namespace App\Http\Middleware;


use Auth;
use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && Auth::user()->is_admin){//Auth::user() поверне поточного користувача
            return $next($request);//Auth::check() поверне true якщо користувач залогінений
        }
        abort(404);//виведеться 404 помилка
    }

}
