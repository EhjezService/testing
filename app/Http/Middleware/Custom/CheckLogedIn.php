<?php

namespace App\Http\Middleware\Custom;

use App\Models\Hall;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Users;
use Closure;
use Illuminate\Http\Request;

class CheckLogedIn
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
        if (!Session()->has('userID')) {
            return redirect(RouteServiceProvider::LOGIN);
        }

        return $next($request);
    }
}