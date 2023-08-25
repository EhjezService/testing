<?php

namespace App\Http\Controllers;
use App\Models\Hall;
use Illuminate\Http\Request;

class IndexController extends Controller
{
   public function index () {
    
    return view('pages.index')->with('halls',Hall::all());
    }

}
