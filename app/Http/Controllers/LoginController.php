<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\QueryException;
use App\Models\Users;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //


    }

    /////////////////////////////
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $request->validate([
            'email_phone' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Check if the input resembles an email address
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return; // Valid email format
                    }

                    // Check if the input resembles a phone number with specified prefixes
                    $prefixes = ['77', '78', '73', '71', '70'];
                    foreach ($prefixes as $prefix) {
                        if (Str::startsWith($value, $prefix) && strlen($value) === 9) {
                            return; // Valid phone number format
                        }
                    }

                    // If neither email nor phone number, fail the validation
                    $fail('خطا في رقم الهاتف');
                },
            ],
            'password' => ['required'],
        ]);




        try {
            $user = Users::where('email_phone', '=', $request->email_phone)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $request->session()->put("userID", $user->id);
                    //
                    return $this->redirectToSpecificAdmin($user->id);
                } else {
                    return back()->with('fail', 'error password');
                }
            } else {
                return back()->with('fail', 'no user with this email ');
            }
        } catch (QueryException $ex) {
            return back()->with('fail', 'Opps something wrong when connect to server');
        }


    }
    /////////////////////////////////////////////
    public function redirectToSpecificAdmin($id)
    {
        $user = Users::where('id', '=', $id)->first();
        switch ($user->role) {

            case 1:
                Session::put("userRole", $user->role);
                //redirect to index() of HallAdminController
                return redirect(RouteServiceProvider::DASHHALL);
                break;
            case 2:
                Session::put("userRole", $user->role);
                //redirect to index() of PhotoAdminController
                return redirect(RouteServiceProvider::DASHPHOTO);
                break;
            default:
                Session::put("userRole", $user->role);
                
                return redirect(RouteServiceProvider::HOME);
                break;
        }


    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}