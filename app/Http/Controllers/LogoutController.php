<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function logout()
    {
        if (Session::has('userID')) {
            Session::pull('userID');
        }
        return redirect(RouteServiceProvider::HOME);
    }
}