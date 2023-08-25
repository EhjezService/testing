<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function Admin () {
       $adminName="Basheer";
      //   return view('pages.admin',compact('adminName'));
      //  return view('pages.admin')->with('name',$adminName);
      $data=array(
      'name'=>array(
        'fname'=>'anas',
        'sname'=>'abdo'
      ),
      'id'=>9098098

      );
     //return view('pages.admin',$data);
      return view('pages.admin')->with($data);
    }
}