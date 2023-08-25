<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\QueryException;
use App\Models\Users;
use Mailjet\LaravelMailjet\Facades\Mailjet;
use Mailjet\Resources;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.register');
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        try {

            $way = $request->register_way;

            if ($way == 'email') {

                $email_phone = $request->email;
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email_phone')],
                    'password' => ['required', 'confirmed', Password::defaults()],
                ]);

                $user = Users::create([
                    'name' => $request->name,
                    'email_phone' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                return $this->sendVerificationCode($user);
                $request->session()->put("userID", $user->id);
                $request->session()->put("userRole", $user->role);
                return redirect(RouteServiceProvider::HOME);

            } else if ($way == 'phone') {

                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'password' => ['required', 'confirmed', Password::defaults()],
                    'phone' => [
                        'required',
                        function ($attribute, $value, $fail) {

                            // Check if the input resembles a phone number with specified prefixes
                            $prefixes = ['77', '78', '73', '71', '70'];
                            foreach ($prefixes as $prefix) {
                                if (Str::startsWith($value, $prefix) && strlen($value) === 9) {
                                    return; // Valid phone number format
                                }
                            }

                            // If neither email nor phone number, fail the validation
                            $fail('خطا في رقم الهاتف');
                        }, Rule::unique('users', 'email_phone')
                    ]
                ]);

                $user = Users::create([
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'email_phone' => $request->phone,
                ]);
                $request->session()->put("userID", $user->id);
                $request->session()->put("userRole", $user->role);
                return redirect(RouteServiceProvider::HOME);

            }



        } catch (QueryException $ex) {
            return back()->with('fail', 'Opps An error occurred: ');
        }


    }



    public function sendVerificationCode(&$user)
    {


        // Generate a random verification code
        $verificationCode = rand(10000, 99999);
        $user->remember_token = $verificationCode;
        $user->save();
        // Send the verification code via email
        $mj = Mailjet::getClient();
        $formdata = [
            'name' => $user->name,
            'code' => $verificationCode,
        ];
        $body = [
            'FromEmail' => "ehjezservice@gmail.com",
            'FromName' => "موقع احجز",
            'Subject' => "التحقق من الحساب",
            'MJ-TemplateID' => 5017070,
            'MJ-TemplateLanguage' => true,
            'Vars' => json_decode(json_encode($formdata), true),
            'Recipients' => [['Email' => $user->email_phone]]
        ];
$response = $mj->get(Resources::$Email, [
    'body' => $body,
    'verify' => false,
]);

        if ($response->success()) {

        } else {

        }

        return view('pages.verification.verificationEmail')->with('message', 'Verification code sent!');
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