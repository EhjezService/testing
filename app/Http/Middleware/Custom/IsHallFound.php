<?php

namespace App\Http\Middleware\Custom;

use App\Models\Hall;
use Closure;
use Illuminate\Http\Request;

class IsHallFound
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
        $hall=Hall::find($request->hall_id);
        if(!$hall){
            return back();
        }
        return $next($request);
    }
}
