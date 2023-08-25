<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;

class DontLoginIfLogedIn
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

        if(Session()->has('userID')){
          
            return back();
        }
        
        return $next($request);
    }
}
