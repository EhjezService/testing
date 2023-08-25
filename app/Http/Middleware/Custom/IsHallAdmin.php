<?php

namespace App\Http\Middleware\Custom;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IsHallAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Session::has("userID")){

        if (!(Session::has("userRole") and Session::get("userRole") == 1)) {
            return back();
        }

        }else{
            return redirect(RouteServiceProvider::LOGIN);
        }
        return $next($request);
    }
}